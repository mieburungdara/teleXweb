<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_role_to_users_table extends CI_Migration {

    public function up()
    {
        $fields = array(
            'role' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'user',
                'null' => FALSE,
            ),
        );
        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('users', 'role');
    }
}
