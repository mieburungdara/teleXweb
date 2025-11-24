<?php
require_once APPPATH . 'models/User_model.php'; // Include the model

class UserModelTest extends CITestCase {

    protected $user_model;

    public function setUp(): void
    {
        parent::setUp();
        // Load the User_model instance
        $this->user_model = new User_model();

        // Assign mocked CI components to the model for testing
        $this->user_model->load = $this->CI->load; // The mocked loader
        $this->user_model->db = $this->CI->db;     // The mocked DB
        $this->user_model->Balance_Transaction_model = new Mock_Balance_Transaction_model(); // Mock dependency
        $this->user_model->Telegram_bot_model = new Mock_Telegram_bot_model(); // Mock dependency
    }

    public function testGetUser()
    {
        $user_id = 1;
        $user = $this->user_model->get_user($user_id);

        $this->assertIsObject($user);
        $this->assertEquals($user_id, $user->id);
        $this->assertEquals('testuser', $user->username);
        $this->assertEquals(100.00, $user->balance);
    }
}

// Mock the Balance_Transaction_model
class Mock_Balance_Transaction_model {
    public function log_transaction($data) { return true; }
}

// Mock the Telegram_bot_model
class Mock_Telegram_bot_model {
    public function send_message($chat_id, $message) { return true; }
}
