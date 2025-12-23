<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Implement_rbac extends CI_Migration {

    public function up()
    {
        // ==== CREATE permissions TABLE ====
        if (!$this->db->table_exists('permissions')) {
            $this->dbforge->add_field([
                'id' => ['type' => 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE],
                'permission_name' => ['type' => 'VARCHAR', 'constraint' => '255', 'unique' => TRUE],
                'category' => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'general'],
                'description' => ['type' => 'TEXT', 'null' => TRUE],
                'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'created_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
                'updated_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP', 'on update' => 'CURRENT_TIMESTAMP'],
            ]);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('permissions');
        }

        // ==== CREATE role_permissions TABLE ====
        if (!$this->db->table_exists('role_permissions')) {
            $this->dbforge->add_field([
                'role_id' => ['type' => 'INT', 'unsigned' => TRUE],
                'permission_id' => ['type' => 'INT', 'unsigned' => TRUE],
                'created_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
            ]);
            $this->dbforge->add_key(['role_id', 'permission_id'], TRUE);
            $this->dbforge->add_field('CONSTRAINT fk_role_permissions_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE');
            $this->dbforge->add_field('CONSTRAINT fk_role_permissions_permission_id FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE ON UPDATE CASCADE');
            $this->dbforge->create_table('role_permissions');
        }

        // ==== CREATE user_roles TABLE ====
        if (!$this->db->table_exists('user_roles')) {
            $this->dbforge->add_field([
                'user_id' => ['type' => 'BIGINT', 'unsigned' => TRUE],
                'role_id' => ['type' => 'INT', 'unsigned' => TRUE],
                'created_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
            ]);
            $this->dbforge->add_key(['user_id', 'role_id'], TRUE);
            $this->dbforge->add_field('CONSTRAINT fk_user_roles_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
            $this->dbforge->add_field('CONSTRAINT fk_user_roles_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE');
            $this->dbforge->create_table('user_roles');
        }
        
        // ==== MODIFY users TABLE ====
        if ($this->db->field_exists('role_id', 'users')) {
            // First drop the foreign key if it exists
            $this->db->query('ALTER TABLE `users` DROP FOREIGN KEY `fk_users_role_id`');
            // Then drop the column
            $this->dbforge->drop_column('users', 'role_id');
        }
        if ($this->db->field_exists('role', 'users')) {
            $this->dbforge->drop_column('users', 'role');
        }
    }

    public function down()
    {
        // Drop user_roles table
        $this->dbforge->drop_table('user_roles', TRUE);

        // Drop role_permissions table
        $this->dbforge->drop_table('role_permissions', TRUE);

        // Drop permissions table
        $this->dbforge->drop_table('permissions', TRUE);

        // Re-add role_id to users table (approximating old structure)
        if (!$this->db->field_exists('role_id', 'users')) {
             $fields = [
                'role_id' => ['type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE, 'after' => 'status']
            ];
            $this->dbforge->add_column('users', $fields);
        }
    }
}
