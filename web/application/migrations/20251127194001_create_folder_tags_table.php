<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_folder_tags_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'folder_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'tag_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key(['folder_id', 'tag_id'], TRUE);
        $this->dbforge->add_field('CONSTRAINT fk_folder_tags_folder_id FOREIGN KEY (folder_id) REFERENCES folders(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_folder_tags_tag_id FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('folder_tags');
    }

    public function down()
    {
        $this->dbforge->drop_table('folder_tags');
    }
}
