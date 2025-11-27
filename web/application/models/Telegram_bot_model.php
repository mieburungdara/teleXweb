<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Telegram\Bot\Api;

class Telegram_bot_model extends CI_Model {

    protected $telegram;
    protected $bot_token;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize the Telegram Bot API with a specific bot's token.
     *
     * @param string $token The bot token.
     * @return bool
     */
    public function init($token)
    {
        if (empty($token)) {
            log_message('error', 'Telegram_bot_model: No token provided for initialization.');
            return false;
        }
        $this->bot_token = $token;
        $this->telegram = new Api($this->bot_token);
        return true;
    }

    /**
     * Send a text message.
     *
     * @param int $chat_id
     * @param string $text
     * @param array $params Optional additional parameters (e.g., reply_markup).
     * @return \Telegram\Bot\Objects\Message|bool
     */
    public function sendMessage($chat_id, $text, $params = [])
    {
        if (!$this->telegram) {
            log_message('error', 'Telegram_bot_model: Not initialized. Call init() first.');
            return false;
        }
        try {
            $data = array_merge([
                'chat_id' => $chat_id,
                'text' => $text,
            ], $params);

            return $this->telegram->sendMessage($data);
        } catch (\Telegram\Bot\Exceptions\TelegramSDKException $e) {
            log_message('error', 'Telegram sendMessage failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a file back to the user using its telegram_file_id.
     *
     * @param int $chat_id
     * @param string $file_id
     * @param string $caption
     * @return \Telegram\Bot\Objects\Message|bool
     */
    public function sendFile($chat_id, $file_id, $caption = '')
    {
        if (!$this->telegram) {
            log_message('error', 'Telegram_bot_model: Not initialized. Call init() first.');
            return false;
        }
        try {
            return $this->telegram->sendFile([
                'chat_id' => $chat_id,
                'document' => $file_id,
                'caption' => $caption,
            ]);
        } catch (\Telegram\Bot\Exceptions\TelegramSDKException $e) {
            log_message('error', 'Telegram sendFile failed: ' . $e->getMessage());
            return false;
        }
    }
}
