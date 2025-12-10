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
        $folder_data['code'] = substr(md5(uniqid(rand(), true)), 0, 12); // Generate unique code
        
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
    public function get_user_folders($user_id, $parent_folder_id = null)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('parent_folder_id', $parent_folder_id);
        $this->db->where('deleted_at IS NULL');
        $query = $this->db->get('folders');
        return $query->result_array();
    }

    /**
     * Get a single folder by its ID and user ID.
     *
     * @param int $id
     * @param int $user_id
     * @return array|null
     */
    public function get_folder_by_id($id, $user_id)
    {
        $query = $this->db->get_where('folders', ['id' => $id, 'user_id' => $user_id]);
        return $query->row_array();
    }

    /**
     * Get the hierarchy (breadcrumbs) for a given folder.
     *
     * @param int $folder_id
     * @return array
     */
    public function get_folder_hierarchy($folder_id)
    {
        $path = [];
        $current_folder_id = $folder_id;

        while ($current_folder_id) {
            $this->db->select('id, folder_name, parent_folder_id');
            $folder = $this->db->get_where('folders', ['id' => $current_folder_id])->row_array();
            if ($folder) {
                array_unshift($path, $folder);
                $current_folder_id = $folder['parent_folder_id'];
            } else {
                $current_folder_id = null; // Break loop if folder not found
            }
        }
        return $path;
    }

    /**
     * Update an existing folder.
     *
     * @param int $id
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_folder($id, $user_id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('folders', $data);
    }

    /**
     * Soft delete a folder.
     *
     * @param int $id
     * @param int $user_id
     * @return bool
     */
    public function delete_folder($id, $user_id)
    {
        $data = ['deleted_at' => date('Y-m-d H:i:s')];
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('folders', $data);
    }

    /**
     * Recalculates and updates the folder_size for a given folder.
     *
     * @param int $folder_id The ID of the folder to update.
     * @return bool True on success, False on failure.
     */
    public function update_folder_size($folder_id)
    {
        // Calculate the sum of file sizes for all files in this folder
        $this->db->select_sum('file_size');
        $this->db->where('folder_id', $folder_id);
        $this->db->where('deleted_at IS NULL'); // Only consider non-deleted files
        $query = $this->db->get('files');
        $result = $query->row();
        $total_size = $result->file_size ?? 0;

        // Update the folder_size in the folders table
        $this->db->where('id', $folder_id);
        return $this->db->update('folders', ['folder_size' => $total_size, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get all tags for a specific folder.
     *
     * @param int $folder_id
     * @return array
     */
    public function get_folder_tags($folder_id)
    {
        $this->db->select('tags.tag_name');
        $this->db->from('folder_tags');
        $this->db->join('tags', 'folder_tags.tag_id = tags.id');
        $this->db->where('folder_tags.folder_id', $folder_id);
        $query = $this->db->get();
        return array_column($query->result_array(), 'tag_name');
    }

    /**
     * Update the tags for a folder from a comma-separated string.
     *
     * @param int $folder_id
     * @param string $tags_string
     * @param int $user_id
     */
    public function update_folder_tags($folder_id, $tags_string, $user_id)
    {
        $this->load->model('Tag_model');

        // 1. Remove all existing tags for this folder
        $this->db->where('folder_id', $folder_id);
        $this->db->delete('folder_tags');

        // 2. Add the new tags
        $tags = array_map('trim', explode(',', $tags_string));
        $tags = array_filter($tags); // Remove empty tags

        foreach ($tags as $tag_name) {
            $tag_id = $this->Tag_model->get_or_create_tag($tag_name, $user_id);
            if ($tag_id) {
                $this->db->insert('folder_tags', [
                    'folder_id' => $folder_id,
                    'tag_id' => $tag_id,
                ]);
            }
        }
    }

    /**
     * Get a single folder by its unique share code.
     *
     * @param string $code
     * @return array|null
     */
    public function get_folder_by_code($code)
    {
        $query = $this->db->get_where('folders', ['code' => $code, 'deleted_at' => NULL]);
        return $query->row_array();
    }

    /**
     * Toggle the favorite status of a folder.
     *
     * @param int $id
     * @param int $user_id
     * @return bool
     */
    public function toggle_favorite_folder($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->set('is_favorited', '1 - is_favorited', FALSE); // SQL to toggle boolean/tinyint
        return $this->db->update('folders');
    }

    /**
     * Get statistics for a given folder.
     *
     * @param int $folder_id
     * @return array
     */
    public function get_folder_stats($folder_id)
    {
        $stats = [];

        // Get file count
        $this->db->where('folder_id', $folder_id);
        $this->db->where('deleted_at IS NULL');
        $stats['file_count'] = $this->db->count_all_results('files');

        // Get total size (already in folders.folder_size, but can be recalculated if needed)
        // For now, we will rely on the pre-calculated folder_size.

        // Get latest activity (latest file added)
        $this->db->select_max('created_at', 'latest_activity');
        $this->db->where('folder_id', $folder_id);
        $query = $this->db->get('files');
        $result = $query->row();
        $stats['latest_activity'] = $result->latest_activity;

        return $stats;
    }

    /**
     * Count all non-deleted folder records.
     *
     * @return int
     */
    public function count_all_folders()
    {
        $this->db->where('deleted_at IS NULL');
        return $this->db->count_all_results('folders');
    }

    /**
     * Count all non-deleted folders for a specific user.
     *
     * @param int $user_id
     * @return int
     */
    public function count_all_folders_for_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at IS NULL');
        return $this->db->count_all_results('folders');
    }

    /**
     * Get the most recent folders for a user.
     *
     * @param int $user_id
     * @param int $limit
     * @return array
     */
    public function get_recent_folders_by_user($user_id, $limit = 5)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at IS NULL');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('folders');
        return $query->result_array();
    }
}
