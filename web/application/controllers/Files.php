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
        $this->load->model(['File_model', 'Bot_model', 'Telegram_bot_model']);
        $this->load->helper('url');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $files = $this->File_model->get_user_files($user_id);

        // Generate thumbnail URLs
        foreach ($files as &$file) {
            $file['thumbnail_url'] = null;
            if (!empty($file['thumbnail_file_id']) && !empty($file['bot_id'])) {
                $bot_record = $this->Bot_model->get_bot_by_id($file['bot_id']);
                if ($bot_record && !empty($bot_record['token'])) {
                    if ($this->Telegram_bot_model->init($bot_record['token'])) {
                        $file['thumbnail_url'] = $this->Telegram_bot_model->get_file_url($file['thumbnail_file_id']);
                    }
                }
            }
        }
        
        $data['files'] = $files;
        $data['title'] = 'My Files';

        $this->load->view('templates/header', $data);
        $this->load->view('file_list', $data);
        $this->load->view('templates/footer');
    }

    public function gallery()
    {
        $user_id = $this->session->userdata('user_id');
        $files = $this->File_model->get_user_image_files($user_id);

        // Generate thumbnail URLs
        foreach ($files as &$file) {
            $file['thumbnail_url'] = null;
            if (!empty($file['thumbnail_file_id']) && !empty($file['bot_id'])) {
                $bot_record = $this->Bot_model->get_bot_by_id($file['bot_id']);
                if ($bot_record && !empty($bot_record['token'])) {
                    if ($this->Telegram_bot_model->init($bot_record['token'])) {
                        $file['thumbnail_url'] = $this->Telegram_bot_model->get_file_url($file['thumbnail_file_id']);
                    }
                }
            }
        }
        
        $data['files'] = $files;
        $data['title'] = 'Image Gallery';

        $this->load->view('templates/header', $data);
        $this->load->view('gallery_view', $data);
        $this->load->view('templates/footer');
    }

    public function details($id)
    {
        $user_id = $this->session->userdata('user_id');
        $file = $this->File_model->get_file_by_id($id, $user_id);

        if (!$file) {
            $this->session->set_flashdata('error_message', 'File not found.');
            redirect('files');
            return;
        }

        // Generate thumbnail URL
        $file['thumbnail_url'] = null;
        if (!empty($file['thumbnail_file_id']) && !empty($file['bot_id'])) {
            $bot_record = $this->Bot_model->get_bot_by_id($file['bot_id']);
            if ($bot_record && !empty($bot_record['token'])) {
                if ($this->Telegram_bot_model->init($bot_record['token'])) {
                    $file['thumbnail_url'] = $this->Telegram_bot_model->get_file_url($file['thumbnail_file_id']);
                }
            }
        }

        $data['file'] = $file;
        $data['title'] = 'File Details';

        $this->load->view('templates/header', $data);
        $this->load->view('file_detail_view', $data);
        $this->load->view('templates/footer');
    }
}
