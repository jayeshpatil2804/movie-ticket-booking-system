<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookedSeatsTable extends Migration
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
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'show_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'seat_number' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
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
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('show_id', 'shows', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['show_id', 'seat_number']);
        $this->forge->createTable('booked_seats');
    }

    public function down()
    {
        $this->forge->dropTable('booked_seats');
    }
}
