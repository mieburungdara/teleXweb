<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_log_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log an action to the audit_logs table.
     *
     * @param array $data Audit log data: user_id, action, entity_type, entity_id, old_value_json, new_value_json, ip_address
     * @return int Inserted ID
     */
    public function log_action($data)
    {
        $this->db->insert('audit_logs', $data);
        return $this->db->insert_id();
    }

    /**
     * Get audit logs for a specific user.
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_audit_logs($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('audit_logs');
        return $query->result();
    }

    /**
     * Get all audit logs.
     *
     * @return array
     */
    public function get_all_audit_logs()
    {
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('audit_logs');
        return $query->result();
    }
}
