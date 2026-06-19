<?= $this->include('templates/header') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="section-title mb-1"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Admin</h2>
            <p class="text-muted mb-0">Kelola data menu makanan & minuman Burjo</p>
        </div>
        <a href="<?= base_url('/admin/form-menu') ?>" class="btn btn-burjo btn-lg">
            <i class="fas fa-plus me-2"></i> Tambah Menu Baru
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-hamburger fa-3x me-3 opacity-75"></i>
                    <div>
                        <h3 class="mb-0"><?= $total_makanan ?? 0 ?></h3>
                        <small>Total Menu Makanan</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-glass-cheers fa-3x me-3 opacity-75"></i>
                    <div>
                        <h3 class="mb-0"><?= $total_minuman ?? 0 ?></h3>
                        <small>Total Menu Minuman</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-list fa-3x me-3 opacity-75"></i>
                    <div>
                        <h3 class="mb-0"><?= count($menus) ?></h3>
                        <small>Total Semua Menu</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body py-2">
            <form method="get" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama menu..." value="<?= esc($keyword ?? '') ?>">
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-success" type="submit">Cari</button>
                    <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Menu -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Daftar Menu</strong>
            <span class="badge bg-success"><?= count($menus) ?> item</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Menu</th>
                        <th>Jenis</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="180" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($menus)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada menu. Silakan tambahkan menu baru.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($menus as $m): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($m['gambar'])): ?>
                                        <img src="<?= base_url('uploads/menu/' . $m['gambar']) ?>" 
                                             onerror="this.src='https://picsum.photos/id/292/40/40'" 
                                             class="rounded me-3" width="45" height="45" style="object-fit:cover">
                                    <?php else: ?>
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width:45px;height:45px">
                                            <i class="fas fa-<?= $m['jenis']==='makanan'?'hamburger':'glass-cheers' ?> text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <strong><?= esc($m['nama']) ?></strong><br>
                                        <small class="text-muted"><?= esc(substr($m['deskripsi'] ?? '', 0, 45)) ?>...</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge <?= $m['jenis'] === 'makanan' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= ucfirst($m['jenis']) ?>
                                </span>
                            </td>
                            <td class="fw-bold text-success">Rp <?= number_format($m['harga'], 0, ',', '.') ?></td>
                            <td><span class="badge bg-light text-dark"><?= $m['stok'] ?? 0 ?></span></td>
                            <td class="text-center">
                                <a href="<?= base_url('/admin/form-menu/' . $m['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/admin/delete-menu/' . $m['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Yakin ingin menghapus menu <?= esc($m['nama']) ?>?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>