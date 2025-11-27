<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_access_logs_table extends CI_Migration {

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
                'null' => TRUE,
            ),
            'entity_type' => array(
                'type' => 'ENUM("file","folder")',
                'null' => FALSE,
            ),
            'entity_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'accessed_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(['entity_type', 'entity_id']);
        $this->dbforge->add_field('CONSTRAINT fk_access_logs_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->dbforge->create_table('access_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('access_logs');
    }
}
