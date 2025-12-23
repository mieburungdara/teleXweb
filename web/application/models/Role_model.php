<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all role records.
     *
     * @return array An array of all role data.
     */
    public function get_all_roles()
    {
        $query = $this->db->get('roles');
        return $query->result_array();
    }

    /**
     * Get a single role by its primary ID.
     *
     * @param int $id
     * @return array|null Role data if found, null otherwise.
     */
    public function get_role_by_id($id)
    {
        $query = $this->db->get_where('roles', ['id' => $id]);
        return $query->row_array();
    }

    /**
     * Get the permissions for a specific role using the junction table.
     *
     * @param int $role_id
     * @return array An array of permissions, or an empty array if none.
     */
    public function get_role_permissions($role_id)
    {
        $this->db->select('p.*');
        $this->db->from('permissions p');
        $this->db->join('role_permissions rp', 'p.id = rp.permission_id');
        $this->db->where('rp.role_id', $role_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Update the permissions for a specific role by replacing them.
     *
     * @param int $role_id
     * @param array $permission_ids An array of permission IDs.
     * @return bool
     */
    public function update_role_permissions($role_id, $permission_ids)
    {
        $this->db->trans_start();
        
        // Delete existing permissions for the role
        $this->db->where('role_id', $role_id);
        $this->db->delete('role_permissions');

        // Add new permissions if any
        if (!empty($permission_ids)) {
            $data = [];
            foreach ($permission_ids as $permission_id) {
                $data[] = [
                    'role_id' => $role_id,
                    'permission_id' => $permission_id
                ];
            }
            $this->db->insert_batch('role_permissions', $data);
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Add a single permission to a role.
     *
     * @param int $role_id
     * @param int $permission_id
     * @return bool
     */
    public function add_permission_to_role($role_id, $permission_id)
    {
        $data = [
            'role_id' => $role_id,
            'permission_id' => $permission_id
        ];
        // Use insert ignore to prevent errors on duplicate entries
        $query = 'INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)';
        return $this->db->query($query, [$role_id, $permission_id]);
    }

    /**
     * Remove a single permission from a role.
     *
     * @param int $role_id
     * @param int $permission_id
     * @return bool
     */
    public function remove_permission_from_role($role_id, $permission_id)
    {
        $this->db->delete('role_permissions', ['role_id' => $role_id, 'permission_id' => $permission_id]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return int|false The ID of the new role, or false on failure or if name exists.
     */
    public function create_role($data)
    {
        // Check if a role with this name already exists
        $query = $this->db->get_where('roles', ['role_name' => $data['role_name']]);
        if ($query->num_rows() > 0) {
            return false; // A role with this name already exists
        }

        if ($this->db->insert('roles', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update a role's details.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_role($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('roles', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete a role.
     *
     * @param int $id
     * @return bool
     */
    public function delete_role($id)
    {
        $this->db->delete('roles', ['id' => $id]);
        return $this->db->affected_rows() > 0;
    }
}
