<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_failed_webhooks_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'webhook_url' => array(
                'type' => 'TEXT', // Store the full URL that was called
                'null' => FALSE,
            ),
            'payload' => array(
                'type' => 'TEXT', // Store the original payload sent to the webhook
                'null' => TRUE,
            ),
            'error_message' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'attempt_count' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => 1,
            ),
            'last_attempt_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'next_attempt_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
            'status' => array(
                'type' => 'ENUM("pending","retrying","failed","success")',
                'null' => FALSE,
                'default' => 'pending',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('failed_webhooks');
    }

    public function down()
    {
        $this->dbforge->drop_table('failed_webhooks');
    }
}
