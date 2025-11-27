<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folder_Like_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Toggles a like for a folder by a user.
     * If the like exists, it's removed. If not, it's added.
     *
     * @param int $folder_id
     * @param int $user_id
     * @return string 'liked' or 'unliked'
     */
    public function toggle_like($folder_id, $user_id)
    {
        $this->db->where('folder_id', $folder_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('folder_likes');

        if ($query->num_rows() > 0) {
            // Like exists, so remove it
            $this->db->where('folder_id', $folder_id);
            $this->db->where('user_id', $user_id);
            $this->db->delete('folder_likes');
            return 'unliked';
        } else {
            // Like does not exist, so add it
            $data = [
                'folder_id' => $folder_id,
                'user_id' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->insert('folder_likes', $data);
            return 'liked';
        }
    }

    /**
     * Get the number of likes for a folder.
     *
     * @param int $folder_id
     * @return int
     */
    public function get_like_count($folder_id)
    {
        $this->db->where('folder_id', $folder_id);
        return $this->db->count_all_results('folder_likes');
    }

    /**
     * Check if a user has liked a folder.
     *
     * @param int $folder_id
     * @param int $user_id
     * @return bool
     */
    public function has_user_liked($folder_id, $user_id)
    {
        $this->db->where('folder_id', $folder_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('folder_likes');
        return ($query->num_rows() > 0);
    }
}
