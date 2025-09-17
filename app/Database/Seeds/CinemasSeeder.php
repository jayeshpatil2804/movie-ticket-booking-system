<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CinemasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Miraj City Center',
                'address' => 'City Center, Main Road, Ahmedabad'
            ],
            [
                'name' => 'Miraj Downtown',
                'address' => 'Downtown Plaza, Ring Road, Surat'
            ],
        ];

        $this->db->table('cinemas')->insertBatch($data);
    }
}
