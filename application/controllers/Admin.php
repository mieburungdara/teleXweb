<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load necessary models, helpers, libraries
        $this->load->model('User_model');
        $this->load->model('Subscription_model');
        $this->load->helper('url');
        // Assuming admin authentication and authorization is handled elsewhere
        // For demonstration, let's assume the user is an admin
    }

    public function index()
    {
        // Admin dashboard view
        $this->load->view('admin/dashboard');
    }

    public function subscription_analytics()
    {
        // Define a period for analytics, e.g., last 30 days
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-30 days'));

        $data['total_active_subscribers'] = $this->Subscription_model->get_total_active_subscribers();
        $data['new_subscribers_last_30_days'] = $this->Subscription_model->get_new_subscribers_in_period($start_date, $end_date);
        $data['churn_rate_last_30_days'] = $this->Subscription_model->calculate_churn_rate($start_date, $end_date);
        $data['revenue_last_30_days'] = $this->Subscription_model->get_revenue_in_period($start_date, $end_date);
        $data['status_distribution'] = $this->Subscription_model->get_status_distribution();
        $data['subscribers_by_plan'] = $this->Subscription_model->get_subscribers_by_plan();

        $this->load->view('admin/subscription_analytics', $data);
    }

    public function subscriptions()
    {
        $data['subscriptions'] = $this->Subscription_model->get_all_subscriptions();
        $this->load->view('admin/subscription_management', $data);
    }

    public function edit_user_subscription($user_id)
    {
        $user = $this->User_model->get_user($user_id);
        if (!$user) {
            show_404();
        }

        if ($this->input->post()) {
            $subscription_plan = $this->input->post('subscription_plan');
            $payment_status = $this->input->post('payment_status');
            $subscription_start_date = $this->input->post('subscription_start_date');
            $subscription_end_date = $this->input->post('subscription_end_date');

            $this->User_model->update_user_subscription_details(
                $user_id,
                $subscription_plan,
                $payment_status,
                $subscription_start_date,
                $subscription_end_date
            );

            // Also update the active subscription in the subscriptions table if necessary
            $active_sub = $this->Subscription_model->get_user_active_subscription($user_id);
            if ($active_sub) {
                $this->Subscription_model->update_subscription($active_sub->id, array(
                    'plan_name' => $subscription_plan,
                    'status' => $payment_status,
                    'current_period_start' => $subscription_start_date,
                    'current_period_end' => $subscription_end_date
                ));
            } else {
                // If no active subscription, create one (simplified for demo)
                $this->Subscription_model->create_subscription(array(
                    'user_id' => $user_id,
                    'plan_name' => $subscription_plan,
                    'status' => $payment_status,
                    'amount' => 0, // Admin manual change, amount might be 0 or custom
                    'currency' => 'USD',
                    'current_period_start' => $subscription_start_date,
                    'current_period_end' => $subscription_end_date
                ));
            }

            redirect('admin/subscriptions');
        }

        $data['user'] = $user;
        $this->load->view('admin/edit_user_subscription', $data);
    }

    public function manage_user_balance($user_id = null)
    {
        $this->load->model('Balance_Transaction_model');

        if ($this->input->post()) {
            $user_id = $this->input->post('user_id');
            $amount = (float)$this->input->post('amount');
            $transaction_type = $this->input->post('transaction_type');
            $description = $this->input->post('description');
            $admin_id = $this->user_id; // Assuming admin_id is available from session

            if ($transaction_type == 'top_up') {
                $this->User_model->add_balance($user_id, $amount, $description, $admin_id, 'manual_top_up');
            } elseif ($transaction_type == 'deduction') {
                $this->User_model->deduct_balance($user_id, $amount, $description, $admin_id, 'manual_deduction');
            }
            redirect('admin/manage_user_balance/' . $user_id);
        }

        $data['user'] = null;
        $data['transactions'] = [];
        if ($user_id) {
            $data['user'] = $this->User_model->get_user($user_id);
            if ($data['user']) {
                $data['transactions'] = $this->Balance_Transaction_model->get_user_transactions($user_id);
            }
        }
        $data['users'] = $this->User_model->get_all_users(); // Assuming a method to get all users
        $this->load->view('admin/manage_user_balance', $data);
    }

    public function user_balance_history($user_id)
    {
        $this->load->model('Balance_Transaction_model');
        $data['user'] = $this->User_model->get_user($user_id);
        if (!$data['user']) {
            show_404();
        }
        $data['transactions'] = $this->Balance_Transaction_model->get_user_transactions($user_id);
        $this->load->view('admin/user_balance_history', $data);
    }
