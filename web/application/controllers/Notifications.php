<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'You must be logged in to view notifications.');
            redirect('miniapp/unauthorized');
            return;
        }
        $this->load->model('Notification_model');
        $this->load->helper('url');
    }

    /**
     * Display a list of the current user's notifications.
     */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['notifications'] = $this->Notification_model->get_user_notifications($user_id);
        $data['title'] = 'My Notifications';

        $this->load->view('templates/header', $data);
        $this->load->view('notifications/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Mark a specific notification as read.
     * @param int $notification_id
     */
    public function mark_as_read($notification_id)
    {
        $user_id = $this->session->userdata('user_id');
        $success = $this->Notification_model->mark_as_read($notification_id, $user_id);

        if ($success) {
            $this->session->set_flashdata('success_message', 'Notification marked as read.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to mark notification as read.');
        }
        redirect('notifications');
    }
}
