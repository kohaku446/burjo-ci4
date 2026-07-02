<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'     => 'admin',
                'password'     => password_hash('admin123', PASSWORD_BCRYPT),
                'role'         => 'admin',
                'nama_lengkap' => 'Administrator Burjo',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'tamu1',
                'password'     => password_hash('tamu123', PASSWORD_BCRYPT),
                'role'         => 'tamu',
                'nama_lengkap' => 'Tamu Demo',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        $inserted = 0;
        foreach ($data as $user) {
            // Cek apakah username sudah ada
            $exists = $this->db->table('users')
                               ->where('username', $user['username'])
                               ->get()->getRow();
            if (!$exists) {
                $this->db->table('users')->insert($user);
                $inserted++;
                echo "  + User '{$user['username']}' ({$user['role']}) ditambahkan.\n";
            } else {
                echo "  ~ User '{$user['username']}' sudah ada, dilewati.\n";
            }
        }

        echo "UserSeeder: {$inserted} user baru ditambahkan.\n";
    }
}
