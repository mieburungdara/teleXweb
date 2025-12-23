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
        // Check if 'admin' exists in the roles array stored in the session
        $roles = $CI->session->userdata('roles') ?? [];
        return in_array('admin', $roles);
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

        // Check against the permissions array stored in the session
        $permissions = $CI->session->userdata('permissions') ?? [];
        return in_array($permission_name, $permissions);
    }
}
