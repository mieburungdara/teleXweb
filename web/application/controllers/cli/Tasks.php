<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!is_cli()) {
            show_404();
            return;
        }
        $this->load->model(['File_model', 'Folder_model']);
        $this->load->helper('url');
    }

    /**
     * Permanently delete records that have been soft-deleted for a while.
     * @param int $days The number of days after which to permanently delete. Defaults to 30.
     */
    public function cleanup_soft_deletes($days = 30)
    {
        echo "Starting cleanup of soft-deleted records older than {$days} days...\n";

        $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        // Cleanup files
        $this->db->where('deleted_at <', $cutoff_date);
        $this->db->delete('files');
        $files_affected = $this->db->affected_rows();
        echo "Deleted {$files_affected} old file records.\n";

        // Cleanup folders
        $this->db->where('deleted_at <', $cutoff_date);
        $this->db->delete('folders');
        $folders_affected = $this->db->affected_rows();
        echo "Deleted {$folders_affected} old folder records.\n";

        echo "Cleanup complete.\n";
    }

    /**
     * Placeholder for a weekly report generation task.
     */
    public function generate_weekly_report()
    {
        echo "Generating weekly report (placeholder)...";
        // In a real application, you would:
        // 1. Gather stats (new files, new users, etc.).
        // 2. Format them into an email or a report file.
        // 3. Send the report to administrators.
        echo "Weekly report generation complete.";
    }
}

