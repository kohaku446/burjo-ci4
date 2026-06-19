<?= $this->include('templates/header') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="section-title mb-1"><i class="fas fa-book-open me-2"></i> Daftar Menu Burjo</h2>
            <p class="text-muted mb-0">Pilih makanan atau minuman favorit Anda</p>
        </div>
        <a href="<?= base_url('/cart') ?>" class="btn btn-burjo position-relative">
            <i class="fas fa-shopping-cart me-2"></i> Keranjang
            <?php $cartCount = count(session()->get('cart') ?? []); ?>
            <?php if ($cartCount > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= $cartCount ?>
                </span>
            <?php endif; ?>
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="get" action="<?= base_url('/menu') ?>" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">Filter Jenis</label>
                    <select name="jenis" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Menu</option>
                        <option value="makanan" <?= ($current_jenis ?? '') === 'makanan' ? 'selected' : '' ?>>🍽️ Makanan</option>
                        <option value="minuman" <?= ($current_jenis ?? '') === 'minuman' ? 'selected' : '' ?>>🥤 Minuman</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small">Cari Menu</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau deskripsi..." value="<?= esc($keyword ?? '') ?>">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-fill"><i class="fas fa-search me-1"></i> Cari</button>
                    <a href="<?= base_url('/menu') ?>" class="btn btn-outline-secondary"><i class="fas fa-sync"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Menu Cards -->
    <?php if (empty($menus)): ?>
        <div class="alert alert-warning text-center py-5">
            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
            <h5>Tidak ada menu ditemukan</h5>
            <p>Coba kata kunci lain atau hapus filter.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($menus as $menu): ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card card-menu h-100 shadow-sm border-0">
                    <?php if (!empty($menu['gambar'])): ?>
                        <?php if (filter_var($menu['gambar'], FILTER_VALIDATE_URL)): ?>
                            <img src="<?= esc($menu['gambar']) ?>" class="card-img-top menu-img" alt="<?= esc($menu['nama']) ?>">
                        <?php else: ?>
                            <img src="<?= base_url('uploads/menu/' . $menu['gambar']) ?>" class="card-img-top menu-img" alt="<?= esc($menu['nama']) ?>" onerror="this.src='https://picsum.photos/id/292/300/160'">
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="menu-img d-flex align-items-center justify-content-center bg-light">
                            <i class="fas fa-<?= $menu['jenis'] === 'makanan' ? 'hamburger' : 'glass-cheers' ?> fa-4x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge <?= $menu['jenis'] === 'makanan' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= ucfirst($menu['jenis']) ?>
                            </span>
                            <span class="badge bg-light text-dark">Stok: <?= $menu['stok'] ?? 0 ?></span>
                        </div>
                        
                        <h5 class="card-title fw-bold"><?= esc($menu['nama']) ?></h5>
                        <p class="card-text text-muted small flex-grow-1"><?= esc($menu['deskripsi']) ?></p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></span>
                                <?php if (session()->get('isLoggedIn') && session()->get('role') === 'tamu'): ?>
                                    <a href="<?= base_url('/add-to-cart/' . $menu['id']) ?>" class="btn btn-sm btn-burjo">
                                        <i class="fas fa-plus me-1"></i> Pesan
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('/login') ?>" class="btn btn-sm btn-outline-secondary">Login untuk Pesan</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('templates/footer') ?>