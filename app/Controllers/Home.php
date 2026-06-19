<?php

namespace App\Controllers;

use App\Models\MenuModel;

class Home extends BaseController
{
    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Burjo - Warung Makan & Minum Khas Jogja',
            'makanan' => $this->menuModel->getMenuByJenis('makanan'),
            'minuman' => $this->menuModel->getMenuByJenis('minuman'),
        ];

        return view('guest/home', $data);
    }

    public function menu()
    {
        $jenis = $this->request->getGet('jenis');
        $keyword = $this->request->getGet('search');

        if ($keyword) {
            $menus = $this->menuModel->searchMenu($keyword);
        } elseif ($jenis) {
            $menus = $this->menuModel->getMenuByJenis($jenis);
        } else {
            $menus = $this->menuModel->findAll();
        }

        $data = [
            'title' => 'Daftar Menu Burjo',
            'menus' => $menus,
            'current_jenis' => $jenis,
            'keyword' => $keyword,
        ];

        return view('guest/menu', $data);
    }

    public function addToCart($id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan');
        }

        $cart = session()->get('cart') ?? [];

        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $id) {
                $item['qty'] = ($item['qty'] ?? 1) + 1;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $menu['qty'] = 1;
            $cart[] = $menu;
        }

        session()->set('cart', $cart);

        return redirect()->back()->with('success', $menu['nama'] . ' ditambahkan ke keranjang!');
    }

    public function cart()
    {
        $data = [
            'title' => 'Keranjang Pesanan',
            'cart' => session()->get('cart') ?? [],
        ];
        return view('guest/cart', $data);
    }

    public function clearCart()
    {
        session()->remove('cart');
        return redirect()->to('/menu')->with('success', 'Keranjang dikosongkan');
    }
}