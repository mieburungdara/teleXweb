<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_files_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'folder_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'file_unique_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => TRUE,
                'null' => FALSE,
            ),
            'media_group_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'storage_channel_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => FALSE,
            ),
            'storage_message_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => FALSE,
            ),
            'telegram_file_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            ),
            'thumbnail_file_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'original_file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'file_size' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
            'mime_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'is_favorited' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE,
            ),
            'process_status' => array(
                'type' => 'ENUM("pending","processed","indexed","failed")',
                'default' => 'pending',
                'null' => FALSE,
            ),
            'webhook_reliability_status' => array(
                'type' => 'ENUM("success","failed","retried")',
                'default' => 'success',
                'null' => FALSE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'deleted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(['storage_channel_id', 'storage_message_id'], FALSE, TRUE); // Unique key
        $this->dbforge->add_field('CONSTRAINT fk_files_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_files_folder_id FOREIGN KEY (folder_id) REFERENCES folders(id) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->dbforge->create_table('files');
    }

    public function down()
    {
        $this->dbforge->drop_table('files');
    }
}
