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
