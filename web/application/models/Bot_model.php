<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Find a bot by its Telegram Bot ID.
     *
     * @param int $bot_id_telegram
     * @return array|null Bot data if found, null otherwise.
     */
    public function get_bot_by_telegram_id($bot_id_telegram)
    {
        $this->db->cache_off(); // Disable caching for this specific query
        $query = $this->db->get_where('bots', ['bot_id_telegram' => $bot_id_telegram]);
        $this->db->cache_on(); // Re-enable caching if it was globally on
        return $query->row_array();
    }

    /**
     * Create a new bot record.
     *
     * @param array $bot_data Data conforming to the bots table structure.
     * @return int|bool The ID of the new bot on success, or FALSE on failure.
     */
    public function create_bot($bot_data)
    {
        $bot_data['created_at'] = date('Y-m-d H:i:s');
        $bot_data['updated_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert('bots', $bot_data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Get a single bot by its primary ID.
     *
     * @param int $id
     * @return array|null Bot data if found, null otherwise.
     */
    public function get_bot_by_id($id)
    {
        $query = $this->db->get_where('bots', ['id' => $id]);
        return $query->row_array();
    }

    /**
     * Update an existing bot record.
     *
     * @param int $id The bot's primary key ID.
     * @param array $bot_data Data to update.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function update_bot($id, $bot_data)
    {
        $bot_data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('bots', $bot_data);
    }

    /**
     * Delete a bot record.
     *
     * @param int $id The bot's primary key ID.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function delete_bot($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('bots');
    }

    /**
     * Get all bot records.
     *
     * @return array An array of all bot data.
     */
    public function get_all_bots()
    {
        $query = $this->db->get('bots');
        return $query->result_array();
    }

    /**
     * Count all bot records.
     *
     * @return int
     */
    public function count_all_bots()
    {
        return $this->db->count_all('bots');
    }
}
