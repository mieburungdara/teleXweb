<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'You must be logged in to view files.');
            redirect('miniapp/unauthorized');
            return;
        }
        $this->load->model('File_model');
        $this->load->helper('url');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['files'] = $this->File_model->get_user_files($user_id);
        $data['title'] = 'My Files';

        $this->load->view('templates/header', $data);
        $this->load->view('file_list', $data);
        $this->load->view('templates/footer');
    }
}
