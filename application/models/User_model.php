<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Balance_Transaction_model'); // Load Balance_Transaction_model
        // Assuming Telegram_bot_model exists for sending notifications
        $this->load->model('Telegram_bot_model'); // Load Telegram_bot_model
    }

    /**
     * Get a user by ID.
     *
     * @param int $user_id
     * @return object|null
     */
    public function get_user($user_id)
    {
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row();
    }

    /**
     * Update a user's subscription details.
     *
     * @param int $user_id
     * @param string $subscription_plan
     * @param string $payment_status
     * @param string|null $subscription_start_date
     * @param string|null $subscription_end_date
     * @return bool
     */
    public function update_user_subscription_details($user_id, $subscription_plan, $payment_status, $subscription_start_date = null, $subscription_end_date = null)
    {
        $data = array(
            'subscription_plan' => $subscription_plan,
            'payment_status' => $payment_status,
            'subscription_start_date' => $subscription_start_date,
            'subscription_end_date' => $subscription_end_date
        );
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    /**
     * Add amount to user's balance and log the transaction.
     *
     * @param int $user_id
     * @param float $amount
     * @param string $description
     * @param int|null $admin_id
     * @param string|null $related_entity_type
     * @param int|null $related_entity_id
     * @return bool
     */
    public function add_balance($user_id, $amount, $description, $admin_id = null, $related_entity_type = null, $related_entity_id = null)
    {
        $this->db->trans_start(); // Start transaction

        // Update user's balance
        $this->db->set('balance', 'balance + ' . (float)$amount, FALSE);
        $this->db->where('id', $user_id);
        $this->db->update('users');

        // Log transaction
        $transaction_data = array(
            'user_id' => $user_id,
            'transaction_type' => 'top_up', // Or 'refund' depending on context
            'amount' => $amount,
            'description' => $description,
            'admin_id' => $admin_id,
            'related_entity_type' => $related_entity_type,
            'related_entity_id' => $related_entity_id
        );
        $this->Balance_Transaction_model->log_transaction($transaction_data);

        $this->db->trans_complete(); // Complete transaction

        if ($this->db->trans_status()) {
            // Send Telegram notification to user
            $user = $this->get_user($user_id);
            if ($user && isset($this->Telegram_bot_model)) {
                $message = "💰 Your balance has been topped up by " . number_format($amount) . " Credits.\n";
                $message .= "New balance: " . number_format($user->balance) . " Credits.\n";
                $message .= "Description: " . $description;
                $this->Telegram_bot_model->send_message($user_id, $message);
            }
        }

        return $this->db->trans_status();
    }

    /**
     * Deduct amount from user's balance and log the transaction.
     *
     * @param int $user_id
     * @param float $amount
     * @param string $description
     * @param int|null $admin_id
     * @param string|null $related_entity_type
     * @param int|null $related_entity_id
     * @return bool
     */
    public function deduct_balance($user_id, $amount, $description, $admin_id = null, $related_entity_type = null, $related_entity_id = null)
    {
        $this->db->trans_start(); // Start transaction

        // Check if user has sufficient balance (optional, but good practice)
        $user = $this->get_user($user_id);
        if ($user && $user->balance >= $amount) {
            // Update user's balance
            $this->db->set('balance', 'balance - ' . (float)$amount, FALSE);
            $this->db->where('id', $user_id);
            $this->db->update('users');

            // Log transaction
            $transaction_data = array(
                'user_id' => $user_id,
                'transaction_type' => 'deduction', // Or 'purchase'
                'amount' => -$amount, // Store as negative for deduction
                'description' => $description,
                'admin_id' => $admin_id,
                'related_entity_type' => $related_entity_type,
                'related_entity_id' => $related_entity_id
            );
            $this->Balance_Transaction_model->log_transaction($transaction_data);
        } else {
            $this->db->trans_rollback(); // Rollback if insufficient balance
            return false;
        }

        $this->db->trans_complete(); // Complete transaction

        if ($this->db->trans_status()) {
            // Send Telegram notification to user
            $user = $this->get_user($user_id);
            if ($user && isset($this->Telegram_bot_model)) {
                $message = "💸 Your balance has been deducted by " . number_format($amount) . " Credits.\n";
                $message .= "New balance: " . number_format($user->balance) . " Credits.\n";
                $message .= "Description: " . $description;
                $this->Telegram_bot_model->send_message($user_id, $message);
            }
        }

        return $this->db->trans_status();
    }

    /**
     * Get all users.
     *
     * @param string|null $search_term Optional search term to filter users by ID, codename, or username.
     * @return array
     */
    public function get_all_users($search_term = null)
    {
        $this->db->select('id, codename, username, balance'); // Select relevant fields
        if ($search_term) {
            $this->db->group_start();
            $this->db->or_like('id', $search_term);
            $this->db->or_like('codename', $search_term);
            $this->db->or_like('username', $search_term);
            $this->db->group_end();
        }
        $query = $this->db->get('users');
        return $query->result();
    }

}