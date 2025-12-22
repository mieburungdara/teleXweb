<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monetization extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'You must be logged in to access monetization features.');
            redirect('miniapp/unauthorized');
            return;
        }
        $this->load->model(['User_model', 'Balance_Transaction_model', 'Audit_Log_model']);
        $this->load->library('form_validation');
        $this->load->helper(['url', 'auth_helper']);
    }

    /**
     * Display the current user's balance and transaction history.
     */
    public function balance()
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['balance'] = $this->User_model->get_user_balance($user_id);
        $data['transactions'] = $this->Balance_Transaction_model->get_user_transactions($user_id);
        $data['title'] = 'My Balance';

        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('monetization/balance', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Admin-only: Add funds to a user's balance. (Placeholder for real payment integration)
     */
    public function add_funds()
    {
        if (!has_permission('manage_users')) { // Only admins can add funds manually for now
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('monetization/balance');
            return;
        }

        $this->form_validation->set_rules('user_id', 'User ID', 'required|numeric');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('description', 'Description', 'max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
        } else {
            $user_id = $this->input->post('user_id');
            $amount = $this->input->post('amount');
            $description = $this->input->post('description') ?? 'Admin added funds';

            $success = $this->User_model->update_balance($user_id, $amount, 'credit', $description);

            if ($success) {
                $this->Audit_Log_model->log_action(
                    'funds_added',
                    'user_balance',
                    $user_id,
                    [],
                    ['amount' => $amount, 'description' => $description]
                );
                $this->session->set_flashdata('success_message', 'Funds added successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to add funds.');
            }
        }
        redirect('monetization/balance'); // Or to an admin specific page
    }
}
