<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tags_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'tag_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => TRUE,
                'null' => FALSE,
            ),
            'created_by_user_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT fk_tags_created_by_user_id FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->dbforge->create_table('tags');
    }

    public function down()
    {
        $this->dbforge->drop_table('tags');
    }
}
