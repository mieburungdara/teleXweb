<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folder_Review_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Add a review for a folder.
     *
     * @param array $data
     * @return bool
     */
    public function add_review($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('folder_reviews', $data);
    }

    /**
     * Get all reviews for a folder.
     *
     * @param int $folder_id
     * @return array
     */
    public function get_reviews_for_folder($folder_id)
    {
        $this->db->select('folder_reviews.*, users.username, users.first_name');
        $this->db->from('folder_reviews');
        $this->db->join('users', 'folder_reviews.user_id = users.id');
        $this->db->where('folder_reviews.folder_id', $folder_id);
        $this->db->where('folder_reviews.deleted_at IS NULL');
        $this->db->order_by('folder_reviews.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Update a review.
     *
     * @param int $review_id
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_review($review_id, $user_id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $review_id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('folder_reviews', $data);
    }

    /**
     * Soft delete a review.
     *
     * @param int $review_id
     * @param int $user_id
     * @return bool
     */
    public function delete_review($review_id, $user_id)
    {
        $data = ['deleted_at' => date('Y-m-d H:i:s')];
        $this->db->where('id', $review_id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('folder_reviews', $data);
    }
}
