<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Smart_Collection_Rule_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('File_model'); // To apply rules against files
    }

    /**
     * Create a new smart collection rule.
     *
     * @param array $data
     * @return int|bool The ID of the new rule on success, or FALSE on failure.
     */
    public function create_rule($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($this->db->insert('smart_collection_rules', $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get a smart collection rule by ID.
     *
     * @param int $id
     * @param int $user_id
     * @return array|null
     */
    public function get_rule($id, $user_id)
    {
        $query = $this->db->get_where('smart_collection_rules', ['id' => $id, 'user_id' => $user_id, 'deleted_at' => NULL]);
        return $query->row_array();
    }

    /**
     * Get all smart collection rules for a user.
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_rules($user_id)
    {
        $query = $this->db->get_where('smart_collection_rules', ['user_id' => $user_id, 'deleted_at' => NULL]);
        return $query->result_array();
    }

    /**
     * Update a smart collection rule.
     *
     * @param int $id
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_rule($id, $user_id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('smart_collection_rules', $data);
    }

    /**
     * Soft delete a smart collection rule.
     *
     * @param int $id
     * @param int $user_id
     * @return bool
     */
    public function delete_rule($id, $user_id)
    {
        $data = ['deleted_at' => date('Y-m-d H:i:s')];
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('smart_collection_rules', $data);
    }

    /**
     * Apply a smart collection rule (JSON) to retrieve matching files.
     *
     * Rule JSON example:
     * {
     *   "conditions": [
     *     {"field": "mime_type", "operator": "LIKE", "value": "image%"},
     *     {"field": "is_favorited", "operator": "=", "value": true}
     *   ],
     *   "logic": "AND"
     * }
     *
     * @param int $user_id
     * @param string $rule_json
     * @return array
     */
    public function apply_rule($user_id, $rule_json)
    {
        $rules = json_decode($rule_json, true);

        if (!$rules || !isset($rules['conditions']) || !is_array($rules['conditions'])) {
            log_message('error', 'Invalid smart collection rule JSON: ' . $rule_json);
            return [];
        }

        $this->db->select('files.*, folders.folder_name, users.username'); // Select all necessary fields
        $this->db->from('files');
        $this->db->join('users', 'files.user_id = users.id');
        $this->db->join('folders', 'files.folder_id = folders.id', 'left');
        $this->db->join('folder_tags', 'files.folder_id = folder_tags.folder_id', 'left'); // For tags in rules
        $this->db->join('tags', 'folder_tags.tag_id = tags.id', 'left'); // For tags in rules
        $this->db->where('files.user_id', $user_id);
        $this->db->where('files.deleted_at IS NULL');
        
        $this->db->group_start(); // Start a group for all conditions
        foreach ($rules['conditions'] as $condition) {
            $field = $condition['field'];
            $operator = $condition['operator'];
            $value = $condition['value'];

            // Basic sanitization and field mapping
            $db_field = '';
            switch ($field) {
                case 'mime_type':
                    $db_field = 'files.mime_type';
                    break;
                case 'original_file_name':
                    $db_field = 'files.original_file_name';
                    break;
                case 'file_size':
                    $db_field = 'files.file_size';
                    break;
                case 'created_at':
                    $db_field = 'files.created_at';
                    break;
                case 'is_favorited':
                    $db_field = 'files.is_favorited';
                    $value = (bool)$value; // Ensure boolean
                    break;
                case 'folder_id':
                    $db_field = 'files.folder_id';
                    break;
                case 'tag': // Special handling for tags
                    if (!empty($value)) {
                        $this->db->where('tags.tag_name', $value);
                    }
                    continue 2; // Skip other processing for this condition
                default:
                    continue 2; // Skip invalid fields
            }

            // Apply operator
            switch ($operator) {
                case '=':
                case '>':
                case '<':
                case '>=':
                case '<=':
                case '!=':
                    $this->db->where($db_field . ' ' . $operator, $value);
                    break;
                case 'LIKE':
                    $this->db->like($db_field, $value);
                    break;
                case 'NOT LIKE':
                    $this->db->not_like($db_field, $value);
                    break;
                // Add more operators as needed
                default:
                    break;
            }
        }
        $this->db->group_end(); // End the group for all conditions

        $this->db->group_by('files.id'); // Avoid duplicate files if joining with tags
        $this->db->order_by('files.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
