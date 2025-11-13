<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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

    // Other user-related methods would go here
}
