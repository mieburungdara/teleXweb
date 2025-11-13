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
        $data['is_owner'] = ($folder->user_id == $this->user_id);
        $data['has_purchased'] = $this->Folder_Purchase_model->has_user_purchased_folder($this->user_id, $folder_id);

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
}
