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
        // Check if logged in and if the role_id is the admin role ID (99)
        return ($CI->session->userdata('logged_in') && $CI->session->userdata('role_id') === 99);
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

        // Admins always have all permissions
        if (is_admin()) {
            return TRUE;
        }

        $user_role_id = $CI->session->userdata('role_id');
        if (!$user_role_id) {
            return FALSE;
        }

        // Load Role_model if not already loaded
        if (!isset($CI->Role_model)) {
            $CI->load->model('Role_model');
        }
        
        $permissions = $CI->Role_model->get_role_permissions($user_role_id);

        return in_array($permission_name, $permissions);
    }
}
