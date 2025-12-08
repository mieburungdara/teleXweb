<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MiniApp extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('User_model'); // Load the User_model
        $this->load->model('Bot_model'); // Load the Bot_model
        $this->load->helper('file'); // Load the file helper for write_file() used by DB_cache
    }

    public function index($bot_id = null)
    {
        log_message('debug', 'MiniApp Controller: index() called with bot_id: ' . ($bot_id ?? 'NULL'));
        
        // Check if bot_id is provided and valid
        $bot_exists = $this->Bot_model->get_bot_by_telegram_id($bot_id);
        if (empty($bot_id) || !$bot_exists) {
            log_message('warn', 'MiniApp Controller: Invalid or missing Bot ID. Provided: ' . ($bot_id ?? 'NULL') . '. Bot exists: ' . ($bot_exists ? 'Yes' : 'No'));
            $this->session->set_flashdata('error_message', 'Invalid or missing Bot ID.');
            redirect('miniapp/unauthorized');
            return;
        }

        log_message('debug', 'MiniApp Controller: Bot ID ' . $bot_id . ' is valid. Loading miniapp_loading_view.');
        $data['bot_id'] = $bot_id;
        $this->load->view('miniapp_loading_view', $data);
    }

    /**
     * Authenticate the data from the Telegram Mini App.
     */
    public function auth()
    {
        log_message('debug', 'MiniApp Auth: auth() method started.');
        $init_data = $this->input->post('init_data');
        $bot_id = $this->input->post('bot_id'); // Get bot_id from POST
        log_message('debug', 'MiniApp Auth: Received POST with bot_id: ' . ($bot_id ?? 'N/A'));

        // --- Basic input validation ---
        if (!$init_data) {
            log_message('error', 'MiniApp Auth: init_data is missing from POST request (Bot ID: ' . ($bot_id ?? 'N/A') . ').');
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Telegram initialization data is missing.']));
            return;
        }

        if (!$bot_id) {
            log_message('error', 'MiniApp Auth: bot_id is missing from POST request.');
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Bot ID is missing from the request.']));
            return;
        }

        log_message('debug', 'MiniApp Auth: Starting data validation for bot_id: ' . $bot_id);
        // --- Validate Telegram initData signature ---
        if ($this->_is_valid_telegram_data($init_data, $bot_id)) {
            log_message('debug', 'MiniApp Auth: Telegram data is valid.');
            parse_str($init_data, $data_array);
            $telegram_user_data = isset($data_array['user']) ? json_decode($data_array['user'], true) : null;

            if ($telegram_user_data && isset($telegram_user_data['id'])) {
                $telegram_id = $telegram_user_data['id'];
                log_message('debug', 'MiniApp Auth: Extracted Telegram User ID: ' . $telegram_id);

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
                    log_message('debug', 'MiniApp Auth: User found in DB with ID: ' . $user['id'] . '. Updating user data.');
                    // Update existing user
                    // Check if user_code is missing and generate it
                    if (empty($user['user_code'])) {
                        $generated_user_code = $this->User_model->generate_unique_user_code();
                        $db_user_data['user_code'] = $generated_user_code;
                        log_message('debug', 'MiniApp Auth: Generated missing user_code ' . $generated_user_code . ' for user ' . $user['id']);
                    }
                    $updated = $this->User_model->update_user($user['id'], $db_user_data);
                    if (!$updated) {
                        log_message('error', 'MiniApp Auth: Failed to update user with ID ' . $user['id'] . ' and Telegram ID ' . $telegram_id);
                        $this->output->set_status_header(500);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Internal server error: Could not update user data.']));
                        return;
                    }
                    log_message('debug', 'MiniApp Auth: User ' . $user['id'] . ' updated successfully.');
                    $user_id = $user['id'];
                } else {
                    log_message('debug', 'MiniApp Auth: User not found for Telegram ID: ' . $telegram_id . '. Creating new user.');
                    // Create new user
                    $user_id = $this->User_model->create_user($db_user_data);
                    if (!$user_id) {
                        log_message('error', 'MiniApp Auth: Failed to create new user for Telegram ID: ' . $telegram_id . '. Data: ' . json_encode($db_user_data));
                        $this->output->set_status_header(500);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Internal server error: Could not create user record.']));
                        return;
                    }
                    log_message('debug', 'MiniApp Auth: New user created with ID: ' . $user_id);
                    $is_new_user = TRUE;
                }

                // Retrieve the full user data including role_id and role_name for session
                log_message('debug', 'MiniApp Auth: Retrieving full user data for session for user ID: ' . $user_id);
                $full_user_data = $this->User_model->get_user_by_id($user_id);
                if (!$full_user_data) {
                    log_message('error', 'MiniApp Auth: Could not retrieve full user data for ID: ' . $user_id);
                    $this->output->set_status_header(500);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Internal server error: Could not retrieve user session data.']));
                    return;
                }

                // Store user data in session
                $session_data = [
                    'user_id' => $user_id,
                    'telegram_id' => $telegram_id,
                    'username' => $full_user_data['username'],
                    'logged_in' => TRUE,
                    'role_id' => $full_user_data['role_id'],
                    'role_name' => $full_user_data['role_name'],
                    'user_code' => $full_user_data['user_code'], // Add user_code to session
                    'new_user_onboard' => $is_new_user // Set onboarding flag
                ];
                $this->session->set_userdata($session_data);



                    // MANUAL SESSION FIX FOR TELEGRAM
    // Force write and close session to ensure it's saved
    session_write_close();
    
    // Set headers for iframe compatibility
    header('P3P: CP="CAO PSA OUR"'); // For IE/old browsers
    header('Access-Control-Allow-Origin: https://web.telegram.org');
    header('Access-Control-Allow-Credentials: true');
    
    // For JavaScript to access cookies in iframe
    header('Set-Cookie: ' . session_name() . '=' . session_id() . 
           '; Path=/; SameSite=None; Secure=false');



           
                log_message('debug', 'MiniApp Auth: Session data set: ' . json_encode($session_data));

                // VERIFICATION STEP: Immediately check if the session data can be read back.
                log_message('debug', 'MiniApp Auth: VERIFYING session post-set. Logged_in status is: ' . ($this->session->userdata('logged_in') ? 'TRUE' : 'FALSE'));


                log_message('debug', 'MiniApp Auth: User ' . $user_id . ' (Telegram ID: ' . $telegram_id . ') authenticated and session created.');
                $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success', 'redirect_url' => site_url('miniapp/dashboard')]));

            } else {
                log_message('error', 'MiniApp Auth: Missing or invalid Telegram user data in init_data. Data: ' . $init_data . ' (Bot ID: ' . $bot_id . ').');
                $this->output->set_status_header(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Invalid Telegram user data received.']));
            }
        } else {
            log_message('warning', 'MiniApp Auth: Authentication failed for init_data: ' . $init_data . ' (Bot ID: ' . $bot_id . ').');
            $this->output->set_status_header(401);
            $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Authentication failed: Invalid data signature.']));
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
        log_message('debug', 'Validation: _is_valid_telegram_data() started for bot ID: ' . $bot_id);
        $bot_record = $this->Bot_model->get_bot_by_telegram_id($bot_id);

        if (!$bot_record) {
            log_message('error', 'MiniApp Auth: Bot not found for Telegram ID: ' . $bot_id);
            return false;
        }
        log_message('debug', 'Validation: Bot record found for bot ID: ' . $bot_id);

        $bot_token_from_db = $bot_record['token'];
        $full_bot_token = $bot_id . ':' . $bot_token_from_db; // Construct the full token
        log_message('debug', 'Validation: Full bot token constructed.');
        
        parse_str($init_data, $data_array);

        if (!isset($data_array['hash'])) {
            log_message('error', 'MiniApp Auth: Hash missing from init_data for Bot ID: ' . $bot_id);
            return false;
        }

        $hash = $data_array['hash'];
        unset($data_array['hash']);
        // Do NOT unset 'signature' as per user's working snippet
        ksort($data_array);
        log_message('debug', 'Validation: init_data parsed and sorted.');

        $data_check_string = ""; // Manual loop for data_check_string as per user's snippet
        foreach ($data_array as $k => $v) {
            $data_check_string .= "$k=$v\n";
        }
        $data_check_string = rtrim($data_check_string, "\n");
        log_message('debug', 'Validation: Data check string created: ' . $data_check_string);

        // Secret key generation with arguments swapped as per user's working snippet
        $secret_key = hash_hmac('sha256', $full_bot_token, 'WebAppData', true);
        log_message('debug', 'Validation: Secret key generated.');

        $calculated_hash = hash_hmac('sha256', $data_check_string, $secret_key);
        log_message('debug', 'Validation: Hash calculated. Comparing with received hash.');

        if (hash_equals($calculated_hash, $hash)) {
            log_message('debug', 'Validation: Hash validation successful.');
            // Remove debug logs here if this works
            return true;
        }

        // Log details on failure for debugging
        log_message('debug', 'Hash Validation Failed. Telegram Hash: ' . $hash . ' | Calculated Hash: ' . $calculated_hash);
        log_message('debug', 'Data Check String Used: ' . $data_check_string);

        return false;
    }



    /**
     * Admin Panel - accessible only by admin users.
     */
    public function admin_panel($bot_id = null)
    {
        log_message('debug', 'MiniApp Controller: admin_panel() called.');
        // Set the content type to application/json
        $this->output->set_content_type('application/json');

        if (!is_admin()) {
            log_message('warn', 'MiniApp Controller: Non-admin user tried to access admin_panel. User ID: ' . $this->session->userdata('user_id'));
            $this->output->set_status_header(403);
            echo json_encode(['status' => 'error', 'message' => 'Access Denied: You must be an administrator to view this page.']);
            return;
        }

        log_message('debug', 'MiniApp Controller: Admin access granted to admin_panel.');
        echo json_encode(['status' => 'success', 'message' => 'Welcome to the Admin Panel, Administrator!']);
    }

    /**
     * Dashboard page for authenticated users.
     */
    public function dashboard()
    {
        log_message('debug', 'Session ID: ' . $this->session->session_id);
        log_message('debug', 'Session save path: ' . ini_get('session.save_path'));
        log_message('debug', 'MiniApp Dashboard: Access attempt.');
        log_message('debug', 'MiniApp Dashboard: Session logged_in status: ' . ($this->session->userdata('logged_in') ? 'TRUE' : 'FALSE'));
        log_message('debug', 'MiniApp Dashboard: Session new_user_onboard status: ' . ($this->session->userdata('new_user_onboard') ? 'TRUE' : 'FALSE'));
        log_message('debug', 'MiniApp Dashboard: Full Session Data: ' . json_encode($this->session->all_userdata()));

        if (!$this->session->userdata('logged_in')) {
            log_message('warn', 'MiniApp Dashboard: Unauthenticated access attempt. Redirecting to unauthorized.');
            $this->session->set_flashdata('error_message', 'You must be logged in to access the dashboard.');
            redirect('miniapp/unauthorized');
            return;
        }
        
        if ($this->session->userdata('new_user_onboard')) {
            log_message('debug', 'MiniApp Dashboard: New user detected, showing onboarding view.');
            $this->session->unset_userdata('new_user_onboard'); // Unset flag after showing
            $this->load->view('welcome_onboarding_view');
            return;
        }

        log_message('debug', 'MiniApp Dashboard: Existing user, loading dashboard_view.');
        $this->load->view('dashboard_view');
    }

    /**
     * Unauthorized/Error page for authentication failures.
     */
    public function unauthorized()
    {
        log_message('debug', 'Session ID: ' . $this->session->session_id);
        log_message('debug', 'Session save path: ' . ini_get('session.save_path'));
        $error_message = $this->session->flashdata('error_message');
        log_message('warn', 'MiniApp Controller: unauthorized() page loaded. Error message: ' . ($error_message ?? 'N/A'));
        $data['error_message'] = $error_message;
        $this->load->view('auth_error_view', $data);
    }

    /**
     * Set the user's preferred language.
     * @param string $lang The language code (e.g., 'english', 'indonesian').
     */
    public function set_language($lang)
    {
        log_message('debug', 'MiniApp Controller: set_language() called with lang: ' . $lang);
        $this->load->library('user_agent'); // Load user agent for referrer access
        
        // Validate language
        $available_languages = $this->config->item('available_languages');
        if (array_key_exists($lang, $available_languages)) {
            $this->session->set_userdata('site_language', $lang);
            log_message('debug', 'MiniApp Controller: Language set to ' . $lang . ' in session.');
        } else {
            log_message('warn', 'MiniApp Controller: Attempted to set invalid language: ' . $lang);
        }

        // Redirect back to the previous page or default
        if ($this->agent->is_referral()) {
            $referrer = $this->agent->referrer();
            log_message('debug', 'MiniApp Controller: Redirecting to referrer: ' . $referrer);
            redirect($referrer);
        } else {
            log_message('debug', 'MiniApp Controller: No referrer found, redirecting to dashboard.');
            redirect('miniapp/dashboard');
        }
    }
}