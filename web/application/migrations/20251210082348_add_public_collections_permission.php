<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_public_collections_permission extends CI_Migration {

    public function up()
    {
        $this->load->model('Role_model');
        $role_id = 1; // Assuming 'user' role is ID 1
        
        $permissions = $this->Role_model->get_role_permissions($role_id);
        if (!in_array('manage_public_collections', $permissions)) {
            $permissions[] = 'manage_public_collections';
        }

        $this->Role_model->update_role_permissions($role_id, $permissions);

        echo "Permission 'manage_public_collections' added to role ID {$role_id}.\n";
    }

    public function down()
    {
        $this->load->model('Role_model');
        $role_id = 1; // Assuming 'user' role is ID 1

        $permissions = $this->Role_model->get_role_permissions($role_id);
        $permissions = array_filter($permissions, function($p) {
            return $p !== 'manage_public_collections';
        });

        $this->Role_model->update_role_permissions($role_id, array_values($permissions));

        echo "Permission 'manage_public_collections' removed from role ID {$role_id}.\n";
    }
}

