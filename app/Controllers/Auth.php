<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

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
        return view('admin/login', $data);  // view login terpusat
    }

    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $loginAs = $this->request->getPost('login_as'); // admin atau tamu

        // Login sebagai Tamu (tanpa password atau password sederhana)
        if ($loginAs === 'tamu') {
        $tamuName   = $this->request->getPost('tamu_name') ?: 'Tamu';
        $nomorMeja  = $this->request->getPost('nomor_meja');

        session()->set([
        'isLoggedIn'   => true,
        'user_id'      => 0,
        'username'     => $tamuName,
        'role'         => 'tamu',
        'nama_lengkap' => $tamuName,
        'nomor_meja'   => $nomorMeja
    ]);

    return redirect()->to('/menu')->with('success', 'Selamat datang! Anda login sebagai Tamu di Meja No. ' . $nomorMeja);
    }

        // Login Admin
        if (empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Username dan password harus diisi');
        }

        $user = $this->userModel->getUserByUsername($username);

        if ($user && $user['role'] === 'admin' && $password === $user['password']) {
            session()->set([
                'isLoggedIn' => true,
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'nama_lengkap' => $user['nama_lengkap'] ?? $user['username']
            ]);
            return redirect()->to('/admin/dashboard')->with('success', 'Login admin berhasil!');
        }

        return redirect()->back()->withInput()->with('error', 'Username atau password salah, atau bukan admin');
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
    public static function checkLogin($requiredRole = null)
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