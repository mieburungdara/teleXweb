<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_folder_comments_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'folder_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'user_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'parent_comment_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'comment_text' => array(
                'type' => 'TEXT',
                'null' => FALSE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
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
        $this->dbforge->add_field('CONSTRAINT fk_folder_comments_folder_id FOREIGN KEY (folder_id) REFERENCES folders(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_folder_comments_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_folder_comments_parent_comment_id FOREIGN KEY (parent_comment_id) REFERENCES folder_comments(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('folder_comments');
    }

    public function down()
    {
        $this->dbforge->drop_table('folder_comments');
    }
}
