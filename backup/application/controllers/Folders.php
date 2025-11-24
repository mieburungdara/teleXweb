<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folders extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Folder_model');
        $this->load->model('User_model');
        $this->load->model('Folder_Purchase_model');
        $this->load->model('Balance_Transaction_model'); // For logging
        $this->load->model('Tipping_model'); // For tipping transactions
        $this->load->helper('url');
        $this->load->library('form_validation'); // For input validation

        // Assuming user authentication is handled elsewhere and user_id is available
        // For now, let's assume a dummy user_id for demonstration
        $this->user_id = 1; 
    }

    public function index()
    {
        // Display user's folders
        $data['folders'] = $this->Folder_model->get_user_folders($this->user_id);
        $this->load->view('folder_list', $data); // Assuming a folder_list view
    }

    public function detail($folder_id)
    {
        $folder = $this->Folder_model->get_folder($folder_id);
        if (!$folder) {
            show_404();
        }

        $data['folder'] = $folder;
        $creator_user = $this->User_model->get_user($folder->user_id);
        $current_user = $this->User_model->get_user($this->user_id); // Assuming $this->user_id is the logged-in user

        $data['is_owner'] = ($folder->user_id == $this->user_id);
        $data['has_purchased'] = $this->Folder_Purchase_model->has_user_purchased_folder($this->user_id, $folder_id);
        $data['current_user_id'] = $this->user_id;
        $data['creator_username'] = $creator_user ? $creator_user->username : 'Unknown';
        $data['current_user_balance'] = $current_user ? $current_user->balance : 0;

        $this->load->view('folder_detail', $data);
    }

    public function set_for_sale($folder_id)
    {
        $folder = $this->Folder_model->get_folder($folder_id);
        if (!$folder || $folder->user_id != $this->user_id) {
            show_error('Folder not found or you do not own this folder.');
        }

        $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('is_for_sale', 'For Sale Status', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            // Reload view with validation errors
            $data['folder'] = $folder;
            $this->load->view('folder_detail', $data); // Or a dedicated set_for_sale view
        } else {
            $price = $this->input->post('price');
            $is_for_sale = $this->input->post('is_for_sale');

            $update_data = array(
                'price' => $price,
                'is_for_sale' => $is_for_sale
            );

            if ($this->Folder_model->update_folder($folder_id, $update_data)) {
                redirect('folders/detail/' . $folder_id);
            } else {
                show_error('Failed to update folder sale status.');
            }
        }
    }

    public function buy_folder($folder_id)
    {
        $folder = $this->Folder_model->get_folder($folder_id);
        if (!$folder || !$folder->is_for_sale || $folder->price <= 0) {
            show_error('Folder not found or not available for sale.');
        }

        if ($folder->user_id == $this->user_id) {
            show_error('You cannot purchase your own folder.');
        }

        if ($this->Folder_Purchase_model->has_user_purchased_folder($this->user_id, $folder_id)) {
            show_error('You have already purchased this folder.');
        }

        $buyer = $this->User_model->get_user($this->user_id);
        $seller = $this->User_model->get_user($folder->user_id);

        if (!$buyer || !$seller) {
            show_error('User data not found.');
        }

        if ($buyer->balance < $folder->price) {
            show_error('Insufficient balance to purchase this folder.');
        }

        // Start a transaction for the entire purchase process
        $this->db->trans_start();

        // Deduct from buyer's balance
        $deduction_description = 'Purchase of folder "' . $folder->folder_name . '" (ID: ' . $folder_id . ')';
        $this->User_model->deduct_balance($this->user_id, $folder->price, $deduction_description, null, 'folder_purchase', $folder_id);

        // Add to seller's balance
        $addition_description = 'Sale of folder "' . $folder->folder_name . '" (ID: ' . $folder_id . ')';
        $this->User_model->add_balance($folder->user_id, $folder->price, $addition_description, null, 'folder_sale', $folder_id);

        // Record the purchase
        $purchase_data = array(
            'folder_id' => $folder_id,
            'buyer_user_id' => $this->user_id,
            'seller_user_id' => $folder->user_id,
            'price_at_purchase' => $folder->price,
            // 'balance_transaction_id' will be set by the model if needed, or can be retrieved from add/deduct balance methods
        );
        $this->Folder_Purchase_model->record_purchase($purchase_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            show_error('Purchase failed due to a transaction error.');
        } else {
            // Generate deep-link for bot content delivery
            $bot_username = 'YourBotName'; // Replace with actual bot username
            $deep_link = "https://t.me/{$bot_username}?start=folder_access_{$folder_id}_{$this->user_id}";
            
            // Redirect to a success page or folder detail with deep-link
            $this->session->set_flashdata('success_message', 'Folder purchased successfully! Click <a href="' . $deep_link . '">here</a> to get your content via bot.');
            redirect('folders/detail/' . $folder_id);
        }
    }

    // Other folder-related methods would go here

    public function tip($folder_id)
    {
        // 1. Check if user is logged in
        if (!$this->user_id) { // Assuming $this->user_id is set for logged-in users
            $this->session->set_flashdata('error_message', 'You must be logged in to send a tip.');
            redirect('folders/detail/' . $folder_id);
            return;
        }

        $this->form_validation->set_rules('tip_amount', 'Tip Amount', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_message', validation_errors());
            redirect('folders/detail/' . $folder_id);
            return;
        }

        $folder = $this->Folder_model->get_folder($folder_id);
        if (!$folder) {
            show_404();
        }

        // 2. Authorization: Tipper cannot be the recipient
        if ($folder->user_id == $this->user_id) {
            $this->session->set_flashdata('error_message', 'You cannot send a tip to yourself.');
            redirect('folders/detail/' . $folder_id);
            return;
        }

        $tipper_user_id = $this->user_id;
        $recipient_user_id = $folder->user_id;
        $tip_amount = $this->input->post('tip_amount');

        $tipper = $this->User_model->get_user($tipper_user_id);
        $recipient = $this->User_model->get_user($recipient_user_id);

        if (!$tipper || !$recipient) {
            $this->session->set_flashdata('error_message', 'Invalid user data for tipping.');
            redirect('folders/detail/' . $folder_id);
            return;
        }

        // 3. Check Balance
        if ($tipper->balance < $tip_amount) {
            $this->session->set_flashdata('error_message', 'Insufficient balance to send this tip. Your current balance is ' . number_format($tipper->balance, 2) . ' credits.');
            redirect('folders/detail/' . $folder_id);
            return;
        }

        // Start a database transaction
        $this->db->trans_start();

        // Platform fee (e.g., 5%) - this can be configured
        $platform_fee_percentage = 0.05;
        $platform_fee = $tip_amount * $platform_fee_percentage;
        $net_amount_to_recipient = $tip_amount - $platform_fee;

        // Deduct from tipper's balance
        $deduction_description = 'Tip sent to ' . $recipient->username . ' for folder "' . $folder->folder_name . '" (ID: ' . $folder_id . ')';
        $tipper_balance_trans_id = $this->User_model->deduct_balance(
            $tipper_user_id,
            $tip_amount,
            $deduction_description,
            null, // No admin_id for user-initiated tip
            'tip_sent',
            $folder_id
        );

        // Add to recipient's balance
        $addition_description = 'Tip received from ' . $tipper->username . ' for folder "' . $folder->folder_name . '" (ID: ' . $folder_id . ')';
        $recipient_balance_trans_id = $this->User_model->add_balance(
            $recipient_user_id,
            $net_amount_to_recipient,
            $addition_description,
            null, // No admin_id for user-initiated tip
            'tip_received',
            $folder_id
        );

        // Record the tip in tipping_transactions table
        $tip_data = array(
            'tipper_user_id' => $tipper_user_id,
            'recipient_user_id' => $recipient_user_id,
            'folder_id' => $folder_id,
            'gross_amount' => $tip_amount,
            'platform_fee' => $platform_fee,
            'net_amount' => $net_amount_to_recipient,
            'balance_transaction_id_tipper' => $tipper_balance_trans_id,
            'balance_transaction_id_recipient' => $recipient_balance_trans_id,
        );
        $this->Tipping_model->create_tip($tip_data);

        $this->db->trans_complete(); // Complete transaction

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error_message', 'Tip failed due to a transaction error. Please try again.');
        } else {
            $this->session->set_flashdata('success_message', 'Tip of ' . number_format($tip_amount, 2) . ' credits sent successfully to ' . $recipient->username . '!');
        }

        redirect('folders/detail/' . $folder_id);
    }
}
