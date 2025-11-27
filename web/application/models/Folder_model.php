<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folder_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new folder record.
     *
     * @param array $folder_data Data conforming to the folders table structure.
     * @return int|bool The ID of the new folder on success, or FALSE on failure.
     */
    public function create_folder($folder_data)
    {
        $folder_data['created_at'] = date('Y-m-d H:i:s');
        $folder_data['updated_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert('folders', $folder_data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get a user's folders.
     *
     * @param int $user_id
     * @return array An array of folder data.
     */
    public function get_user_folders($user_id)
    {
        $query = $this->db->get_where('folders', ['user_id' => $user_id, 'deleted_at' => NULL]);
        return $query->result_array();
    }
}
