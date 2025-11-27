<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance_Transaction_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('User_model'); // For updating user balance
    }

    /**
     * Record a balance transaction.
     *
     * @param int $user_id
     * @param float $amount
     * @param string $type 'credit' or 'debit'
     * @param string $description
     * @return int|bool The ID of the new transaction on success, or FALSE on failure.
     */
    public function record_transaction($user_id, $amount, $type, $description)
    {
        $data = [
            'user_id' => $user_id,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if ($this->db->insert('balance_transactions', $data)) {
            // Update user's balance
            $current_balance = $this->User_model->get_user_balance($user_id);
            $new_balance = ($type === 'credit') ? ($current_balance + $amount) : ($current_balance - $amount);
            $this->User_model->update_user_balance_value($user_id, $new_balance); // Need a dedicated method for this
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get transaction history for a user.
     *
     * @param int $user_id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_user_transactions($user_id, $limit = 10, $offset = 0)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('balance_transactions');
        return $query->result_array();
    }
}
