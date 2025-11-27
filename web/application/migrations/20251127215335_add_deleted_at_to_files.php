<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_deleted_at_to_files extends CI_Migration {

    public function up()
    {
        if (!$this->db->field_exists('deleted_at', 'files')) {
            $fields = array(
                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE,
                    'after' => 'updated_at'
                ),
            );
            $this->dbforge->add_column('files', $fields);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('deleted_at', 'files')) {
            $this->dbforge->drop_column('files', 'deleted_at');
        }
    }
}
