<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telegram_bot_model extends CI_Model {

    private $bot_token;
    private $api_url;

    public function __construct()
    {
        parent::__construct();
        // Load Telegram bot token from config or environment variables
        // For demonstration, let's use a placeholder
        $this->bot_token = 'YOUR_TELEGRAM_BOT_TOKEN'; 
        $this->api_url = 'https://api.telegram.org/bot' . $this->bot_token . '/';
    }

    /**
     * Send a message to a Telegram user.
     *
     * @param int $chat_id The user's Telegram chat ID.
     * @param string $message The message text.
     * @return bool True on success, false on failure.
     */
    public function send_message($chat_id, $message)
    {
        $url = $this->api_url . 'sendMessage';
        $data = array(
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML' // Or 'MarkdownV2'
        );

        // Use cURL to send the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            // Log success or handle response
            return true;
        } else {
            // Log error or handle failure
            error_log("Telegram send_message failed: " . $response);
            return false;
        }
    }

    // Other Telegram bot related methods (e.g., sendPhoto, sendDocument) would go here
}
