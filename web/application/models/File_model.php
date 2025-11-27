<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new file record.
     *
     * @param array $file_data Data conforming to the files table structure.
     * @return int|bool The ID of the new file on success, or FALSE on failure.
     */
    public function create_file($file_data)
    {
        $file_data['created_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert('files', $file_data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Find a file by its unique Telegram file ID.
     *
     * @param string $file_unique_id
     * @return array|null File data if found, null otherwise.
     */
    public function get_file_by_unique_id($file_unique_id)
    {
        $query = $this->db->get_where('files', ['file_unique_id' => $file_unique_id]);
        return $query->row_array();
    }
}
