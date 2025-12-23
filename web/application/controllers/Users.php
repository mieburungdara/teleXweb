<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'You must be logged in to view your profile.');
            redirect('miniapp/unauthorized');
            return;
        }
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'auth_helper']);
    }

    /**
     * Display the current user's profile.
     */
    public function profile()
    {
        if (!has_permission('view_own_profile')) {
            $this->session->set_flashdata('error_message', 'Access Denied: You do not have permission to view your profile.');
            redirect('miniapp/unauthorized');
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_profile_data($user_id);
        if (!$data['user']) {
            $this->session->set_flashdata('error_message', 'User profile not found.');
            redirect('dashboard'); // Redirect to a safe page
            return;
        }

        // Load additional models and data for the dashboard
        $this->load->model('Folder_model');
        $this->load->model('File_model');
        $data['recent_folders'] = $this->Folder_model->get_recent_folders_by_user($user_id, 5);
        $data['recent_files'] = $this->File_model->get_recent_files($user_id, 5);

        $data['title'] = 'My Profile';

        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('user/profile', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Display form to edit user profile.
     */
    public function edit_profile()
    {
        if (!has_permission('edit_own_profile')) {
            $this->session->set_flashdata('error_message', 'Access Denied: You do not have permission to edit your profile.');
            redirect('miniapp/unauthorized');
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        if (!$data['user']) {
            $this->session->set_flashdata('error_message', 'User profile not found.');
            redirect('dashboard');
            return;
        }
        $data['title'] = 'Edit Profile';

        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('user/profile_edit', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle form submission for updating profile.
     */
    public function update_profile()
    {
        if (!has_permission('edit_own_profile')) {
            $this->session->set_flashdata('error_message', 'Access Denied: You do not have permission to edit your profile.');
            redirect('miniapp/unauthorized');
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $username = $this->input->post('username');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[50]|alpha_dash');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|max_length[50]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect('users/edit_profile');
            return;
        }

        $old_user_data = $this->User_model->get_user_by_id($user_id);

        $user_data_to_update = [
            'username' => $username,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ];

        $success = $this->User_model->update_user($user_id, $user_data_to_update);

        if ($success) {
            $this->load->model('Audit_Log_model');
            $this->Audit_Log_model->log_action(
                'user_profile_updated',
                'user',
                $user_id,
                ['username' => $old_user_data['username'], 'first_name' => $old_user_data['first_name'], 'last_name' => $old_user_data['last_name']],
                $user_data_to_update
            );
            $this->session->set_flashdata('success_message', 'Profile updated successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to update profile.');
        }
        redirect('users/profile');
    }
}
