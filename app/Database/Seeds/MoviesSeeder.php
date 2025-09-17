<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MoviesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Jawan',
                'description' => 'An action-packed thriller.',
                'director' => 'Atlee',
                'language' => 'Hindi',
                'certification' => 'UA',
                'genre' => 'Action, Thriller',
                'duration_minutes' => 169,
                'release_date' => date('Y-m-d', strtotime('-20 days')),
                'poster_url' => 'https://image.tmdb.org/t/p/w500/ps2oKfhY6DL3alynlSqY97gHSsg.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/original/nGxUxi3PfXDRm7Vg95VBNgNM8yc.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=video1',
                'status' => 'now_showing',
                'is_featured' => 1,
            ],
            [
                'title' => 'Brahmastra',
                'description' => 'A fantasy adventure.',
                'director' => 'Ayan Mukerji',
                'language' => 'Hindi',
                'certification' => 'U',
                'genre' => 'Adventure, Fantasy',
                'duration_minutes' => 167,
                'release_date' => date('Y-m-d', strtotime('-60 days')),
                'poster_url' => 'https://image.tmdb.org/t/p/w500/someposter2.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/original/somebackdrop2.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=video2',
                'status' => 'now_showing',
                'is_featured' => 0,
            ],
            [
                'title' => 'Tiger 3',
                'description' => 'Spy universe returns.',
                'director' => 'Maneesh Sharma',
                'language' => 'Hindi',
                'certification' => 'UA',
                'genre' => 'Action',
                'duration_minutes' => 150,
                'release_date' => date('Y-m-d', strtotime('+15 days')),
                'poster_url' => 'https://image.tmdb.org/t/p/w500/someposter3.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/original/somebackdrop3.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=video3',
                'status' => 'coming_soon',
                'is_featured' => 1,
            ],
        ];

        $this->db->table('movies')->insertBatch($data);
    }
}
