<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load necessary models, helpers, libraries
        $this->load->model('User_model');
        $this->load->model('Subscription_model');
        $this->load->model('Balance_Transaction_model'); // Load Balance_Transaction_model
        $this->load->model('Folder_Purchase_model'); // Load Folder_Purchase_model
        $this->load->helper('url');
        $this->load->helper('subscription'); // Load the new helper
        $this->load->library('pagination'); // Load pagination library
        // Assuming user authentication is handled elsewhere and user_id is available
        // $this->user_id = $this->session->userdata('user_id'); 
        // For now, let's assume a dummy user_id for demonstration
        $this->user_id = 1; 
    }

    public function index()
    {
        // Default user profile view
        $data['user'] = $this->User_model->get_user($this->user_id);
        $this->load->view('user/profile', $data);
    }

    public function balance($offset = 0)
    {
        $data['user'] = $this->User_model->get_user($this->user_id);
        if (!$data['user']) {
            show_error('User not found.');
        }
        // Assuming admin_username is configured somewhere, e.g., in config
        $data['admin_telegram_username'] = '@teleXweb_admin'; 

        // Pagination for transactions
        $config['base_url'] = site_url('users/balance');
        $config['total_rows'] = $this->Balance_Transaction_model->count_user_transactions($this->user_id);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3; // The offset will be the 3rd segment in the URL
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
            $this->user_id, 
            $config['per_page'], 
            $this->uri->segment(3) ? ($this->uri->segment(3) - 1) * $config['per_page'] : 0
        );
        $data['pagination_links'] = $this->pagination->create_links();

        $this->load->view('user/user_balance_view', $data);
    }

    public function subscription()
    {
        $data['user'] = $this->User_model->get_user($this->user_id);
        if (!$data['user']) {
            show_error('User not found.');
        }

        $data['active_subscription'] = $this->Subscription_model->get_user_active_subscription($this->user_id);
        $data['subscription_history'] = $this->Subscription_model->get_user_subscription_history($this->user_id);

        // For communication in UI (Task 8)
        $data['plan_benefits'] = array(
            'free' => 'Limited storage, basic notifications, view public collections.',
            'pro' => 'Increased storage, advanced notifications, create public collections, priority support.',
            'enterprise' => 'All Pro features, team management, custom branding, dedicated support.'
        );
        $data['plan_limits'] = array(
            'metadata_storage_limit' => get_user_plan_limit($data['user'], 'metadata_storage_limit'),
            'folder_limit' => get_user_plan_limit($data['user'], 'folder_limit'),
            'smart_collection_limit' => get_user_plan_limit($data['user'], 'smart_collection_limit'),
            'notification_rules_limit' => get_user_plan_limit($data['user'], 'notification_rules_limit')
        );

        $this->load->view('user/subscription_management', $data);
    }

    public function upgrade_plan()
    {
        // Logic to handle plan upgrade
        // This would typically involve redirecting to a payment gateway checkout
        // For demonstration, let's just update the user's plan directly
        if ($this->input->post('new_plan')) {
            $new_plan = $this->input->post('new_plan');
            // In a real scenario, this would involve payment processing and webhook updates
            // For now, simulate a successful upgrade
            $this->User_model->update_user_subscription_details(
                $this->user_id, 
                $new_plan, 
                'active', 
                date('Y-m-d H:i:s'), 
                date('Y-m-d H:i:s', strtotime('+1 month')) // Example: 1 month subscription
            );
            redirect('users/subscription');
        }
        $this->load->view('user/upgrade_plan_form');
    }

    public function cancel_subscription()
    {
        // Logic to handle subscription cancellation
        $active_subscription = $this->Subscription_model->get_user_active_subscription($this->user_id);
        if ($active_subscription) {
            $this->Subscription_model->cancel_subscription($active_subscription->id);
            $this->User_model->update_user_subscription_details(
                $this->user_id, 
                'free', // Revert to free plan
                'canceled', 
                null, 
                null
            );
        }
        redirect('users/subscription');
    }

    public function my_purchased_folders()
    {
        $data['user'] = $this->User_model->get_user($this->user_id);
        if (!$data['user']) {
            show_error('User not found.');
        }
        $data['purchased_folders'] = $this->Folder_Purchase_model->get_purchased_folders($this->user_id);
        $data['bot_username'] = 'YourBotName'; // Replace with actual bot username
        $this->load->view('user/my_purchased_folders', $data);
    }

    public function my_sold_folders()
    {
        $data['user'] = $this->User_model->get_user($this->user_id);
        if (!$data['user']) {
            show_error('User not found.');
        }
        $data['sold_folders'] = $this->Folder_Purchase_model->get_sold_folders($this->user_id);
        $this->load->view('user/my_sold_folders', $data);
    }

    // Other user-related methods would go here
}
