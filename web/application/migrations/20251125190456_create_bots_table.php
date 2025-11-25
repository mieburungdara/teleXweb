<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_bots_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'bot_id_telegram' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'unique' => TRUE,
                'null' => FALSE,
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            ),
            'token' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('bots');
    }

    public function down()
    {
        $this->dbforge->drop_table('bots');
    }
}
