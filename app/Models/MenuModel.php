<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'menu';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'deskripsi', 'harga', 'jenis', 'gambar', 'stok'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama'      => 'required|min_length[3]|max_length[100]',
        'harga'     => 'required|numeric|greater_than[0]',
        'jenis'     => 'required|in_list[makanan,minuman]',
        'deskripsi' => 'permit_empty|max_length[500]',
        'stok'      => 'permit_empty|integer|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Ambil menu berdasarkan jenis (makanan/minuman), atau semua jika null
     */
    public function getMenuByJenis(?string $jenis = null): array
    {
        if ($jenis) {
            return $this->where('jenis', $jenis)->findAll();
        }
        return $this->findAll();
    }

    /**
     * Cari menu berdasarkan keyword (nama atau deskripsi)
     */
    public function searchMenu(string $keyword): array
    {
        return $this->like('nama', $keyword)
                    ->orLike('deskripsi', $keyword)
                    ->findAll();
    }

    /**
     * Ambil menu yang masih tersedia (stok > 0)
     */
    public function getAvailableMenu(?string $jenis = null): array
    {
        $builder = $this->where('stok >', 0);
        if ($jenis) {
            $builder = $builder->where('jenis', $jenis);
        }
        return $builder->findAll();
    }

    /**
     * Ambil statistik menu: total makanan, total minuman, total stok
     */
    public function getMenuStats(): array
    {
        return [
            'total_makanan' => $this->where('jenis', 'makanan')->countAllResults(false),
            'total_minuman' => $this->where('jenis', 'minuman')->countAllResults(false),
            'total_menu'    => $this->countAllResults(false),
            'total_stok'    => $this->selectSum('stok')->first()['stok'] ?? 0,
        ];
    }

    /**
     * Kurangi stok menu setelah pemesanan
     */
    public function kurangiStok(int $id, int $jumlah = 1): bool
    {
        $menu = $this->find($id);
        if (!$menu || $menu['stok'] < $jumlah) {
            return false;
        }
        return $this->update($id, ['stok' => $menu['stok'] - $jumlah]);
    }
}