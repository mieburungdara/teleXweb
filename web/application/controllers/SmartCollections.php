<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SmartCollections extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'You must be logged in to manage smart collections.');
            redirect('miniapp/unauthorized');
            return;
        }
        $this->load->model(['Smart_Collection_Rule_model', 'File_model', 'Folder_model', 'Audit_Log_model']); // Load Audit Log Model
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    /**
     * Display a list of all smart collections for the user.
     */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['collections'] = $this->Smart_Collection_Rule_model->get_user_rules($user_id);
        $data['title'] = 'Smart Collections';

        $this->load->view('templates/header', $data);
        $this->load->view('smart_collections/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display form to create or edit a smart collection rule.
     * @param int|null $id
     */
    public function create_edit($id = null)
    {
        $user_id = $this->session->userdata('user_id');
        $data['rule'] = null;
        $data['files'] = []; // Files matching the rule preview

        if ($id) {
            $data['rule'] = $this->Smart_Collection_Rule_model->get_rule($id, $user_id);
            if (!$data['rule']) {
                $this->session->set_flashdata('error_message', 'Smart collection rule not found.');
                redirect('smartcollections');
                return;
            }
            $data['title'] = 'Edit Smart Collection';
            $data['rule']['rule_json'] = json_decode($data['rule']['rule_json'], true);
            // Apply rule to show preview
            $data['files'] = $this->Smart_Collection_Rule_model->apply_rule($user_id, json_encode($data['rule']['rule_json']));
        } else {
            $data['title'] = 'Create Smart Collection';
        }

        $data['all_mime_types'] = $this->File_model->get_all_mime_types($user_id);
        $data['user_folders'] = $this->Folder_model->get_user_folders($user_id, null);

        $this->load->view('templates/header', $data);
        $this->load->view('smart_collections/create_edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Handle form submission for saving a smart collection rule.
     */
    public function save()
    {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $rule_conditions = $this->input->post('rule_conditions'); // Array of conditions from form
        $user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('name', 'Collection Name', 'required|max_length[255]');
        // Further validation for rule_conditions will be done client-side or within the model's apply_rule if strict

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
            redirect($id ? 'smartcollections/create_edit/' . $id : 'smartcollections/create_edit');
            return;
        }

        // Construct rule JSON from conditions
        $rule_json = json_encode(['conditions' => $rule_conditions, 'logic' => 'AND']); // Simple logic for now

        $data = [
            'user_id' => $user_id,
            'name' => $name,
            'rule_json' => $rule_json,
        ];

        if ($id) {
            $old_rule_data = $this->Smart_Collection_Rule_model->get_rule($id, $user_id);
            $success = $this->Smart_Collection_Rule_model->update_rule($id, $user_id, $data);
            if ($success) {
                $this->Audit_Log_model->log_action(
                    'smart_collection_updated',
                    'smart_collection',
                    $id,
                    $old_rule_data,
                    $data
                );
                $this->session->set_flashdata('success_message', 'Smart collection updated successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to update smart collection.');
            }
        } else {
            $new_rule_id = $this->Smart_Collection_Rule_model->create_rule($data);
            if ($new_rule_id) {
                $this->Audit_Log_model->log_action(
                    'smart_collection_created',
                    'smart_collection',
                    $new_rule_id,
                    [],
                    $data
                );
                $this->session->set_flashdata('success_message', 'Smart collection created successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to create smart collection.');
            }
        }

        redirect('smartcollections');
    }

    /**
     * Soft delete a smart collection rule.
     * @param int $id
     */
    public function delete($id)
    {
        $user_id = $this->session->userdata('user_id');
        $old_rule_data = $this->Smart_Collection_Rule_model->get_rule($id, $user_id);
        if (!$old_rule_data) {
            $this->session->set_flashdata('error_message', 'Smart collection rule not found.');
            redirect('smartcollections');
            return;
        }

        $success = $this->Smart_Collection_Rule_model->delete_rule($id, $user_id);
        if ($success) {
            $this->Audit_Log_model->log_action(
                'smart_collection_deleted',
                'smart_collection',
                $id,
                ['deleted_at' => null], // Assuming it was not deleted before
                ['deleted_at' => date('Y-m-d H:i:s')]
            );
            $this->session->set_flashdata('success_message', 'Smart collection deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to delete smart collection.');
        }
        redirect('smartcollections');
    }

    /**
     * View files belonging to a smart collection.
     * @param int $id
     */
    public function view_collection($id)
    {
        $user_id = $this->session->userdata('user_id');
        $rule = $this->Smart_Collection_Rule_model->get_rule($id, $user_id);
        if (!$rule) {
            $this->session->set_flashdata('error_message', 'Smart collection not found.');
            redirect('smartcollections');
            return;
        }

        $files = $this->Smart_Collection_Rule_model->apply_rule($user_id, $rule['rule_json']);

        // Generate thumbnail URLs
        foreach ($files as &$file) {
            $file['thumbnail_url'] = null;
            if (!empty($file['thumbnail_file_id']) && !empty($file['bot_id'])) {
                $bot_record = $this->Bot_model->get_bot_by_id($file['bot_id']);
                if ($bot_record && !empty($bot_record['token'])) {
                    $this->load->model('Telegram_bot_model'); // Load if not already loaded
                    if ($this->Telegram_bot_model->init($bot_record['token'])) {
                        $file['thumbnail_url'] = $this->Telegram_bot_model->get_file_url($file['thumbnail_file_id']);
                    }
                }
            }
        }

        $data['files'] = $files;
        $data['title'] = $rule['name'];
        $data['rule_name'] = $rule['name'];

        $this->load->view('templates/header', $data);
        $this->load->view('smart_collections/view_collection', $data); // New view for displaying files in a smart collection
        $this->load->view('templates/footer');
    }
}