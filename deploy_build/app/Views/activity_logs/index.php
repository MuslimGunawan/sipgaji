<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-custom p-4">
    <!-- Header & Action Controls -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Log Aktivitas System (Audit Log)</h5>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Pemantauan rekam jejak perilaku & kegiatan seluruh pengguna sistem secara real-time</p>
        </div>

        <div class="d-flex flex-column flex-sm-row header-action-group gap-2">
            <!-- Search & Filter Form -->
            <form action="<?= base_url('activity-logs') ?>" method="GET" class="d-flex flex-column flex-sm-row gap-2 w-100">
                <select name="action" class="form-select rounded-pill" onchange="this.form.submit()">
                    <option value="">-- Semua Aksi --</option>
                    <option value="LOGIN" <?= ($actionFilter === 'LOGIN') ? 'selected' : '' ?>>LOGIN</option>
                    <option value="LOGOUT" <?= ($actionFilter === 'LOGOUT') ? 'selected' : '' ?>>LOGOUT</option>
                    <option value="HITUNG_GAJI" <?= ($actionFilter === 'HITUNG_GAJI') ? 'selected' : '' ?>>HITUNG GAJI</option>
                    <option value="INPUT_PRESENSI" <?= ($actionFilter === 'INPUT_PRESENSI') ? 'selected' : '' ?>>INPUT PRESENSI</option>
                    <option value="TAMBAH_KARYAWAN" <?= ($actionFilter === 'TAMBAH_KARYAWAN') ? 'selected' : '' ?>>TAMBAH KARYAWAN</option>
                    <option value="EDIT_KARYAWAN" <?= ($actionFilter === 'EDIT_KARYAWAN') ? 'selected' : '' ?>>EDIT KARYAWAN</option>
                    <option value="EDIT_PROFIL" <?= ($actionFilter === 'EDIT_PROFIL') ? 'selected' : '' ?>>EDIT PROFIL</option>
                    <option value="HAPUS_KARYAWAN" <?= ($actionFilter === 'HAPUS_KARYAWAN') ? 'selected' : '' ?>>HAPUS KARYAWAN</option>
                </select>

                <div class="input-group w-100 flex-nowrap">
                    <input type="text" name="search" class="form-control rounded-start-pill border-end-0" placeholder="Cari user/deskripsi..." value="<?= esc($search ?? '') ?>">
                    <button class="btn btn-outline-secondary rounded-end-pill border-start-0" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>

            <form action="<?= base_url('activity-logs/clear') ?>" method="POST" class="form-confirm-action" data-confirm-title="Bersihkan Histori Log?" data-confirm-text="Apakah Anda yakin ingin menghapus seluruh data histori log aktivitas?" data-confirm-button="Ya, Bersihkan Log">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-danger rounded-pill px-3 text-nowrap w-100">
                    <i class="fa-solid fa-trash-can me-1"></i> Bersihkan Log
                </button>
            </form>
        </div>
    </div>

    <!-- Activity Log Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-size: 0.875rem;">
            <thead class="table-light">
                <tr>
                    <th style="width: 170px;">Waktu & Tanggal</th>
                    <th style="width: 160px;">Pengguna</th>
                    <th style="width: 140px;">Jenis Aksi</th>
                    <th>Deskripsi Kegiatan</th>
                    <th style="width: 130px;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-muted" style="font-size: 0.8rem;">
                                <i class="fa-regular fa-clock me-1"></i>
                                <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><i class="fa-solid fa-circle-user me-1 text-secondary"></i> <?= esc($log['username']) ?></div>
                                <small class="badge <?= $log['role'] === 'admin' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' ?> px-2 py-0" style="font-size: 0.7rem; text-transform: uppercase;">
                                    <?= esc($log['role']) ?>
                                </small>
                            </td>
                            <td>
                                <?php
                                $action = strtoupper($log['action']);
                                $badgeClass = 'bg-secondary-subtle text-secondary';
                                if ($action === 'LOGIN') {
                                    $badgeClass = 'bg-success-subtle text-success';
                                } elseif ($action === 'LOGOUT') {
                                    $badgeClass = 'bg-secondary-subtle text-secondary';
                                } elseif (in_array($action, ['HITUNG_GAJI', 'UPLOAD_BUKTI_GAJI'])) {
                                    $badgeClass = 'bg-primary-subtle text-primary';
                                } elseif (str_contains($action, 'TAMBAH') || $action === 'INPUT_PRESENSI') {
                                    $badgeClass = 'bg-info-subtle text-info';
                                } elseif (str_contains($action, 'EDIT')) {
                                    $badgeClass = 'bg-warning-subtle text-warning';
                                } elseif (str_contains($action, 'HAPUS') || str_contains($action, 'CLEAR')) {
                                    $badgeClass = 'bg-danger-subtle text-danger';
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?> fw-bold px-2 py-1">
                                    <?= esc($log['action']) ?>
                                </span>
                            </td>
                            <td class="fw-medium text-dark text-break">
                                <?= esc($log['description']) ?>
                            </td>
                            <td class="text-muted fw-mono" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-network-wired me-1"></i><?= esc($log['ip_address'] ?? '127.0.0.1') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat aktivitas yang tercatat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
