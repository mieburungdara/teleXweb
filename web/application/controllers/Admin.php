<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // All admin panel actions require login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Please log in.');
            redirect('miniapp/unauthorized');
            return;
        }
        $this->load->model(['Bot_model', 'User_model', 'Role_model', 'File_model', 'Folder_model', 'Access_Log_model', 'Audit_Log_model', 'Permission_model']); // Load all necessary models including Permission_model
        $this->load->library('form_validation');
        $this->load->helper(['url', 'auth_helper']); // Ensure auth_helper is loaded
    }

    /**
     * Display the Admin Dashboard with key statistics.
     */
    public function dashboard()
    {
        if (!has_permission('view_admin_dashboard')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        $data['total_users'] = $this->User_model->count_all_users();
        $data['total_bots'] = $this->Bot_model->count_all_bots();
        $data['total_files'] = $this->File_model->count_all_files();
        $data['total_folders'] = $this->Folder_model->count_all_folders();

        // Get Trending Items for the dashboard
        $trending_files_raw = $this->Access_Log_model->get_trending_items('file');
        $trending_folders_raw = $this->Access_Log_model->get_trending_items('folder');
        
        $data['trending_files'] = [];
        foreach($trending_files_raw as $item) {
            $file_details = $this->File_model->get_file_by_id($item['entity_id'], $this->session->userdata('user_id')); // Assuming user has access for display
            if ($file_details) {
                $item['original_file_name'] = $file_details['original_file_name'];
                $item['mime_type'] = $file_details['mime_type'];
                $data['trending_files'][] = $item;
            }
        }

        $data['trending_folders'] = [];
        foreach($trending_folders_raw as $item) {
            $folder_details = $this->Folder_model->get_folder_by_id($item['entity_id'], $this->session->userdata('user_id')); // Assuming user has access for display
            if ($folder_details) {
                $item['folder_name'] = $folder_details['folder_name'];
                $data['trending_folders'][] = $item;
            }
        }

        $data['title'] = 'Admin Dashboard';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/dashboard_view', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Display a list of all bots.
     */
    public function index()
    {
        if (!has_permission('manage_bots')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $data['bots'] = $this->Bot_model->get_all_bots();
        $data['title'] = 'Manage Bots';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/bot_list', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Display form to add a new bot or edit an existing one.
     * @param int $id Bot ID to edit, if any.
     */
    public function form($id = null)
    {
        if (!has_permission('manage_bots')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $data['bot'] = null;
        if ($id) {
            $data['bot'] = $this->Bot_model->get_bot_by_id($id);
            if (!$data['bot']) {
                $this->session->set_flashdata('error_message', 'Bot not found.');
                redirect('admin');
                return;
            }
        }
        $data['title'] = $id ? 'Edit Bot' : 'Add New Bot';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/bot_form', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle form submission for adding or updating a bot.
     */
    public function save()
    {
        if (!has_permission('manage_bots')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $id = $this->input->post('id');
        $bot_id_telegram = $this->input->post('bot_id_telegram');
        $name = $this->input->post('name');
        $token = $this->input->post('token');
        $storage_channel_id = $this->input->post('storage_channel_id');

        $this->form_validation->set_rules('bot_id_telegram', 'Telegram Bot ID', 'required|numeric');
        $this->form_validation->set_rules('name', 'Bot Name', 'required|max_length[255]');
        $this->form_validation->set_rules('token', 'Bot Token', 'required|max_length[255]');
        $this->form_validation->set_rules('storage_channel_id', 'Storage Channel ID', 'required');

        if ($this->form_validation->run() === FALSE) {
            // Validation failed, reload form with errors
            $this->session->set_flashdata('errors', validation_errors());
            if ($id) {
                redirect('admin/form/' . $id);
            } else {
                redirect('admin/form');
            }
            return;
        }

        $bot_data = [
            'bot_id_telegram' => $bot_id_telegram,
            'name' => $name,
            'token' => $token,
            'storage_channel_id' => $storage_channel_id
        ];

        if ($id) {
            // Update existing bot
            $old_bot_data = $this->Bot_model->get_bot_by_id($id);
            $success = $this->Bot_model->update_bot($id, $bot_data);
            if ($success) {
                $this->Audit_Log_model->log_action(
                    'bot_updated',
                    'bot',
                    $id,
                    $old_bot_data,
                    $bot_data
                );
                $this->session->set_flashdata('success_message', 'Bot updated successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to update bot.');
            }
        } else {
            // Add new bot
            $new_bot_id = $this->Bot_model->create_bot($bot_data);
            if ($new_bot_id) {
                $this->Audit_Log_model->log_action(
                    'bot_created',
                    'bot',
                    $new_bot_id,
                    [],
                    $bot_data
                );
                $this->session->set_flashdata('success_message', 'Bot added successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to add bot.');
            }
        }
        redirect('admin');
    }

    /**
     * Delete a bot.
     * @param int $id Bot ID to delete.
     */
    public function delete($id)
    {
        if (!has_permission('manage_bots')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        if (!$id) {
            $this->session->set_flashdata('error_message', 'Bot ID is required for deletion.');
            redirect('admin');
            return;
        }

        $old_bot_data = $this->Bot_model->get_bot_by_id($id);
        $success = $this->Bot_model->delete_bot($id);
        if ($success) {
            $this->Audit_Log_model->log_action(
                'bot_deleted',
                'bot',
                $id,
                $old_bot_data,
                []
            );
            $this->session->set_flashdata('success_message', 'Bot deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to delete bot.');
        }
        redirect('admin');
    }

    /**
     * Display a list of all users.
     */
    public function users()
    {
        if (!has_permission('manage_users')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        $users = $this->User_model->get_all_users();
        foreach ($users as &$user) { // Use reference to modify array directly
            $user['roles'] = $this->User_model->get_user_roles($user['id']);
        }

        $data['users'] = $users;
        $data['title'] = 'Manage Users';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/user_list', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Display form to edit a user's role.
     * @param int $id User ID to edit.
     */
    public function edit_user_roles($id)
    {
        if (!has_permission('manage_users')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $data['user'] = $this->User_model->get_user_by_id($id);
        if (!$data['user']) {
            $this->session->set_flashdata('error_message', 'User not found.');
            redirect('admin/users');
            return;
        }
        
        $assigned_roles = $this->User_model->get_user_roles($id);
        $data['assigned_role_ids'] = array_column($assigned_roles, 'id');
        $data['all_roles'] = $this->Role_model->get_all_roles();
        
        $data['title'] = 'Edit User Roles';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/user_roles_form', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle form submission for updating a user's roles.
     */
    public function update_user_roles()
    {
        if (!has_permission('manage_users')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $user_id = $this->input->post('user_id');
        $role_ids = $this->input->post('role_ids');

        $this->form_validation->set_rules('user_id', 'User ID', 'required|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect('admin/edit_user_roles/' . $user_id);
            return;
        }

        // Ensure $role_ids is an array
        $role_ids = is_array($role_ids) ? $role_ids : [];

        // For audit logging
        $old_assigned_roles = $this->User_model->get_user_roles($user_id);
        $old_role_ids = array_column($old_assigned_roles, 'id');

        $success = $this->User_model->update_user_roles($user_id, $role_ids);
        if ($success) {
            $this->Audit_Log_model->log_action(
                'user_roles_updated',
                'user',
                $user_id,
                ['old_role_ids' => $old_role_ids],
                ['new_role_ids' => $role_ids]
            );
            $this->session->set_flashdata('success_message', 'User roles updated successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to update user roles.');
        }
        redirect('admin/users');
    }

    /**
     * Display a list of all roles for permission management.
     */
    public function roles()
    {
        if (!has_permission('manage_roles')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $data['roles'] = $this->Role_model->get_all_roles();
        $data['title'] = 'Manage Roles';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/role_list', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Display form to edit a role's permissions.
     * @param int $id Role ID to edit.
     */
    public function edit_role_permissions($id)
    {
        if (!has_permission('manage_roles')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $data['role'] = $this->Role_model->get_role_by_id($id);
        if (!$data['role']) {
            $this->session->set_flashdata('error_message', 'Role not found.');
            redirect('admin/roles');
            return;
        }
        
        // Get permissions assigned to this role (these are full permission objects)
        $assigned_permissions = $this->Role_model->get_role_permissions($id);
        // Extract just the IDs of assigned permissions for easy checking in the view
        $data['assigned_permission_ids'] = array_column($assigned_permissions, 'id');

        // Get all available permissions (full permission objects)
        $data['all_permissions'] = $this->Permission_model->get_all_permissions();
        
        $data['title'] = 'Edit Role Permissions';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/role_permissions_form', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle form submission for updating a role's permissions.
     */
    public function update_role_permissions()
    {
        if (!has_permission('manage_roles')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $role_id = $this->input->post('role_id');
        $permissions = $this->input->post('permissions'); // Array of selected permission IDs

        if (!$role_id) {
            $this->session->set_flashdata('error_message', 'Role ID is required.');
            redirect('admin/roles');
            return;
        }

        // Ensure $permissions is an array, even if empty
        $permissions = is_array($permissions) ? $permissions : [];

        // Get old permissions before updating for audit logging
        $old_assigned_permissions_objects = $this->Role_model->get_role_permissions($role_id);
        $old_assigned_permission_ids = array_column($old_assigned_permissions_objects, 'id');

        $success = $this->Role_model->update_role_permissions($role_id, $permissions);
        if ($success) {
            $this->Audit_Log_model->log_action(
                'role_permissions_updated',
                'role',
                $role_id,
                ['old_permissions' => $old_assigned_permission_ids],
                ['new_permissions' => $permissions]
            );
            $this->session->set_flashdata('success_message', 'Role permissions updated successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to update role permissions.');
        }
        redirect('admin/roles');
    }

    /**
     * Display form to add a new role or edit an existing one.
     * @param int $id Role ID to edit, if any.
     */
    public function role_form($id = null)
    {
        if (!has_permission('manage_roles')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        $data['role'] = null;
        if ($id) {
            $data['role'] = $this->Role_model->get_role_by_id($id);
            if (!$data['role']) {
                $this->session->set_flashdata('error_message', 'Role not found.');
                redirect('admin/roles');
                return;
            }
        }

        $data['title'] = $id ? 'Edit Role' : 'Add New Role';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/role_form', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle form submission for adding or updating a role.
     */
    public function save_role()
    {
        if (!has_permission('manage_roles')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        $id = $this->input->post('id');
        $role_name = $this->input->post('role_name');
        $description = $this->input->post('description');

        $this->form_validation->set_rules('role_name', 'Role Name', 'required|max_length[50]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect('admin/role_form/' . $id);
            return;
        }

        $role_data = [
            'role_name' => $role_name,
            'description' => $description
        ];

        if ($id) {
            // Update existing role
            $success = $this->Role_model->update_role($id, $role_data);
            if ($success) {
                $this->session->set_flashdata('success_message', 'Role updated successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to update role.');
            }
        } else {
            // Add new role
            $new_role_id = $this->Role_model->create_role($role_data);
            if ($new_role_id) {
                $this->session->set_flashdata('success_message', 'Role created successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to create role. It might already exist.');
            }
        }
        redirect('admin/roles');
    }

    /**
     * Delete a role.
     * @param int $id Role ID to delete.
     */
    public function delete_role($id)
    {
        if (!has_permission('manage_roles')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        if (!$id) {
            $this->session->set_flashdata('error_message', 'Role ID is required for deletion.');
            redirect('admin/roles');
            return;
        }

        // Prevent deletion of default roles if needed
        if ($id <= 4) { // Assuming IDs 1-4 are default roles
            $this->session->set_flashdata('error_message', 'Default roles cannot be deleted.');
            redirect('admin/roles');
            return;
        }

        $success = $this->Role_model->delete_role($id);
        if ($success) {
            $this->session->set_flashdata('success_message', 'Role deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to delete role. It might be in use.');
        }
        redirect('admin/roles');
    }

    /**
     * Display a list of all permissions.
     */
    public function permissions()
    {
        if (!has_permission('manage_permissions')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }
        $data['permissions'] = $this->Permission_model->get_all_permissions();
        $data['title'] = 'Manage Permissions';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/permission_list', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Display form to add a new permission or edit an existing one.
     * @param int $id Permission ID to edit, if any.
     */
    public function permission_form($id = null)
    {
        if (!has_permission('manage_permissions')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        $data['permission'] = null;
        if ($id) {
            $data['permission'] = $this->Permission_model->get_permission_by_id($id);
            if (!$data['permission']) {
                $this->session->set_flashdata('error_message', 'Permission not found.');
                redirect('admin/permissions');
                return;
            }
        }

        $data['title'] = $id ? 'Edit Permission' : 'Add New Permission';
        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/permission_form', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle form submission for adding or updating a permission.
     */
    public function save_permission()
    {
        if (!has_permission('manage_permissions')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        $id = $this->input->post('id');
        $permission_name = $this->input->post('permission_name');
        $category = $this->input->post('category');
        $description = $this->input->post('description');

        $this->form_validation->set_rules('permission_name', 'Permission Name', 'required|max_length[255]');
        $this->form_validation->set_rules('category', 'Category', 'required|max_length[100]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect('admin/permission_form/' . $id);
            return;
        }

        $permission_data = [
            'permission_name' => $permission_name,
            'category' => $category,
            'description' => $description,
        ];

        if ($id) {
            // Update existing permission
            $success = $this->Permission_model->update_permission($id, $permission_data);
            if ($success) {
                $this->session->set_flashdata('success_message', 'Permission updated successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to update permission.');
            }
        } else {
            // Add new permission
            $new_permission_id = $this->Permission_model->create_permission($permission_data);
            if ($new_permission_id) {
                $this->session->set_flashdata('success_message', 'Permission created successfully.');
            } else {
                $this->session->set_flashdata('error_message', 'Failed to create permission. It might already exist.');
            }
        }
        redirect('admin/permissions');
    }

    /**
     * Delete a permission.
     * @param int $id Permission ID to delete.
     */
    public function delete_permission($id)
    {
        if (!has_permission('manage_permissions')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        if (!$id) {
            $this->session->set_flashdata('error_message', 'Permission ID is required for deletion.');
            redirect('admin/permissions');
            return;
        }

        $success = $this->Permission_model->delete_permission($id);
        if ($success) {
            $this->session->set_flashdata('success_message', 'Permission deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to delete permission. It might be in use by a role.');
        }
        redirect('admin/permissions');
    }

    /**
     * Display a matrix of roles and permissions for easy assignment.
     */
    public function permissions_matrix()
    {
        if (!has_permission('manage_permissions')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        $data['all_roles'] = $this->Role_model->get_all_roles();
        $all_permissions = $this->Permission_model->get_all_permissions();
        
        // Get all role-permission assignments at once
        $assignments = $this->db->get('role_permissions')->result_array();
        $assigned_lookup = [];
        foreach ($assignments as $assignment) {
            $assigned_lookup[$assignment['role_id']][$assignment['permission_id']] = true;
        }

        // Group permissions by category for the view
        $permissions_by_category = [];
        foreach ($all_permissions as $permission) {
            $permissions_by_category[$permission['category']][] = $permission;
        }

        $data['permissions_by_category'] = $permissions_by_category;
        $data['assigned_lookup'] = $assigned_lookup;
        $data['title'] = 'Permissions Matrix';

        $this->load->view('templates/dashmix_header', $data);
        $this->load->view('admin/permissions_matrix_view', $data);
        $this->load->view('templates/dashmix_footer');
    }

    /**
     * Handle form submission for the entire permissions matrix.
     */
    public function update_permissions_matrix()
    {
        if (!has_permission('manage_permissions')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        $assignments = $this->input->post('assignments');
        
        $this->db->trans_start();

        // Clear all existing assignments
        $this->db->truncate('role_permissions');

        // Insert new assignments
        if (!empty($assignments)) {
            $batch_data = [];
            foreach ($assignments as $role_id => $permissions) {
                foreach ($permissions as $permission_id => $value) {
                    $batch_data[] = [
                        'role_id' => $role_id,
                        'permission_id' => $permission_id
                    ];
                }
            }

            if (!empty($batch_data)) {
                $this->db->insert_batch('role_permissions', $batch_data);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error_message', 'Failed to update permissions.');
        } else {
            $this->session->set_flashdata('success_message', 'Permissions matrix updated successfully.');
        }

        redirect('admin/permissions_matrix');
    }


    /**
     * Populates user_code for existing users who do not have one.
     * Accessible only by admins with 'manage_users' permission.
     */
    public function populate_user_codes()
    {
        if (!has_permission('manage_users')) {
            $this->session->set_flashdata('error_message', 'Access Denied: Insufficient permissions.');
            redirect('miniapp/unauthorized');
            return;
        }

        $users_without_code = $this->User_model->get_users_without_code(); // New method needed in User_model
        $updated_count = 0;

        foreach ($users_without_code as $user) {
            $user_code = $this->User_model->generate_unique_user_code(); // Call the public version
            if ($user_code) {
                $success = $this->User_model->update_user($user['id'], ['user_code' => $user_code]);
                if ($success) {
                    $updated_count++;
                    $this->Audit_Log_model->log_action(
                        'user_code_generated',
                        'user',
                        $user['id'],
                        ['old_user_code' => null],
                        ['new_user_code' => $user_code]
                    );
                }
            }
        }

        if ($updated_count > 0) {
            $this->session->set_flashdata('success_message', "Successfully generated {$updated_count} user codes for existing users.");
        } else {
            $this->session->set_flashdata('info_message', 'No users found without a user code.');
        }
        redirect('admin/users'); // Redirect back to user list
    }

}