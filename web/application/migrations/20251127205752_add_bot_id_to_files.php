<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_bot_id_to_files extends CI_Migration {

    public function up()
    {
        $fields = array(
            'bot_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
                'after' => 'user_id'
            ),
        );
        $this->dbforge->add_column('files', $fields);

        // Add foreign key constraint
        $this->dbforge->add_field('CONSTRAINT fk_files_bot_id FOREIGN KEY (bot_id) REFERENCES bots(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_column('files', 'bot_id');
    }
}
