<?php

class File_model_test extends CITestCase
{
    private static $user_id = 1; // Assuming a test user with ID 1
    private $file_id;

    public function setUp()
    {
        parent::setUp();
        $this->CI->load->model('File_model');
        
        // Clean up before test
        $this->CI->db->delete('files', ['user_id' => self::$user_id]);
    }

    public function tearDown()
    {
        // Clean up after test
        $this->CI->db->delete('files', ['user_id' => self::$user_id]);
        parent::tearDown();
    }

    public function test_create_file()
    {
        $file_data = [
            'user_id' => self::$user_id,
            'bot_id' => 1,
            'file_unique_id' => 'unique_id_test_123',
            'telegram_file_id' => 'telegram_id_test_123',
            'original_file_name' => 'test_file.txt',
            'mime_type' => 'text/plain',
        ];

        $this->file_id = $this->CI->File_model->create_file($file_data);

        $this->assertIsInt($this->file_id);
        $this->assertGreaterThan(0, $this->file_id);
    }

    public function test_get_file_by_id()
    {
        // First, create a file to fetch
        $file_data = [
            'user_id' => self::$user_id,
            'bot_id' => 1,
            'file_unique_id' => 'unique_id_test_456',
            'telegram_file_id' => 'telegram_id_test_456',
            'original_file_name' => 'another_test_file.txt',
        ];
        $this->file_id = $this->CI->File_model->create_file($file_data);

        $fetched_file = $this->CI->File_model->get_file_by_id($this->file_id, self::$user_id);

        $this->assertIsArray($fetched_file);
        $this->assertEquals($this->file_id, $fetched_file['id']);
        $this->assertEquals('another_test_file.txt', $fetched_file['original_file_name']);
    }

    public function test_soft_delete_file()
    {
        // First, create a file to delete
        $file_data = [
            'user_id' => self::$user_id,
            'bot_id' => 1,
            'file_unique_id' => 'unique_id_to_delete_789',
            'telegram_file_id' => 'telegram_id_to_delete_789',
        ];
        $this->file_id = $this->CI->File_model->create_file($file_data);

        // Soft delete it
        $result = $this->CI->File_model->soft_delete_file($this->file_id, self::$user_id);
        $this->assertTrue($result);

        // Try to fetch it (it should not be in the regular get_user_files query)
        $this->CI->db->where('id', $this->file_id);
        $deleted_file = $this->CI->db->get('files')->row_array();

        $this->assertNotNull($deleted_file['deleted_at']);
    }
}
