<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_notifications_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'type' => array( // e.g., 'email', 'telegram'
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE,
            ),
            'subject' => array(
                'type' => 'VARCHAR',
                'constraint' => 512,
                'null' => FALSE,
            ),
            'body' => array(
                'type' => 'TEXT',
                'null' => FALSE,
            ),
            'sent_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'read_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT fk_notifications_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('notifications');
    }

    public function down()
    {
        $this->dbforge->drop_table('notifications');
    }
}
