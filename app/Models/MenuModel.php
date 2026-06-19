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
    protected $validationRules      = [
        'nama'      => 'required|min_length[3]|max_length[100]',
        'harga'     => 'required|numeric|greater_than[0]',
        'jenis'     => 'required|in_list[makanan,minuman]',
        'deskripsi' => 'permit_empty|max_length[500]',
        'stok'      => 'permit_empty|integer|greater_than_equal_to[0]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function getMenuByJenis($jenis = null)
    {
        if ($jenis) {
            return $this->where('jenis', $jenis)->findAll();
        }
        return $this->findAll();
    }

    public function searchMenu($keyword)
    {
        return $this->like('nama', $keyword)
                    ->orLike('deskripsi', $keyword)
                    ->findAll();
    }
}