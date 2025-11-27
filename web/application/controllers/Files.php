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
        $this->load->model(['File_model', 'Bot_model', 'Telegram_bot_model', 'Folder_model', 'Access_Log_model', 'Audit_Log_model']);
        $this->load->helper('url');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        // Extract filters from GET request
        $filters = [
            'keyword' => $this->input->get('keyword', TRUE),
            'mime_type' => $this->input->get('mime_type', TRUE),
            'folder_id' => $this->input->get('folder_id', TRUE),
            'is_favorited' => $this->input->get('is_favorited', TRUE),
        ];

        $files = $this->File_model->get_files_with_filters($user_id, $filters);

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
        
        // Get Trending Items
        $trending_files_raw = $this->Access_Log_model->get_trending_items('file');
        $trending_folders_raw = $this->Access_Log_model->get_trending_items('folder');
        
        $data['trending_files'] = [];
        foreach($trending_files_raw as $item) {
            $file_details = $this->File_model->get_file_by_id($item['entity_id'], $user_id); // Assuming user has access
            if ($file_details) {
                $item['original_file_name'] = $file_details['original_file_name'];
                $item['mime_type'] = $file_details['mime_type'];
                $data['trending_files'][] = $item;
            }
        }

        $data['trending_folders'] = [];
        foreach($trending_folders_raw as $item) {
            $folder_details = $this->Folder_model->get_folder_by_id($item['entity_id'], $user_id); // Assuming user has access
            if ($folder_details) {
                $item['folder_name'] = $folder_details['folder_name'];
                $data['trending_folders'][] = $item;
            }
        }

        $data['files'] = $files;
        $data['title'] = 'My Files';
        $data['filters'] = $filters; // Pass filters back to view for form population
        $data['all_mime_types'] = $this->File_model->get_all_mime_types($user_id);
        $data['user_folders'] = $this->Folder_model->get_user_folders($user_id, null); // Top-level folders
        $data['breadcrumbs'] = !empty($filters['folder_id']) ? $this->Folder_model->get_folder_hierarchy($filters['folder_id']) : [];

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

        // Log access
        $this->Access_Log_model->log_access('file', $id, $user_id);

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
        $data['breadcrumbs'] = !empty($file['folder_id']) ? $this->Folder_model->get_folder_hierarchy($file['folder_id']) : [];

        $this->load->view('templates/header', $data);
        $this->load->view('file_detail_view', $data);
        $this->load->view('templates/footer');
    }

    public function toggle_favorite($id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $file = $this->File_model->get_file_by_id($id, $user_id);
        if (!$file) {
            $this->session->set_flashdata('error_message', 'File not found.');
            redirect('files');
            return;
        }

        $old_is_favorited = $file['is_favorited'];
        $new_is_favorited = !$old_is_favorited;

        $success = $this->File_model->toggle_favorite($id, $user_id);
        if ($success) {
            $this->Audit_Log_model->log_action(
                'toggle_file_favorite',
                'file',
                $id,
                ['is_favorited' => $old_is_favorited],
                ['is_favorited' => $new_is_favorited]
            );
            $this->session->set_flashdata('success_message', 'File favorite status toggled.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to toggle file favorite status.');
        }
        redirect('files');
    }

    public function timeline()
    {
        $user_id = $this->session->userdata('user_id');
        $files = $this->File_model->get_files_for_timeline($user_id);

        $timeline_data = [];
        foreach ($files as $file) {
            $date = date('Y-m-d', strtotime($file['created_at']));
            if (!isset($timeline_data[$date])) {
                $timeline_data[$date] = [];
            }
            $timeline_data[$date][] = $file;
        }

        $data['timeline_data'] = $timeline_data;
        $data['title'] = 'File Timeline';

        $this->load->view('templates/header', $data);
        $this->load->view('timeline_view', $data);
        $this->load->view('templates/footer');
    }
}