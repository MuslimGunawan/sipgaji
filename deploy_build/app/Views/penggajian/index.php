<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-custom p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1">Perhitungan Gaji Otomatis & Rekapitulasi</h5>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Kalkulasi gaji berbasis algoritma matematika (Tunjangan, Lembur, BPJS, PPh 21, & Potongan Alpa)</p>
        </div>
        <div class="d-flex flex-column flex-sm-row header-action-group gap-2">
            <!-- Filter & Search Form -->
            <form action="<?= base_url('penggajian') ?>" method="GET" class="d-flex flex-wrap gap-2">
                <div class="input-group flex-nowrap" style="max-width: 200px;">
                    <input type="text" name="search" id="quickSearchInput" class="form-control rounded-start-pill border-end-0" placeholder="Cari nama/NIP..." value="<?= esc($search ?? '') ?>">
                    <button class="btn btn-outline-secondary rounded-end-pill border-start-0" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
                <select name="bulan" class="form-select rounded-pill" style="width: 110px;">
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
                <select name="tahun" class="form-select rounded-pill" style="width: 90px;">
                    <option value="2026" <?= $tahun == 2026 ? 'selected' : '' ?>>2026</option>
                    <option value="2025" <?= $tahun == 2025 ? 'selected' : '' ?>>2025</option>
                </select>
                <button type="submit" class="btn btn-outline-primary rounded-pill px-3">Filter</button>
            </form>

            <?php if (session()->get('role') === 'admin'): ?>
                <form action="<?= base_url('penggajian/hitung') ?>" method="POST" onsubmit="return confirm('Jalankan kalkulasi gaji otomatis untuk SELURUH KARYAWAN pada Bulan <?= $bulan ?> Tahun <?= $tahun ?>?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="bulan" value="<?= $bulan ?>">
                    <input type="hidden" name="tahun" value="<?= $tahun ?>">
                    <button type="submit" class="btn btn-success rounded-pill px-4 text-nowrap w-100">
                        <i class="fa-solid fa-calculator me-1"></i> Hitung Gaji Otomatis
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-size: 0.825rem;">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode & Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Gaji Pokok</th>
                    <th>Tunjangan</th>
                    <th>Bonus Lembur</th>
                    <th>Total Gross</th>
                    <th class="text-danger">Total Potongan</th>
                    <th class="text-success">Gaji Bersih</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($penggajianList)): ?>
                    <?php foreach ($penggajianList as $idx => $g): ?>
                        <?php 
                            $totalTunjangan = $g['tunj_jabatan'] + $g['tunj_kehadiran'] + $g['tunj_keluarga'];
                        ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($g['nama']) ?></div>
                                <small class="text-muted" style="font-size: 0.75rem;"><?= esc($g['kode_transaksi']) ?></small>
                            </td>
                            <td><span class="badge bg-indigo-subtle text-primary"><?= esc($g['nama_jabatan'] ?? '-') ?></span></td>
                            <td>Rp <?= number_format($g['gaji_pokok'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($totalTunjangan, 0, ',', '.') ?></td>
                            <td class="text-indigo fw-semibold">+Rp <?= number_format($g['bonus_lembur'], 0, ',', '.') ?></td>
                            <td class="fw-semibold">Rp <?= number_format($g['total_pendapatan'], 0, ',', '.') ?></td>
                            <td class="text-danger fw-semibold">-Rp <?= number_format($g['total_potongan'], 0, ',', '.') ?></td>
                            <td class="fw-bold text-success" style="font-size: 0.9rem;">Rp <?= number_format($g['gaji_bersih'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-success-subtle text-success"><?= esc($g['status_bayar']) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group gap-1">
                                    <a href="<?= base_url('penggajian/slip/' . $g['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Lihat & Cetak Slip Gaji">
                                        <i class="fa-solid fa-print me-1"></i> Slip
                                    </a>
                                    <?php if (! empty($g['foto_bukti_transfer'])): ?>
                                        <a href="<?= base_url('uploads/bukti/' . $g['foto_bukti_transfer']) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3" title="Lihat Bukti Transfer Pembayaran">
                                            <i class="fa-solid fa-receipt me-1"></i> Bukti Bayar
                                        </a>
                                    <?php endif; ?>
                                    <?php if (session()->get('role') === 'admin'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-success rounded-circle ms-1" data-bs-toggle="modal" data-bs-target="#modalBukti<?= $g['id'] ?>" title="Upload / Edit Bukti Transfer">
                                            <i class="fa-solid fa-upload"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">Belum ada data penggajian untuk periode ini. Tekan tombol "Hitung Gaji Otomatis" untuk melakukan kalkulasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modals outside table -->
<?php if (session()->get('role') === 'admin' && ! empty($penggajianList)): ?>
    <?php foreach ($penggajianList as $g): ?>
        <div class="modal fade" id="modalBukti<?= $g['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded-4 border-0">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Upload Bukti Transfer Gaji</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url('penggajian/upload-bukti/' . $g['id']) ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Penerima: <?= esc($g['nama']) ?></label>
                                <div class="text-success fw-bold">Total Pembayaran: Rp <?= number_format($g['gaji_bersih'], 0, ',', '.') ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">File Bukti Transfer (JPG, PNG, atau PDF - Max 3MB)</label>
                                <input type="file" name="bukti" class="form-control" accept="image/*,application/pdf" required>
                            </div>
                            <?php if ($g['foto_bukti_transfer']): ?>
                                <div class="alert alert-info py-2" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-file me-1"></i> Bukti saat ini: <a href="<?= base_url('uploads/bukti/' . $g['foto_bukti_transfer']) ?>" target="_blank">Lihat Bukti</a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success rounded-pill px-4">Upload & Set Lunas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("quickSearchInput");
    if (searchInput) {
        searchInput.addEventListener("keyup", function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll("table tbody tr");
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (query === "" || text.includes(query)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }
});
</script>

<?= $this->endSection() ?>
