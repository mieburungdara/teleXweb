<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller for handling custom error pages.
 */
class Errors extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Log the 404 error with the requested URI
        log_message('error', '404 Page Not Found --> ' . $this->input->server('REQUEST_URI'));
    }

    /**
     * Shows the custom 404 page.
     * Sets the 404 status header and loads the custom view.
     */
    public function show_404() {
        $this->output->set_status_header('404');
        // Load the custom 404 view from application/views/errors/
        $this->load->view('errors/error_404_custom');
    }
}
