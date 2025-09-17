<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $this->call('CinemasSeeder');
        $this->call('ScreensSeeder');
        $this->call('MoviesSeeder');
        $this->call('ShowsSeeder');
    }
}
