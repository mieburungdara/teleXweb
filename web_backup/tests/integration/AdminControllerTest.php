<?php
require_once __DIR__ . '/../ControllerTestCase.php'; // Include the base ControllerTestCase

class AdminControllerTest extends ControllerTestCase {

    protected $admin_controller;

    public function setUp(): void
    {
        parent::setUp();
        // Load the Admin controller
        $this->admin_controller = $this->loadController('Admin');
    }

    public function testIndexLoadsDashboardView()
    {
        // Call the index method
        $this->admin_controller->index();

        // Assert that the 'admin/dashboard' view was loaded
        $this->assertCount(1, $this->CI->load->views_loaded);
        $this->assertEquals('admin/dashboard', $this->CI->load->views_loaded[0]['name']);
    }
}
