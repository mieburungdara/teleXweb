<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_balance_to_users_table extends CI_Migration {

    public function up()
    {
        $fields = array(
            'balance' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'level'
            ),
        );
        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('users', 'balance');
    }
}
