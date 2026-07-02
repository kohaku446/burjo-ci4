<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "=== Menjalankan DatabaseSeeder ===\n";

        $this->call('UserSeeder');
        $this->call('MenuSeeder');

        echo "=== Seeding selesai! ===\n";
    }
}
