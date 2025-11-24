<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Folder_model');
        $this->load->helper('url');
        $this->load->library('pagination');

        // Assuming user authentication is handled elsewhere and user_id is available
        // For now, let's assume a dummy user_id for demonstration
        $this->user_id = 1; 
    }

    public function index()
    {
        $filters = array(
            'search' => $this->input->get('search', TRUE),
            'min_price' => $this->input->get('min_price', TRUE),
            'max_price' => $this->input->get('max_price', TRUE),
            'sort_by' => $this->input->get('sort_by', TRUE),
            'sort_order' => $this->input->get('sort_order', TRUE)
        );

        $total_rows = $this->Folder_model->count_folders_for_sale($filters);

        $config['base_url'] = site_url('marketplace/index');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 12;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;

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

        $offset = $this->uri->segment(3) ? ($this->uri->segment(3) - 1) * $config['per_page'] : 0;
        
        $data['folders'] = $this->Folder_model->search_folders_for_sale($config['per_page'], $offset, $filters);
        $data['pagination_links'] = $this->pagination->create_links();
        $data['filters'] = $filters;

        $this->load->view('marketplace/index', $data);
    }
}
