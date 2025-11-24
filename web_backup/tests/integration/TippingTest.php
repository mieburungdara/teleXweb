<?php

require_once __DIR__ . '/../ControllerTestCase.php';

class TippingTest extends ControllerTestCase
{
    private $tipper_id = 101;
    private $recipient_id = 102;
    private $folder_id = 201;

    public function setUp(): void
    {
        parent::setUp();
        
        // This is where you would typically set up your test database.
        // For example, creating users and a folder for testing.
        // We will mock the models' responses instead.
        
        // Resetting mocks and any session data before each test
        $this->resetInstance();
        $this->CI->session->set_flashdata(null, null); // Clear flashdata
    }

    /**
     * Mocks the necessary models for a standard tipping scenario.
     */
    private function mock_standard_scenario()
    {
        // --- Mock User_model ---
        $user_model = $this->getMockBuilder('User_model')
            ->disableOriginalConstructor()
            ->getMock();

        $tipper = (object)['id' => $this->tipper_id, 'username' => 'test_tipper', 'balance' => 100.00];
        $recipient = (object)['id' => $this->recipient_id, 'username' => 'test_recipient', 'balance' => 50.00];

        // The get_user method will be called multiple times, we can use a map
        $user_model->method('get_user')->will($this->returnValueMap([
            [$this->tipper_id, $tipper],
            [$this->recipient_id, $recipient]
        ]));

        // Expect deduct_balance to be called and return a transaction ID
        $user_model->method('deduct_balance')->willReturn(1); // Mock transaction ID
        $user_model->method('add_balance')->willReturn(2); // Mock transaction ID
        
        $this->CI->User_model = $user_model;

        // --- Mock Folder_model ---
        $folder_model = $this->getMockBuilder('Folder_model')
            ->disableOriginalConstructor()
            ->getMock();
        
        $folder = (object)[
            'id' => $this->folder_id,
            'user_id' => $this->recipient_id,
            'folder_name' => 'Test Folder'
        ];
        $folder_model->method('get_folder')->with($this->folder_id)->willReturn($folder);
        $this->CI->Folder_model = $folder_model;

        // --- Mock Tipping_model ---
        $tipping_model = $this->getMockBuilder('Tipping_model')
            ->disableOriginalConstructor()
            ->getMock();
        $tipping_model->method('create_tip')->willReturn(1); // Mock tipping record ID
        $this->CI->Tipping_model = $tipping_model;
        
        // --- Mock current user ---
        // In the Folders controller, user ID is hardcoded as $this->user_id = 1
        // We need to reflect that in our test setup. We will set it to our tipper's ID.
        $this->CI->user_id = $this->tipper_id;
    }

    public function test_tip_success()
    {
        $this->mock_standard_scenario();

        // Simulate a POST request to the tip method
        $output = $this->request('POST', 'folders/tip/' . $this->folder_id, ['tip_amount' => 10]);

        // Assert that the user is redirected back to the folder detail page
        $this->assertRedirect('folders/detail/' . $this->folder_id, 302);
        
        // Assert that a success flash message is set
        $this->assertEquals('Tip of 10.00 credits sent successfully to test_recipient!', $this->CI->session->flashdata('success_message'));
        
        // In a real test with a database, you would also assert:
        // 1. Tipper's balance has decreased by 10.
        // 2. Recipient's balance has increased by 9.5 (assuming 5% fee).
        // 3. A record exists in the `tipping_transactions` table with the correct details.
        // 4. Two corresponding records exist in the `balance_transactions` table.
    }
    
    public function test_tip_insufficient_balance()
    {
        $this->mock_standard_scenario();

        // Override the tipper's balance for this specific test
        $tipper_low_balance = (object)['id' => $this->tipper_id, 'username' => 'test_tipper', 'balance' => 5.00];
        $this->CI->User_model->method('get_user')->will($this->returnValueMap([
            [$this->tipper_id, $tipper_low_balance],
            [$this->recipient_id, $this->CI->User_model->get_user($this->recipient_id)]
        ]));

        $output = $this->request('POST', 'folders/tip/' . $this->folder_id, ['tip_amount' => 10]);
        
        $this->assertRedirect('folders/detail/' . $this->folder_id, 302);
        $this->assertStringContainsString('Insufficient balance', $this->CI->session->flashdata('error_message'));
    }

    public function test_tip_to_self()
    {
        $this->mock_standard_scenario();
        
        // Set the logged-in user to be the folder owner
        $this->CI->user_id = $this->recipient_id;

        $output = $this->request('POST', 'folders/tip/' . $this->folder_id, ['tip_amount' => 10]);

        $this->assertRedirect('folders/detail/' . $this->folder_id, 302);
        $this->assertEquals('You cannot send a tip to yourself.', $this->CI->session->flashdata('error_message'));
    }

    public function test_tip_not_logged_in()
    {
        $this->mock_standard_scenario();
        
        // Unset the logged-in user
        $this->CI->user_id = null;
        
        $output = $this->request('POST', 'folders/tip/' . $this->folder_id, ['tip_amount' => 10]);

        $this->assertRedirect('folders/detail/' . $this->folder_id, 302);
        $this->assertEquals('You must be logged in to send a tip.', $this->CI->session->flashdata('error_message'));
    }

    public function test_tip_invalid_amount()
    {
        $this->mock_standard_scenario();
        
        $output = $this->request('POST', 'folders/tip/' . $this->folder_id, ['tip_amount' => -10]);

        $this->assertRedirect('folders/detail/' . $this->folder_id, 302);
        // The default validation error message might be more complex
        $this->assertStringContainsString('The Tip Amount field must contain a number greater than 0.', validation_errors());
    }
}
