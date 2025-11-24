<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Creates a new content request (public bounty or direct request).
     *
     * @param array $data Associative array of request data.
     * @return int The ID of the newly inserted request.
     */
    public function create_request($data)
    {
        $this->db->insert('requests', $data);
        return $this->db->insert_id();
    }

    /**
     * Retrieves a single request by its ID.
     *
     * @param int $request_id
     * @return object|null
     */
    public function get_request($request_id)
    {
        return $this->db->get_where('requests', ['id' => $request_id])->row();
    }

    /**
     * Retrieves all open public bounties with filtering.
     *
     * @param string $sort_by 'reward' or 'time'
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_public_bounties($sort_by = 'time', $limit = 20, $offset = 0)
    {
        $this->db->select('r.*, u.username as requester_username');
        $this->db->from('requests r');
        $this->db->join('users u', 'u.id = r.requester_user_id');
        $this->db->where('r.type', 'public_bounty');
        $this->db->where('r.status', 'open');

        if ($sort_by == 'reward') {
            $this->db->order_by('r.reward_amount', 'DESC');
        } else {
            $this->db->order_by('r.created_at', 'DESC');
        }

        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    /**
     * Retrieves all direct requests sent to a specific creator.
     *
     * @param int $creator_user_id
     * @return array
     */
    public function get_direct_requests_for_creator($creator_user_id)
    {
        $this->db->select('r.*, u.username as requester_username');
        $this->db->from('requests r');
        $this->db->join('users u', 'u.id = r.requester_user_id');
        $this->db->where('r.type', 'direct_request');
        $this->db->where('r.target_creator_user_id', $creator_user_id);
        $this->db->order_by('r.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Retrieves all requests created by a specific user.
     *
     * @param int $requester_user_id
     * @return array
     */
    public function get_requests_by_requester($requester_user_id)
    {
        $this->db->where('requester_user_id', $requester_user_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('requests')->result();
    }
    
    /**
     * Updates the status of a request.
     *
     * @param int $request_id
     * @param string $status 'open', 'closed', 'cancelled'
     * @return bool
     */
    public function update_status($request_id, $status)
    {
        return $this->db->update('requests', ['status' => $status], ['id' => $request_id]);
    }
}
