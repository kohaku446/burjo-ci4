<?php

namespace App\Controllers;

use App\Models\MenuModel;
use CodeIgniter\HTTP\RedirectResponse;

class Admin extends BaseController
{
    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    // Helper method untuk cek akses admin
    protected function checkAdminAccess()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Silakan login sebagai Admin terlebih dahulu.');
        }
        return null;
    }

    public function dashboard()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;
        
        $keyword = $this->request->getGet('search');
        
        if ($keyword) {
            $menus = $this->menuModel->searchMenu($keyword);
        } else {
            $menus = $this->menuModel->orderBy('jenis', 'ASC')->orderBy('nama', 'ASC')->findAll();
        }

        $data = [
            'title' => 'Dashboard Admin - Kelola Menu Burjo',
            'menus' => $menus,
            'keyword' => $keyword,
            'total_makanan' => $this->menuModel->where('jenis', 'makanan')->countAllResults(false),
            'total_minuman' => $this->menuModel->where('jenis', 'minuman')->countAllResults(false),
        ];

        return view('admin/dashboard', $data);
    }

    public function formMenu($id = null)
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;
        
        $menu = null;
        if ($id) {
            $menu = $this->menuModel->find($id);
            if (!$menu) {
                return redirect()->to('/admin/dashboard')->with('error', 'Menu tidak ditemukan');
            }
        }

        $data = [
            'title' => $id ? 'Edit Menu' : 'Tambah Menu Baru',
            'menu' => $menu,
            'is_edit' => (bool)$id
        ];

        return view('admin/form_menu', $data);
    }

    public function saveMenu()
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;
        
        $id = $this->request->getPost('id');
        
        $data = [
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga'     => $this->request->getPost('harga'),
            'jenis'     => $this->request->getPost('jenis'),
            'stok'      => $this->request->getPost('stok') ?? 10,
        ];

        // Handle upload gambar (opsional)
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/menu', $newName);
            $data['gambar'] = $newName;
        } elseif ($id) {
            // Jika edit dan tidak upload baru, pertahankan gambar lama
            $existing = $this->menuModel->find($id);
            if ($existing) {
                $data['gambar'] = $existing['gambar'];
            }
        }

        if ($id) {
            // Update
            if ($this->menuModel->update($id, $data)) {
                return redirect()->to('/admin/dashboard')->with('success', 'Menu berhasil diperbarui!');
            }
        } else {
            // Insert baru
            if ($this->menuModel->insert($data)) {
                return redirect()->to('/admin/dashboard')->with('success', 'Menu baru berhasil ditambahkan!');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan menu. Periksa kembali data.');
    }

    public function deleteMenu($id)
    {
        if ($redirect = $this->checkAdminAccess()) return $redirect;
        
        $menu = $this->menuModel->find($id);
        if ($menu) {
            // Hapus file gambar jika ada
            if (!empty($menu['gambar']) && file_exists(ROOTPATH . 'public/uploads/menu/' . $menu['gambar'])) {
                @unlink(ROOTPATH . 'public/uploads/menu/' . $menu['gambar']);
            }
            $this->menuModel->delete($id);
            return redirect()->to('/admin/dashboard')->with('success', 'Menu "' . $menu['nama'] . '" berhasil dihapus.');
        }
        return redirect()->to('/admin/dashboard')->with('error', 'Menu tidak ditemukan.');
    }
}