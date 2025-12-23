<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bots extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Please log in.');
            redirect('miniapp/unauthorized');
            return;
        }
        
        // This entire controller requires 'manage_bots' permission.
        if (!has_permission('manage_bots')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions for Bot Management.');
            redirect('dashboard'); // Redirect to a safe page
            return;
        }

        $this->load->model(['Bot_model', 'Audit_Log_model']);
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    /**
     * Display a list of all bots.
     */
    public function index()
    {
        $data['bots'] = $this->Bot_model->get_all_bots();
        $data['title'] = 'Manage Bots';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('bots/index', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Display form to add a new bot or edit an existing one.
     * @param int $id Bot ID to edit, if any.
     */
    public function form($id = null)
    {
        $data['bot'] = null;
        if ($id) {
            $data['bot'] = $this->Bot_model->get_bot_by_id($id);
            if (!$data['bot']) {
                $this->session->set_flashdata('error_message', 'Bot not found.');
                redirect('bots');
                return;
            }
        }
        $data['title'] = $id ? 'Edit Bot' : 'Add New Bot';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('bots/form', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle form submission for adding or updating a bot.
     */
    public function save()
    {
        $id = $this->input->post('id');
        
        $this->form_validation->set_rules('bot_id_telegram', 'Telegram Bot ID', 'required|numeric');
        $this->form_validation->set_rules('name', 'Bot Name', 'required|max_length[255]');
        $this->form_validation->set_rules('token', 'Bot Token', 'required|max_length[255]');
        $this->form_validation->set_rules('storage_channel_id', 'Storage Channel ID', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            // Reload the form with validation errors and input data
            $this->form($id);
            return;
        }

        $bot_data = [
            'bot_id_telegram' => $this->input->post('bot_id_telegram'),
            'name' => $this->input->post('name'),
            'token' => $this->input->post('token'),
            'storage_channel_id' => $this->input->post('storage_channel_id')
        ];

        if ($id) {
            // Update existing bot
            $old_bot_data = $this->Bot_model->get_bot_by_id($id);
            $success = $this->Bot_model->update_bot($id, $bot_data);
            if ($success) {
                $this->Audit_Log_model->log_action('bot_updated', 'bot', $id, $old_bot_data, $bot_data);
                $this->session->set_flashdata('success_message', 'Bot updated successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to update bot.');
            }
        } else {
            // Add new bot
            $new_bot_id = $this->Bot_model->create_bot($bot_data);
            if ($new_bot_id) {
                $this->Audit_Log_model->log_action('bot_created', 'bot', $new_bot_id, [], $bot_data);
                $this->session->set_flashdata('success_message', 'Bot added successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to add bot. It might already exist.');
            }
        }
        redirect('bots');
    }

    /**
     * Delete a bot.
     * @param int $id Bot ID to delete.
     */
    public function delete($id)
    {
        if (!$id) {
            $this->session->set_flashdata('error_message', 'Bot ID is required for deletion.');
            redirect('bots');
            return;
        }

        $old_bot_data = $this->Bot_model->get_bot_by_id($id);
        if (!$old_bot_data) {
            $this->session->set_flashdata('error_message', 'Bot not found.');
            redirect('bots');
            return;
        }

        $success = $this->Bot_model->delete_bot($id);
        if ($success) {
            $this->Audit_Log_model->log_action('bot_deleted', 'bot', $id, $old_bot_data, []);
            $this->session->set_flashdata('success_message', 'Bot deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to delete bot.');
        }
        redirect('bots');
    }
}
