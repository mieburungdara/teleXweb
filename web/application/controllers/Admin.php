<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Ensure user is logged in and is an admin
        if (!is_admin()) {
            $this->session->set_flashdata('error_message', 'Access Denied: Admin privileges required.');
            redirect('miniapp/unauthorized');
            return;
        }
        $this->load->model('Bot_model');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    /**
     * Display a list of all bots.
     */
    public function index()
    {
        $data['bots'] = $this->Bot_model->get_all_bots();
        $this->load->view('admin/bot_list', $data);
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
                redirect('admin');
                return;
            }
        }
        $this->load->view('admin/bot_form', $data);
    }

    /**
     * Handle form submission for adding or updating a bot.
     */
    public function save()
    {
        $id = $this->input->post('id');
        $bot_id_telegram = $this->input->post('bot_id_telegram');
        $name = $this->input->post('name');
        $token = $this->input->post('token');

        $this->form_validation->set_rules('bot_id_telegram', 'Telegram Bot ID', 'required|numeric');
        $this->form_validation->set_rules('name', 'Bot Name', 'required|max_length[255]');
        $this->form_validation->set_rules('token', 'Bot Token', 'required|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            // Validation failed, reload form with errors
            $this->session->set_flashdata('errors', validation_errors());
            if ($id) {
                redirect('admin/form/' . $id);
            } else {
                redirect('admin/form');
            }
            return;
        }

        $bot_data = [
            'bot_id_telegram' => $bot_id_telegram,
            'name' => $name,
            'token' => $token,
        ];

        if ($id) {
            // Update existing bot
            $success = $this->Bot_model->update_bot($id, $bot_data);
            if ($success) {
                $this->session->set_flashdata('success_message', 'Bot updated successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to update bot.');
            }
        } else {
            // Add new bot
            $success = $this->Bot_model->create_bot($bot_data);
            if ($success) {
                $this->session->set_flashdata('success_message', 'Bot added successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to add bot.');
            }
        }
        redirect('admin');
    }

    /**
     * Delete a bot.
     * @param int $id Bot ID to delete.
     */
    public function delete($id)
    {
        if (!$id) {
            $this->session->set_flashdata('error_message', 'Bot ID is required for deletion.');
            redirect('admin');
            return;
        }

        $success = $this->Bot_model->delete_bot($id);
        if ($success) {
            $this->session->set_flashdata('success_message', 'Bot deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to delete bot.');
        }
        redirect('admin');
    }
}
