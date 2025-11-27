<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Telegram\Bot\Api;

class Upload extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['File_model', 'User_model', 'Bot_model', 'Telegram_bot_model', 'Tag_model']);
        $this->output->set_content_type('application/json');
    }

    public function send_message()
    {
        $bot_id = $this->input->post('bot_id');
        $chat_id = $this->input->post('chat_id');
        $text = $this->input->post('text');

        if (!$bot_id || !$chat_id || !$text) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing bot_id, chat_id, or text.']);
            return;
        }

        $bot_record = $this->Bot_model->get_bot_by_telegram_id($bot_id);
        if (!$bot_record || empty($bot_record['token'])) {
            log_message('error', 'send_message: Bot not found or missing token for Bot ID: ' . $bot_id);
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Internal server configuration error: Bot not configured.']);
            return;
        }

        if ($this->Telegram_bot_model->init($bot_record['token'])) {
            $success = $this->Telegram_bot_model->sendMessage($chat_id, $text);
            if ($success) {
                echo json_encode(['status' => 'success', 'message' => 'Message sent.']);
            } else {
                $this->output->set_status_header(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to send Telegram message.']);
            }
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to initialize Telegram Bot.']);
        }
    }

    public function index()
    {
        // For simplicity, we assume the webhook sends data via POST.
        // A real implementation should have a secret key validation here.
        $post_data = $this->input->post();

        // Basic validation
        $required_fields = ['bot_id', 'original_chat_id', 'original_message_id', 'telegram_user_id', 'file_unique_id', 'telegram_file_id'];
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

        // Fetch bot details
        $bot_record = $this->Bot_model->get_bot_by_telegram_id($post_data['bot_id']);
        if (!$bot_record || empty($bot_record['token']) || empty($bot_record['storage_channel_id'])) {
            log_message('error', 'Upload API: Bot not found or missing token/storage_channel_id for Bot ID: ' . $post_data['bot_id']);
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Internal server configuration error: Bot not configured for storage.']);
            return;
        }

        try {
            // Initialize Telegram Bot SDK
            $telegram = new Api($bot_record['token']);

            // Perform the copyMessage operation
            $copied_message = $telegram->copyMessage([
                'chat_id'      => $bot_record['storage_channel_id'],
                'from_chat_id' => $post_data['original_chat_id'],
                'message_id'   => $post_data['original_message_id'],
            ]);

            if (!$copied_message || !$copied_message->getMessageId()) {
                throw new Exception("copyMessage did not return a valid message object.");
            }

            // Prepare file data for insertion with the new storage message ID
            $file_data = [
                'user_id' => $post_data['telegram_user_id'],
                'bot_id' => $post_data['bot_id'], // Add bot_id here
                'file_unique_id' => $post_data['file_unique_id'],
                'media_group_id' => $post_data['media_group_id'] ?? null,
                'storage_channel_id' => $bot_record['storage_channel_id'],
                'storage_message_id' => $copied_message->getMessageId(),
                'telegram_file_id' => $post_data['telegram_file_id'],
                'thumbnail_file_id' => $post_data['thumbnail_file_id'] ?? null,
                'file_name' => $post_data['file_name'] ?? null,
                'original_file_name' => $post_data['original_file_name'] ?? null,
                'file_size' => $post_data['file_size'] ?? null,
                'mime_type' => $post_data['mime_type'] ?? null,
                'process_status' => 'processed', // Mark as processed after copy
            ];

            $file_id = $this->File_model->create_file($file_data);

            if ($file_id) {
                // If the file was added to a folder, update its size
                if (!empty($file_data['folder_id'])) {
                    $this->Folder_model->update_folder_size($file_data['folder_id']);
                }

                $this->output->set_status_header(201); // 201 Created
                echo json_encode(['status' => 'success', 'message' => 'File metadata saved successfully.', 'file_id' => $file_id]);
            } else {
                log_message('error', 'Failed to save file metadata after copying: ' . json_encode($file_data));
                $this->output->set_status_header(500);
                echo json_encode(['status' => 'error', 'message' => 'Internal server error: Could not save file metadata.']);
            }

        } catch (\Telegram\Bot\Exceptions\TelegramSDKException $e) {
            log_message('error', 'Telegram API Error during copyMessage: ' . $e->getMessage());
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Telegram API error: ' . $e->getMessage()]);
        } catch (Exception $e) {
            log_message('error', 'General Error during file processing: ' . $e->getMessage());
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
        }
    }

    public function get_recent_files()
    {
        $user_id = $this->input->post('user_id');
        $limit = $this->input->post('limit') ?? 5;

        if (!$user_id) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing user_id.']);
            return;
        }

        $files = $this->File_model->get_recent_files($user_id, $limit);
        echo json_encode(['status' => 'success', 'files' => $files]);
    }

    public function search_files()
    {
        $user_id = $this->input->post('user_id');
        $keyword = $this->input->post('keyword');

        if (!$user_id || !$keyword) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing user_id or keyword.']);
            return;
        }

        $files = $this->File_model->search_files($user_id, $keyword);
        echo json_encode(['status' => 'success', 'files' => $files]);
    }

    public function toggle_favorite()
    {
        $user_id = $this->input->post('user_id');
        $file_id = $this->input->post('file_id');

        if (!$user_id || !$file_id) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing user_id or file_id.']);
            return;
        }

        $success = $this->File_model->toggle_favorite($file_id, $user_id);
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Favorite status toggled.']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to toggle favorite status.']);
        }
    }

    public function update_file()
    {
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(403);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $file_id = $this->input->post('file_id');
        $field = $this->input->post('field');
        $value = $this->input->post('value');
        $user_id = $this->session->userdata('user_id');

        // Basic validation
        if (!$file_id || !$field || !isset($value)) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing required parameters: file_id, field, or value.']);
            return;
        }

        // Whitelist of editable fields
        $allowed_fields = ['original_file_name', 'file_name'];
        if (!in_array($field, $allowed_fields)) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'This field cannot be edited.']);
            return;
        }

        $data = [
            $field => $value
        ];

        $success = $this->File_model->update_file_field($file_id, $user_id, $data);

        $response = [
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'File updated successfully.' : 'Failed to update file.',
            'csrf_hash' => $this->security->get_csrf_hash()
        ];
        
        $this->output->set_status_header($success ? 200 : 500);
        echo json_encode($response);
    }

    public function file_preview_data($id)
    {
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(403);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $file = $this->File_model->get_file_by_id($id, $user_id);

        if (!$file) {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'error', 'message' => 'File not found.']);
            return;
        }

        // Generate thumbnail URL for the preview
        $file['thumbnail_url'] = null;
        if (!empty($file['thumbnail_file_id']) && !empty($file['bot_id'])) {
            $bot_record = $this->Bot_model->get_bot_by_id($file['bot_id']);
            if ($bot_record && !empty($bot_record['token'])) {
                if ($this->Telegram_bot_model->init($bot_record['token'])) {
                    $file['thumbnail_url'] = $this->Telegram_bot_model->get_file_url($file['thumbnail_file_id']);
                }
            }
        }

        echo json_encode(['status' => 'success', 'file' => $file]);
    }

    public function bulk_action()
    {
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(403);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $action = $this->input->post('action');
        $file_ids = $this->input->post('file_ids');
        $user_id = $this->session->userdata('user_id');

        if (!$action || !$file_ids || !is_array($file_ids)) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing required parameters: action or file_ids.']);
            return;
        }

        $success_count = 0;
        $error_count = 0;

        switch ($action) {
            case 'delete':
                foreach ($file_ids as $file_id) {
                    if ($this->File_model->soft_delete_file($file_id, $user_id)) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                }
                break;
            default:
                $this->output->set_status_header(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
                return;
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Action '{$action}' completed. Success: {$success_count}, Failed: {$error_count}.",
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }
    
    public function tag_suggestions()
    {
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(403);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $term = $this->input->get('term');
        if (!$term) {
            echo json_encode([]);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $suggestions = $this->Tag_model->get_tag_suggestions($user_id, $term);
        echo json_encode(array_column($suggestions, 'tag_name'));
    }
}
