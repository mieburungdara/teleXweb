<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folder_Purchase_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Record a folder purchase.
     *
     * @param array $data Purchase data: folder_id, buyer_user_id, seller_user_id, price_at_purchase, balance_transaction_id (optional)
     * @return int Inserted ID
     */
    public function record_purchase($data)
    {
        $this->db->insert('folder_purchases', $data);
        return $this->db->insert_id();
    }

    /**
     * Check if a user has purchased a specific folder.
     *
     * @param int $user_id
     * @param int $folder_id
     * @return bool
     */
    public function has_user_purchased_folder($user_id, $folder_id)
    {
        $this->db->where('buyer_user_id', $user_id);
        $this->db->where('folder_id', $folder_id);
        $query = $this->db->get('folder_purchases');
        return $query->num_rows() > 0;
    }

    /**
     * Get all folders purchased by a user.
     *
     * @param int $user_id
     * @return array
     */
    public function get_purchased_folders($user_id)
    {
        $this->db->select('fp.*, f.folder_name, f.description, f.user_id as seller_id');
        $this->db->from('folder_purchases fp');
        $this->db->join('folders f', 'fp.folder_id = f.id');
        $this->db->where('fp.buyer_user_id', $user_id);
        $this->db->order_by('fp.purchase_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get all folders sold by a user.
     *
     * @param int $user_id
     * @return array
     */
    public function get_sold_folders($user_id)
    {
        $this->db->select('fp.*, f.folder_name, f.description, u.codename as buyer_codename');
        $this->db->from('folder_purchases fp');
        $this->db->join('folders f', 'fp.folder_id = f.id');
        $this->db->join('users u', 'fp.buyer_user_id = u.id');
        $this->db->where('fp.seller_user_id', $user_id);
        $this->db->order_by('fp.purchase_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
}
