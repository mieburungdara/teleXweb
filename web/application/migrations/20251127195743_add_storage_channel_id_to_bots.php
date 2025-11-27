<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_storage_channel_id_to_bots extends CI_Migration {

    public function up()
    {
        $fields = array(
            'storage_channel_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => TRUE, // Can be NULL initially, or if a bot doesn't use a storage channel
                'after' => 'token' // Add after the 'token' column
            ),
        );
        $this->dbforge->add_column('bots', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('bots', 'storage_channel_id');
    }
}
