<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipping_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Inserts a new tipping transaction into the database.
     *
     * @param array $data An associative array containing the tipping transaction data.
     *                    Expected keys: tipper_user_id, recipient_user_id, folder_id (optional),
     *                    gross_amount, platform_fee, net_amount,
     *                    balance_transaction_id_tipper, balance_transaction_id_recipient.
     * @return int The ID of the newly inserted record, or false on failure.
     */
    public function create_tip($data)
    {
        $this->db->insert('tipping_transactions', $data);
        return $this->db->insert_id();
    }

    /**
     * Retrieves tipping transactions by recipient user ID.
     *
     * @param int $recipient_user_id The ID of the user who received the tip.
     * @param int $limit Optional limit for the number of results.
     * @param int $offset Optional offset for pagination.
     * @return array An array of tipping transaction objects.
     */
    public function get_tips_received($recipient_user_id, $limit = 10, $offset = 0)
    {
        $this->db->select('tt.*, tu.username as tipper_username, ru.username as recipient_username, f.name as folder_name');
        $this->db->from('tipping_transactions tt');
        $this->db->join('users tu', 'tu.id = tt.tipper_user_id');
        $this->db->join('users ru', 'ru.id = tt.recipient_user_id');
        $this->db->join('folders f', 'f.id = tt.folder_id', 'left'); // Left join because folder_id can be null
        $this->db->where('tt.recipient_user_id', $recipient_user_id);
        $this->db->order_by('tt.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    /**
     * Retrieves tipping transactions by tipper user ID.
     *
     * @param int $tipper_user_id The ID of the user who gave the tip.
     * @param int $limit Optional limit for the number of results.
     * @param int $offset Optional offset for pagination.
     * @return array An array of tipping transaction objects.
     */
    public function get_tips_given($tipper_user_id, $limit = 10, $offset = 0)
    {
        $this->db->select('tt.*, tu.username as tipper_username, ru.username as recipient_username, f.name as folder_name');
        $this->db->from('tipping_transactions tt');
        $this->db->join('users tu', 'tu.id = tt.tipper_user_id');
        $this->db->join('users ru', 'ru.id = tt.recipient_user_id');
        $this->db->join('folders f', 'f.id = tt.folder_id', 'left'); // Left join because folder_id can be null
        $this->db->where('tt.tipper_user_id', $tipper_user_id);
        $this->db->order_by('tt.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    /**
     * Retrieves a single tipping transaction by its ID.
     *
     * @param int $tip_id The ID of the tipping transaction.
     * @return object|null The tipping transaction object, or null if not found.
     */
    public function get_tip_by_id($tip_id)
    {
        $this->db->select('tt.*, tu.username as tipper_username, ru.username as recipient_username, f.name as folder_name');
        $this->db->from('tipping_transactions tt');
        $this->db->join('users tu', 'tu.id = tt.tipper_user_id');
        $this->db->join('users ru', 'ru.id = tt.recipient_user_id');
        $this->db->join('folders f', 'f.id = tt.folder_id', 'left');
        $this->db->where('tt.id', $tip_id);
        return $this->db->get()->row();
    }
}
