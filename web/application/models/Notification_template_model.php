<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_template_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new notification template.
     *
     * @param array $data
     * @return int|bool The ID of the new template on success, or FALSE on failure.
     */
    public function create_template($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($this->db->insert('notification_templates', $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get a notification template by ID.
     *
     * @param int $id
     * @return array|null
     */
    public function get_template($id)
    {
        $query = $this->db->get_where('notification_templates', ['id' => $id]);
        return $query->row_array();
    }

    /**
     * Get a notification template by event name.
     *
     * @param string $event_name
     * @return array|null
     */
    public function get_template_by_event($event_name)
    {
        $query = $this->db->get_where('notification_templates', ['event_name' => $event_name]);
        return $query->row_array();
    }

    /**
     * Get all notification templates.
     *
     * @return array
     */
    public function get_all_templates()
    {
        $query = $this->db->get('notification_templates');
        return $query->result_array();
    }

    /**
     * Update a notification template.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_template($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('notification_templates', $data);
    }

    /**
     * Delete a notification template.
     *
     * @param int $id
     * @return bool
     */
    public function delete_template($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('notification_templates');
    }
}
