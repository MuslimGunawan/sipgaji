<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-custom p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1">Data Jabatan & Skema Gaji</h5>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manajemen gaji pokok dan tunjangan dasar per jabatan</p>
        </div>
        <div class="d-flex flex-column flex-sm-row header-action-group gap-2">
            <button class="btn btn-primary rounded-pill px-4 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalAddJabatan">
                <i class="fa-solid fa-plus me-1"></i> Tambah Jabatan
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-size: 0.875rem;">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Jabatan</th>
                    <th>Gaji Pokok</th>
                    <th>Tunj. Jabatan</th>
                    <th>Uang Makan / Hari</th>
                    <th>Transport / Hari</th>
                    <th class="text-center" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($jabatan)): ?>
                    <?php foreach ($jabatan as $idx => $j): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td class="fw-bold text-dark"><?= esc($j['nama_jabatan']) ?></td>
                            <td class="fw-semibold text-primary">Rp <?= number_format($j['gaji_pokok'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($j['tunj_jabatan'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($j['tunj_makan_per_hari'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($j['tunj_transport_per_hari'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-warning rounded-circle" data-bs-toggle="modal" data-bs-target="#modalEditJabatan<?= $j['id'] ?>" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="<?= base_url('jabatan/delete/' . $j['id']) ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Yakin ingin menghapus data jabatan ini?')" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data jabatan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modals outside table -->
<?php if (! empty($jabatan)): ?>
    <?php foreach ($jabatan as $j): ?>
        <div class="modal fade" id="modalEditJabatan<?= $j['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded-4 border-0">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Edit Data Jabatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url('jabatan/update/' . $j['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Jabatan</label>
                                <input type="text" name="nama_jabatan" class="form-control" value="<?= esc($j['nama_jabatan']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Gaji Pokok (Rp)</label>
                                <input type="number" step="0.01" name="gaji_pokok" class="form-control" value="<?= $j['gaji_pokok'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tunjangan Jabatan (Rp)</label>
                                <input type="number" step="0.01" name="tunj_jabatan" class="form-control" value="<?= $j['tunj_jabatan'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tunjangan Makan Per Hari (Rp)</label>
                                <input type="number" step="0.01" name="tunj_makan_per_hari" class="form-control" value="<?= $j['tunj_makan_per_hari'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tunjangan Transport Per Hari (Rp)</label>
                                <input type="number" step="0.01" name="tunj_transport_per_hari" class="form-control" value="<?= $j['tunj_transport_per_hari'] ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Modal Add Jabatan -->
<div class="modal fade" id="modalAddJabatan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Jabatan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('jabatan/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Jabatan</label>
                        <input type="text" name="nama_jabatan" class="form-control" placeholder="Contoh: Senior Developer" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gaji Pokok (Rp)</label>
                        <input type="number" step="0.01" name="gaji_pokok" class="form-control" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tunjangan Jabatan (Rp)</label>
                        <input type="number" step="0.01" name="tunj_jabatan" class="form-control" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tunjangan Makan Per Hari (Rp)</label>
                        <input type="number" step="0.01" name="tunj_makan_per_hari" class="form-control" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tunjangan Transport Per Hari (Rp)</label>
                        <input type="number" step="0.01" name="tunj_transport_per_hari" class="form-control" placeholder="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Jabatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
