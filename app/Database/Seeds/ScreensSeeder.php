<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ScreensSeeder extends Seeder
{
    public function run()
    {
        // Fetch existing cinemas
        $cinemas = $this->db->table('cinemas')->get()->getResultArray();
        if (empty($cinemas)) {
            // Ensure cinemas exist
            $this->call('CinemasSeeder');
            $cinemas = $this->db->table('cinemas')->get()->getResultArray();
        }

        $rows = [];
        foreach ($cinemas as $cinema) {
            $rows[] = [
                'cinema_id' => $cinema['id'],
                'name' => 'Screen 1',
                'rows' => 10,
                'seats_per_row' => 10,
            ];
            $rows[] = [
                'cinema_id' => $cinema['id'],
                'name' => 'Screen 2',
                'rows' => 10,
                'seats_per_row' => 10,
            ];
        }

        if (!empty($rows)) {
            $this->db->table('screens')->insertBatch($rows);
        }
    }
}
