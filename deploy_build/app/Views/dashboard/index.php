<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php if (session()->get('role') === 'admin'): ?>
    <!-- Admin Dashboard View -->
    <div class="row g-4 mb-4">
        <!-- Stat Card 1 -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card bg-gradient-indigo card-custom">
                <div class="stat-title text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem;">Total Karyawan</div>
                <h3 class="fw-bold my-2"><?= number_format($totalKaryawan) ?> <span style="font-size: 0.85rem;" class="fw-normal">Orang</span></h3>
                <div class="text-white-50" style="font-size: 0.75rem;"><i class="fa-solid fa-users me-1"></i> Terdaftar aktif</div>
                <i class="fa-solid fa-user-group stat-icon"></i>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card bg-gradient-emerald card-custom">
                <div class="stat-title text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem;">Master Jabatan</div>
                <h3 class="fw-bold my-2"><?= number_format($totalJabatan) ?> <span style="font-size: 0.85rem;" class="fw-normal">Posisi</span></h3>
                <div class="text-white-50" style="font-size: 0.75rem;"><i class="fa-solid fa-sitemap me-1"></i> Skema struktur gaji</div>
                <i class="fa-solid fa-briefcase stat-icon"></i>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card bg-gradient-amber card-custom">
                <div class="stat-title text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem;">Presensi Bulan Ini</div>
                <h3 class="fw-bold my-2"><?= number_format($totalPresensi) ?> <span style="font-size: 0.85rem;" class="fw-normal">Entri</span></h3>
                <div class="text-white-50" style="font-size: 0.75rem;"><i class="fa-solid fa-calendar-check me-1"></i> Rekap kehadiran</div>
                <i class="fa-solid fa-clipboard-user stat-icon"></i>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card bg-gradient-rose card-custom">
                <div class="stat-title text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem;">Pengeluaran Gaji Bulan Ini</div>
                <h3 class="fw-bold my-2">Rp <?= number_format($totalGajiBulanIni, 0, ',', '.') ?></h3>
                <div class="text-white-50" style="font-size: 0.75rem;"><i class="fa-solid fa-wallet me-1"></i> Total Gaji Bersih</div>
                <i class="fa-solid fa-money-bill-wave stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- Interactive Charts Section -->
    <div class="row g-4">
        <!-- Chart 1: Bar Chart Pengeluaran Gaji -->
        <div class="col-12 col-lg-8">
            <div class="card card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Tren Pengeluaran Gaji Per Bulan</h6>
                        <small class="text-muted">Total pengeluaran akumulasi gaji bersih</small>
                    </div>
                    <span class="badge bg-indigo-subtle text-primary"><i class="fa-solid fa-chart-line me-1"></i> Real-time</span>
                </div>
                <div style="position: relative; height: 300px;">
                    <canvas id="monthlyPayrollChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart 2: Donut Chart Distribusi Jabatan -->
        <div class="col-12 col-lg-4">
            <div class="card card-custom p-4 h-100">
                <div class="mb-3">
                    <h6 class="fw-bold mb-0 text-dark">Komposisi Karyawan Per Jabatan</h6>
                    <small class="text-muted">Persentase jumlah SDM</small>
                </div>
                <div style="position: relative; height: 280px;" class="d-flex align-items-center justify-content-center">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent System Activity Logs Widget -->
    <div class="card card-custom p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Log Aktivitas System Terkini</h6>
                <small class="text-muted">Rekam jejak tindakan dan perilaku pengguna di aplikasi</small>
            </div>
            <a href="<?= base_url('activity-logs') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                Lihat Semua Log <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 160px;">Waktu</th>
                        <th style="width: 150px;">User</th>
                        <th style="width: 130px;">Aksi</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($recentLogs)): ?>
                        <?php foreach ($recentLogs as $log): ?>
                            <tr>
                                <td class="text-muted" style="font-size: 0.78rem;">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= esc($log['username']) ?></span>
                                    <small class="badge <?= $log['role'] === 'admin' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' ?> px-2 py-0 ms-1" style="font-size: 0.68rem;">
                                        <?= esc($log['role']) ?>
                                    </small>
                                </td>
                                <td>
                                    <?php
                                    $action = strtoupper($log['action']);
                                    $badgeClass = 'bg-secondary-subtle text-secondary';
                                    if ($action === 'LOGIN') {
                                        $badgeClass = 'bg-success-subtle text-success';
                                    } elseif (in_array($action, ['HITUNG_GAJI', 'UPLOAD_BUKTI_GAJI'])) {
                                        $badgeClass = 'bg-primary-subtle text-primary';
                                    } elseif (str_contains($action, 'TAMBAH') || $action === 'INPUT_PRESENSI') {
                                        $badgeClass = 'bg-info-subtle text-info';
                                    } elseif (str_contains($action, 'EDIT')) {
                                        $badgeClass = 'bg-warning-subtle text-warning';
                                    } elseif (str_contains($action, 'HAPUS')) {
                                        $badgeClass = 'bg-danger-subtle text-danger';
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?> fw-bold px-2 py-1" style="font-size: 0.75rem;">
                                        <?= esc($log['action']) ?>
                                    </span>
                                </td>
                                <td class="fw-medium text-dark text-break">
                                    <?= esc($log['description']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Belum ada riwayat aktivitas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data Chart 1: Bar Chart
            const rawMonthly = <?= json_encode($chartMonthly ?? []) ?>;
            const monthsName = ["", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];
            
            const labelsMonthly = rawMonthly.map(item => monthsName[item.bulan] + " " + item.tahun);
            const dataMonthly = rawMonthly.map(item => item.total_gaji_bersih);

            const ctx1 = document.getElementById('monthlyPayrollChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: labelsMonthly.length > 0 ? labelsMonthly : ['Jul 2026'],
                    datasets: [{
                        label: 'Total Gaji Bersih (Rp)',
                        data: dataMonthly.length > 0 ? dataMonthly : [<?= $totalGajiBulanIni ?>],
                        backgroundColor: 'rgba(79, 70, 229, 0.85)',
                        borderColor: '#4f46e5',
                        borderWidth: 1,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                }
                            }
                        }
                    }
                }
            });

            // Data Chart 2: Donut Chart
            const rawJabatan = <?= json_encode($chartJabatan ?? []) ?>;
            const labelsJabatan = rawJabatan.map(item => item.nama_jabatan);
            const dataJabatan = rawJabatan.map(item => item.count);

            const ctx2 = document.getElementById('departmentChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: labelsJabatan,
                    datasets: [{
                        data: dataJabatan,
                        backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#f43f5e', '#8b5cf6'],
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    </script>

<?php else: ?>
    <!-- Karyawan Dashboard View -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card card-custom p-4 text-center">
                <div class="mb-3">
                    <img src="<?= base_url('uploads/karyawan/' . ($karyawanInfo['foto'] ?? 'default.png')) ?>" class="rounded-circle shadow-sm" style="width: 110px; height: 110px; object-fit: cover;" alt="Foto Profil">
                </div>
                <h5 class="fw-bold mb-1"><?= esc($karyawanInfo['nama'] ?? 'Karyawan') ?></h5>
                <p class="text-muted mb-2" style="font-size: 0.85rem;"><i class="fa-solid fa-id-card me-1"></i> NIP: <?= esc($karyawanInfo['nip'] ?? '-') ?></p>
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill"><?= esc($karyawanInfo['nama_jabatan'] ?? 'Staff') ?></span>
                <hr class="my-3">
                <div class="text-start text-muted" style="font-size: 0.85rem;">
                    <div class="mb-2"><i class="fa-solid fa-ring me-2 text-primary"></i> <strong>Status Nikah:</strong> <?= esc($karyawanInfo['status_nikah'] ?? '-') ?> (Anak: <?= esc($karyawanInfo['jumlah_anak'] ?? '0') ?>)</div>
                    <div class="mb-2"><i class="fa-solid fa-phone me-2 text-primary"></i> <strong>No. Telp:</strong> <?= esc($karyawanInfo['no_telp'] ?? '-') ?></div>
                    <div><i class="fa-solid fa-location-dot me-2 text-primary"></i> <strong>Alamat:</strong> <?= esc($karyawanInfo['alamat'] ?? '-') ?></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card card-custom p-4 mb-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-wallet text-success me-2"></i> Rincian Gaji Pokok & Tunjangan Anda</h6>
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block mb-1">Gaji Pokok</small>
                            <span class="fw-bold text-dark">Rp <?= number_format($karyawanInfo['gaji_pokok'] ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block mb-1">Tunj. Jabatan</small>
                            <span class="fw-bold text-dark">Rp <?= number_format($karyawanInfo['tunj_jabatan'] ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block mb-1">Uang Makan/Hari</small>
                            <span class="fw-bold text-dark">Rp <?= number_format($karyawanInfo['tunj_makan_per_hari'] ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block mb-1">Transport/Hari</small>
                            <span class="fw-bold text-dark">Rp <?= number_format($karyawanInfo['tunj_transport_per_hari'] ?? 0, 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-receipt text-primary me-2"></i> Riwayat Slip Gaji Terbaru</h6>
                    <a href="<?= base_url('penggajian') ?>" class="btn btn-sm btn-outline-primary rounded-pill">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Bulan / Tahun</th>
                                <th>Gaji Bersih</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($riwayatGaji)): ?>
                                <?php foreach (array_slice($riwayatGaji, 0, 5) as $g): ?>
                                    <tr>
                                        <td class="fw-semibold">Bulan <?= $g['bulan'] ?> - <?= $g['tahun'] ?></td>
                                        <td class="fw-bold text-success">Rp <?= number_format($g['gaji_bersih'], 0, ',', '.') ?></td>
                                        <td><span class="badge bg-success-subtle text-success"><?= $g['status_bayar'] ?></span></td>
                                        <td>
                                            <a href="<?= base_url('penggajian/slip/' . $g['id']) ?>" class="btn btn-sm btn-primary rounded-pill"><i class="fa-solid fa-print me-1"></i> Slip Gaji</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada riwayat penggajian.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
