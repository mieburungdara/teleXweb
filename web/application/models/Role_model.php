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
     * Get the permissions for a specific role.
     *
     * @param int $role_id
     * @return array An array of permissions, or an empty array if none.
     */
    public function get_role_permissions($role_id)
    {
        $role = $this->get_role_by_id($role_id);
        if ($role && !empty($role['permissions'])) {
            return json_decode($role['permissions'], true);
        }
        return [];
    }

    /**
     * Update the permissions for a specific role.
     *
     * @param int $role_id
     * @param array $permissions An array of permission strings.
     * @return bool
     */
    public function update_role_permissions($role_id, $permissions)
    {
        $data = ['permissions' => json_encode($permissions)];
        $this->db->where('id', $role_id);
        return $this->db->update('roles', $data);
    }
}
