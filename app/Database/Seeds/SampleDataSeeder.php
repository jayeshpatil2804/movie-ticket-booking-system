<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Clear existing data
        $db->table('booked_seats')->truncate();
        $db->table('bookings')->truncate();
        $db->table('shows')->truncate();
        $db->table('movies')->truncate();
        $db->table('screens')->truncate();
        $db->table('cinemas')->truncate();

        // Insert Cinemas
        $cinemas = [
            ['name' => 'PVR Cinemas', 'location' => 'City Center', 'total_screens' => 5],
            ['name' => 'INOX', 'location' => 'Mall of India', 'total_screens' => 7],
            ['name' => 'Cinepolis', 'location' => 'Trendset Mall', 'total_screens' => 4]
        ];
        $db->table('cinemas')->insertBatch($cinemas);
        $cinemaIds = $db->insertID();
        
        // Insert Screens for each cinema
        $screens = [];
        foreach ([1, 2, 3] as $cinemaId) {
            for ($i = 1; $i <= 3; $i++) {
                $screens[] = [
                    'cinema_id' => $cinemaId,
                    'name' => 'Screen ' . $i,
                    'capacity' => rand(100, 200),
                    'is_active' => 1
                ];
            }
        }
        $db->table('screens')->insertBatch($screens);
        $screenIds = $db->insertID();

        // Insert Movies
        $movies = [
            [
                'title' => 'Avengers: Endgame',
                'description' => 'After the devastating events of Avengers: Infinity War, the universe is in ruins.',
                'duration_minutes' => 181,
                'release_date' => '2023-04-26',
                'genre' => 'Action, Adventure, Sci-Fi',
                'poster_url' => 'https://m.media-amazon.com/images/M/MV5BMTc5MDE2ODcwNV5BMl5BanBnXkFtZTgwMzI2NzQ2NzM@._V1_.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=TcMBFSGVi1c',
                'status' => 'now_showing'
            ],
            [
                'title' => 'The Batman',
                'description' => 'When a sadistic serial killer begins murdering key political figures in Gotham.',
                'duration_minutes' => 176,
                'release_date' => '2023-03-04',
                'genre' => 'Action, Crime, Drama',
                'poster_url' => 'https://m.media-amazon.com/images/M/MV5BMDdmMTBiNTYtMDIzNi00NGVlLWIzMDYtZTk3MTQ3NGQxZGEwXkEyXkFqcGdeQXVyMzMwOTU5MDk@._V1_.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=mqqft2x_Aa4',
                'status' => 'now_showing'
            ],
            [
                'title' => 'Top Gun: Maverick',
                'description' => 'After thirty years, Maverick is still pushing the envelope as a top naval aviator.',
                'duration_minutes' => 131,
                'release_date' => '2023-05-27',
                'genre' => 'Action, Drama',
                'poster_url' => 'https://m.media-amazon.com/images/M/MV5BZWYzOGEwNTgtNWU3NS00ZTQ0LWJkODUtMmVhMjIwMjA1ZmQwXkEyXkFqcGdeQXVyMjkwOTAyMDU@._V1_.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=giXco2jaZ_4',
                'status' => 'now_showing'
            ]
        ];
        $db->table('movies')->insertBatch($movies);
        $movieIds = $db->insertID();

        // Insert Shows for the next 7 days
        $showTimes = ['10:00:00', '13:00:00', '16:00:00', '19:00:00', '22:00:00'];
        $shows = [];
        $startDate = date('Y-m-d');
        
        for ($day = 0; $day < 7; $day++) {
            $showDate = date('Y-m-d', strtotime("+$day days"));
            
            foreach ($showTimes as $time) {
                foreach (range(1, 3) as $movieId) { // For each movie
                    foreach (range(1, 3) as $screenId) { // For each screen
                        $shows[] = [
                            'movie_id' => $movieId,
                            'screen_id' => $screenId,
                            'show_time' => "$showDate $time",
                            'ticket_price' => rand(200, 500),
                            'status' => 'scheduled'
                        ];
                    }
                }
            }
        }
        $db->table('shows')->insertBatch($shows);
    }
}
