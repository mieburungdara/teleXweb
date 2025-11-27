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

    /**
     * Count all user records.
     *
     * @return int
     */
    public function count_all_users()
    {
        return $this->db->count_all('users');
    }

    /**
     * Get comprehensive profile data for a user.
     *
     * @param int $user_id
     * @return array|null User data including file and folder counts.
     */
    public function get_user_profile_data($user_id)
    {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('users.id', $user_id);
        $user_data = $this->db->get()->row_array();

        if ($user_data) {
            $this->load->model('File_model');
            $this->load->model('Folder_model');
            $user_data['total_files'] = $this->File_model->count_all_files_for_user($user_id); // Need to add this method
            $user_data['total_folders'] = $this->Folder_model->count_all_folders_for_user($user_id); // Need to add this method
        }
        return $user_data;
    }

    /**
     * Define XP thresholds for levels.
     * @return array
     */
    private function get_xp_thresholds()
    {
        return [
            1 => 0,    // Level 1 starts at 0 XP
            2 => 100,  // Level 2 at 100 XP
            3 => 250,  // Level 3 at 250 XP
            4 => 500,
            5 => 1000,
            // Add more levels and thresholds as needed
        ];
    }

    /**
     * Add XP to a user and check for level up.
     *
     * @param int $user_id
     * @param int $amount
     * @return bool TRUE on success, FALSE on failure.
     */
    public function add_xp($user_id, $amount)
    {
        $user = $this->get_user_by_id($user_id);
        if (!$user) {
            return FALSE;
        }

        $new_xp = $user['xp'] + $amount;
        $this->db->where('id', $user_id);
        $this->db->update('users', ['xp' => $new_xp, 'updated_at' => date('Y-m-d H:i:s')]);

        return $this->check_level_up($user_id);
    }

    /**
     * Check if a user has leveled up and update their level.
     *
     * @param int $user_id
     * @return bool TRUE if level changed, FALSE otherwise.
     */
    public function check_level_up($user_id)
    {
        $user = $this->get_user_by_id($user_id);
        if (!$user) {
            return FALSE;
        }

        $current_level = $user['level'];
        $current_xp = $user['xp'];
        $xp_thresholds = $this->get_xp_thresholds();
        $leveled_up = FALSE;

        foreach ($xp_thresholds as $level => $threshold_xp) {
            if ($current_xp >= $threshold_xp && $level > $current_level) {
                $this->db->where('id', $user_id);
                $this->db->update('users', ['level' => $level, 'updated_at' => date('Y-m-d H:i:s')]);
                $leveled_up = TRUE;
                // You might want to add a notification here for the user
                $this->load->model('Audit_Log_model');
                $this->Audit_Log_model->log_action(
                    'user_leveled_up',
                    'user',
                    $user_id,
                    ['old_level' => $current_level],
                    ['new_level' => $level]
                );
            }
        }
        return $leveled_up;
    }

    /**
     * Get a user's current balance.
     *
     * @param int $user_id
     * @return float
     */
    public function get_user_balance($user_id)
    {
        $user = $this->get_user_by_id($user_id); // Re-use existing method to get user
        return $user ? (float)$user['balance'] : 0.00;
    }

    /**
     * Update a user's balance directly. Used internally by Balance_Transaction_model.
     *
     * @param int $user_id
     * @param float $new_balance
     * @return bool
     */
    public function update_user_balance_value($user_id, $new_balance)
    {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['balance' => $new_balance, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Update a user's balance and record a transaction.
     *
     * @param int $user_id
     * @param float $amount The amount to add (credit) or deduct (debit).
     * @param string $type 'credit' or 'debit'.
     * @param string $description Description of the transaction.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function update_balance($user_id, $amount, $type, $description)
    {
        $this->load->model('Balance_Transaction_model'); // Ensure Balance_Transaction_model is loaded
        return $this->Balance_Transaction_model->record_transaction($user_id, $amount, $type, $description);
    }
}
