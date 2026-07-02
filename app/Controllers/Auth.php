<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    // Aturan validasi form login admin
    protected $loginRules = [
        'username' => [
            'label' => 'Username',
            'rules' => 'required|min_length[3]|max_length[50]',
            'errors' => [
                'required'   => 'Username wajib diisi.',
                'min_length' => 'Username minimal 3 karakter.',
                'max_length' => 'Username maksimal 50 karakter.',
            ],
        ],
        'password' => [
            'label' => 'Password',
            'rules' => 'required|min_length[6]',
            'errors' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 6 karakter.',
            ],
        ],
    ];

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        // Jika sudah login, redirect sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }

        $data = ['title' => 'Login - Burjo'];
        return view('admin/login', $data);
    }

    public function attemptLogin()
    {
        $loginAs = $this->request->getPost('login_as');

        // ─── Login sebagai Tamu ───
        if ($loginAs === 'tamu') {
            // Validasi nomor meja wajib
            if (!$this->validate([
                'nomor_meja' => [
                    'label' => 'Nomor Meja',
                    'rules' => 'required|integer|greater_than[0]',
                    'errors' => [
                        'required'     => 'Nomor meja wajib diisi.',
                        'integer'      => 'Nomor meja harus berupa angka.',
                        'greater_than' => 'Nomor meja harus lebih dari 0.',
                    ],
                ],
            ])) {
                return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
            }

            $tamuName  = $this->request->getPost('tamu_name') ?: 'Tamu';
            $nomorMeja = $this->request->getPost('nomor_meja');

            session()->set([
                'isLoggedIn'   => true,
                'user_id'      => 0,
                'username'     => $tamuName,
                'role'         => 'tamu',
                'nama_lengkap' => $tamuName,
                'nomor_meja'   => $nomorMeja,
            ]);

            return redirect()->to('/menu')->with('success', 'Selamat datang! Anda login sebagai Tamu di Meja No. ' . $nomorMeja);
        }

        // ─── Login sebagai Admin ───

        // 1. Validasi form terlebih dahulu (field tidak boleh kosong, format benar)
        if (!$this->validate($this->loginRules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // 2. Cek user di database berdasarkan username
        $user = $this->userModel->getUserByUsername($username);

        // 3. Validasi: user harus ada, berstatus admin, dan password cocok (bcrypt)
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Username tidak ditemukan.');
        }

        if ($user['role'] !== 'admin') {
            return redirect()->back()->withInput()->with('error', 'Akun ini bukan akun admin.');
        }

        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah. Silakan coba lagi.');
        }

        // 4. Login berhasil — simpan data ke session
        session()->set([
            'isLoggedIn'   => true,
            'user_id'      => $user['id'],
            'username'     => $user['username'],
            'role'         => $user['role'],
            'nama_lengkap' => $user['nama_lengkap'] ?? $user['username'],
        ]);

        return redirect()->to('/admin/dashboard')->with('success', 'Login admin berhasil! Selamat datang, ' . ($user['nama_lengkap'] ?? $user['username']) . '.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Anda telah logout.');
    }

    private function redirectByRole()
    {
        $role = session()->get('role');
        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        }
        return redirect()->to('/menu');
    }

    // Helper untuk cek login di controller lain
    public static function checkLogin(?string $requiredRole = null): bool
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return false;
        }
        if ($requiredRole && $session->get('role') !== $requiredRole) {
            return false;
        }
        return true;
    }
}