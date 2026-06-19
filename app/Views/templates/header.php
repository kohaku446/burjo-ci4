<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Burjo') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --burjo-green: #2e7d32;
            --burjo-orange: #ff6f00;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar-burjo { background-color: var(--burjo-green); }
        .btn-burjo { background-color: var(--burjo-orange); color: white; border: none; }
        .btn-burjo:hover { background-color: #e65100; color: white; }
        .card-menu { transition: transform 0.2s, box-shadow 0.2s; }
        .card-menu:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .menu-img { height: 160px; object-fit: cover; background: #f8f9fa; }
        .section-title { color: var(--burjo-green); font-weight: 700; }
        .price { color: var(--burjo-orange); font-weight: 600; font-size: 1.1rem; }
        .admin-sidebar { background: #1b5e20; min-height: 100vh; }
        .flash-message { animation: fadeIn 0.5s; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-burjo sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= base_url('/') ?>">
                <i class="fas fa-utensils me-2"></i> BURJO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/menu') ?>">Daftar Menu</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <span class="text-white me-3">
                            <i class="fas fa-user-circle"></i> 
                            <?= esc(session()->get('nama_lengkap') ?? session()->get('username')) ?>
                            <small class="badge bg-light text-dark ms-1"><?= ucfirst(session()->get('role')) ?></small>
                        </span>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
                        <?php endif; ?>
                        <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light btn-sm">Logout</a>
                    <?php else: ?>
                        <a href="<?= base_url('/login') ?>" class="btn btn-burjo btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show flash-message" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show flash-message" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>
