<?php
// tests/bootstrap.php

// Define APPPATH
if (!defined('APPPATH')) {
    define('APPPATH', __DIR__ . '/../application/');
}

// Determine BASEPATH (CodeIgniter system path)
$ci_system_path = __DIR__ . '/../vendor/codeigniter/framework/system/';
if (!is_dir($ci_system_path)) {
    die('Error: CodeIgniter system path not found at ' . $ci_system_path . '. Please check your Composer installation and bootstrap.php.');
}

// Define BASEPATH only if not already defined
if (!defined('BASEPATH')) {
    define('BASEPATH', $ci_system_path);
}

// Define ENVIRONMENT for testing
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'testing');
}

// Include Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Include CodeIgniter's system/core/Common.php to get access to global functions like get_instance()
require_once BASEPATH . 'core/Common.php';

// Include CodeIgniter's main controller file to get CI_Controller class
require_once BASEPATH . 'core/CodeIgniter.php';

// Manually instantiate the CI_Controller if needed for get_instance() to work in tests
// This is a simplified approach, a more robust solution might use Reflection to mock CI_Controller
if (!function_exists('get_instance')) {
    function &get_instance()
    {
        static $CI;
        if (!isset($CI)) {
            $CI = new CI_Controller();
        }
        return $CI;
    }
} else {
    // If get_instance already exists, ensure it works for our testing context
    // This part is tricky as CI's get_instance is global and usually set by the front controller
    // For PHPUnit, we need to ensure our mocked CI instance is returned
    // This is handled in CITestCase's setUp for now.
}