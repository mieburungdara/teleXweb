<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MiniApp extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->config->load('config');
    }

    public function index()
    {
        $this->load->view('miniapp_view');
    }

    /**
     * Authenticate the data from the Telegram Mini App.
     */
    public function auth()
    {
        // Set the content type to application/json
        $this->output->set_content_type('application/json');

        $init_data = $this->input->post('init_data');

        if (!$init_data) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'init_data is required.']);
            return;
        }

        if ($this->_is_valid_telegram_data($init_data)) {
            // Parse user data from init_data
            parse_str($init_data, $data_array);
            $user_data = isset($data_array['user']) ? json_decode($data_array['user'], true) : null;

            // Here you would typically find or create a user in your database
            // and create a session for them.
            
            $this->output->set_status_header(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Authentication successful.',
                'user_data' => $user_data
            ]);
        } else {
            $this->output->set_status_header(403);
            echo json_encode(['status' => 'error', 'message' => 'Authentication failed: Invalid hash.']);
        }
    }

    /**
     * Validate the initData string from Telegram.
     *
     * @param string $init_data The initData string.
     * @return bool True if the data is valid, false otherwise.
     */
    private function _is_valid_telegram_data($init_data)
    {
        $bot_token = $this->config->item('telegram_bot_token');
        if ($bot_token === 'YOUR_BOT_TOKEN_HERE' || empty($bot_token)) {
            // Do not allow validation with a placeholder token
            log_message('error', 'Telegram Bot Token is not configured.');
            return false;
        }

        parse_str($init_data, $data_array);

        if (!isset($data_array['hash'])) {
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

        $secret_key = hash_hmac('sha256', $bot_token, 'WebAppData', true);
        $calculated_hash = hash_hmac('sha256', $data_check_string, $secret_key);

        return hash_equals($calculated_hash, $hash);
    }
}
