<?= $this->include('templates/header') ?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold text-success">Selamat Datang di <span class="text-warning">BURJO</span></h1>
            <p class="lead">Warung makan & minum legendaris khas Semarang. Nikmati hidangan lezat dengan harga ramah di kantong.</p>
            <div class="d-flex gap-3 mt-4">
                <a href="<?= base_url('/menu') ?>" class="btn btn-burjo btn-lg px-4">
                    <i class="fas fa-list me-2"></i> Lihat Menu
                </a>
                <a href="<?= base_url('/login') ?>" class="btn btn-outline-success btn-lg px-4">
                    <i class="fas fa-user me-2"></i> Login Tamu / Admin
                </a>
            </div>
            <div class="mt-3 text-muted">
                <small><i class="fas fa-map-marker-alt"></i> Jl. Kaliurang, Semarang • Buka 24 Jam</small>
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <img src="https://picsum.photos/id/292/600/400" alt="Burjo Jogja" class="img-fluid rounded-3 shadow" style="max-height: 380px; object-fit: cover;">
        </div>
    </div>

    <!-- Highlight Menu -->
    <h2 class="section-title text-center mb-4">Menu Unggulan Kami</h2>
    
    <div class="row g-4">
        <!-- Makanan Highlight -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-hamburger me-2"></i> Makanan</h5>
                </div>
                <div class="card-body">
                    <?php foreach (array_slice($makanan, 0, 3) as $m): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <strong><?= esc($m['nama']) ?></strong><br>
                            <small class="text-muted"><?= esc(substr($m['deskripsi'], 0, 60)) ?>...</small>
                        </div>
                        <div class="text-end">
                            <span class="price">Rp <?= number_format($m['harga'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <a href="<?= base_url('/menu?jenis=makanan') ?>" class="btn btn-success w-100 mt-2">Lihat Semua Makanan</a>
                </div>
            </div>
        </div>

        <!-- Minuman Highlight -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-glass-cheers me-2"></i> Minuman</h5>
                </div>
                <div class="card-body">
                    <?php foreach (array_slice($minuman, 0, 3) as $m): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <strong><?= esc($m['nama']) ?></strong><br>
                            <small class="text-muted"><?= esc(substr($m['deskripsi'], 0, 60)) ?>...</small>
                        </div>
                        <div class="text-end">
                            <span class="price">Rp <?= number_format($m['harga'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <a href="<?= base_url('/menu?jenis=minuman') ?>" class="btn btn-warning w-100 mt-2 text-dark">Lihat Semua Minuman</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container text-center mt-5 mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body py-3">
            <h6 class="mb-1 text-muted">Dibuat oleh:</h6>
            <p class="mb-0 fw-bold">Gastiadirrijal Rafi Maulana</p>
            <small class="text-muted">NIM: A11.2024.15842 | Kelompok: A11.4404</small>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>