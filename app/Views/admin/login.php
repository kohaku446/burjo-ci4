<?= $this->include('templates/header') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-lock me-2"></i> Login Burjo</h4>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Pilihan Login -->
                    <ul class="nav nav-pills nav-justified mb-4" id="loginTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tamu-tab" data-bs-toggle="pill" data-bs-target="#tamu" type="button">
                                <i class="fas fa-user me-1"></i> Masuk sebagai Tamu
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admin-tab" data-bs-toggle="pill" data-bs-target="#admin" type="button">
                                <i class="fas fa-user-shield me-1"></i> Login Admin
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Form Tamu -->
<div class="tab-pane fade show active" id="tamu">
    <form action="<?= base_url('/login/attempt') ?>" method="post">
        <input type="hidden" name="login_as" value="tamu">
        
        <div class="mb-3">
            <label class="form-label">Nama Anda (opsional)</label>
            <input type="text" name="tamu_name" class="form-control" placeholder="Contoh: Budi Santoso" value="Tamu">
        </div>

        <!-- TAMBAHAN: Nomor Meja -->
        <div class="mb-3">
            <label class="form-label">Nomor Meja <span class="text-danger">*</span></label>
            <input type="number" name="nomor_meja" class="form-control" placeholder="Contoh: 5" min="1" required>
            <small class="text-muted">Masukkan nomor meja kamu</small>
        </div>

        <button type="submit" class="btn btn-warning w-100 py-2 fw-bold">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk sebagai Tamu
        </button>
    </form>
</div>

                        <!-- Form Admin -->
                        <div class="tab-pane fade" id="admin">
                            <form action="<?= base_url('/login/attempt') ?>" method="post">
                                <input type="hidden" name="login_as" value="admin">
                                <div class="mb-3">
                                    <label class="form-label">Username Admin</label>
                                    <input type="text" name="username" class="form-control" placeholder="admin" value="<?= old('username') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="admin123" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                                    <i class="fas fa-shield-alt me-2"></i> Login sebagai Admin
                                </button>
                                <div class="text-center mt-3">
                                    <small class="text-muted">Default: <strong>admin</strong> / <strong>admin123</strong></small>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <a href="<?= base_url('/') ?>" class="text-decoration-none">&larr; Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>