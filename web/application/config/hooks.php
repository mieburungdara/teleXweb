<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CodeIgniter without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// Hook to initialize our Monolog logger
$hook['post_controller_constructor'][] = array(
    'class'    => 'Monolog_hook',
    'function' => 'init_logger',
    'filename' => 'Monolog_hook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'LanguageLoader',
    'function' => 'load_language',
    'filename' => 'LanguageLoader.php',
    'filepath' => 'hooks'
);