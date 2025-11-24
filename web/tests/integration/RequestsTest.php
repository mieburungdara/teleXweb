<?php

require_once __DIR__ . '/../ControllerTestCase.php';

class RequestsTest extends ControllerTestCase
{
    private $requester_id = 1;
    private $creator_id = 2;
    private $other_user_id = 3;
    private $public_request_id = 101;
    private $direct_request_id = 102;
    private $submission_id = 201;
    private $folder_id = 301;

    public function setUp(): void
    {
        parent::setUp();
        $this->resetInstance();
        $this->CI->session->set_flashdata(null, null); // Clear flashdata
    }

    private function mock_base_models()
    {
        // --- Mock User_model ---
        $user_model = $this->getMockBuilder('User_model')->disableOriginalConstructor()->getMock();
        $requester = (object)['id' => $this->requester_id, 'username' => 'requester', 'balance' => 500.00];
        $creator = (object)['id' => $this->creator_id, 'username' => 'creator', 'balance' => 100.00];
        $user_model->method('get_user')->will($this->returnValueMap([
            [$this->requester_id, $requester],
            [$this->creator_id, $creator]
        ]));
        $user_model->method('deduct_balance')->willReturn(10); // Mock transaction ID
        $user_model->method('add_balance')->willReturn(11); // Mock transaction ID
        $this->CI->User_model = $user_model;

        // --- Mock Folder_model & Purchase_model ---
        $folder_model = $this->getMockBuilder('Folder_model')->disableOriginalConstructor()->getMock();
        $this->CI->Folder_model = $folder_model;
        $purchase_model = $this->getMockBuilder('Folder_Purchase_model')->disableOriginalConstructor()->getMock();
        $purchase_model->method('record_purchase')->willReturn(1);
        $this->CI->Folder_Purchase_model = $purchase_model;

        // --- Mock Request_model ---
        $request_model = $this->getMockBuilder('Request_model')->disableOriginalConstructor()->getMock();
        $public_request = (object)[
            'id' => $this->public_request_id, 'requester_user_id' => $this->requester_id,
            'title' => 'Public Request', 'reward_amount' => 100.00, 'type' => 'public_bounty'
        ];
        $request_model->method('get_request')->willReturn($public_request);
        $this->CI->Request_model = $request_model;
        
        // --- Mock Request_submission_model ---
        $submission_model = $this->getMockBuilder('Request_submission_model')->disableOriginalConstructor()->getMock();
        $submission = (object)[
            'id' => $this->submission_id, 'request_id' => $this->public_request_id,
            'creator_user_id' => $this->creator_id, 'folder_id' => $this->folder_id, 'status' => 'pending_review'
        ];
        $submission_model->method('get_submission')->willReturn($submission);
        $submission_model->method('update_submission')->willReturn(true);
        $this->CI->Request_submission_model = $submission_model;
    }
    
    public function test_review_submission_accept_success()
    {
        $this->mock_base_models();
        // Set logged-in user as the requester
        $this->CI->user_id = $this->requester_id;

        $output = $this->request('POST', "requests/review_submission/{$this->submission_id}/accept");

        $this->assertRedirect("requests/view/{$this->public_request_id}");
        $this->assertEquals('Submission accepted and reward sent!', $this->CI->session->flashdata('success_message'));
    }

    public function test_review_submission_accept_insufficient_funds()
    {
        $this->mock_base_models();
        // Mock requester with low balance
        $requester_low_balance = (object)['id' => $this->requester_id, 'username' => 'requester', 'balance' => 50.00];
        $this->CI->User_model->method('get_user')->will($this->returnValueMap([
            [$this->requester_id, $requester_low_balance],
            [$this->creator_id, (object)['id' => $this->creator_id, 'username' => 'creator', 'balance' => 100.00]]
        ]));
        
        // Set logged-in user as the requester
        $this->CI->user_id = $this->requester_id;

        $output = $this->request('POST', "requests/review_submission/{$this->submission_id}/accept");
        
        $this->assertRedirect("requests/view/{$this->public_request_id}");
        $this->assertEquals('Insufficient balance to accept this submission.', $this->CI->session->flashdata('error_message'));
    }
    
    public function test_review_submission_reject_success()
    {
        $this->mock_base_models();
        $this->CI->user_id = $this->requester_id;

        $output = $this->request('POST', "requests/review_submission/{$this->submission_id}/reject");

        $this->assertRedirect("requests/view/{$this->public_request_id}");
        $this->assertEquals('Submission has been rejected.', $this->CI->session->flashdata('success_message'));
    }

    public function test_review_submission_not_requester()
    {
        $this->mock_base_models();
        // Log in as a random user, not the requester
        $this->CI->user_id = $this->other_user_id;

        $output = $this->request('POST', "requests/review_submission/{$this->submission_id}/accept");
        
        // Expect a 'show_error' call from the controller
        $this->assertStringContainsString('You do not have permission to review this submission.', $output);
    }
    
    public function test_review_submission_already_reviewed()
    {
        $this->mock_base_models();
        // Mock a submission that's already accepted
        $accepted_submission = (object)[
            'id' => $this->submission_id, 'request_id' => $this->public_request_id,
            'creator_user_id' => $this->creator_id, 'folder_id' => $this->folder_id, 'status' => 'accepted'
        ];
        $this->CI->Request_submission_model->method('get_submission')->willReturn($accepted_submission);
        
        $this->CI->user_id = $this->requester_id;

        $output = $this->request('POST', "requests/review_submission/{$this->submission_id}/accept");
        
        $this->assertStringContainsString('This submission has already been reviewed.', $output);
    }

    public function test_create_public_bounty()
    {
        $this->mock_base_models();
        $this->CI->user_id = $this->requester_id;
        $this->CI->Request_model->method('create_request')->willReturn($this->public_request_id);

        $post_data = [
            'title' => 'New Public Bounty',
            'description' => 'Details here',
            'reward_amount' => 150,
            'target_creator_user_id' => '' // Empty for public
        ];

        $output = $this->request('POST', 'requests/create', $post_data);
        
        $this->assertRedirect("requests/view/{$this->public_request_id}");
        $this->assertEquals('Your request has been posted successfully.', $this->CI->session->flashdata('success_message'));
    }
    
    public function test_create_direct_request()
    {
        $this->mock_base_models();
        $this->CI->user_id = $this->requester_id;
        $this->CI->Request_model->method('create_request')->willReturn($this->direct_request_id);

        $post_data = [
            'title' => 'New Direct Request',
            'description' => 'Details for you',
            'reward_amount' => 200,
            'target_creator_user_id' => $this->creator_id // Specific creator
        ];

        $output = $this->request('POST', 'requests/create', $post_data);
        
        $this->assertRedirect("requests/view/{$this->direct_request_id}");
        $this->assertEquals('Your request has been posted successfully.', $this->CI->session->flashdata('success_message'));
    }
}
