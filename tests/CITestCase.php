<?php
class CITestCase extends PHPUnit\Framework\TestCase {

    protected $CI;

    public function setUp(): void
    {
        parent::setUp();

        // Set a dummy REQUEST_URI to prevent errors in CodeIgniter's Input class
        $_SERVER['REQUEST_URI'] = '/';

        // Get the CodeIgniter instance. It should be available after bootstrap.php
        $this->CI = &get_instance();

        // Assign mocked CI components to the CI super object
        $this->CI->load = new Mock_Loader(); // Mock the loader
        $this->CI->db = new Mock_DB_Driver(); // Mock the database driver
        // You might need to mock more components depending on your test needs
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // Clean up resources if necessary
    }

    // Helper to get CI instance
    protected function &get_ci_instance()
    {
        return $this->CI;
    }
}

// Mock CodeIgniter's Loader class for testing
class Mock_Loader {
    public function model($model_name) {
        // Simple mock: return a new instance of the model.
        // In real tests, you might want to return a mocked version of the model.
        if (file_exists(APPPATH . 'models/' . $model_name . '.php')) {
            require_once APPPATH . 'models/' . $model_name . '.php';
            $this->{$model_name} = new $model_name();
            // Assign to CI superobject
            $CI = get_instance();
            if ($CI) {
                $CI->{$model_name} = $this->{$model_name};
            }
            return $this->{$model_name};
        }
        return false;
    }
    public function helper($helper_name) {}
    public function library($library_name) {}
    public function view($view_name, $data = array(), $return = FALSE) {}
}

// Spy_Loader to record view calls
class Spy_Loader extends Mock_Loader {
    public $views_loaded = [];
    public function view($view_name, $data = array(), $return = FALSE) {
        $this->views_loaded[] = ['name' => $view_name, 'data' => $data, 'return' => $return];
        // For testing, we don't actually render the view
        if ($return) {
            return 'mock_view_content_for_' . $view_name;
        }
        return '';
    }
}

// Mock CodeIgniter's DB_Driver class for testing
class Mock_DB_Driver {
    public function get($table_name) { return new Mock_DB_Result(); }
    public function where($field, $value) { return $this; }
    public function update($table, $data) { return true; }
    public function set($field, $value, $escape = TRUE) { return $this; }
    public function row() { return (object)['id' => 1, 'username' => 'testuser', 'balance' => 100.00]; } // Example row
    public function trans_start() {}
    public function trans_complete() {}
    public function trans_status() { return true; }
    public function trans_rollback() {}
    // Add more mock methods as needed for your tests
}

// Mock CodeIgniter's DB_Result class
class Mock_DB_Result {
    public function row() { return (object)['id' => 1, 'username' => 'testuser', 'balance' => 100.00]; }
    public function result() { return [(object)['id' => 1, 'codename' => 'testcodename', 'username' => 'testuser', 'balance' => 100.00]]; }
    public function num_rows() { return 1; }
}
