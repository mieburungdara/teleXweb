<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance_Transaction_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log a balance transaction.
     *
     * @param array $data Transaction data: user_id, transaction_type, amount, description, admin_id (optional), related_entity_type (optional), related_entity_id (optional)
     * @return int Inserted ID
     */
    public function log_transaction($data)
    {
        $this->db->insert('balance_transactions', $data);
        return $this->db->insert_id();
    }

    /**
     * Get all balance transactions for a specific user.
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_transactions($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('balance_transactions');
        return $query->result();
    }

    /**
     * Get all balance transactions (for admin).
     *
     * @return array
     */
    public function get_all_transactions()
    {
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('balance_transactions');
        return $query->result();
    }

    /**
     * Get paginated balance transactions for a specific user.
     *
     * @param int $user_id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_paginated_user_transactions($user_id, $limit, $offset)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('balance_transactions');
        return $query->result();
    }

    /**
     * Count total balance transactions for a specific user.
     *
     * @param int $user_id
     * @return int
     */
    public function count_user_transactions($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('balance_transactions');
    }

    /**
     * Calculates the total income for a user.
     * Income is defined as positive transactions from sales, tips, or rewards.
     *
     * @param int $user_id
     * @return float
     */
    public function get_total_income_for_user($user_id)
    {
        $income_types = ['folder_sale', 'tip_received', 'request_reward'];

        $this->db->select_sum('amount');
        $this->db->where('user_id', $user_id);
        $this->db->where('amount >', 0);
        $this->db->where_in('related_entity_type', $income_types);
        
        $result = $this->db->get('balance_transactions')->row();
        
        return (float)($result->amount ?? 0.0);
    }
}
