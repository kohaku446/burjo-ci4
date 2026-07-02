<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'password', 'role', 'nama_lengkap'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[admin,tamu]',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Ambil user berdasarkan username
     */
    public function getUserByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Verifikasi password menggunakan bcrypt
     */
    public function verifyPassword(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }

    /**
     * Ambil semua user
     */
    public function getAllUsers(): array
    {
        return $this->orderBy('role', 'ASC')->orderBy('username', 'ASC')->findAll();
    }

    /**
     * Ambil user berdasarkan role
     */
    public function getUsersByRole(string $role): array
    {
        return $this->where('role', $role)->findAll();
    }

    /**
     * Hitung jumlah user per role
     */
    public function countByRole(string $role): int
    {
        return $this->where('role', $role)->countAllResults();
    }
}