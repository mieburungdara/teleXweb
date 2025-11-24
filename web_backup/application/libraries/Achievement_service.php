<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Achievement_service {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Achievement_model');
        $this->CI->load->model('User_achievement_model');
        $this->CI->load->model('Balance_Transaction_model');
        // Add other models as new criteria are developed
    }

    /**
     * Checks all achievement criteria for a specific user.
     * This is the main method to be called by a cron job.
     *
     * @param int $user_id
     */
    public function check_all_achievements_for_user($user_id)
    {
        $this->check_income_achievements($user_id);
        // Add calls to other achievement check methods here
        // e.g., $this->check_folder_count_achievements($user_id);
    }

    /**
     * Checks and awards achievements related to total income.
     *
     * @param int $user_id
     */
    public function check_income_achievements($user_id)
    {
        // 1. Get all achievements related to 'total_income'
        $income_achievements = $this->CI->Achievement_model->get_achievements_by_criteria_type('total_income');
        if (empty($income_achievements)) {
            return;
        }

        // 2. Get user's total income (this is the expensive query we do once)
        $total_income = $this->CI->Balance_Transaction_model->get_total_income_for_user($user_id);

        foreach ($income_achievements as $achievement) {
            // 3. Check if user already has this achievement
            if ($this->CI->User_achievement_model->has_achievement($user_id, $achievement->id)) {
                continue; // Skip to the next achievement
            }

            // 4. Decode criteria and check if user qualifies
            $criteria = json_decode($achievement->criteria_json);
            $required_income = $criteria->value ?? null;

            if ($required_income !== null && $total_income >= $required_income) {
                // 5. Award the achievement
                $this->CI->User_achievement_model->award_achievement($user_id, $achievement->id);
                // Optionally, you can also award XP here
                // $this->CI->User_model->add_xp($user_id, $achievement->xp_reward);
            }
        }
    }

    // You can add more methods for other criteria types here.
    // For example:
    /*
    public function check_folder_count_achievements($user_id)
    {
        // 1. Get achievements for 'folder_count'
        // 2. Get user's folder count from Folder_model
        // 3. Loop, check, and award
    }
    */
}
