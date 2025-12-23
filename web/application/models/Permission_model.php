<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all permission records.
     *
     * @return array An array of all permission data.
     */
    public function get_all_permissions()
    {
        $query = $this->db->get('permissions');
        return $query->result_array();
    }

    /**
     * Get a single permission by its primary ID.
     *
     * @param int $id
     * @return array|null Permission data if found, null otherwise.
     */
    public function get_permission_by_id($id)
    {
        $query = $this->db->get_where('permissions', ['id' => $id]);
        return $query->row_array();
    }

    /**
     * Create a new permission, checking for uniqueness first.
     *
     * @param array $data
     * @return int|false The ID of the new permission, or false on failure or if name exists.
     */
    public function create_permission($data)
    {
        // Check if a permission with this name already exists
        $query = $this->db->get_where('permissions', ['permission_name' => $data['permission_name']]);
        if ($query->num_rows() > 0) {
            return false; // A permission with this name already exists
        }

        // Proceed with insertion
        if ($this->db->insert('permissions', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update a permission.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_permission($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('permissions', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete a permission.
     *
     * @param int $id
     * @return bool
     */
    public function delete_permission($id)
    {
        $this->db->delete('permissions', ['id' => $id]);
        return $this->db->affected_rows() > 0;
    }
}
