<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-custom p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1">Rekapitulasi Presensi & Kehadiran</h5>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Input dan kelola data hari kerja, sakit, izin, alpa, serta jam lembur</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Filter Form -->
            <form action="<?= base_url('presensi') ?>" method="GET" class="d-flex gap-2">
                <select name="bulan" class="form-select rounded-pill">
                    <?php
                    $months = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    foreach ($months as $num => $name):
                    ?>
                        <option value="<?= $num ?>" <?= $bulan == $num ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="tahun" class="form-select rounded-pill">
                    <option value="2026" <?= $tahun == 2026 ? 'selected' : '' ?>>2026</option>
                    <option value="2025" <?= $tahun == 2025 ? 'selected' : '' ?>>2025</option>
                </select>
                <button type="submit" class="btn btn-outline-primary rounded-pill px-3">Filter</button>
            </form>

            <?php if (session()->get('role') === 'admin'): ?>
                <button class="btn btn-primary rounded-pill px-4 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalAddPresensi">
                    <i class="fa-solid fa-plus me-1"></i> Input Presensi
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-size: 0.875rem;">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>NIP & Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th class="text-center">Hadir (Hari)</th>
                    <th class="text-center">Sakit</th>
                    <th class="text-center">Izin</th>
                    <th class="text-center">Alpa</th>
                    <th class="text-center">Jam Lembur</th>
                    <?php if (session()->get('role') === 'admin'): ?>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($presensiList)): ?>
                    <?php foreach ($presensiList as $idx => $p): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($p['nama']) ?></div>
                                <small class="text-muted" style="font-size: 0.78rem;">NIP: <?= esc($p['nip']) ?></small>
                            </td>
                            <td><span class="badge bg-indigo-subtle text-primary"><?= esc($p['nama_jabatan'] ?? '-') ?></span></td>
                            <td class="text-center fw-bold text-success"><?= $p['jumlah_hadir'] ?></td>
                            <td class="text-center text-warning"><?= $p['jumlah_sakit'] ?></td>
                            <td class="text-center text-info"><?= $p['jumlah_izin'] ?></td>
                            <td class="text-center text-danger fw-bold"><?= $p['jumlah_alpa'] ?></td>
                            <td class="text-center fw-bold text-indigo"><i class="fa-solid fa-clock me-1" style="font-size: 0.8rem;"></i> <?= $p['jumlah_lembur_jam'] ?> Jam</td>
                            <?php if (session()->get('role') === 'admin'): ?>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning rounded-circle" data-bs-toggle="modal" data-bs-target="#modalEditPresensi<?= $p['id'] ?>" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>

                        <?php if (session()->get('role') === 'admin'): ?>
                            <!-- Modal Edit Presensi -->
                            <div class="modal fade" id="modalEditPresensi<?= $p['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Presensi: <?= esc($p['nama']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?= base_url('presensi/store') ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="karyawan_id" value="<?= $p['karyawan_id'] ?>">
                                            <input type="hidden" name="bulan" value="<?= $p['bulan'] ?>">
                                            <input type="hidden" name="tahun" value="<?= $p['tahun'] ?>">

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Jumlah Hadir (Hari Kerja)</label>
                                                    <input type="number" name="jumlah_hadir" class="form-control" value="<?= $p['jumlah_hadir'] ?>" min="0" max="31" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Jumlah Sakit (Hari)</label>
                                                    <input type="number" name="jumlah_sakit" class="form-control" value="<?= $p['jumlah_sakit'] ?>" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Jumlah Izin (Hari)</label>
                                                    <input type="number" name="jumlah_izin" class="form-control" value="<?= $p['jumlah_izin'] ?>" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Jumlah Alpa / Tanpa Keterangan (Hari)</label>
                                                    <input type="number" name="jumlah_alpa" class="form-control" value="<?= $p['jumlah_alpa'] ?>" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Jumlah Jam Lembur</label>
                                                    <input type="number" name="jumlah_lembur_jam" class="form-control" value="<?= $p['jumlah_lembur_jam'] ?>" min="0" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Presensi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada data presensi pada bulan dan tahun ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (session()->get('role') === 'admin'): ?>
    <!-- Modal Add Presensi -->
    <div class="modal fade" id="modalAddPresensi" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Input Data Presensi Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('presensi/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="bulan" value="<?= $bulan ?>">
                    <input type="hidden" name="tahun" value="<?= $tahun ?>">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Karyawan</label>
                            <select name="karyawan_id" class="form-select" required>
                                <option value="">-- Pilih Karyawan --</option>
                                <?php foreach ($karyawanList as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= esc($k['nip']) ?> - <?= esc($k['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Hadir (Hari Kerja)</label>
                            <input type="number" name="jumlah_hadir" class="form-control" value="22" min="0" max="31" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Sakit (Hari)</label>
                            <input type="number" name="jumlah_sakit" class="form-control" value="0" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Izin (Hari)</label>
                            <input type="number" name="jumlah_izin" class="form-control" value="0" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Alpa / Tanpa Keterangan (Hari)</label>
                            <input type="number" name="jumlah_alpa" class="form-control" value="0" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Jam Lembur</label>
                            <input type="number" name="jumlah_lembur_jam" class="form-control" value="0" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Presensi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
