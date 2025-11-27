<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get a tag by name, or create it if it doesn't exist.
     *
     * @param string $tag_name
     * @param int $user_id The ID of the user creating the tag.
     * @return int The ID of the tag.
     */
    public function get_or_create_tag($tag_name, $user_id)
    {
        // Trim and sanitize the tag name
        $tag_name = trim(strtolower($tag_name));
        if (empty($tag_name)) {
            return null;
        }

        // Check if the tag already exists
        $query = $this->db->get_where('tags', ['tag_name' => $tag_name]);
        $tag = $query->row_array();

        if ($tag) {
            return $tag['id'];
        } else {
            // Create the tag
            $data = [
                'tag_name' => $tag_name,
                'created_by_user_id' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->insert('tags', $data);
            return $this->db->insert_id();
        }
    }
}
