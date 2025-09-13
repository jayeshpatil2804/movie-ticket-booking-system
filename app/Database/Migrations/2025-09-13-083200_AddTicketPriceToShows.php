<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTicketPriceToShows extends Migration
{
    public function up()
    {
        $fields = [
            'ticket_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 200.00,
                'after' => 'show_time'
            ]
        ];

        $this->forge->addColumn('shows', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('shows', 'ticket_price');
    }
}
