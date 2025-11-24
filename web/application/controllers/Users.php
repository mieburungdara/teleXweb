<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load necessary models, helpers, libraries
        $this->load->model('User_model');
        $this->load->model('User_achievement_model');

        $this->load->model('Balance_Transaction_model'); // Load Balance_Transaction_model
        $this->load->model('Folder_Purchase_model'); // Load Folder_Purchase_model
        $this->load->helper('url');

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
        $data['achievements'] = $this->User_achievement_model->get_user_achievements($this->user_id);
        
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







    public function topup_credits()
    {
        $data['user_id'] = $this->user_id; // Assuming $this->user_id is set in the constructor
        $this->load->view('user/topup_credits', $data);
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
