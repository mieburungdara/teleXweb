<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_throttle_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Record a notification send and check if it's throttled.
     *
     * @param int $user_id
     * @param string $event_name
     * @param int $duration_seconds How long to throttle this event for this user (e.g., 3600 for 1 hour).
     * @return bool TRUE if notification can be sent (not throttled), FALSE if it should be throttled.
     */
    public function can_send_notification($user_id, $event_name, $duration_seconds = 3600)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('event_name', $event_name);
        $query = $this->db->get('notification_throttles');
        $throttle = $query->row_array();

        if ($throttle) {
            $last_sent_time = strtotime($throttle['last_sent_at']);
            if ((time() - $last_sent_time) < $throttle['throttle_duration_seconds']) {
                // Still throttled
                return FALSE;
            } else {
                // Throttle expired, update it
                $this->db->where('user_id', $user_id);
                $this->db->where('event_name', $event_name);
                $this->db->update('notification_throttles', [
                    'last_sent_at' => date('Y-m-d H:i:s'),
                    'throttle_duration_seconds' => $duration_seconds,
                ]);
                return TRUE;
            }
        } else {
            // No throttle record, create one and allow sending
            $this->db->insert('notification_throttles', [
                'user_id' => $user_id,
                'event_name' => $event_name,
                'last_sent_at' => date('Y-m-d H:i:s'),
                'throttle_duration_seconds' => $duration_seconds,
            ]);
            return TRUE;
        }
    }
}
