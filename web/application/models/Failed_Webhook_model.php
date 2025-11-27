<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Failed_Webhook_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log a failed webhook.
     *
     * @param string $webhook_url
     * @param string $payload
     * @param string $error_message
     * @param int $delay_seconds Delay until next retry, default 60 seconds (1 minute).
     * @return int|bool The ID of the new record on success, or FALSE on failure.
     */
    public function log_failed_webhook($webhook_url, $payload, $error_message, $delay_seconds = 60)
    {
        $data = [
            'webhook_url' => $webhook_url,
            'payload' => $payload,
            'error_message' => $error_message,
            'attempt_count' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'last_attempt_at' => date('Y-m-d H:i:s'),
            'next_attempt_at' => date('Y-m-d H:i:s', time() + $delay_seconds),
            'status' => 'pending',
        ];
        if ($this->db->insert('failed_webhooks', $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get a failed webhook record by ID.
     *
     * @param int $id
     * @return array|null
     */
    public function get_failed_webhook($id)
    {
        $query = $this->db->get_where('failed_webhooks', ['id' => $id]);
        return $query->row_array();
    }

    /**
     * Get all failed webhook records (or filter by status).
     *
     * @param string|null $status Filter by status ('pending', 'retrying', 'failed', 'success').
     * @return array
     */
    public function get_all_failed_webhooks($status = null)
    {
        if ($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('failed_webhooks');
        return $query->result_array();
    }

    /**
     * Update a failed webhook record (e.g., after a retry attempt).
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_failed_webhook($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('failed_webhooks', $data);
    }

    /**
     * Mark a failed webhook as success.
     *
     * @param int $id
     * @return bool
     */
    public function mark_as_success($id)
    {
        return $this->update_failed_webhook($id, ['status' => 'success']);
    }

    /**
     * Mark a failed webhook as permanently failed.
     *
     * @param int $id
     * @param string $error_message
     * @return bool
     */
    public function mark_as_failed($id, $error_message)
    {
        return $this->update_failed_webhook($id, ['status' => 'failed', 'error_message' => $error_message]);
    }

    /**
     * Get webhooks that are due for retry.
     *
     * @return array
     */
    public function get_webhooks_due_for_retry()
    {
        $this->db->where_in('status', ['pending', 'retrying']);
        $this->db->where('next_attempt_at <=', date('Y-m-d H:i:s'));
        $query = $this->db->get('failed_webhooks');
        return $query->result_array();
    }
}
