<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get a subscription by its ID.
     *
     * @param int $subscription_id
     * @return object|null
     */
    public function get_subscription($subscription_id)
    {
        $this->db->where('id', $subscription_id);
        $query = $this->db->get('subscriptions');
        return $query->row();
    }

    /**
     * Get a user's active subscription.
     *
     * @param int $user_id
     * @return object|null
     */
    public function get_user_active_subscription($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'active'); // Assuming 'active' is the status for current subscriptions
        $query = $this->db->get('subscriptions');
        return $query->row();
    }

    /**
     * Create a new subscription.
     *
     * @param array $data
     * @return int Inserted ID
     */
    public function create_subscription($data)
    {
        $this->db->insert('subscriptions', $data);
        return $this->db->insert_id();
    }

    /**
     * Update an existing subscription.
     *
     * @param int $subscription_id
     * @param array $data
     * @return bool
     */
    public function update_subscription($subscription_id, $data)
    {
        $this->db->where('id', $subscription_id);
        return $this->db->update('subscriptions', $data);
    }

    /**
     * Cancel a subscription.
     *
     * @param int $subscription_id
     * @return bool
     */
    public function cancel_subscription($subscription_id)
    {
        $data = array(
            'status' => 'canceled',
            'canceled_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $subscription_id);
        return $this->db->update('subscriptions', $data);
    }

    /**
     * Get all subscriptions (for admin).
     *
     * @return array
     */
    public function get_all_subscriptions()
    {
        $query = $this->db->get('subscriptions');
        return $query->result();
    }

    /**
     * Get a user's subscription history.
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_subscription_history($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('subscriptions');
        return $query->result();
    }

    /**
     * Get total number of active subscribers, optionally by plan.
     *
     * @param string|null $plan_name
     * @return int
     */
    public function get_total_active_subscribers($plan_name = null)
    {
        $this->db->where('status', 'active');
        if ($plan_name) {
            $this->db->where('plan_name', $plan_name);
        }
        return $this->db->count_all_results('subscriptions');
    }

    /**
     * Get new subscribers within a given period.
     *
     * @param string $start_date YYYY-MM-DD
     * @param string $end_date YYYY-MM-DD
     * @return int
     */
    public function get_new_subscribers_in_period($start_date, $end_date)
    {
        $this->db->where('status', 'active');
        $this->db->where('created_at >=', $start_date . ' 00:00:00');
        $this->db->where('created_at <=', $end_date . ' 23:59:59');
        return $this->db->count_all_results('subscriptions');
    }

    /**
     * Calculate churn rate for a given period.
     * Churn Rate = (Number of Churned Customers in Period / Number of Customers at Start of Period) * 100
     *
     * @param string $start_date YYYY-MM-DD
     * @param string $end_date YYYY-MM-DD
     * @return float
     */
    public function calculate_churn_rate($start_date, $end_date)
    {
        // Customers at the start of the period (active before start_date)
        $this->db->where('status', 'active');
        $this->db->where('created_at <', $start_date . ' 00:00:00');
        $customers_at_start = $this->db->count_all_results('subscriptions');

        // Churned customers in the period (canceled_at within period)
        $this->db->where('status', 'canceled');
        $this->db->where('canceled_at >=', $start_date . ' 00:00:00');
        $this->db->where('canceled_at <=', $end_date . ' 23:59:59');
        $churned_customers = $this->db->count_all_results('subscriptions');

        if ($customers_at_start > 0) {
            return ($churned_customers / $customers_at_start) * 100;
        }
        return 0.0;
    }

    /**
     * Get total revenue within a given period.
     *
     * @param string $start_date YYYY-MM-DD
     * @param string $end_date YYYY-MM-DD
     * @return float
     */
    public function get_revenue_in_period($start_date, $end_date)
    {
        $this->db->select_sum('amount');
        $this->db->where('status', 'active'); // Only count revenue from active subscriptions
        $this->db->where('current_period_start >=', $start_date . ' 00:00:00');
        $this->db->where('current_period_end <=', $end_date . ' 23:59:59');
        $query = $this->db->get('subscriptions');
        return (float) $query->row()->amount;
    }

    /**
     * Get subscription status distribution.
     *
     * @return array
     */
    public function get_status_distribution()
    {
        $this->db->select('status, COUNT(id) as count');
        $this->db->group_by('status');
        $query = $this->db->get('subscriptions');
        return $query->result();
    }

    /**
     * Get subscriber count by plan.
     *
     * @return array
     */
    public function get_subscribers_by_plan()
    {
        $this->db->select('plan_name, COUNT(id) as count');
        $this->db->where('status', 'active');
        $this->db->group_by('plan_name');
        $query = $this->db->get('subscriptions');
        return $query->result();
    }
}
