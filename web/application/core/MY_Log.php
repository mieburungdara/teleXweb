<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// The main autoloader should be loaded via config.php
// But we can keep this for redundancy if MY_Log is ever called outside the normal flow
if (file_exists(FCPATH . 'vendor/autoload.php')) {
    require_once FCPATH . 'vendor/autoload.php';
}

use Monolog\Logger;

class MY_Log extends CI_Log {

    // Public property to hold the Monolog instance
    public $monolog;

    // The hook will call this method to inject the fully configured Monolog instance
    public function set_monolog_instance(Logger $monolog_instance)
    {
        $this->monolog = $monolog_instance;
    }

    /**
     * Write Log File
     *
     * This is the function that is called by the global log_message() function
     *
     * @param	string	$level	The error level: 'error', 'debug', 'info', etc.
     * @param	string	$msg	The error message
     * @return	bool
     */
    public function write_log($level, $msg)
    {
        // If Monolog hasn't been initialized by the hook yet, do nothing.
        if (!$this->monolog) {
            // Or fallback to parent::write_log($level, $msg); if you want CI's default logging as a backup
            return false;
        }

        // Map CI log levels to Monolog levels
        switch (strtoupper($level)) {
            case 'ERROR':
                $monolog_level = Logger::ERROR;
                break;
            case 'DEBUG':
                $monolog_level = Logger::DEBUG;
                break;
            case 'INFO':
                $monolog_level = Logger::INFO;
                break;
            case 'NOTICE':
                $monolog_level = Logger::NOTICE;
                break;
            case 'WARNING':
                $monolog_level = Logger::WARNING;
                break;
            default:
                $monolog_level = Logger::INFO;
                break;
        }
        
        $this->monolog->addRecord($monolog_level, $msg);

        return true;
    }
}
