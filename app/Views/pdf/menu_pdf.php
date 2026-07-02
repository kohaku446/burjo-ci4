<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Menu Burjo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            background: #fff;
        }

        /* ── Header ── */
        .pdf-header {
            background: #1a7a3e;
            color: #fff;
            padding: 20px 30px;
            margin-bottom: 20px;
        }
        .pdf-header h1 {
            font-size: 22px;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .pdf-header p {
            font-size: 11px;
            opacity: 0.85;
        }
        .pdf-header .generated {
            float: right;
            text-align: right;
            font-size: 10px;
            margin-top: -40px;
        }

        /* ── Info Box ── */
        .info-row {
            padding: 0 30px;
            margin-bottom: 16px;
            display: table;
            width: 100%;
        }
        .info-box {
            display: inline-block;
            background: #f0faf5;
            border: 1px solid #a8d8bc;
            border-radius: 4px;
            padding: 8px 16px;
            margin-right: 10px;
            font-size: 11px;
        }
        .info-box strong { color: #1a7a3e; }

        /* ── Section Title ── */
        .section-title {
            padding: 8px 30px;
            background: #f7f7f7;
            border-left: 4px solid #1a7a3e;
            font-size: 13px;
            font-weight: bold;
            color: #1a7a3e;
            margin-bottom: 8px;
        }

        /* ── Table ── */
        .menu-table {
            width: calc(100% - 60px);
            margin: 0 30px 20px;
            border-collapse: collapse;
            font-size: 11px;
        }
        .menu-table thead tr {
            background: #1a7a3e;
            color: #fff;
        }
        .menu-table thead th {
            padding: 9px 10px;
            text-align: left;
        }
        .menu-table tbody tr:nth-child(even) {
            background: #f5faf7;
        }
        .menu-table tbody tr:hover {
            background: #e8f5ed;
        }
        .menu-table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }
        .menu-table .harga {
            text-align: right;
            font-weight: bold;
            color: #1a7a3e;
            white-space: nowrap;
        }
        .menu-table .stok {
            text-align: center;
        }
        .menu-table .no {
            text-align: center;
            color: #888;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-makanan { background: #d4edda; color: #155724; }
        .badge-minuman { background: #fff3cd; color: #856404; }
        .badge-stok-ok { background: #cce5ff; color: #004085; }
        .badge-stok-low { background: #f8d7da; color: #721c24; }

        /* ── Footer ── */
        .pdf-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px 30px;
            background: #f7f7f7;
            border-top: 2px solid #1a7a3e;
            font-size: 10px;
            color: #666;
        }
        .pdf-footer .page-num { float: right; }

        /* Total row */
        .total-row {
            margin: 0 30px 20px;
            text-align: right;
            font-size: 12px;
            color: #333;
        }
        .total-row strong { color: #1a7a3e; font-size: 14px; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="pdf-header">
        <h1>🍽️ Daftar Menu Burjo</h1>
        <p>Warung Makan & Minum Khas Jogja — Nikmat, Murah, dan Mengenyangkan</p>
        <div class="generated">
            Dicetak: <?= date('d/m/Y H:i') ?> WIB<br>
            Oleh: <?= esc(session()->get('nama_lengkap') ?? 'Admin') ?>
        </div>
    </div>

    <!-- Info Ringkasan -->
    <div style="padding: 0 30px; margin-bottom: 16px;">
        <span class="info-box">Total Menu: <strong><?= count($menus) ?></strong></span>
        <span class="info-box">Makanan: <strong><?= count(array_filter($menus, fn($m) => $m['jenis'] === 'makanan')) ?></strong></span>
        <span class="info-box">Minuman: <strong><?= count(array_filter($menus, fn($m) => $m['jenis'] === 'minuman')) ?></strong></span>
        <span class="info-box">Periode: <strong><?= date('F Y') ?></strong></span>
    </div>

    <?php
    $makanan = array_filter($menus, fn($m) => $m['jenis'] === 'makanan');
    $minuman = array_filter($menus, fn($m) => $m['jenis'] === 'minuman');

    $renderTable = function($items, $title) {
        if (empty($items)) return;
        $no = 1;
    ?>

    <!-- Section Title -->
    <div class="section-title"><?= $title ?></div>

    <table class="menu-table">
        <thead>
            <tr>
                <th style="width:35px">No</th>
                <th>Nama Menu</th>
                <th>Deskripsi</th>
                <th style="width:90px">Harga</th>
                <th style="width:50px">Stok</th>
                <th style="width:70px">Jenis</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td class="no"><?= $no++ ?></td>
                <td><strong><?= esc($item['nama']) ?></strong></td>
                <td style="color:#555"><?= esc($item['deskripsi']) ?></td>
                <td class="harga">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td class="stok">
                    <span class="badge <?= ($item['stok'] > 5) ? 'badge-stok-ok' : 'badge-stok-low' ?>">
                        <?= $item['stok'] ?>
                    </span>
                </td>
                <td>
                    <span class="badge <?= $item['jenis'] === 'makanan' ? 'badge-makanan' : 'badge-minuman' ?>">
                        <?= ucfirst($item['jenis']) ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    }; // end renderTable
    ?>

    <?php $renderTable($makanan, '🍽️ Menu Makanan'); ?>
    <?php $renderTable($minuman, '🥤 Menu Minuman'); ?>

    <!-- Footer -->
    <div class="pdf-footer">
        <span>Burjo — Dokumen ini dibuat otomatis oleh sistem</span>
        <span class="page-num">Halaman <span class="pagenum"></span></span>
    </div>

</body>
</html>
