<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folder_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get a folder by its ID.
     *
     * @param int $folder_id
     * @return object|null
     */
    public function get_folder($folder_id)
    {
        $this->db->where('id', $folder_id);
        $query = $this->db->get('folders');
        return $query->row();
    }

    /**
     * Update folder details, including price and for sale status.
     *
     * @param int $folder_id
     * @param array $data
     * @return bool
     */
    public function update_folder($folder_id, $data)
    {
        $this->db->where('id', $folder_id);
        return $this->db->update('folders', $data);
    }

    /**
     * Get folders listed for sale.
     *
     * @return array
     */
    public function get_folders_for_sale()
    {
        $this->db->where('is_for_sale', 1);
        $this->db->where('price >', 0);
        $query = $this->db->get('folders');
        return $query->result();
    }

    /**
     * Get a user's folders.
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_folders($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at IS NULL');
        $query = $this->db->get('folders');
        return $query->result();
    }

    // Other folder-related methods would go here
}
