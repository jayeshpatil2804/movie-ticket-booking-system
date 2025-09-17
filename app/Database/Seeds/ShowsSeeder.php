<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ShowsSeeder extends Seeder
{
    public function run()
    {
        // Ensure prerequisites exist
        if ($this->db->table('cinemas')->countAll() === 0) {
            $this->call('CinemasSeeder');
        }
        if ($this->db->table('screens')->countAll() === 0) {
            $this->call('ScreensSeeder');
        }
        if ($this->db->table('movies')->countAll() === 0) {
            $this->call('MoviesSeeder');
        }

        $screens = $this->db->table('screens')->get()->getResultArray();
        $movies  = $this->db->table('movies')->get()->getResultArray();

        if (empty($screens) || empty($movies)) {
            return;
        }

        $rows = [];
        $now = time();
        // Create 2-3 shows per movie across available screens
        foreach ($movies as $mIndex => $movie) {
            // pick up to 2 screens for each movie
            $assignedScreens = array_slice($screens, 0, min(2, count($screens)));
            $basePrice = 220 + ($mIndex % 3) * 30; // 220/250/280

            foreach ($assignedScreens as $sIndex => $screen) {
                // three showtimes: today +1h, +4h, +7h (if in future)
                for ($i = 1; $i <= 3; $i++) {
                    $showTime = date('Y-m-d H:i:s', $now + ($i * 60 * 60) + ($sIndex * 1800));
                    $rows[] = [
                        'movie_id'     => (int)$movie['id'],
                        'screen_id'    => (int)$screen['id'],
                        'show_time'    => $showTime,
                        'ticket_price' => $basePrice,
                        'status'       => 'active',
                        'created_at'   => date('Y-m-d H:i:s'),
                        'updated_at'   => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        if (!empty($rows)) {
            $this->db->table('shows')->insertBatch($rows);
        }
    }
}
