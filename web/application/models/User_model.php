<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Find a user by their Telegram ID.
     *
     * @param int $telegram_id
     * @return array|null User data if found, null otherwise.
     */
    public function get_user_by_telegram_id($telegram_id)
    {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('users.telegram_id', $telegram_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Create a new user record.
     *
     * @param array $user_data Data conforming to the users table structure.
     * @return int|bool The ID of the new user on success, or FALSE on failure.
     */
    public function create_user($user_data)
    {
        $user_data['created_at'] = date('Y-m-d H:i:s');
        $user_data['updated_at'] = date('Y-m-d H:i:s');
        $user_data['role_id'] = $user_data['role_id'] ?? 1; // Default to 'user' role ID (1)
        
        if ($this->db->insert('users', $user_data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Update an existing user record.
     *
     * @param int $id The user's primary key ID.
     * @param array $user_data Data to update.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function update_user($id, $user_data)
    {
        $user_data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('users', $user_data);
    }

    /**
     * Find a user by their primary key ID.
     *
     * @param int $id
     * @return array|null User data if found, null otherwise.
     */
    public function get_user_by_id($id)
    {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('users.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get all user records with their role names.
     *
     * @return array An array of all user data.
     */
    public function get_all_users()
    {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Update an existing user's role.
     *
     * @param int $user_id The user's primary key ID.
     * @param int $new_role_id The new role ID.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function update_user_role($user_id, $new_role_id)
    {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['role_id' => $new_role_id, 'updated_at' => date('Y-m-d H:i:s')]);
    }
}
