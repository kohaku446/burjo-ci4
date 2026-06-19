<?= $this->include('templates/header') ?>

<div class="container py-4">
    <h2 class="section-title mb-4"><i class="fas fa-shopping-cart me-2"></i> Keranjang Pesanan</h2>

    <?php if (empty($cart)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
            <h4>Keranjang Anda kosong</h4>
            <p>Mulai pesan menu favorit Anda!</p>
            <a href="<?= base_url('/menu') ?>" class="btn btn-burjo mt-2">Lihat Menu</a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-success">
                        <tr>
                            <th>Menu</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($cart as $item): ?>
                            <?php $subtotal = $item['harga'] * ($item['qty'] ?? 1); $total += $subtotal; ?>
                            <tr>
                                <td>
                                    <strong><?= esc($item['nama']) ?></strong><br>
                                    <small class="text-muted"><?= esc($item['jenis']) ?></small>
                                </td>
                                <td class="text-end">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td class="text-center"><?= $item['qty'] ?? 1 ?></td>
                                <td class="text-end fw-bold">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                <td class="text-end">
                                    <a href="<?= base_url('/menu') ?>" class="btn btn-sm btn-outline-danger">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3" class="text-end">Total Pembayaran</th>
                            <th class="text-end text-success fs-5">Rp <?= number_format($total, 0, ',', '.') ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="<?= base_url('/menu') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Menu
            </a>
            <div>
                <a href="<?= base_url('/clear-cart') ?>" class="btn btn-outline-danger me-2">Kosongkan Keranjang</a>
                <button class="btn btn-burjo" onclick="alert('Terima kasih! Pesanan Anda akan segera diproses (simulasi).'); window.location.href='<?= base_url('/menu') ?>'">
                    <i class="fas fa-check me-2"></i> Checkout / Bayar
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('templates/footer') ?>