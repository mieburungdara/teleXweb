<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Public_Collection_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new public collection.
     *
     * @param array $data
     * @return int|bool The ID of the new collection on success, or FALSE on failure.
     */
    public function create_collection($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['access_code'] = substr(md5(uniqid(rand(), true)), 0, 10); // Generate unique access code
        if ($this->db->insert('public_collections', $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get a public collection by ID.
     *
     * @param int $id
     * @param int $user_id
     * @return array|null
     */
    public function get_collection($id, $user_id)
    {
        $query = $this->db->get_where('public_collections', ['id' => $id, 'user_id' => $user_id, 'deleted_at' => NULL]);
        return $query->row_array();
    }

    /**
     * Get a public collection by access code.
     *
     * @param string $access_code
     * @return array|null
     */
    public function get_collection_by_code($access_code)
    {
        $query = $this->db->get_where('public_collections', ['access_code' => $access_code, 'deleted_at' => NULL, 'is_private' => 0]);
        return $query->row_array();
    }

    /**
     * Get all public collections for a user.
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_collections($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get('public_collections');
        return $query->result_array();
    }

    /**
     * Update a public collection.
     *
     * @param int $id
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_collection($id, $user_id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('public_collections', $data);
    }

    /**
     * Soft delete a public collection.
     *
     * @param int $id
     * @param int $user_id
     * @return bool
     */
    public function delete_collection($id, $user_id)
    {
        $data = ['deleted_at' => date('Y-m-d H:i:s')];
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('public_collections', $data);
    }
}
