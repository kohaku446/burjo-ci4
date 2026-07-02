<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $makanan = [
            [
                'nama'      => 'Nasi Goreng Spesial',
                'deskripsi' => 'Nasi goreng dengan telur, ayam, dan sayuran segar khas Burjo',
                'harga'     => 15000,
                'jenis'     => 'makanan',
                'gambar'    => 'nasi_goreng.jpg',
                'stok'      => 20,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'      => 'Mie Ayam Bakso',
                'deskripsi' => 'Mie kuning dengan ayam suwir dan bakso sapi spesial',
                'harga'     => 12000,
                'jenis'     => 'makanan',
                'gambar'    => 'mie_ayam.jpg',
                'stok'      => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'      => 'Nasi Campur',
                'deskripsi' => 'Nasi putih dengan lauk pauk lengkap: ayam, telur, tempe, tahu',
                'harga'     => 18000,
                'jenis'     => 'makanan',
                'gambar'    => 'nasi_campur.jpg',
                'stok'      => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'      => 'Lontong Opor',
                'deskripsi' => 'Lontong dengan opor ayam dan sambal terasi',
                'harga'     => 14000,
                'jenis'     => 'makanan',
                'gambar'    => 'lontong_opor.jpg',
                'stok'      => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $minuman = [
            [
                'nama'      => 'Es Teh Manis',
                'deskripsi' => 'Teh hitam segar dengan es dan gula pasir',
                'harga'     => 5000,
                'jenis'     => 'minuman',
                'gambar'    => 'es_teh.jpg',
                'stok'      => 50,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'      => 'Jus Alpukat',
                'deskripsi' => 'Jus alpukat segar dengan susu dan gula',
                'harga'     => 12000,
                'jenis'     => 'minuman',
                'gambar'    => 'jus_alpukat.jpg',
                'stok'      => 12,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'      => 'Es Jeruk Peras',
                'deskripsi' => 'Jeruk segar peras dengan es batu',
                'harga'     => 8000,
                'jenis'     => 'minuman',
                'gambar'    => 'es_jeruk.jpg',
                'stok'      => 25,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'      => 'Kopi Hitam Burjo',
                'deskripsi' => 'Kopi hitam khas Jogja, strong dan nikmat',
                'harga'     => 7000,
                'jenis'     => 'minuman',
                'gambar'    => 'kopi_hitam.jpg',
                'stok'      => 30,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'      => 'Wedang Jahe',
                'deskripsi' => 'Minuman jahe hangat dengan gula merah',
                'harga'     => 6000,
                'jenis'     => 'minuman',
                'gambar'    => 'wedang_jahe.jpg',
                'stok'      => 18,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $allMenu  = array_merge($makanan, $minuman);
        $inserted = 0;
        foreach ($allMenu as $menu) {
            // Cek apakah menu dengan nama yang sama sudah ada
            $exists = $this->db->table('menu')
                               ->where('nama', $menu['nama'])
                               ->get()->getRow();
            if (!$exists) {
                $this->db->table('menu')->insert($menu);
                $inserted++;
                echo "  + Menu '{$menu['nama']}' ({$menu['jenis']}) ditambahkan.\n";
            } else {
                echo "  ~ Menu '{$menu['nama']}' sudah ada, dilewati.\n";
            }
        }

        echo "MenuSeeder: {$inserted} menu baru ditambahkan.\n";
    }
}
