<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScreensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'cinema_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'rows' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
            ],
            'seats_per_row' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('cinema_id', 'cinemas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('screens');
    }

    public function down()
    {
        $this->forge->dropTable('screens');
    }
}