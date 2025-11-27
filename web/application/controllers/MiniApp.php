<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MiniApp extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->config->load('config');
        $this->load->model('User_model'); // Load the User_model
        $this->load->model('Bot_model'); // Load the Bot_model
        $this->load->library('session'); // Load the session library
    }

    public function index($bot_id = null)
    {
        $data['bot_id'] = $bot_id;
        $this->load->view('miniapp_view', $data);
    }

    /**
     * Authenticate the data from the Telegram Mini App.
     */
    public function auth()
    {
        $init_data = $this->input->post('init_data');
        $bot_id = $this->input->post('bot_id'); // Get bot_id from POST

        // --- Basic input validation ---
        if (!$init_data) {
            log_message('error', 'MiniApp Auth: init_data is missing from POST request (Bot ID: ' . ($bot_id ?? 'N/A') . ').');
            $this->session->set_flashdata('error_message', 'Telegram initialization data is missing.');
            redirect('miniapp/unauthorized');
            return;
        }

        if (!$bot_id) {
            log_message('error', 'MiniApp Auth: bot_id is missing from POST request.');
            $this->session->set_flashdata('error_message', 'Bot ID is missing from the request.');
            redirect('miniapp/unauthorized');
            return;
        }

        // --- Validate Telegram initData signature ---
        if ($this->_is_valid_telegram_data($init_data, $bot_id)) {
            parse_str($init_data, $data_array);
            $telegram_user_data = isset($data_array['user']) ? json_decode($data_array['user'], true) : null;

            if ($telegram_user_data && isset($telegram_user_data['id'])) {
                $telegram_id = $telegram_user_data['id'];

                $user = $this->User_model->get_user_by_telegram_id($telegram_id);

                $db_user_data = [
                    'telegram_id' => $telegram_id,
                    'username' => $telegram_user_data['username'] ?? null,
                    'first_name' => $telegram_user_data['first_name'] ?? '',
                    'last_name' => $telegram_user_data['last_name'] ?? null,
                    'language_code' => $telegram_user_data['language_code'] ?? null,
                ];

                $is_new_user = FALSE;
                $user_id = null;
                if ($user) {
                    // Update existing user
                    $updated = $this->User_model->update_user($user['id'], $db_user_data);
                    if (!$updated) {
                        log_message('error', 'MiniApp Auth: Failed to update user with ID ' . $user['id'] . ' and Telegram ID ' . $telegram_id);
                        $this->session->set_flashdata('error_message', 'Internal server error: Could not update user data.');
                        redirect('miniapp/unauthorized');
                        return;
                    }
                    $user_id = $user['id'];
                } else {
                    // Create new user
                    $user_id = $this->User_model->create_user($db_user_data);
                    if (!$user_id) {
                        log_message('error', 'MiniApp Auth: Failed to create new user for Telegram ID: ' . $telegram_id . '. Data: ' . json_encode($db_user_data));
                        $this->session->set_flashdata('error_message', 'Internal server error: Could not create user record.');
                        redirect('miniapp/unauthorized');
                        return;
                    }
                    $is_new_user = TRUE;
                }

                // Retrieve the full user data including role_id and role_name for session
                $full_user_data = $this->User_model->get_user_by_id($user_id);
                if (!$full_user_data) {
                    log_message('error', 'MiniApp Auth: Could not retrieve full user data for ID: ' . $user_id);
                    $this->session->set_flashdata('error_message', 'Internal server error: Could not retrieve user session data.');
                    redirect('miniapp/unauthorized');
                    return;
                }

                // Store user data in session
                $this->session->set_userdata([
                    'user_id' => $user_id,
                    'telegram_id' => $telegram_id,
                    'username' => $full_user_data['username'],
                    'logged_in' => TRUE,
                    'role_id' => $full_user_data['role_id'],
                    'role_name' => $full_user_data['role_name'],
                    'new_user_onboard' => $is_new_user // Set onboarding flag
                ]);

                log_message('info', 'MiniApp Auth: User ' . $user_id . ' (Telegram ID: ' . $telegram_id . ') authenticated and session created.');
                redirect('miniapp/dashboard'); // Redirect to dashboard on success
                return;

            } else {
                log_message('error', 'MiniApp Auth: Missing or invalid Telegram user data in init_data. Data: ' . $init_data . ' (Bot ID: ' . $bot_id . ').');
                $this->session->set_flashdata('error_message', 'Invalid Telegram user data received.');
                redirect('miniapp/unauthorized');
                return;
            }
        } else {
            log_message('warning', 'MiniApp Auth: Authentication failed for init_data: ' . $init_data . ' (Bot ID: ' . $bot_id . ').');
            $this->session->set_flashdata('error_message', 'Authentication failed: Invalid data signature.');
            redirect('miniapp/unauthorized');
            return;
        }
    }

    /**
     * Validate the initData string from Telegram.
     *
     * @param string $init_data The initData string.
     * @return bool True if the data is valid, false otherwise.
     */
    private function _is_valid_telegram_data($init_data, $bot_id)
    {
        $bot_record = $this->Bot_model->get_bot_by_telegram_id($bot_id);

        if (!$bot_record) {
            log_message('error', 'MiniApp Auth: Bot not found for Telegram ID: ' . $bot_id);
            return false;
        }

        $bot_token = $bot_record['token'];
        
        parse_str($init_data, $data_array);

        if (!isset($data_array['hash'])) {
            log_message('error', 'MiniApp Auth: Hash missing from init_data for Bot ID: ' . $bot_id);
            return false;
        }

        $hash = $data_array['hash'];
        unset($data_array['hash']);
        ksort($data_array);

        $data_check_string = '';
        foreach ($data_array as $key => $value) {
            $data_check_string .= $key . '=' . $value . "\n";
        }
        $data_check_string = rtrim($data_check_string, "\n");

        $secret_key = hash_hmac('sha256', 'WebAppData', $bot_token, true);
        $calculated_hash = hash_hmac('sha256', $data_check_string, $secret_key);

        if (!hash_equals($calculated_hash, $hash)) {
            log_message('warning', 'MiniApp Auth: Invalid hash for Bot ID: ' . $bot_id . '. init_data: ' . $init_data);
            return false;
        }

        return true;
    }



    /**
     * Admin Panel - accessible only by admin users.
     */
    public function admin_panel($bot_id = null)
    {
        // Set the content type to application/json
        $this->output->set_content_type('application/json');

        if (!is_admin()) {
            $this->output->set_status_header(403);
            echo json_encode(['status' => 'error', 'message' => 'Access Denied: You must be an administrator to view this page.']);
            return;
        }

        echo json_encode(['status' => 'success', 'message' => 'Welcome to the Admin Panel, Administrator!']);
    }

    /**
     * Dashboard page for authenticated users.
     */
    public function dashboard()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'You must be logged in to access the dashboard.');
            redirect('miniapp/unauthorized');
            return;
        }
        
        if ($this->session->userdata('new_user_onboard')) {
            $this->session->unset_userdata('new_user_onboard'); // Unset flag after showing
            $this->load->view('welcome_onboarding_view');
            return;
        }

        $this->load->view('dashboard_view');
    }

    /**
     * Unauthorized/Error page for authentication failures.
     */
    public function unauthorized()
    {
        $data['error_message'] = $this->session->flashdata('error_message');
        $this->load->view('auth_error_view', $data);
    }

    /**
     * Set the user's preferred language.
     * @param string $lang The language code (e.g., 'english', 'indonesian').
     */
    public function set_language($lang)
    {
        $this->load->library('user_agent'); // Load user agent for referrer access
        // Validate language
        $available_languages = $this->config->item('available_languages');
        if (array_key_exists($lang, $available_languages)) {
            $this->session->set_userdata('site_language', $lang);
        }

        // Redirect back to the previous page or default
        if ($this->agent->is_referral()) {
            redirect($this->agent->referrer());
        } else {
            redirect('miniapp/dashboard');
        }
    }
}