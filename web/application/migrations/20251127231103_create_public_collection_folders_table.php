<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_public_collection_folders_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'public_collection_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'folder_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'display_order' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => 0,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(['public_collection_id', 'folder_id'], FALSE, TRUE); // Unique compound key
        $this->dbforge->add_field('CONSTRAINT fk_public_collection_folders_collection_id FOREIGN KEY (public_collection_id) REFERENCES public_collections(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_public_collection_folders_folder_id FOREIGN KEY (folder_id) REFERENCES folders(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('public_collection_folders');
    }

    public function down()
    {
        $this->dbforge->drop_table('public_collection_folders');
    }
}
