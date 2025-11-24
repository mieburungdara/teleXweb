<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load necessary models, helpers, libraries
        $this->load->model('User_model');

        $this->load->model('Audit_log_model');
        $this->load->model('Balance_Transaction_model');
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->library('session'); // Loaded for flashdata

        $this->admin_id = $this->session->userdata('admin_id') ?? 1; // Placeholder for dynamic admin ID
    }

    public function index()
    {
        $this->load->view('admin/dashboard');
    }







    public function manage_credit_topups()
    {
        // Load necessary models
        $this->load->model('User_model');
        $this->load->model('Balance_Transaction_model');

        // Handle form submission for adding credits
        if ($this->input->post()) {
            $user_id = $this->input->post('user_id');
            $credits_to_add = (int)$this->input->post('credits_to_add');
            $description = $this->input->post('description'); // e.g., "Manual Top-up via Bank Transfer"

            if ($user_id && $credits_to_add > 0) {
                // Assuming 1 Credit = 0.01 USD for balance tracking (if balance is USD-based)
                // If balance is already in Credits, then just add credits_to_add
                // For simplicity, let's assume balance directly reflects credits
                $success = $this->User_model->add_balance($user_id, $credits_to_add, $description, $this->admin_id, 'manual_topup');

                if ($success) {
                    $this->session->set_flashdata('success_message', 'Credits added successfully!');
                } else {
                    $this->session->set_flashdata('error_message', 'Failed to add credits.');
                }
            } else {
                $this->session->set_flashdata('error_message', 'Invalid user ID or credit amount.');
            }
            redirect('admin/manage_credit_topups');
        }

        // Display the form
        $data['users'] = $this->User_model->get_all_users(); // Get a list of users for selection
        $this->load->view('admin/manage_credit_topups', $data);
    }

    public function manage_user_balance($user_id = null, $offset = 0)
    {
        $search_term = $this->input->get('search', TRUE); // Get search term from GET request

        if ($this->input->post()) {
            $user_id = $this->input->post('user_id');
            $amount = (float)$this->input->post('amount');
            $transaction_type = $this->input->post('transaction_type');
            $description = $this->input->post('description');
            $admin_id = $this->admin_id; // Get admin ID from constructor/session

            $user_target = $this->User_model->get_user($user_id);
            if (!$user_target) {
                $this->session->set_flashdata('error_message', 'Target user not found.');
                redirect('admin/manage_user_balance');
            }

            $old_balance = $user_target->balance;

            $success = false;
            if ($transaction_type == 'top_up') {
                $success = $this->User_model->add_balance($user_id, $amount, $description, $admin_id, 'manual_top_up');
            } elseif ($transaction_type == 'deduction') {
                $success = $this->User_model->deduct_balance($user_id, $amount, $description, $admin_id, 'manual_deduction');
            }

            if ($success) {
                $user_after_update = $this->User_model->get_user($user_id);
                $new_balance = $user_after_update ? $user_after_update->balance : $old_balance;

                $admin_user = $this->User_model->get_user($admin_id);
                $admin_identifier = $admin_user ? ($admin_user->codename . ' (ID: ' . $admin_id . ')') : 'Admin ID: ' . $admin_id;

                $this->Audit_log_model->log_action(array(
                    'user_id' => $admin_id, // Admin performing the action
                    'action' => 'update_user_balance',
                    'entity_type' => 'user',
                    'entity_id' => $user_id, // User whose balance was updated
                    'old_value_json' => json_encode(array('balance' => $old_balance)),
                    'new_value_json' => json_encode(array('balance' => $new_balance)),
                    'ip_address' => $this->input->ip_address(),
                    'description' => $admin_identifier . ' ' . $transaction_type . ' balance for user ' . $user_id . ': ' . $description
                ));
                $this->session->set_flashdata('success_message', 'User balance updated successfully!');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to update user balance. Please check user funds for deductions.');
            }
            redirect('admin/manage_user_balance/' . $user_id . ($search_term ? '?search=' . urlencode($search_term) : ''));
        }

        $data['user'] = null;
        $data['transactions'] = [];
        if ($user_id) {
            $data['user'] = $this->User_model->get_user($user_id);
            if ($data['user']) {
                // Pagination for transactions
                $config['base_url'] = site_url('admin/manage_user_balance/' . $user_id);
                $config['total_rows'] = $this->Balance_Transaction_model->count_user_transactions($user_id);
                $config['per_page'] = 10;
                $config['uri_segment'] = 4; // The offset will be the 4th segment in the URL
                $config['use_page_numbers'] = TRUE; // Use page numbers instead of offset for links

                // Bootstrap 5 pagination styling
                $config['full_tag_open'] = '<ul class="pagination">';
                $config['full_tag_close'] = '</ul>';
                $config['num_tag_open'] = '<li class="page-item">';
                $config['num_tag_close'] = '</li>';
                $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
                $config['cur_tag_close'] = '</a></li>';
                $config['next_tag_open'] = '<li class="page-item">';
                $config['next_tag_close'] = '</li>';
                $config['prev_tag_open'] = '<li class="page-item">';
                $config['prev_tag_close'] = '</li>';
                $config['first_tag_open'] = '<li class="page-item">';
                $config['first_tag_close'] = '</li>';
                $config['last_tag_open'] = '<li class="page-item">';
                $config['last_tag_close'] = '</li>';
                $config['attributes'] = array('class' => 'page-link');

                $this->pagination->initialize($config);

                $data['transactions'] = $this->Balance_Transaction_model->get_paginated_user_transactions(
                    $user_id, 
                    $config['per_page'], 
                    $this->uri->segment(4) ? ($this->uri->segment(4) - 1) * $config['per_page'] : 0
                );
                $data['pagination_links'] = $this->pagination->create_links();
            }
        }
        $data['users'] = $this->User_model->get_all_users($search_term); // Pass search term to get_all_users
        $data['search_term'] = $search_term; // Pass search term to view
        $this->load->view('admin/manage_user_balance', $data);
    }

    public function user_balance_history($user_id, $offset = 0)
    {
        $data['user'] = $this->User_model->get_user($user_id);
        if (!$data['user']) {
            show_404();
        }

        // Pagination for transactions
        $config['base_url'] = site_url('admin/user_balance_history/' . $user_id);
        $config['total_rows'] = $this->Balance_Transaction_model->count_user_transactions($user_id);
        $config['per_page'] = 10;
        $config['uri_segment'] = 4; // The offset will be the 4th segment in the URL
        $config['use_page_numbers'] = TRUE; // Use page numbers instead of offset for links

        // Bootstrap 5 pagination styling
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $data['transactions'] = $this->Balance_Transaction_model->get_paginated_user_transactions(
            $user_id, 
            $config['per_page'], 
            $this->uri->segment(4) ? ($this->uri->segment(4) - 1) * $config['per_page'] : 0
        );
        $data['pagination_links'] = $this->pagination->create_links();

        $this->load->view('admin/user_balance_history', $data);
    }

    /**
     * AJAX endpoint to search users.
     */
    public function search_users_ajax()
    {
        $search_term = $this->input->get('term', TRUE); // 'term' is common for autocomplete
        $users = $this->User_model->get_all_users($search_term);

        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'id' => $user->id,
                'text' => $user->codename . ' (ID: ' . $user->id . ')' . ($user->username ? ' (@' . $user->username . ')' : '')
            ];
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($results));
    }
}