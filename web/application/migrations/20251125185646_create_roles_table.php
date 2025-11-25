<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_roles_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => FALSE // Specific IDs like 1 and 99
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => TRUE,
                'null' => FALSE,
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('roles');

        // Seed initial roles - user (ID 1), admin (ID 99)
        $this->db->insert('roles', ['id' => 1, 'name' => 'user', 'description' => 'Standard user role']);
        $this->db->insert('roles', ['id' => 99, 'name' => 'admin', 'description' => 'Administrator role with full privileges']);
    }

    public function down()
    {
        $this->dbforge->drop_table('roles');
    }
}
