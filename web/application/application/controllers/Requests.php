<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requests extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Request_model');
        $this->load->model('Request_submission_model');
        $this->load->model('User_model');
        $this->load->model('Folder_model');
        $this->load->model('Folder_Purchase_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('session');

        // Assuming user authentication is handled and user_id is available in session
        // For demonstration, we'll use a hardcoded ID but check for its existence.
        // $this->user_id = $this->session->userdata('user_id');
        $this->user_id = 1; // Dummy user ID. Replace with session logic.
        if (!$this->user_id) {
            // In a real app, you'd redirect to a login page.
            // For now, we'll just show an error if no user is 'logged in'.
            show_error('You must be logged in to use this feature.');
        }
    }

    /**
     * Displays the public bounties page with filtering.
     */
    public function index()
    {
        $sort_by = $this->input->get('sort_by') === 'reward' ? 'reward' : 'time';
        
        $data['bounties'] = $this->Request_model->get_public_bounties($sort_by);
        $data['title'] = 'Public Bounties';

        $this->load->view('requests/public_bounties_list', $data); // Assumed view path
    }

    /**
     * Displays the user's personal requests dashboard.
     */
    public function my_requests()
    {
        $data['my_posted_requests'] = $this->Request_model->get_requests_by_requester($this->user_id);
        $data['direct_requests_to_me'] = $this->Request_model->get_direct_requests_for_creator($this->user_id);
        $data['my_submissions'] = $this->Request_submission_model->get_submissions_by_creator($this->user_id);
        $data['title'] = 'My Requests Dashboard';

        // You might want to fetch more details for each request/submission here
        
        $this->load->view('requests/my_requests_dashboard', $data); // Assumed view path
    }

    /**
     * View a single request and its submissions.
     */
    public function view($request_id)
    {
        $request = $this->Request_model->get_request($request_id);
        if (!$request) {
            show_404();
        }

        $data['request'] = $request;
        $data['submissions'] = $this->Request_submission_model->get_submissions_for_request($request_id);
        $data['is_requester'] = ($request->requester_user_id == $this->user_id);
        
        // Check if current user is the target creator for a direct request
        $data['is_target_creator'] = ($request->target_creator_user_id == $this->user_id);

        $data['title'] = htmlspecialchars($request->title);
        $this->load->view('requests/view_request', $data); // Assumed view path
    }

    /**
     * Create a new request (handles both form display and submission).
     */
    public function create()
    {
        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('reward_amount', 'Reward Amount', 'required|numeric|greater_than[0]');
        // Add more validation rules as needed for deadline, priority, etc.

        if ($this->form_validation->run() === FALSE) {
            // Load view with the form
            $data['title'] = 'Create New Request';
            $this->load->view('requests/create_request_form', $data); // Assumed view path
        } else {
            // Process the form
            $target_creator_id = $this->input->post('target_creator_user_id');

            $request_data = [
                'requester_user_id' => $this->user_id,
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'reward_amount' => $this->input->post('reward_amount'),
                'deadline_at' => $this->input->post('deadline_at') ?: null,
                'priority' => $this->input->post('priority') ?: 'normal',
                'target_creator_user_id' => $target_creator_id ?: null,
                'type' => $target_creator_id ? 'direct_request' : 'public_bounty',
                'status' => 'open',
            ];

            $request_id = $this->Request_model->create_request($request_data);
            
            $this->session->set_flashdata('success_message', 'Your request has been posted successfully.');
            redirect('requests/view/' . $request_id);
        }
    }

    /**
     * Submit a folder for a request.
     */
    public function submit($request_id)
    {
        $this->form_validation->set_rules('folder_id', 'Folder', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $data['request'] = $this->Request_model->get_request($request_id);
            // Pass user's own folders to the view so they can select one
            $data['my_folders'] = $this->Folder_model->get_user_folders($this->user_id);
            $data['title'] = 'Submit Folder for Request';
            $this->load->view('requests/submit_folder_form', $data); // Assumed view path
        } else {
            $submission_data = [
                'request_id' => $request_id,
                'creator_user_id' => $this->user_id,
                'folder_id' => $this->input->post('folder_id'),
                'status' => 'pending_review',
            ];

            $this->Request_submission_model->create_submission($submission_data);
            $this->session->set_flashdata('success_message', 'Your folder has been submitted for review.');
            redirect('requests/view/' . $request_id);
        }
    }

    /**
     * Review a submission (Accept or Reject).
     */
    public function review_submission($submission_id, $action)
    {
        $submission = $this->Request_submission_model->get_submission($submission_id);
        if (!$submission) show_404();

        $request = $this->Request_model->get_request($submission->request_id);
        if (!$request || $request->requester_user_id != $this->user_id) {
            show_error('You do not have permission to review this submission.');
        }

        if ($submission->status !== 'pending_review') {
            show_error('This submission has already been reviewed.');
        }

        if ($action === 'accept') {
            $requester = $this->User_model->get_user($this->user_id);
            $creator = $this->User_model->get_user($submission->creator_user_id);

            // 1. Check requester's balance
            if ($requester->balance < $request->reward_amount) {
                $this->session->set_flashdata('error_message', 'Insufficient balance to accept this submission.');
                redirect('requests/view/' . $request->id);
                return;
            }

            // 2. Start Transaction
            $this->db->trans_start();

            // 3. Deduct from requester
            $deduction_desc = 'Payment for accepted submission for request: "' . htmlspecialchars($request->title) . '"';
            $tipper_trans_id = $this->User_model->deduct_balance($requester->id, $request->reward_amount, $deduction_desc, null, 'request_payment', $request->id);

            // 4. Add to creator (assuming a 10% platform fee)
            $platform_fee = $request->reward_amount * 0.10;
            $net_amount = $request->reward_amount - $platform_fee;
            $addition_desc = 'Reward for accepted submission for request: "' . htmlspecialchars($request->title) . '"';
            $this->User_model->add_balance($creator->id, $net_amount, $addition_desc, null, 'request_reward', $request->id);

            // 5. Update submission status
            $this->Request_submission_model->update_submission($submission_id, [
                'status' => 'accepted',
                'reviewed_at' => date('Y-m-d H:i:s'),
                'balance_transaction_id' => $tipper_trans_id
            ]);
            
            // 6. Grant folder access to requester
            $this->Folder_Purchase_model->record_purchase([
                'folder_id' => $submission->folder_id,
                'buyer_user_id' => $requester->id,
                'seller_user_id' => $creator->id,
                'price_at_purchase' => $request->reward_amount,
                'purchase_type' => 'bounty_reward'
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error_message', 'A transaction error occurred. Please try again.');
            } else {
                $this->session->set_flashdata('success_message', 'Submission accepted and reward sent!');
            }

        } elseif ($action === 'reject') {
            $this->Request_submission_model->update_submission($submission_id, [
                'status' => 'rejected',
                'reviewed_at' => date('Y-m-d H:i:s')
            ]);
            $this->session->set_flashdata('success_message', 'Submission has been rejected.');
        }

        redirect('requests/view/' . $request->id);
    }
}
