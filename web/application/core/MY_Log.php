<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

if (file_exists(FCPATH . 'vendor/autoload.php')) {
    require_once FCPATH . 'vendor/autoload.php';
}

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\IntrospectionProcessor;

class MY_Log extends CI_Log {

    protected $monolog;

    public function __construct()
    {
        parent::__construct();
    }

    // This method will initialize Monolog on the first log write
    protected function init_monolog()
    {
        if ($this->monolog) {
            return;
        }

        // Use the global get_config() function which is available early
        $config =& get_config();

        // If config is not loaded for some reason, we can't initialize Monolog.
        if (empty($config)) {
             error_log('MY_Log: Could not load config. Monolog not initialized.');
             return;
        }

        $logger_name = $config['monolog_logger_name'] ?? 'CodeIgniterApp';
        $this->monolog = new Logger($logger_name);

        // Add a processor to include file and line number
        $this->monolog->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, [], 4));

        $handlers_config = $config['monolog_handlers'] ?? [];

        if (!empty($handlers_config)) {
            foreach ($handlers_config as $handler_settings) {
                if (!empty($handler_settings['class'])) {
                    $handler_class = $handler_settings['class'];
                    $handler_args = $handler_settings['args'] ?? [];
                    
                    try {
                        $reflectionClass = new ReflectionClass($handler_class);
                        $handler = $reflectionClass->newInstanceArgs($handler_args);

                        if ($handler instanceof StreamHandler && !isset($handler_settings['formatter'])) {
                            // Customize the line format to include file and line from 'extra'
                            $output = "[%datetime%] %channel%.%level_name%: %message% [Origin: %extra.file%:%extra.line%] %context%\n";
                            $formatter = new LineFormatter($output, null, true, true);
                            $handler->setFormatter($formatter);
                        }

                        $this->monolog->pushHandler($handler);
                    } catch (ReflectionException $e) {
                        error_log('Monolog configuration error in MY_Log: ' . $e->getMessage());
                    }
                }
            }
        }
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
        // Do not log messages with the level 'INFO' as requested.
        $levels_to_exclude = ['INFO'];
        if (in_array(strtoupper($level), $levels_to_exclude)) {
            return true; // Act as if handled, but do nothing.
        }

        // Lazy initialize Monolog on the first call
        if (!$this->monolog) {
            $this->init_monolog();
        }
        
        // If for some reason initialization failed, fallback to the parent logger.
        if (!$this->monolog) {
            return parent::write_log($level, $msg);
        }

        // Map CI log levels to Monolog levels
        $monolog_level = $this->get_monolog_level($level);
        
        $this->monolog->addRecord($monolog_level, $msg);

        return true;
    }

    protected function get_monolog_level($level)
    {
        switch (strtoupper($level)) {
            case 'ERROR':
                return Logger::ERROR;
            case 'DEBUG':
                return Logger::DEBUG;
            case 'INFO':
                return Logger::INFO;
            case 'NOTICE':
                return Logger::NOTICE;
            case 'WARNING':
                return Logger::WARNING;
            default:
                // Default to INFO for custom levels
                return Logger::INFO;
        }
    }
}
