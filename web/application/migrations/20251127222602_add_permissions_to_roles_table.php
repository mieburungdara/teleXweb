<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_permissions_to_roles_table extends CI_Migration {

    public function up()
    {
        $fields = array(
            'permissions' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'after' => 'name'
            ),
        );
        $this->dbforge->add_column('roles', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('roles', 'permissions');
    }
}
