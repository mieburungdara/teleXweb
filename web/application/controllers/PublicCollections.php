<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PublicCollections extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Public_Collection_model', 'Public_Collection_Folder_model', 'Folder_model', 'Audit_Log_model']);
        $this->load->library('form_validation');
        $this->load->helper('url'); // Removed 'auth_helper' from here
    }

    /**
     * Display a list of all public collections for the user.
     */
    public function index()
    {
        $this->load->helper('auth_helper'); // Load auth helper specifically for this method
        if (!has_permission('manage_public_collections')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $data['collections'] = $this->Public_Collection_model->get_user_collections($user_id);
        $data['title'] = 'My Public Collections';

        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('public_collections/index', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Display form to create or edit a public collection.
     * @param int|null $id
     */
    public function create_edit($id = null)
    {
        $this->load->helper('auth_helper'); // Load auth helper specifically for this method
        if (!has_permission('manage_public_collections')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $data['collection'] = null;
        $data['folders_in_collection'] = [];

        if ($id) {
            $data['collection'] = $this->Public_Collection_model->get_collection($id, $user_id);
            if (!$data['collection']) {
                $this->session->set_flashdata('error_message', 'Public collection not found.');
                redirect('publiccollections');
                return;
            }
            $data['folders_in_collection'] = $this->Public_Collection_Folder_model->get_folders_in_collection($id);
            $data['title'] = 'Edit Public Collection';
        } else {
            $data['title'] = 'Create New Public Collection';
        }

        $data['available_folders'] = $this->Folder_model->get_user_folders($user_id); // All user folders
        
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('public_collections/create_edit', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle form submission for saving a public collection.
     */
    public function save()
    {
        $this->load->helper('auth_helper'); // Load auth helper specifically for this method
        if (!has_permission('manage_public_collections')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $description = $this->input->post('description');
        $is_private = $this->input->post('is_private') ? 1 : 0;
        $selected_folders = $this->input->post('folders'); // Array of folder IDs
        $user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('name', 'Collection Name', 'required|max_length[255]');
        $this->form_validation->set_rules('description', 'Description', 'max_length[1000]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect($id ? 'publiccollections/create_edit/' . $id : 'publiccollections/create_edit');
            return;
        }

        $collection_data = [
            'user_id' => $user_id,
            'name' => $name,
            'description' => $description,
            'is_private' => $is_private,
        ];

        if ($id) {
            $old_collection_data = $this->Public_Collection_model->get_collection($id, $user_id);
            $success = $this->Public_Collection_model->update_collection($id, $user_id, $collection_data);
            if ($success) {
                // Update associated folders
                $this->Public_Collection_Folder_model->remove_all_folders_from_collection($id);
                if (!empty($selected_folders)) {
                    foreach ($selected_folders as $order => $folder_id) {
                        $this->Public_Collection_Folder_model->add_folder_to_collection($id, $folder_id, $order);
                    }
                }
                $this->Audit_Log_model->log_action(
                    'public_collection_updated',
                    'public_collection',
                    $id,
                    $old_collection_data,
                    $collection_data
                );
                $this->session->set_flashdata('success_message', 'Public collection updated successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to update public collection.');
            }
        } else {
            $new_collection_id = $this->Public_Collection_model->create_collection($collection_data);
            if ($new_collection_id) {
                if (!empty($selected_folders)) {
                    foreach ($selected_folders as $order => $folder_id) {
                        $this->Public_Collection_Folder_model->add_folder_to_collection($new_collection_id, $folder_id, $order);
                    }
                }
                $this->Audit_Log_model->log_action(
                    'public_collection_created',
                    'public_collection',
                    $new_collection_id,
                    [],
                    $collection_data
                );
                $this->session->set_flashdata('success_message', 'Public collection created successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to create public collection.');
            }
        }
        redirect('publiccollections');
    }

    /**
     * Soft delete a public collection.
     * @param int $id
     */
    public function delete($id)
    {
        $this->load->helper('auth_helper'); // Load auth helper specifically for this method
        if (!has_permission('manage_public_collections')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $old_collection_data = $this->Public_Collection_model->get_collection($id, $user_id);
        if (!$old_collection_data) {
            $this->session->set_flashdata('error_message', 'Public collection not found.');
            redirect('publiccollections');
            return;
        }

        $success = $this->Public_Collection_model->delete_collection($id, $user_id);
        if ($success) {
            $this->Audit_Log_model->log_action(
                'public_collection_deleted',
                'public_collection',
                $id,
                ['deleted_at' => null],
                ['deleted_at' => date('Y-m-d H:i:s')]
            );
            $this->session->set_flashdata('success_message', 'Public collection deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to delete public collection.');
        }
        redirect('publiccollections');
    }

}
