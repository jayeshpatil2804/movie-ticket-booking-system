<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSeatLocksTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('seat_locks')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'show_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'seat_number' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false,
            ],
            'locked_until' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['show_id', 'seat_number']);
        $this->forge->createTable('seat_locks');
    }

    public function down()
    {
        $this->forge->dropTable('seat_locks');
    }
}
