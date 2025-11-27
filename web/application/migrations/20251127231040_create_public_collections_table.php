<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_public_collections_table extends CI_Migration {

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
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'access_code' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => TRUE,
                'null' => FALSE,
            ),
            'is_private' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => TRUE,
                'default' => 0, // 0 for public, 1 for private
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'deleted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT fk_public_collections_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('public_collections');
    }

    public function down()
    {
        $this->dbforge->drop_table('public_collections');
    }
}
