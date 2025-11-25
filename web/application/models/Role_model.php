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
}
