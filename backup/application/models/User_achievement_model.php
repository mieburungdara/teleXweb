<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_achievement_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Awards an achievement to a user.
     *
     * @param int $user_id
     * @param int $achievement_id
     * @return bool Returns true on success, false if the user already has it or on failure.
     */
    public function award_achievement($user_id, $achievement_id)
    {
        // First, check if the user already has this achievement to prevent duplicates
        if ($this->has_achievement($user_id, $achievement_id)) {
            return false;
        }

        $data = [
            'user_id' => $user_id,
            'achievement_id' => $achievement_id,
            'achieved_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('user_achievements', $data);
    }

    /**
     * Checks if a user has a specific achievement.
     *
     * @param int $user_id
     * @param int $achievement_id
     * @return bool
     */
    public function has_achievement($user_id, $achievement_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('achievement_id', $achievement_id);
        return $this->db->count_all_results('user_achievements') > 0;
    }

    /**
     * Retrieves all achievements for a given user.
     *
     * @param int $user_id
     * @return array An array of achievement objects joined with user_achievements data.
     */
    public function get_user_achievements($user_id)
    {
        $this->db->select('a.name, a.description, a.badge_icon_url, a.xp_reward, ua.achieved_at');
        $this->db->from('user_achievements ua');
        $this->db->join('achievements a', 'a.id = ua.achievement_id');
        $this->db->where('ua.user_id', $user_id);
        $this->db->order_by('ua.achieved_at', 'DESC');
        
        return $this->db->get()->result();
    }
}
