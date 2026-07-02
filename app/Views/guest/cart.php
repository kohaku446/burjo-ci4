<?= $this->include('templates/header') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0"><i class="fas fa-shopping-cart me-2"></i> Keranjang Pesanan</h2>
        <?php if (!empty($cart)): ?>
            <span class="badge bg-success fs-6"><?= count($cart) ?> item</span>
        <?php endif; ?>
    </div>

    <?php // Flash messages ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-3x mb-3 d-block"></i>
            <h4>Keranjang Anda kosong</h4>
            <p class="text-muted">Mulai pesan menu favorit Anda!</p>
            <a href="<?= base_url('/menu') ?>" class="btn btn-burjo mt-2">
                <i class="fas fa-utensils me-2"></i> Lihat Menu
            </a>
        </div>
    <?php else: ?>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-success">
                        <tr>
                            <th style="width:40%">Menu</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center" style="width:160px">
                                <?php // SOAL 4 — update() — Kolom Quantity ?>
                                Quantity
                            </th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center" style="width:80px">
                                <?php // SOAL 4 — remove() — Kolom Hapus ?>
                                Hapus
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                        <tr>
                            <td>
                                <strong><?= esc($item['nama']) ?></strong><br>
                                <small class="text-muted">
                                    <span class="badge <?= $item['jenis'] === 'makanan' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <?= ucfirst($item['jenis']) ?>
                                    </span>
                                </small>
                            </td>
                            <td class="text-end">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>

                            <?php // SOAL 4 — update() — Form update quantity per item ?>
                            <td class="text-center">
                                <form action="<?= base_url('/cart/update') ?>" method="post"
                                      class="d-flex align-items-center justify-content-center gap-1"
                                      id="form-update-<?= $item['id'] ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="button" class="btn btn-sm btn-outline-secondary px-2"
                                            onclick="changeQty(<?= $item['id'] ?>, -1)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" name="qty" id="qty-<?= $item['id'] ?>"
                                           value="<?= $item['qty'] ?>" min="0" max="99"
                                           class="form-control form-control-sm text-center"
                                           style="width:55px"
                                           onchange="document.getElementById('form-update-<?= $item['id'] ?>').submit()">
                                    <button type="button" class="btn btn-sm btn-outline-secondary px-2"
                                            onclick="changeQty(<?= $item['id'] ?>, 1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </form>
                            </td>

                            <td class="text-end fw-bold text-success">
                                Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                            </td>

                            <?php // SOAL 4 — remove() — Tombol hapus item ?>
                            <td class="text-center">
                                <a href="<?= base_url('/cart/remove/' . $item['id']) ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Hapus <?= esc($item['nama']) ?> dari keranjang?')"
                                   title="Hapus item">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                    <?php // SOAL 4 — total() — Tampilkan total dari Cart Library ?>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3" class="text-end fs-6">Total Pembayaran:</th>
                            <th class="text-end text-success fs-5">
                                Rp <?= number_format($total, 0, ',', '.') ?>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between mt-4 flex-wrap gap-2">
            <a href="<?= base_url('/menu') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Menu
            </a>
            <div class="d-flex gap-2">
                <?php // SOAL 4 — destroy() — Kosongkan seluruh keranjang ?>
                <a href="<?= base_url('/clear-cart') ?>"
                   class="btn btn-outline-danger"
                   onclick="return confirm('Kosongkan semua isi keranjang?')">
                    <i class="fas fa-trash me-1"></i> Kosongkan Keranjang
                </a>
                <button class="btn btn-burjo"
                        onclick="alert('Terima kasih! Pesanan Anda akan segera diproses.\n\nTotal: Rp <?= number_format($total, 0, ',', '.') ?>'); window.location.href='<?= base_url('/menu') ?>'">
                    <i class="fas fa-check me-2"></i> Checkout / Bayar
                </button>
            </div>
        </div>

    <?php endif; ?>
</div>

<script>
/**
 * Fungsi untuk menambah/mengurangi qty dan auto-submit form
 * Mendukung SOAL 4 — update() dari Cart Library
 */
function changeQty(itemId, delta) {
    const input = document.getElementById('qty-' + itemId);
    let newQty = parseInt(input.value) + delta;
    if (newQty < 0) newQty = 0;
    input.value = newQty;
    document.getElementById('form-update-' + itemId).submit();
}
</script>

<?= $this->include('templates/footer') ?>