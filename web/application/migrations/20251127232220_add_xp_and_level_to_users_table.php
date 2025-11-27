<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_xp_and_level_to_users_table extends CI_Migration {

    public function up()
    {
        $fields = array(
            'xp' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => 0,
                'after' => 'last_name'
            ),
            'level' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => 1,
                'after' => 'xp'
            ),
        );
        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('users', 'xp');
        $this->dbforge->drop_column('users', 'level');
    }
}
