<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Check if the currently logged-in user has an 'admin' role.
 *
 * @return bool
 */
if ( ! function_exists('is_admin'))
{
    function is_admin()
    {
        $CI =& get_instance();
        
        if (!$CI->session->userdata('logged_in')) {
            return FALSE;
        }

        $user_id = $CI->session->userdata('user_id');
        if (!$user_id) {
            return FALSE;
        }

        // Load User_model if not already loaded
        if (!isset($CI->User_model)) {
            $CI->load->model('User_model');
        }

        $roles = $CI->User_model->get_user_roles($user_id);
        foreach ($roles as $role) {
            if ($role['role_name'] === 'admin') {
                return TRUE;
            }
        }

        return FALSE;
    }
}

/**
 * Check if the currently logged-in user has a specific permission.
 *
 * @param string $permission_name The name of the permission to check.
 * @return bool
 */
if ( ! function_exists('has_permission'))
{
    function has_permission($permission_name)
    {
        $CI =& get_instance();

        // If not logged in, no permissions
        if (!$CI->session->userdata('logged_in')) {
            return FALSE;
        }

        $user_id = $CI->session->userdata('user_id');
        if (!$user_id) {
            return FALSE;
        }

        // Admins always have all permissions
        if (is_admin()) {
            return TRUE;
        }

        // Load models if not already loaded
        if (!isset($CI->User_model)) {
            $CI->load->model('User_model');
        }
        if (!isset($CI->Role_model)) {
            $CI->load->model('Role_model');
        }
        
        $user_roles = $CI->User_model->get_user_roles($user_id);
        if (empty($user_roles)) {
            return FALSE;
        }

        $user_permissions = [];
        foreach ($user_roles as $role) {
            $role_permissions = $CI->Role_model->get_role_permissions($role['id']);
            $permission_names = array_column($role_permissions, 'permission_name');
            $user_permissions = array_merge($user_permissions, $permission_names);
        }

        // Remove duplicates and check for permission
        $user_permissions = array_unique($user_permissions);
        
        return in_array($permission_name, $user_permissions);
    }
}
