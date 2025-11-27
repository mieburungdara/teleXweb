<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['File_model', 'User_model']);
        $this->output->set_content_type('application/json');
    }

    public function index()
    {
        // For simplicity, we assume the webhook sends data via POST.
        // A real implementation should have a secret key validation here.
        $post_data = $this->input->post();

        // Basic validation
        $required_fields = ['telegram_user_id', 'file_unique_id', 'telegram_file_id', 'storage_channel_id', 'storage_message_id'];
        foreach ($required_fields as $field) {
            if (empty($post_data[$field])) {
                $this->output->set_status_header(400);
                echo json_encode(['status' => 'error', 'message' => "Missing required field: {$field}"]);
                return;
            }
        }

        // Check for duplicate file content
        $existing_file = $this->File_model->get_file_by_unique_id($post_data['file_unique_id']);
        if ($existing_file) {
            $this->output->set_status_header(200);
            echo json_encode(['status' => 'success', 'message' => 'File content already exists.', 'file_id' => $existing_file['id']]);
            return;
        }

        // Prepare file data for insertion
        $file_data = [
            'user_id' => $post_data['telegram_user_id'], // Assuming telegram_user_id is the user's primary ID for now
            'file_unique_id' => $post_data['file_unique_id'],
            'media_group_id' => $post_data['media_group_id'] ?? null,
            'storage_channel_id' => $post_data['storage_channel_id'],
            'storage_message_id' => $post_data['storage_message_id'],
            'telegram_file_id' => $post_data['telegram_file_id'],
            'thumbnail_file_id' => $post_data['thumbnail_file_id'] ?? null,
            'file_name' => $post_data['file_name'] ?? null,
            'original_file_name' => $post_data['original_file_name'] ?? null,
            'file_size' => $post_data['file_size'] ?? null,
            'mime_type' => $post_data['mime_type'] ?? null,
            'process_status' => 'pending',
        ];

        // In a real scenario, you should verify the user exists in your `users` table first.
        // For now, we are assuming the user_id is valid.

        $file_id = $this->File_model->create_file($file_data);

        if ($file_id) {
            $this->output->set_status_header(201); // 201 Created
            echo json_encode(['status' => 'success', 'message' => 'File metadata saved successfully.', 'file_id' => $file_id]);
        } else {
            log_message('error', 'Failed to save file metadata: ' . json_encode($file_data));
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Internal server error: Could not save file metadata.']);
        }
    }
}
