<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // migrations can only be run from the CLI
        if (!$this->input->is_cli_request()) {
            show_error('You don\'t have permission for this action');
            exit();
        }

        $this->load->library('migration');
    }

    public function index()
    {
        if ($this->migration->latest() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo 'Migrations ran successfully!' . PHP_EOL;
        }
    }
}

