<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folder_Comment_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new comment for a folder.
     *
     * @param array $data
     * @return int|bool The ID of the new comment on success, or FALSE on failure.
     */
    public function create_comment($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($this->db->insert('folder_comments', $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get comments for a specific folder.
     *
     * @param int $folder_id
     * @return array
     */
    public function get_comments_for_folder($folder_id)
    {
        $this->db->select('folder_comments.*, users.username, users.first_name');
        $this->db->from('folder_comments');
        $this->db->join('users', 'folder_comments.user_id = users.id');
        $this->db->where('folder_comments.folder_id', $folder_id);
        $this->db->where('folder_comments.deleted_at IS NULL');
        $this->db->order_by('folder_comments.created_at', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Update an existing comment.
     *
     * @param int $id
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_comment($id, $user_id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('folder_comments', $data);
    }

    /**
     * Soft delete a comment.
     *
     * @param int $id
     * @param int $user_id
     * @return bool
     */
    public function delete_comment($id, $user_id)
    {
        $data = ['deleted_at' => date('Y-m-d H:i:s')];
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('folder_comments', $data);
    }
}
