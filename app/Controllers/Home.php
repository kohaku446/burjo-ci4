<?php

namespace App\Controllers;

use App\Libraries\Cart;
use App\Models\MenuModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Home extends BaseController
{
    protected $menuModel;
    protected $cart;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->cart      = new Cart();
    }

    // =========================================================
    //  HALAMAN UTAMA & MENU
    // =========================================================

    public function index()
    {
        $data = [
            'title'   => 'Burjo - Warung Makan & Minum Khas Jogja',
            'makanan' => $this->menuModel->getMenuByJenis('makanan'),
            'minuman' => $this->menuModel->getMenuByJenis('minuman'),
        ];

        return view('guest/home', $data);
    }

    public function menu()
    {
        $jenis   = $this->request->getGet('jenis');
        $keyword = $this->request->getGet('search');

        if ($keyword) {
            $menus = $this->menuModel->searchMenu($keyword);
        } elseif ($jenis) {
            $menus = $this->menuModel->getMenuByJenis($jenis);
        } else {
            $menus = $this->menuModel->findAll();
        }

        $data = [
            'title'         => 'Daftar Menu Burjo',
            'menus'         => $menus,
            'current_jenis' => $jenis,
            'keyword'       => $keyword,
            'cart_count'    => $this->cart->count(),
        ];

        return view('guest/menu', $data);
    }

    // =========================================================
    //  SOAL 3 — EXPORT PDF MENGGUNAKAN DOMPDF
    // =========================================================

    /**
     * Export daftar menu ke file PDF menggunakan Dompdf.
     * Dapat difilter berdasarkan jenis (makanan/minuman).
     */
    public function exportMenuPdf()
    {
        $jenis = $this->request->getGet('jenis');

        // Ambil data menu dari database
        if ($jenis && in_array($jenis, ['makanan', 'minuman'])) {
            $menus = $this->menuModel->getMenuByJenis($jenis);
            $judulJenis = ucfirst($jenis);
        } else {
            $menus = $this->menuModel->orderBy('jenis', 'ASC')->orderBy('nama', 'ASC')->findAll();
            $judulJenis = 'Semua Menu';
        }

        // Render view sebagai string HTML
        $html = view('pdf/menu_pdf', [
            'menus'      => $menus,
            'judulJenis' => $judulJenis,
        ]);

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isFontSubsettingEnabled', true);

        // Buat instance Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Nama file PDF
        $fileName = 'menu_burjo_' . ($jenis ?: 'semua') . '_' . date('Ymd_His') . '.pdf';

        // Stream ke browser (download langsung)
        $dompdf->stream($fileName, ['Attachment' => true]);
        exit;
    }

    // =========================================================
    //  SOAL 4 — CART LIBRARY (insert, update, total, remove, destroy)
    // =========================================================

    /**
     * insert() — Tambah item ke keranjang belanja
     */
    public function addToCart($id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan.');
        }

        // Gunakan Cart Library method insert()
        $inserted = $this->cart->insert([
            'id'        => $menu['id'],
            'nama'      => $menu['nama'],
            'harga'     => $menu['harga'],
            'jenis'     => $menu['jenis'],
            'gambar'    => $menu['gambar'],
            'deskripsi' => $menu['deskripsi'],
            'qty'       => 1,
        ]);

        if ($inserted) {
            return redirect()->back()->with('success', '"' . $menu['nama'] . '" ditambahkan ke keranjang!');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan menu ke keranjang.');
    }

    /**
     * update() — Ubah quantity barang/layanan yang dipesan
     */
    public function updateCart()
    {
        $id  = $this->request->getPost('id');
        $qty = (int) $this->request->getPost('qty');

        if (!$id) {
            return redirect()->to('/cart')->with('error', 'ID item tidak valid.');
        }

        // Gunakan Cart Library method update()
        $updated = $this->cart->update($id, $qty);

        if ($updated) {
            if ($qty <= 0) {
                return redirect()->to('/cart')->with('success', 'Item berhasil dihapus dari keranjang.');
            }
            return redirect()->to('/cart')->with('success', 'Quantity berhasil diperbarui.');
        }

        return redirect()->to('/cart')->with('error', 'Item tidak ditemukan di keranjang.');
    }

    /**
     * remove() — Hapus item dari cart
     */
    public function removeFromCart($id)
    {
        // Gunakan Cart Library method remove()
        $removed = $this->cart->remove($id);

        if ($removed) {
            return redirect()->to('/cart')->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        return redirect()->to('/cart')->with('error', 'Item tidak ditemukan di keranjang.');
    }

    /**
     * Halaman keranjang — tampilkan isi cart dan total()
     */
    public function cart()
    {
        // Gunakan Cart Library: contents() dan total()
        $data = [
            'title'      => 'Keranjang Pesanan',
            'cart'       => $this->cart->contents(),    // semua item
            'total'      => $this->cart->total(),        // total() harga
            'cart_count' => $this->cart->count(),
        ];

        return view('guest/cart', $data);
    }

    /**
     * destroy() — Kosongkan keranjang belanja
     */
    public function clearCart()
    {
        // Gunakan Cart Library method destroy()
        $this->cart->destroy();

        return redirect()->to('/menu')->with('success', 'Keranjang belanja dikosongkan.');
    }
}