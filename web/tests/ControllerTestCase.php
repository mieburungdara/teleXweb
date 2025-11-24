<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/CITestCase.php';

class ControllerTestCase extends CITestCase {

    protected $CI_Controller; // To hold the instance of the controller being tested

    public function setUp(): void
    {
        parent::setUp();

        // Use Spy_Loader for controllers to track view calls
        $this->CI->load = new Spy_Loader();

        // Mock Input library if needed
        $this->CI->input = new Mock_Input();

        // Mock Session library if needed
        $this->CI->session = new Mock_Session();
    }

    /**
     * Helper to load and instantiate a controller for testing.
     *
     * @param string $controller_name The name of the controller (e.g., 'Admin')
     * @return CI_Controller
     */
    protected function loadController($controller_name)
    {
        // Require the controller file
        require_once APPPATH . 'controllers/' . $controller_name . '.php';

        // Instantiate the controller
        $this->CI_Controller = new $controller_name();

        // Manually set the CI super object in the controller
        $this->CI_Controller->load =& $this->CI->load;
        $this->CI_Controller->db =& $this->CI->db;
        $this->CI_Controller->input =& $this->CI->input;
        $this->CI_Controller->session =& $this->CI->session;
        // Assign models if they are loaded in the constructor of the controller
        // This is a simplified approach, a more robust solution might dynamically
        // mock models as they are loaded by the controller's constructor.
        if (isset($this->CI_Controller->User_model)) {
            $this->CI_Controller->User_model = new Mock_User_Model();
        }
        if (isset($this->CI_Controller->Subscription_model)) {
            $this->CI_Controller->Subscription_model = new Mock_Subscription_Model();
        }
        if (isset($this->CI_Controller->Audit_log_model)) {
            $this->CI_Controller->Audit_log_model = new Mock_Audit_log_Model();
        }
        if (isset($this->CI_Controller->Balance_Transaction_model)) {
            $this->CI_Controller->Balance_Transaction_model = new Mock_Balance_Transaction_Model();
        }

        return $this->CI_Controller;
    }
}

// Mock CodeIgniter's Input class
class Mock_Input {
    protected $post_data = [];
    protected $get_data = [];

    public function post($index = null, $xss_clean = null) {
        if ($index === null) return $this->post_data;
        return isset($this->post_data[$index]) ? $this->post_data[$index] : null;
    }

    public function get($index = null, $xss_clean = null) {
        if ($index === null) return $this->get_data;
        return isset($this->get_data[$index]) ? $this->get_data[$index] : null;
    }

    public function set_post_data($data) {
        $this->post_data = $data;
    }

    public function set_get_data($data) {
        $this->get_data = $data;
    }

    public function ip_address() { return '127.0.0.1'; }
}

// Mock CodeIgniter's Session class
class Mock_Session {
    protected $userdata = [];

    public function userdata($key = null) {
        if ($key === null) return $this->userdata;
        return isset($this->userdata[$key]) ? $this->userdata[$key] : null;
    }

    public function set_userdata($key, $value = null) {
        if (is_array($key)) {
            $this->userdata = array_merge($this->userdata, $key);
        } else {
            $this->userdata[$key] = $value;
        }
    }

    public function unset_userdata($key) {
        if (is_array($key)) {
            foreach ($key as $k) {
                unset($this->userdata[$k]);
            }
        } else {
            unset($this->userdata[$key]);
        }
    }
}

// Basic Mocks for Models that might be loaded by controllers
class Mock_User_Model {
    public function get_user($user_id) {
        return (object)['id' => $user_id, 'codename' => 'mockuser', 'username' => 'mockusername', 'balance' => 100.00];
    }
    public function update_user_subscription_details($user_id, $plan, $status, $start, $end) { return true; }
    public function add_balance($user_id, $amount, $desc, $admin_id, $type) { return true; }
    public function deduct_balance($user_id, $amount, $desc, $admin_id, $type) { return true; }
    public function get_all_users($search_term = null) {
        return [ (object)['id' => 1, 'codename' => 'user1', 'username' => 'u1', 'balance' => 50.00] ];
    }
}

class Mock_Subscription_Model {
    public function get_total_active_subscribers() { return 10; }
    public function get_new_subscribers_in_period($start, $end) { return 5; }
    public function calculate_churn_rate($start, $end) { return 0.1; }
    public function get_revenue_in_period($start, $end) { return 1000.00; }
    public function get_status_distribution() { return ['active' => 10, 'canceled' => 2]; }
    public function get_subscribers_by_plan() { return ['free' => 5, 'pro' => 5]; }
    public function get_all_subscriptions() {
        return [ (object)['id' => 1, 'user_id' => 1, 'plan_name' => 'pro'] ];
    }
    public function get_user_active_subscription($user_id) {
        return (object)['id' => 1, 'user_id' => $user_id, 'plan_name' => 'pro', 'status' => 'active'];
    }
    public function update_subscription($sub_id, $data) { return true; }
    public function create_subscription($data) { return true; }
}

class Mock_Audit_log_Model {
    public function log_action($data) { return true; }
}

class Mock_Balance_Transaction_Model {
    public function log_transaction($data) { return true; }
    public function count_user_transactions($user_id) { return 0; }
    public function get_paginated_user_transactions($user_id, $limit, $offset) { return []; }
}
