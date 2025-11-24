<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_submission_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Creates a new submission for a request.
     *
     * @param array $data Associative array of submission data.
     * @return int The ID of the newly inserted submission.
     */
    public function create_submission($data)
    {
        $this->db->insert('request_submissions', $data);
        return $this->db->insert_id();
    }

    /**
     * Retrieves a single submission by its ID.
     *
     * @param int $submission_id
     * @return object|null
     */
    public function get_submission($submission_id)
    {
        return $this->db->get_where('request_submissions', ['id' => $submission_id])->row();
    }

    /**
     * Retrieves all submissions for a given request.
     *
     * @param int $request_id
     * @return array
     */
    public function get_submissions_for_request($request_id)
    {
        $this->db->select('rs.*, c.username as creator_username, f.folder_name');
        $this->db->from('request_submissions rs');
        $this->db->join('users c', 'c.id = rs.creator_user_id');
        $this->db->join('folders f', 'f.id = rs.folder_id');
        $this->db->where('rs.request_id', $request_id);
        $this->db->order_by('rs.submitted_at', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Retrieves all submissions made by a specific creator.
     *
     * @param int $creator_user_id
     * @return array
     */
    public function get_submissions_by_creator($creator_user_id)
    {
        $this->db->where('creator_user_id', $creator_user_id);
        $this->db->order_by('submitted_at', 'DESC');
        return $this->db->get('request_submissions')->result();
    }

    /**
     * Updates a submission record. Used to change status, etc.
     *
     * @param int $submission_id
     * @param array $data
     * @return bool
     */
    public function update_submission($submission_id, $data)
    {
        return $this->db->update('request_submissions', $data, ['id' => $submission_id]);
    }

    /**
     * Checks if a creator has already submitted to a request.
     *
     * @param int $request_id
     * @param int $creator_user_id
     * @return bool
     */
    public function has_creator_submitted($request_id, $creator_user_id)
    {
        $this->db->where('request_id', $request_id);
        $this->db->where('creator_user_id', $creator_user_id);
        return $this->db->count_all_results('request_submissions') > 0;
    }
}
