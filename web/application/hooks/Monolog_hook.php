<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Monolog_hook
{
    public function init_logger()
    {
        $CI =& get_instance();

        // At this point (post_controller_constructor), CI instance is fully available.
        // We can safely load config and other resources.
        if (!isset($CI->log) || !($CI->log instanceof MY_Log)) {
            // If MY_Log is not the logging class, do nothing.
            return;
        }

        // Load Monolog configs
        $CI->config->load('config', TRUE); // Load into 'config' index
        $monolog_config = $CI->config->item('config');

        $logger_name = $monolog_config['monolog_logger_name'] ?? 'CodeIgniterApp';
        $monolog_logger = new Logger($logger_name);

        $handlers_config = $monolog_config['monolog_handlers'] ?? [];

        if (!empty($handlers_config)) {
            foreach ($handlers_config as $handler_settings) {
                if (!empty($handler_settings['class'])) {
                    $handler_class = $handler_settings['class'];
                    $handler_args = $handler_settings['args'] ?? [];
                    
                    try {
                        $reflectionClass = new ReflectionClass($handler_class);
                        $handler = $reflectionClass->newInstanceArgs($handler_args);

                        if ($handler instanceof StreamHandler && !isset($handler_settings['formatter'])) {
                            $output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
                            $formatter = new LineFormatter($output);
                            $handler->setFormatter($formatter);
                        }

                        $monolog_logger->pushHandler($handler);
                    } catch (ReflectionException $e) {
                        error_log('Monolog hook configuration error: ' . $e->getMessage());
                    }
                }
            }
        }

        // Set the initialized Monolog instance on the MY_Log object
        $CI->log->set_monolog_instance($monolog_logger);
    }
}
