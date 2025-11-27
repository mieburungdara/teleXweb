<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_notification_throttles_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'event_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ),
            'last_sent_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
            'throttle_duration_seconds' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
                'default' => 3600 // Default to 1 hour
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(['user_id', 'event_name'], FALSE, TRUE); // Unique compound key
        $this->dbforge->add_field('CONSTRAINT fk_notification_throttles_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('notification_throttles');
    }

    public function down()
    {
        $this->dbforge->drop_table('notification_throttles');
    }
}
