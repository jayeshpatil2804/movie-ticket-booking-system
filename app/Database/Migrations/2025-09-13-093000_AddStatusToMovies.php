<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToMovies extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['now_showing', 'coming_soon'],
                'default'    => 'coming_soon',
                'null'       => false,
                'after'      => 'poster_url'
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'status'
            ]
        ];

        $this->forge->addColumn('movies', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('movies', ['status', 'is_featured']);
    }
}
