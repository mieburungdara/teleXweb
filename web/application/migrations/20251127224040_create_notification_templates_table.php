<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_notification_templates_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'event_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => TRUE,
                'null' => FALSE,
            ),
            'subject_template' => array(
                'type' => 'VARCHAR',
                'constraint' => 512,
                'null' => FALSE,
            ),
            'body_template' => array(
                'type' => 'TEXT',
                'null' => FALSE,
            ),
            'default_channels' => array( // e.g., 'email,telegram'
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'default' => 'email'
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
        $this->dbforge->create_table('notification_templates');
    }

    public function down()
    {
        $this->dbforge->drop_table('notification_templates');
    }
}
