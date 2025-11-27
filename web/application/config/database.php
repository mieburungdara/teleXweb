<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => '45.143.81.225',
	'username' => 'u1574101_reza',
	'password' => 'Samarindakaltim',
	'database' => 'u1574101_telexweb',
	'port' => 3306,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => TRUE, // Re-enable DB Caching
	'cachedir' => APPPATH . 'cache/db/', // DB Caching directory
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);