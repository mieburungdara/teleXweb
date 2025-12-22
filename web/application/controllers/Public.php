<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Public extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // This controller is for public-facing pages. No auth checks in constructor.
        $this->load->model(['Public_Collection_model', 'Public_Collection_Folder_model', 'Folder_model', 'Audit_Log_model']);
        $this->load->helper('url');
    }

    /**
     * Public view for a shared collection.
     * @param string $access_code The collection's unique access code.
     */
    public function collection($access_code)
    {
        $collection = $this->Public_Collection_model->get_collection_by_code($access_code);

        // This is the crucial check. The model might be filtering by is_private.
        if (!$collection || $collection['is_private'] == 1) { 
            $this->session->set_flashdata('error_message', 'Public collection not found or is private.');
            redirect('miniapp/unauthorized');
            return;
        }

        $data['collection'] = $collection;
        $data['folders'] = $this->Public_Collection_Folder_model->get_folders_in_collection($collection['id']);
        
        // Log access to the public collection
        $user_id = $this->session->userdata('logged_in') ? $this->session->userdata('user_id') : null;
        $this->Audit_Log_model->log_action(
            'public_collection_viewed',
            'public_collection',
            $collection['id'],
            [],
            ['viewer_user_id' => $user_id] 
        );

        $data['title'] = 'View Public Collection: ' . $collection['name'];
        $this->load->view('public_collections/view_public', $data);
    }
}
