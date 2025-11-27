<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new notification record.
     *
     * @param array $data
     * @return int|bool The ID of the new notification on success, or FALSE on failure.
     */
    public function create_notification($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        if ($this->db->insert('notifications', $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get a notification by ID.
     *
     * @param int $id
     * @param int $user_id (Optional)
     * @return array|null
     */
    public function get_notification($id, $user_id = null)
    {
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        $query = $this->db->get_where('notifications', ['id' => $id]);
        return $query->row_array();
    }

    /**
     * Get all notifications for a user.
     *
     * @param int $user_id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_user_notifications($user_id, $limit = 10, $offset = 0)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('notifications');
        return $query->result_array();
    }

    /**
     * Mark a notification as read.
     *
     * @param int $id
     * @param int $user_id
     * @return bool
     */
    public function mark_as_read($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('notifications', ['read_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get count of unread notifications for a user.
     *
     * @param int $user_id
     * @return int
     */
    public function count_unread_notifications($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('read_at IS NULL');
        return $this->db->count_all_results('notifications');
    }
}
