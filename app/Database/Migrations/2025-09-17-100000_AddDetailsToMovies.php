<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailsToMovies extends Migration
{
    public function up()
    {
        // Build fields array only for columns that are missing
        $fields = [];
        if (! $this->db->fieldExists('language', 'movies')) {
            $fields['language'] = [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'director'
            ];
        }
        if (! $this->db->fieldExists('certification', 'movies')) {
            $fields['certification'] = [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'after' => 'language'
            ];
        }
        if (! $this->db->fieldExists('genre', 'movies')) {
            $fields['genre'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'certification'
            ];
        }
        if (! $this->db->fieldExists('trailer_url', 'movies')) {
            $fields['trailer_url'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'poster_url'
            ];
        }
        if (! $this->db->fieldExists('backdrop_url', 'movies')) {
            $fields['backdrop_url'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'trailer_url'
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('movies', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('movies', ['language', 'certification', 'genre', 'trailer_url', 'backdrop_url']);
    }
}
