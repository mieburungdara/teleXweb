<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Achievement_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Retrieves a single achievement by its ID.
     *
     * @param int $id
     * @return object|null
     */
    public function get_achievement($id)
    {
        return $this->db->get_where('achievements', ['id' => $id])->row();
    }

    /**
     * Retrieves all achievements.
     *
     * @return array
     */
    public function get_all_achievements()
    {
        return $this->db->get('achievements')->result();
    }

    /**
     * Retrieves all achievements of a specific criteria type.
     *
     * @param string $type The criteria type (e.g., 'total_income', 'folders_sold').
     * @return array
     */
    public function get_achievements_by_criteria_type($type)
    {
        $this->db->where("JSON_UNQUOTE(JSON_EXTRACT(criteria_json, '$.type'))", $type);
        return $this->db->get('achievements')->result();
    }

    /**
     * Creates a new achievement definition.
     *
     * @param array $data
     * @return int Insert ID.
     */
    public function create_achievement($data)
    {
        $this->db->insert('achievements', $data);
        return $this->db->insert_id();
    }
}
