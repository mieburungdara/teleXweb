<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Public_Collection_Folder_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Add a folder to a public collection.
     *
     * @param int $public_collection_id
     * @param int $folder_id
     * @param int $display_order
     * @return int|bool The ID of the new record on success, or FALSE on failure.
     */
    public function add_folder_to_collection($public_collection_id, $folder_id, $display_order = 0)
    {
        $data = [
            'public_collection_id' => $public_collection_id,
            'folder_id' => $folder_id,
            'display_order' => $display_order,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if ($this->db->insert('public_collection_folders', $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get all folders in a public collection.
     *
     * @param int $public_collection_id
     * @return array
     */
    public function get_folders_in_collection($public_collection_id)
    {
        $this->db->select('folders.*, public_collection_folders.display_order');
        $this->db->from('public_collection_folders');
        $this->db->join('folders', 'public_collection_folders.folder_id = folders.id');
        $this->db->where('public_collection_folders.public_collection_id', $public_collection_id);
        $this->db->order_by('public_collection_folders.display_order', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Remove a folder from a public collection.
     *
     * @param int $public_collection_id
     * @param int $folder_id
     * @return bool
     */
    public function remove_folder_from_collection($public_collection_id, $folder_id)
    {
        $this->db->where('public_collection_id', $public_collection_id);
        $this->db->where('folder_id', $folder_id);
        return $this->db->delete('public_collection_folders');
    }

    /**
     * Remove all folders from a public collection.
     *
     * @param int $public_collection_id
     * @return bool
     */
    public function remove_all_folders_from_collection($public_collection_id)
    {
        $this->db->where('public_collection_id', $public_collection_id);
        return $this->db->delete('public_collection_folders');
    }
}
