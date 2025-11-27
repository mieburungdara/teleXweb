<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_balance_transactions_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE,
            ),
            'type' => array( // 'credit' or 'debit'
                'type' => 'ENUM("credit","debit")',
                'null' => FALSE,
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT fk_balance_transactions_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('balance_transactions');
    }

    public function down()
    {
        $this->dbforge->drop_table('balance_transactions');
    }
}
