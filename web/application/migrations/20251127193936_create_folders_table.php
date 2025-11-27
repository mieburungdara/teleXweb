<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_folders_table extends CI_Migration {

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
            'parent_folder_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'code' => array(
                'type' => 'VARCHAR',
                'constraint' => 12,
                'unique' => TRUE,
                'null' => FALSE,
            ),
            'folder_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'folder_size' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'default' => 0,
                'null' => FALSE,
            ),
            'is_favorited' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE,
            ),
            'price' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'null' => FALSE,
            ),
            'is_for_sale' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
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
            'deleted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(['user_id', 'folder_name'], FALSE, TRUE); // Unique key
        $this->dbforge->add_field('CONSTRAINT fk_folders_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_folders_parent_folder_id FOREIGN KEY (parent_folder_id) REFERENCES folders(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('folders');
    }

    public function down()
    {
        $this->dbforge->drop_table('folders');
    }
}
