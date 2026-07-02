<?php

namespace App\Libraries;

/**
 * Cart Library
 * 
 * Library keranjang belanja berbasis session untuk CodeIgniter 4.
 * Menyediakan operasi: insert, update, total, remove, destroy.
 * 
 * Penggunaan:
 *   $cart = new \App\Libraries\Cart();
 *   $cart->insert(['id' => 1, 'nama' => 'Nasi Goreng', 'harga' => 15000]);
 *   $cart->update(1, 2);     // set qty item id=1 menjadi 2
 *   $cart->total();          // hitung total harga
 *   $cart->remove(1);        // hapus item id=1
 *   $cart->destroy();        // kosongkan cart
 */
class Cart
{
    /**
     * Nama key yang digunakan di session untuk menyimpan cart
     */
    protected string $sessionKey = 'burjo_cart';

    /**
     * Ambil semua item dari cart
     * 
     * @return array
     */
    public function contents(): array
    {
        return session()->get($this->sessionKey) ?? [];
    }

    /**
     * Tambahkan item ke keranjang belanja.
     * Jika item dengan id yang sama sudah ada, qty akan ditambah.
     * 
     * @param array $item  Harus memiliki key: 'id', 'nama', 'harga'
     *                     Key opsional: 'qty', 'jenis', 'gambar', 'deskripsi'
     * @return bool
     */
    public function insert(array $item): bool
    {
        // Validasi field wajib
        if (empty($item['id']) || empty($item['nama']) || !isset($item['harga'])) {
            return false;
        }

        $cart = $this->contents();
        $qty  = (int) ($item['qty'] ?? 1);
        if ($qty < 1) $qty = 1;

        // Cek apakah item sudah ada di cart
        if (isset($cart[$item['id']])) {
            // Tambah qty jika sudah ada
            $cart[$item['id']]['qty'] += $qty;
        } else {
            // Insert item baru
            $cart[$item['id']] = [
                'id'        => $item['id'],
                'nama'      => $item['nama'],
                'harga'     => (float) $item['harga'],
                'qty'       => $qty,
                'jenis'     => $item['jenis'] ?? '',
                'gambar'    => $item['gambar'] ?? '',
                'deskripsi' => $item['deskripsi'] ?? '',
                'subtotal'  => (float) $item['harga'] * $qty,
            ];
        }

        // Hitung ulang subtotal
        $cart[$item['id']]['subtotal'] = $cart[$item['id']]['harga'] * $cart[$item['id']]['qty'];

        session()->set($this->sessionKey, $cart);

        return true;
    }

    /**
     * Ubah quantity item di keranjang berdasarkan ID.
     * Jika qty diset ke 0 atau negatif, item akan dihapus otomatis.
     * 
     * @param int|string $id   ID item yang akan diubah
     * @param int        $qty  Quantity baru
     * @return bool
     */
    public function update($id, int $qty): bool
    {
        $cart = $this->contents();

        if (!isset($cart[$id])) {
            return false;
        }

        if ($qty <= 0) {
            // Hapus item jika qty 0 atau negatif
            return $this->remove($id);
        }

        $cart[$id]['qty']      = $qty;
        $cart[$id]['subtotal'] = $cart[$id]['harga'] * $qty;

        session()->set($this->sessionKey, $cart);

        return true;
    }

    /**
     * Hapus satu item dari keranjang berdasarkan ID.
     * 
     * @param int|string $id  ID item yang akan dihapus
     * @return bool
     */
    public function remove($id): bool
    {
        $cart = $this->contents();

        if (!isset($cart[$id])) {
            return false;
        }

        unset($cart[$id]);
        session()->set($this->sessionKey, $cart);

        return true;
    }

    /**
     * Kosongkan seluruh isi keranjang belanja.
     * 
     * @return void
     */
    public function destroy(): void
    {
        session()->remove($this->sessionKey);
    }

    /**
     * Hitung total harga seluruh item di keranjang.
     * 
     * @return float
     */
    public function total(): float
    {
        $cart  = $this->contents();
        $total = 0.0;

        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return $total;
    }

    /**
     * Hitung jumlah total item (bukan unique item, tapi total qty).
     * 
     * @return int
     */
    public function count(): int
    {
        $cart  = $this->contents();
        $count = 0;

        foreach ($cart as $item) {
            $count += $item['qty'];
        }

        return $count;
    }

    /**
     * Hitung jumlah unique item (row) di cart.
     * 
     * @return int
     */
    public function countUnique(): int
    {
        return count($this->contents());
    }

    /**
     * Cek apakah cart kosong.
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->contents());
    }

    /**
     * Ambil satu item berdasarkan ID.
     * 
     * @param int|string $id
     * @return array|null
     */
    public function get($id): ?array
    {
        $cart = $this->contents();
        return $cart[$id] ?? null;
    }

    /**
     * Format total harga dalam format Rupiah.
     * 
     * @return string
     */
    public function totalFormatted(): string
    {
        return 'Rp ' . number_format($this->total(), 0, ',', '.');
    }
}
