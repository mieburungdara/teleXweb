<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TagManagement extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Please log in.');
            redirect('miniapp/unauthorized');
            return;
        }
        $this->load->model('Tag_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'auth_helper']); // Ensure auth_helper is loaded
    }

    /**
     * Display a list of all tags.
     */
    public function index()
    {
        if (!has_permission('manage_tags')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $data['tags'] = $this->Tag_model->get_all_tags(); // Need to add get_all_tags to Tag_model
        $data['title'] = 'Tag Management';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/tag_management/index', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Suggest potential duplicate tags.
     */
    public function find_duplicates()
    {
        if (!has_permission('manage_tags')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $data['duplicates'] = [];
        $tags = $this->Tag_model->get_all_tags();
        foreach ($tags as $tag) {
            $similar_tags = $this->Tag_model->find_similar_tags($tag['tag_name']);
            if (count($similar_tags) > 1) {
                // Filter out self and exact matches (case-insensitive)
                $filtered_similar_tags = array_filter($similar_tags, function($s_tag) use ($tag) {
                    return $s_tag['id'] != $tag['id'] && strtolower($s_tag['tag_name']) == strtolower($tag['tag_name']);
                });

                if (!empty($filtered_similar_tags)) {
                    // Ensure each group of duplicates is added only once
                    $group_key = strtolower($tag['tag_name']);
                    if (!isset($data['duplicates'][$group_key])) {
                        $data['duplicates'][$group_key] = array_merge([$tag], $filtered_similar_tags);
                    }
                }
            }
        }
        $data['title'] = 'Find Duplicate Tags';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/tag_management/duplicates', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle merging of tags.
     */
    public function merge()
    {
        if (!has_permission('manage_tags')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $source_tag_id = $this->input->post('source_tag_id');
        $target_tag_id = $this->input->post('target_tag_id');

        $this->form_validation->set_rules('source_tag_id', 'Source Tag', 'required|numeric');
        $this->form_validation->set_rules('target_tag_id', 'Target Tag', 'required|numeric|differs[source_tag_id]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect('tagmanagement/find_duplicates');
            return;
        }

        $source_tag = $this->Tag_model->get_tag_by_id($source_tag_id); // Need to add get_tag_by_id to Tag_model
        $target_tag = $this->Tag_model->get_tag_by_id($target_tag_id);

        if (!$source_tag || !$target_tag) {
            $this->session->set_flashdata('error_message', 'Source or target tag not found.');
            redirect('tagmanagement/find_duplicates');
            return;
        }

        $success = $this->Tag_model->merge_tags($source_tag_id, $target_tag_id);
        if ($success) {
            // Log audit trail
            $this->load->model('Audit_Log_model');
            $this->Audit_Log_model->log_action(
                'tags_merged',
                'tag',
                $target_tag_id,
                ['source_tag_id' => $source_tag_id, 'source_tag_name' => $source_tag['tag_name']],
                ['target_tag_id' => $target_tag_id, 'target_tag_name' => $target_tag['tag_name']]
            );
            $this->session->set_flashdata('success_message', 'Tags merged successfully. "' . htmlspecialchars($source_tag['tag_name']) . '" merged into "' . htmlspecialchars($target_tag['tag_name']) . '".');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to merge tags.');
        }
        redirect('tagmanagement/find_duplicates');
    }
}
