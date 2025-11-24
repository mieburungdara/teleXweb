<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // This controller should only be accessible from the command line (CLI)
        // or a trusted source, not a public browser.
        if (!$this->input->is_cli_request()) {
            // Or check for a specific secret key in the URL
            // e.g. if ($this->input->get('secret') !== 'YOUR_SECRET_KEY')
            show_error('Direct access is not allowed.', 403);
        }
        $this->load->library('achievement_service');
        $this->load->model('User_model');
    }

    /**
     * The main cron job method to award achievements.
     * This method iterates through all users and checks for new achievements.
     * 
     * To run this from your server's command line:
     * cd /path/to/your/project;
     * php index.php cron award_achievements
     */
    public function award_achievements()
    {
        echo "Starting achievement check for all users...\n";

        // Get all users. In a large system, you might paginate this.
        $users = $this->User_model->get_all_users();
        
        if (empty($users)) {
            echo "No users found.\n";
            return;
        }

        $awarded_count = 0;
        foreach ($users as $user) {
            echo "Checking user ID: {$user->id}...\n";
            // The service will handle the logic of checking and awarding
            $this->achievement_service->check_all_achievements_for_user($user->id);
        }

        echo "Achievement check complete.\n";
    }
}

