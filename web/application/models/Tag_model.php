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

    /**
     * Get tag suggestions for a user based on a search term.
     *
     * @param int $user_id
     * @param string $term
     * @return array
     */
    public function get_tag_suggestions($user_id, $term)
    {
        $this->db->select('tag_name');
        $this->db->where('created_by_user_id', $user_id);
        $this->db->like('tag_name', $term, 'after');
        $this->db->limit(10);
        $query = $this->db->get('tags');
        return $query->result_array();
    }

    /**
     * Find tags that are similar to a given term (case-insensitive, basic fuzzy matching).
     *
     * @param string $term The term to find similar tags for.
     * @return array An array of similar tags.
     */
    public function find_similar_tags($term)
    {
        $this->db->select('id, tag_name');
        $this->db->like('LOWER(tag_name)', strtolower($term));
        $this->db->limit(10);
        $query = $this->db->get('tags');
        return $query->result_array();
    }

    /**
     * Merge two tags by reassigning all associations from the source tag to the target tag,
     * and then deleting the source tag.
     *
     * @param int $source_tag_id The ID of the tag to be merged (deleted after merge).
     * @param int $target_tag_id The ID of the tag to merge into.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function merge_tags($source_tag_id, $target_tag_id)
    {
        if ($source_tag_id == $target_tag_id) {
            return FALSE; // Cannot merge a tag with itself
        }

        // 1. Reassign folder_tags from source_tag_id to target_tag_id
        $this->db->set('tag_id', $target_tag_id);
        $this->db->where('tag_id', $source_tag_id);
        $this->db->update('folder_tags');

        // 2. Delete the source tag
        $this->db->where('id', $source_tag_id);
        return $this->db->delete('tags');
    }

    /**
     * Get all tags.
     *
     * @return array
     */
    public function get_all_tags()
    {
        $query = $this->db->get('tags');
        return $query->result_array();
    }

    /**
     * Get a single tag by ID.
     *
     * @param int $id
     * @return array|null
     */
    public function get_tag_by_id($id)
    {
        $query = $this->db->get_where('tags', ['id' => $id]);
        return $query->row_array();
    }
}
