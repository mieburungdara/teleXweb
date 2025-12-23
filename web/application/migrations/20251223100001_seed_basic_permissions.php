<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_basic_permissions extends CI_Migration {

    public function up()
    {
        $permissions = [
            // Admin
            ['permission_name' => 'view_admin_dashboard', 'category' => 'admin', 'description' => 'Can view the main admin dashboard.'],
            ['permission_name' => 'manage_bots', 'category' => 'admin', 'description' => 'Can create, edit, and delete Telegram bots.'],
            ['permission_name' => 'manage_users', 'category' => 'admin', 'description' => 'Can view and edit user roles and details.'],
            ['permission_name' => 'manage_roles', 'category' => 'admin', 'description' => 'Can create, edit, and delete user roles.'],
            ['permission_name' => 'manage_permissions', 'category' => 'admin', 'description' => 'Can view and assign permissions to roles.'],
            ['permission_name' => 'manage_public_collections', 'category' => 'admin', 'description' => 'Can manage admin-curated public collections.'],
            ['permission_name' => 'manage_tags', 'category' => 'admin', 'description' => 'Can manage and merge system-wide tags.'],
            ['permission_name' => 'view_audit_logs', 'category' => 'admin', 'description' => 'Can view the audit trail of system actions.'],
            // File
            ['permission_name' => 'upload_files', 'category' => 'file', 'description' => 'Can upload new files.'],
            ['permission_name' => 'edit_files', 'category' => 'file', 'description' => 'Can edit metadata of their own files.'],
            ['permission_name' => 'delete_files', 'category' => 'file', 'description' => 'Can delete their own files.'],
            ['permission_name' => 'view_own_files', 'category' => 'file', 'description' => 'Can view and list their own files.'],
            ['permission_name' => 'favorite_files', 'category' => 'file', 'description' => 'Can mark files as favorites.'],
            // Folder
            ['permission_name' => 'create_folders', 'category' => 'folder', 'description' => 'Can create new folders.'],
            ['permission_name' => 'edit_folders', 'category' => 'folder', 'description' => 'Can edit metadata of their own folders.'],
            ['permission_name' => 'delete_folders', 'category' => 'folder', 'description' => 'Can delete their own folders.'],
            ['permission_name' => 'view_own_folders', 'category' => 'folder', 'description' => 'Can view and list their own folders.'],
            ['permission_name' => 'favorite_folders', 'category' => 'folder', 'description' => 'Can mark folders as favorites.'],
            ['permission_name' => 'like_folders', 'category' => 'folder', 'description' => 'Can like or unlike public folders.'],
            ['permission_name' => 'review_folders', 'category' => 'folder', 'description' => 'Can submit reviews and ratings for folders.'],
            ['permission_name' => 'comment_on_folders', 'category' => 'folder', 'description' => 'Can post comments on folders.'],
            // Sharing
            ['permission_name' => 'view_shared_content', 'category' => 'sharing', 'description' => 'Can view content shared with them.'],
            ['permission_name' => 'share_content', 'category' => 'sharing', 'description' => 'Can share their own files and folders.'],
            // Collection
            ['permission_name' => 'manage_own_smart_collections', 'category' => 'collection', 'description' => 'Can create, edit, and delete their own smart collections.'],
            // User Profile
            ['permission_name' => 'view_own_profile', 'category' => 'user_profile', 'description' => 'Can view their own user profile.'],
            ['permission_name' => 'edit_own_profile', 'category' => 'user_profile', 'description' => 'Can edit their own user profile details.'],
            // Monetization
            ['permission_name' => 'manage_monetization', 'category' => 'monetization', 'description' => 'Can manage pricing and view financial data.'],
            ['permission_name' => 'view_own_balance', 'category' => 'monetization', 'description' => 'Can view their own account balance.'],
            ['permission_name' => 'add_funds', 'category' => 'monetization', 'description' => 'Can add funds to their own account.'],
        ];

        // Using db->query to use INSERT IGNORE
        foreach ($permissions as $permission) {
            $sql = "INSERT IGNORE INTO permissions (permission_name, category, description) VALUES (" .
                $this->db->escape($permission['permission_name']) . ", " .
                $this->db->escape($permission['category']) . ", " .
                $this->db->escape($permission['description']) . ")";
            $this->db->query($sql);
        }
    }

    public function down()
    {
        // It's not always safe to delete data, but for a seeder, we can.
        // We'll just delete the permissions we added.
        $permission_names = [
            'view_admin_dashboard', 'manage_bots', 'manage_users', 'manage_roles', 'manage_permissions',
            'manage_public_collections', 'manage_tags', 'view_audit_logs', 'upload_files', 'edit_files',
            'delete_files', 'view_own_files', 'favorite_files', 'create_folders', 'edit_folders',
            'delete_folders', 'view_own_folders', 'favorite_folders', 'like_folders', 'review_folders',
            'comment_on_folders', 'view_shared_content', 'share_content', 'manage_own_smart_collections',
            'view_own_profile', 'edit_own_profile', 'manage_monetization', 'view_own_balance', 'add_funds'
        ];
        $this->db->where_in('permission_name', $permission_names);
        $this->db->delete('permissions');
    }
}
