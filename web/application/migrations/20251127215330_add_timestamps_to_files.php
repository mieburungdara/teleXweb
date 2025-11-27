<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_timestamps_to_files extends CI_Migration {

    public function up()
    {
        if (!$this->db->field_exists('created_at', 'files')) {
            $created_at_field = [
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => TRUE,
                    'after' => 'is_favorited'
                ]
            ];
            $this->dbforge->add_column('files', $created_at_field);
        }
        
        if (!$this->db->field_exists('updated_at', 'files')) {
            $updated_at_field = [
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => TRUE,
                    'after' => 'created_at'
                ]
            ];
            $this->dbforge->add_column('files', $updated_at_field);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('created_at', 'files')) {
            $this->dbforge->drop_column('files', 'created_at');
        }
        if ($this->db->field_exists('updated_at', 'files')) {
            $this->dbforge->drop_column('files', 'updated_at');
        }
    }
}
