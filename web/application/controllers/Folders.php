<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folders extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Folder_model', 'Folder_Review_model', 'File_model', 'Folder_Like_model']);
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
    }

    /**
     * Display a list of all folders for the user, optionally within a parent folder.
     */
    public function index($parent_folder_id = null)
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'You must be logged in to manage folders.');
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        
        $data['folders'] = $this->Folder_model->get_user_folders($user_id, $parent_folder_id);
        $data['all_folders'] = $this->Folder_model->get_user_folders($user_id, null); // For parent dropdown
        $data['breadcrumbs'] = $parent_folder_id ? $this->Folder_model->get_folder_hierarchy($parent_folder_id) : [];
        $data['parent_folder_id'] = $parent_folder_id;
        $data['title'] = 'My Folders';

        $this->load->view('templates/header', $data);
        $this->load->view('folder_management_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display a single folder and its reviews.
     * @param int $id
     */
    public function view($id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $data['folder'] = $this->Folder_model->get_folder_by_id($id, $user_id);
        if (!$data['folder']) {
            $this->session->set_flashdata('error_message', 'Folder not found.');
            redirect('folders');
            return;
        }
        $data['folder']['tags'] = implode(', ', $this->Folder_model->get_folder_tags($id));
        $data['reviews'] = $this->Folder_Review_model->get_reviews_for_folder($id);
        $data['title'] = 'View Folder: ' . $data['folder']['folder_name'];

        $this->load->view('templates/header', $data);
        $this->load->view('folders/view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Public view for a shared folder.
     * @param string $code The folder's unique share code.
     */
    public function view_shared($code)
    {
        $data['folder'] = $this->Folder_model->get_folder_by_code($code);
        if (!$data['folder']) {
            $this->session->set_flashdata('error_message', 'Shared folder not found or has been deleted.');
            redirect('miniapp/unauthorized'); // Or a public 404 page
            return;
        }
        $folder_id = $data['folder']['id'];
        $data['folder']['tags'] = implode(', ', $this->Folder_model->get_folder_tags($folder_id));
        $data['reviews'] = $this->Folder_Review_model->get_reviews_for_folder($folder_id);
        $data['like_count'] = $this->Folder_Like_model->get_like_count($folder_id);
        $data['user_has_liked'] = $this->session->userdata('logged_in') ? $this->Folder_Like_model->has_user_liked($folder_id, $this->session->userdata('user_id')) : false;

        $data['title'] = 'Shared Folder: ' . $data['folder']['folder_name'];

        $this->load->view('templates/header', $data);
        $this->load->view('folders/view_shared', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Toggle the like status of a folder.
     * @param int $folder_id
     */
    public function toggle_like($folder_id)
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'You must be logged in to like a folder.');
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $this->Folder_Like_model->toggle_like($folder_id, $user_id);
        
        // Redirect back to the shared folder view
        $folder = $this->db->get_where('folders', ['id' => $folder_id])->row();
        redirect('folders/view_shared/' . $folder->code);
    }

    /**
     * Display sharing options for a folder.
     * @param int $id
     */
    public function share($id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $data['folder'] = $this->Folder_model->get_folder_by_id($id, $user_id);
        if (!$data['folder']) {
            $this->session->set_flashdata('error_message', 'Folder not found.');
            redirect('folders');
            return;
        }
        $data['title'] = 'Share Folder: ' . $data['folder']['folder_name'];

        $this->load->view('templates/header', $data);
        $this->load->view('folders/share', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Handle form submission for adding a review.
     */
    public function submit_review()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('miniapp/unauthorized');
            return;
        }
        $folder_id = $this->input->post('folder_id');
        $rating = $this->input->post('rating');
        $review_text = $this->input->post('review_text');
        $user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('rating', 'Rating', 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[5]');
        $this->form_validation->set_rules('review_text', 'Review', 'max_length[1000]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
        } else {
            $data = [
                'folder_id' => $folder_id,
                'user_id' => $user_id,
                'rating' => $rating,
                'review_text' => $review_text,
            ];
            // In a real app, you would check if the user already reviewed this.
            // For now, we allow multiple reviews for simplicity of this step.
            if ($this->Folder_Review_model->add_review($data)) {
                $this->session->set_flashdata('success_message', 'Review submitted successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to submit review.');
            }
        }
        redirect('folders/view/' . $folder_id);
    }


    /**
     * Handle form submission for adding or updating a folder.
     */
    public function save()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('miniapp/unauthorized');
            return;
        }
        $id = $this->input->post('id');
        $folder_name = $this->input->post('folder_name');
        $description = $this->input->post('description');
        $tags_string = $this->input->post('tags');
        $parent_folder_id = $this->input->post('parent_folder_id') ?: null;
        $user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('folder_name', 'Folder Name', 'required|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
            redirect('folders/index/' . $parent_folder_id);
            return;
        }

        $folder_data = [
            'user_id' => $user_id,
            'folder_name' => $folder_name,
            'description' => $description,
            'parent_folder_id' => $parent_folder_id,
        ];

        if ($id) {
            // Update
            $this->Folder_model->update_folder($id, $user_id, $folder_data);
            $this->Folder_model->update_folder_tags($id, $tags_string, $user_id);
            $this->session->set_flashdata('success_message', 'Folder updated successfully.');
        } else {
            // Create
            $new_folder_id = $this->Folder_model->create_folder($folder_data);
            if ($new_folder_id) {
                $this->Folder_model->update_folder_tags($new_folder_id, $tags_string, $user_id);
                $this->session->set_flashdata('success_message', 'Folder created successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to create folder.');
            }
        }
        redirect('folders/index/' . $parent_folder_id);
    }

    /**
     * Display form to edit a folder.
     * @param int $id
     */
    public function edit($id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $data['folder'] = $this->Folder_model->get_folder_by_id($id, $user_id);
        if (!$data['folder']) {
            $this->session->set_flashdata('error_message', 'Folder not found.');
            redirect('folders');
            return;
        }
        $data['folder']['tags'] = implode(', ', $this->Folder_model->get_folder_tags($id));
        $data['all_folders'] = $this->Folder_model->get_user_folders($user_id, null); // For parent dropdown
        $data['breadcrumbs'] = $this->Folder_model->get_folder_hierarchy($data['folder']['parent_folder_id']);
        $data['title'] = 'Edit Folder';

        $this->load->view('templates/header', $data);
        $this->load->view('folder_management_view', $data); // Re-use the same view for edit form
        $this->load->view('templates/footer');
    }

    /**
     * Delete a folder (soft delete).
     * @param int $id
     */
    public function delete($id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $this->Folder_model->delete_folder($id, $user_id);
        $this->session->set_flashdata('success_message', 'Folder deleted successfully.');
        redirect('folders');
    }
}