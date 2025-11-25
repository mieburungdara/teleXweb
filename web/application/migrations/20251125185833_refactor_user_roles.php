<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Refactor_user_roles extends CI_Migration {

    public function up()
    {
        // 1. Add new column role_id
        $fields = array(
            'role_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => 1, // Default to 'user' role ID
                'null' => FALSE,
                'after' => 'language_code' // Place it after language_code
            ),
        );
        $this->dbforge->add_column('users', $fields);

        // 2. Optional: Migrate existing roles data (if any existed before this refactor)
        // Since we previously added a 'role' column with default 'user', all existing users
        // will have role 'user'. So we just ensure role_id is 1 for everyone.
        // If there were 'admin' roles, we would update based on name:
        // $this->db->query("UPDATE users SET role_id = 99 WHERE role = 'admin'");
        // $this->db->query("UPDATE users SET role_id = 1 WHERE role = 'user'");
        // For now, all existing users will have role_id = 1 (default already).

        // 3. Drop the old 'role' column
        $this->dbforge->drop_column('users', 'role');
    }

    public function down()
    {
        // Re-add the old 'role' column if rolling back
        $fields = array(
            'role' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'user',
                'null' => FALSE,
            ),
        );
        $this->dbforge->add_column('users', $fields);

        // Optionally, re-populate role names from role_ids if needed
        // For simplicity in rollback, we are not mapping back from role_id to name here.

        // Drop the new 'role_id' column
        $this->dbforge->drop_column('users', 'role_id');
    }
}
