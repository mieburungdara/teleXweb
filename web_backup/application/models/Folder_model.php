<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folder_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get a folder by its ID.
     *
     * @param int $folder_id
     * @return object|null
     */
    public function get_folder($folder_id)
    {
        $this->db->where('id', $folder_id);
        $query = $this->db->get('folders');
        return $query->row();
    }

    /**
     * Update folder details, including price and for sale status.
     *
     * @param int $folder_id
     * @param array $data
     * @return bool
     */
    public function update_folder($folder_id, $data)
    {
        $this->db->where('id', $folder_id);
        return $this->db->update('folders', $data);
    }

    /**
     * Get folders listed for sale.
     *
     * @return array
     */
    public function get_folders_for_sale()
    {
        $this->db->where('is_for_sale', 1);
        $this->db->where('price >', 0);
        $query = $this->db->get('folders');
        return $query->result();
    }

    /**
     * Get a user's folders.
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_folders($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at IS NULL');
        $query = $this->db->get('folders');
        return $query->result();
    }

    // Other folder-related methods would go here

    /**
     * Search and filter folders listed for sale with pagination.
     *
     * @param int $limit
     * @param int $offset
     * @param array $filters
     * @return array
     */
    public function search_folders_for_sale($limit, $offset, $filters = [])
    {
        $this->db->select('f.*, u.codename as seller_name');
        $this->db->from('folders f');
        $this->db->join('users u', 'f.user_id = u.id');
        $this->db->where('f.is_for_sale', 1);
        $this->db->where('f.price >', 0);
        $this->db->where('f.deleted_at IS NULL');

        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('f.folder_name', $filters['search']);
            $this->db->or_like('f.description', $filters['search']);
            $this->db->group_end();
        }

        if (!empty($filters['min_price'])) {
            $this->db->where('f.price >=', (float)$filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $this->db->where('f.price <=', (float)$filters['max_price']);
        }

        // Add sorting
        $sort_by = $filters['sort_by'] ?? 'created_at';
        $sort_order = $filters['sort_order'] ?? 'DESC';
        $this->db->order_by('f.' . $sort_by, $sort_order);

        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Count the total number of folders for sale based on filters.
     *
     * @param array $filters
     * @return int
     */
    public function count_folders_for_sale($filters = [])
    {
        $this->db->from('folders f');
        $this->db->join('users u', 'f.user_id = u.id');
        $this->db->where('f.is_for_sale', 1);
        $this->db->where('f.price >', 0);
        $this->db->where('f.deleted_at IS NULL');

        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('f.folder_name', $filters['search']);
            $this->db->or_like('f.description', $filters['search']);
            $this->db->group_end();
        }

        if (!empty($filters['min_price'])) {
            $this->db->where('f.price >=', (float)$filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $this->db->where('f.price <=', (float)$filters['max_price']);
        }

        return $this->db->count_all_results();
    }
}
