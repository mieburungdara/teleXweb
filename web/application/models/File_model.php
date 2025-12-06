<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Audit_Log_model'); // Load Audit Log Model
    }

    /**
     * Create a new file record.
     *
     * @param array $file_data Data conforming to the files table structure.
     * @return int|bool The ID of the new file on success, or FALSE on failure.
     */
    public function create_file($file_data)
    {
        $file_data['created_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert('files', $file_data)) {
            $file_id = $this->db->insert_id();
            $this->Audit_Log_model->log_action(
                'file_created',
                'file',
                $file_id,
                [],
                $file_data
            );
            return $file_id;
        }
        return FALSE;
    }

    /**
     * Find a file by its unique Telegram file ID.
     *
     * @param string $file_unique_id
     * @return array|null File data if found, null otherwise.
     */
    public function get_file_by_unique_id($file_unique_id)
    {
        $query = $this->db->get_where('files', ['file_unique_id' => $file_unique_id]);
        return $query->row_array();
    }

    /**
     * Get recent files for a user.
     *
     * @param int $user_id
     * @param int $limit
     * @return array
     */
    public function get_recent_files($user_id, $limit = 5)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at IS NULL');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('files');
        return $query->result_array();
    }

    /**
     * Search files by keyword (in file name or folder tags).
     *
     * @param int $user_id
     * @param string $keyword
     * @return array
     */
    public function search_files($user_id, $keyword)
    {
        $this->db->select('files.*');
        $this->db->from('files');
        $this->db->join('folders', 'files.folder_id = folders.id', 'left');
        $this->db->join('folder_tags', 'folders.id = folder_tags.folder_id', 'left');
        $this->db->join('tags', 'folder_tags.tag_id = tags.id', 'left');
        $this->db->where('files.user_id', $user_id);
        $this->db->where('files.deleted_at IS NULL');
        $this->db->group_start();
        $this->db->like('files.original_file_name', $keyword);
        $this->db->or_like('tags.tag_name', $keyword);
        $this->db->group_end();
        $this->db->group_by('files.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Toggle the favorite status of a file.
     *
     * @param int $file_id
     * @param int $user_id
     * @return bool
     */
    public function toggle_favorite($file_id, $user_id)
    {
        $this->db->where('id', $file_id);
        $this->db->where('user_id', $user_id);
        $this->db->set('is_favorited', '1 - is_favorited', FALSE); // SQL to toggle boolean/tinyint
        return $this->db->update('files');
    }

    /**
     * Get all files for a user, with folder and user info.
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_files($user_id)
    {
        $this->db->select('files.*, folders.folder_name, users.username');
        $this->db->from('files');
        $this->db->join('users', 'files.user_id = users.id');
        $this->db->join('folders', 'files.folder_id = folders.id', 'left');
        $this->db->where('files.user_id', $user_id);
        $this->db->where('files.deleted_at IS NULL');
        $this->db->order_by('files.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get a single file by its ID and user ID.
     *
     * @param int $id
     * @param int $user_id
     * @return array|null
     */
    public function get_file_by_id($id, $user_id)
    {
        $this->db->select('files.*, folders.folder_name, users.username');
        $this->db->from('files');
        $this->db->join('users', 'files.user_id = users.id');
        $this->db->join('folders', 'files.folder_id = folders.id', 'left');
        $this->db->where('files.id', $id);
        $this->db->where('files.user_id', $user_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get all image files for a user.
     *
     * @param int $user_id
     * @return array
     */
    public function get_user_image_files($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at IS NULL');
        $this->db->like('mime_type', 'image/', 'after');
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('files');
        return $query->result_array();
    }

    /**
     * Get files based on various filters.
     *
     * @param int $user_id
     * @param array $filters
     * @return array
     */
    public function get_files_with_filters($user_id, $filters = [])
    {
        $this->db->select('files.*, folders.folder_name, users.username');
        $this->db->from('files');
        $this->db->join('users', 'files.user_id = users.id');
        $this->db->join('folders', 'files.folder_id = folders.id', 'left');
        $this->db->join('folder_tags', 'folders.id = folder_tags.folder_id', 'left');
        $this->db->join('tags', 'folder_tags.tag_id = tags.id', 'left');
        
        $this->db->where('files.user_id', $user_id);
        $this->db->where('files.deleted_at IS NULL');

        if (!empty($filters['keyword'])) {
            $this->db->group_start();
            $this->db->like('files.original_file_name', $filters['keyword']);
            $this->db->or_like('tags.tag_name', $filters['keyword']);
            $this->db->group_end();
        }

        if (!empty($filters['mime_type'])) {
            $this->db->where('files.mime_type', $filters['mime_type']);
        }

        if (!empty($filters['folder_id'])) {
            $this->db->where('files.folder_id', $filters['folder_id']);
        }
        
        if (isset($filters['is_favorited']) && $filters['is_favorited'] !== '') {
            $this->db->where('files.is_favorited', (bool)$filters['is_favorited']);
        }

        $this->db->group_by('files.id');
        $this->db->order_by('files.created_at', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get all unique MIME types from user's files.
     *
     * @param int $user_id
     * @return array
     */
    public function get_all_mime_types($user_id)
    {
        $this->db->select('mime_type');
        $this->db->distinct(TRUE);
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at IS NULL');
        $this->db->order_by('mime_type', 'ASC');
        $query = $this->db->get('files');
        return $query->result_array();
    }

    /**
     * Update a specific field for a file.
     *
     * @param int $file_id
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_file_field($file_id, $user_id, $data)
    {
        $this->db->where('id', $file_id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('files', $data);
    }

    /**
     * Soft delete a file.
     *
     * @param int $file_id
     * @param int $user_id
     * @return bool
     */
    public function soft_delete_file($file_id, $user_id)
    {
        $old_file_data = $this->get_file_by_id($file_id, $user_id);
        if (!$old_file_data) {
            return FALSE;
        }

        $data = ['deleted_at' => date('Y-m-d H:i:s')];
        $this->db->where('id', $file_id);
        $this->db->where('user_id', $user_id);
        $success = $this->db->update('files', $data);

        if ($success) {
            $this->Audit_Log_model->log_action(
                'file_soft_deleted',
                'file',
                $file_id,
                ['deleted_at' => $old_file_data['deleted_at']],
                ['deleted_at' => $data['deleted_at']]
            );
        }
        return $success;
    }

    /**
     * Get all files for a user, ordered by date for a timeline view.
     *
     * @param int $user_id
     * @return array
     */
    public function get_files_for_timeline($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at IS NULL');
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('files');
        return $query->result_array();
    }

    /**
     * Count all non-deleted file records.
     *
     * @return int
     */
        public function count_all_files()
        {
            $this->db->where('deleted_at IS NULL');
            return $this->db->count_all_results('files');
        }
    
        /**
         * Count all non-deleted files for a specific user.
         *
         * @param int $user_id
         * @return int
         */
        public function count_all_files_for_user($user_id)
        {
            $this->db->where('user_id', $user_id);
            $this->db->where('deleted_at IS NULL');
            return $this->db->count_all_results('files');
        }
    }
    