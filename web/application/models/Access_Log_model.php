<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_Log_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log an access to an entity (file or folder).
     *
     * @param string $entity_type 'file' or 'folder'
     * @param int $entity_id
     * @param int|null $user_id
     * @return bool
     */
    public function log_access($entity_type, $entity_id, $user_id = null)
    {
        $data = [
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'user_id' => $user_id,
            'accessed_at' => date('Y-m-d H:i:s'),
        ];
        return $this->db->insert('access_logs', $data);
    }

    /**
     * Get trending items (files or folders) for a given period.
     *
     * @param string $entity_type 'file' or 'folder'
     * @param int $days
     * @param int $limit
     * @return array
     */
    public function get_trending_items($entity_type, $days = 7, $limit = 5)
    {
        $this->db->select("entity_id, COUNT(id) as access_count");
        $this->db->where('entity_type', $entity_type);
        $this->db->where('accessed_at >=', date('Y-m-d H:i:s', strtotime("-{$days} days")));
        $this->db->group_by('entity_id');
        $this->db->order_by('access_count', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('access_logs');
        return $query->result_array();
    }
}
