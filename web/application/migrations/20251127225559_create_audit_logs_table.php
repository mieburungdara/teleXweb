<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_audit_logs_table extends CI_Migration {

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
                'null' => TRUE, // Can be null if action is by system/unauthenticated
            ),
            'event_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ),
            'entity_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ),
            'entity_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => TRUE, // Can be null if event is general (e.g., login failure)
            ),
            'old_values' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'new_values' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => 45, // IPv4 or IPv6
                'null' => TRUE,
            ),
            'timestamp' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT fk_audit_logs_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->dbforge->create_table('audit_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('audit_logs');
    }
}
