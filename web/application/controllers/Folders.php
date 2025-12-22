<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folders extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Folder_model', 'Folder_Review_model', 'File_model', 'Folder_Like_model', 'Access_Log_model', 'Audit_Log_model', 'Folder_Comment_model', 'User_model']); // Load Folder_Comment_model and User_model
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

        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('folder_management_view', $data);
        $this->load->view('templates/dashmix_footer');
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
        // Log access
        $this->Access_Log_model->log_access('folder', $id, $user_id);

        $data['folder']['tags'] = implode(', ', $this->Folder_model->get_folder_tags($id));
        $data['reviews'] = $this->Folder_Review_model->get_reviews_for_folder($id);
        $data['stats'] = $this->Folder_model->get_folder_stats($id); // Fetch stats
        $data['title'] = 'View Folder: ' . $data['folder']['folder_name'];

        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('folders/view', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Public view for a shared folder.
     * @param string $code The folder's unique share code.
     */
    public function view_shared($code)
    {
        // Cache this page for 60 minutes
        $this->output->cache(60);

        $data['folder'] = $this->Folder_model->get_folder_by_code($code);
        if (!$data['folder']) {
            $this->session->set_flashdata('error_message', 'Shared folder not found or has been deleted.');
            redirect('miniapp/unauthorized'); // Or a public 404 page
            return;
        }
        $folder_id = $data['folder']['id'];
        
        // Log access
        $user_id = $this->session->userdata('logged_in') ? $this->session->userdata('user_id') : null;
        $this->Access_Log_model->log_access('folder', $folder_id, $user_id);

        $data['folder']['tags'] = implode(', ', $this->Folder_model->get_folder_tags($folder_id));
        $data['reviews'] = $this->Folder_Review_model->get_reviews_for_folder($folder_id);
        $data['comments'] = $this->Folder_Comment_model->get_comments_for_folder($folder_id); // Fetch comments
        $data['like_count'] = $this->Folder_Like_model->get_like_count($folder_id);
        $data['user_has_liked'] = $this->session->userdata('logged_in') ? $this->Folder_Like_model->has_user_liked($folder_id, $this->session->userdata('user_id')) : false;

        $data['title'] = 'Shared Folder: ' . $data['folder']['folder_name'];

        $this->load->view('templates/header', $data);
        $this->load->view('folders/view_shared', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Handle form submission for adding a comment to a shared folder.
     */
    public function submit_comment()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'You must be logged in to post a comment.');
            redirect('miniapp/unauthorized');
            return;
        }

        $folder_id = $this->input->post('folder_id');
        $comment_text = $this->input->post('comment_text');
        $user_id = $this->session->userdata('user_id');
        $parent_comment_id = $this->input->post('parent_comment_id'); // For replies

        $this->form_validation->set_rules('comment_text', 'Comment', 'required|max_length[1000]');
        $this->form_validation->set_rules('folder_id', 'Folder ID', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
        } else {
            $comment_data = [
                'folder_id' => $folder_id,
                'user_id' => $user_id,
                'comment_text' => $comment_text,
                'parent_comment_id' => !empty($parent_comment_id) ? $parent_comment_id : null,
            ];

            if ($this->Folder_Comment_model->create_comment($comment_data)) {
                 $this->Audit_Log_model->log_action(
                    'folder_comment_added',
                    'folder_comment',
                    $this->db->insert_id(), // Get the ID of the newly created comment
                    [],
                    $comment_data
                );
                $this->User_model->add_xp($user_id, 5); // Award XP for commenting
                $this->session->set_flashdata('success_message', 'Comment posted successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to post comment.');
            }
        }
        $folder = $this->Folder_model->get_folder_by_id($folder_id, $user_id); // Fetch folder to redirect
        if ($folder) {
            redirect('folders/view_shared/' . $folder['code']);
        } else {
            redirect('folders'); // Fallback
        }
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
        $folder = $this->Folder_model->get_folder_by_id($folder_id, $user_id);
        if (!$folder) {
            $this->session->set_flashdata('error_message', 'Folder not found.');
            redirect('folders');
            return;
        }
        
        $old_is_favorited = $folder['is_favorited'];

        $this->Folder_Like_model->toggle_like($folder_id, $user_id);
        
        // Assuming toggle_like also updates the is_favorited status in the DB
        $new_folder_data = $this->Folder_model->get_folder_by_id($folder_id, $user_id);
        $new_is_favorited = $new_folder_data['is_favorited'];

        $this->Audit_Log_model->log_action(
            'toggle_folder_like',
            'folder',
            $folder_id,
            ['is_favorited' => $old_is_favorited],
            ['is_favorited' => $new_is_favorited]
        );

        // Redirect back to the shared folder view
        $folder_after_toggle = $this->db->get_where('folders', ['id' => $folder_id])->row_array();
        redirect('folders/view_shared/' . $folder_after_toggle['code']);
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

        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('folders/share', $data);
        $this->load->view('templates/dashmix_footer');
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
                 $this->Audit_Log_model->log_action(
                    'folder_review_added',
                    'folder',
                    $folder_id,
                    [],
                    $data
                );
                $this->User_model->add_xp($user_id, 10); // Award XP for submitting a review
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
            $old_folder_data = $this->Folder_model->get_folder_by_id($id, $user_id);
            $success = $this->Folder_model->update_folder($id, $user_id, $folder_data);
            if ($success) {
                $this->Folder_model->update_folder_tags($id, $tags_string, $user_id);
                $this->Audit_Log_model->log_action(
                    'folder_updated',
                    'folder',
                    $id,
                    $old_folder_data,
                    $folder_data
                );
                $this->session->set_flashdata('success_message', 'Folder updated successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to update folder.');
            }
        } else {
            // Create
            $new_folder_id = $this->Folder_model->create_folder($folder_data);
            if ($new_folder_id) {
                $this->Folder_model->update_folder_tags($new_folder_id, $tags_string, $user_id);
                $this->Audit_Log_model->log_action(
                    'folder_created',
                    'folder',
                    $new_folder_id,
                    [],
                    $folder_data
                );
                $this->User_model->add_xp($user_id, 15); // Award XP for creating a folder
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

        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('folder_management_view', $data); // Re-use the same view for edit form
        $this->load->view('templates/dashmix_footer');
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
        $old_folder_data = $this->Folder_model->get_folder_by_id($id, $user_id);
        if (!$old_folder_data) {
            $this->session->set_flashdata('error_message', 'Folder not found.');
            redirect('folders');
            return;
        }

        $success = $this->Folder_model->delete_folder($id, $user_id);
        if ($success) {
            $this->Audit_Log_model->log_action(
                'folder_soft_deleted',
                'folder',
                $id,
                ['deleted_at' => $old_folder_data['deleted_at']],
                ['deleted_at' => date('Y-m-d H:i:s')]
            );
            $this->session->set_flashdata('success_message', 'Folder deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to delete folder.');
        }
        redirect('folders');
    }
}