<?= $this->include('templates/header') ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-<?= $is_edit ? 'edit' : 'plus' ?> me-2"></i> <?= $title ?></h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('/admin/save-menu') ?>" method="post" enctype="multipart/form-data">
                        <?php if ($is_edit && isset($menu['id'])): ?>
                            <input type="hidden" name="id" value="<?= $menu['id'] ?>">
                        <?php endif; ?>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="<?= old('nama', $menu['nama'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Menu <span class="text-danger">*</span></label>
                                <select name="jenis" class="form-select" required>
                                    <option value="makanan" <?= (isset($menu['jenis']) && $menu['jenis']==='makanan') || old('jenis')==='makanan' ? 'selected' : '' ?>>Makanan</option>
                                    <option value="minuman" <?= (isset($menu['jenis']) && $menu['jenis']==='minuman') || old('jenis')==='minuman' ? 'selected' : '' ?>>Minuman</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control" step="100" min="0" value="<?= old('harga', $menu['harga'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" min="0" value="<?= old('stok', $menu['stok'] ?? 10) ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi Menu</label>
                                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat menu..."><?= old('deskripsi', $menu['deskripsi'] ?? '') ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Gambar Menu (opsional)</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                                <?php if ($is_edit && !empty($menu['gambar'])): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Gambar saat ini:</small><br>
                                        <img src="<?= base_url('uploads/menu/' . $menu['gambar']) ?>" width="120" class="rounded border" onerror="this.style.display='none'">
                                    </div>
                                <?php endif; ?>
                                <small class="text-muted">Format: JPG, PNG. Maks 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-outline-secondary flex-fill">Batal</a>
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="fas fa-save me-2"></i> <?= $is_edit ? 'Update Menu' : 'Simpan Menu Baru' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>