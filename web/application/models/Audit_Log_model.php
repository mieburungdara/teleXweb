<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_Log_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    /**
     * Log an action performed by a user.
     *
     * @param string $event_type Description of the event (e.g., 'file_deleted', 'folder_updated').
     * @param string $entity_type Type of entity affected (e.g., 'file', 'folder', 'user').
     * @param int|null $entity_id ID of the affected entity.
     * @param array $old_values Old data (optional).
     * @param array $new_values New data (optional).
     * @return bool
     */
    public function log_action($event_type, $entity_type, $entity_id = null, $old_values = [], $new_values = [])
    {
        $user_id = $this->session->userdata('user_id');
        $ip_address = $this->input->ip_address();

        $data = [
            'user_id' => $user_id,
            'event_type' => $event_type,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'old_values' => json_encode($old_values),
            'new_values' => json_encode($new_values),
            'ip_address' => $ip_address,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        return $this->db->insert('audit_logs', $data);
    }
}
