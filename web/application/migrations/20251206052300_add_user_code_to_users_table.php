<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_user_code_to_users_table extends CI_Migration {

    public function up()
    {
        $fields = array(
            'user_code' => array(
                'type' => 'VARCHAR',
                'constraint' => 12,
                'unique' => TRUE,
                'null' => TRUE, // Allow NULL initially for existing users
                'after' => 'last_name' // Position the new column after 'last_name'
            ),
        );
        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('users', 'user_code');
    }
}
