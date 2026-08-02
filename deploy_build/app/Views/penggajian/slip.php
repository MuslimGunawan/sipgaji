<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - <?= esc($gaji['nama']) ?> (<?= esc($gaji['kode_transaksi']) ?>)</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            padding: 2rem 1rem;
        }

        .slip-card {
            background: #ffffff;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
        }

        .slip-header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .company-title {
            font-weight: 800;
            font-size: 1.4rem;
            color: #4f46e5;
        }

        .table-itemized th {
            background: #f8fafc;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .table-itemized td {
            font-size: 0.9rem;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .slip-card {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="mb-4 text-center no-print">
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 me-2">
            <i class="fa-solid fa-print me-1"></i> Cetak / Simpan PDF
        </button>
        <a href="<?= base_url('penggajian') ?>" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="slip-card">
        <!-- Header -->
        <div class="slip-header d-flex justify-content-between align-items-center">
            <div>
                <div class="company-title"><i class="fa-solid fa-calculator me-2"></i> SIPGAJI CORPORATION</div>
                <div class="text-muted" style="font-size: 0.85rem;">Sistem Penggajian Karyawan Terintegrasi</div>
                <small class="text-muted">Jl. Universitas Malikussaleh No. 1, Reuleut, Aceh Utara</small>
            </div>
            <div class="text-end">
                <h5 class="fw-bold text-dark mb-1">SLIP GAJI KARYAWAN</h5>
                <span class="badge bg-light text-dark border px-3 py-2 fw-mono"><?= esc($gaji['kode_transaksi']) ?></span>
                <div class="text-muted mt-1" style="font-size: 0.8rem;">Periode: Bulan <?= $gaji['bulan'] ?> / <?= $gaji['tahun'] ?></div>
            </div>
        </div>

        <!-- Employee Info -->
        <div class="row g-3 mb-4 p-3 bg-light rounded-3" style="font-size: 0.875rem;">
            <div class="col-6 col-md-3">
                <div class="text-muted">NIP</div>
                <div class="fw-bold"><?= esc($gaji['nip']) ?></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-muted">Nama Karyawan</div>
                <div class="fw-bold"><?= esc($gaji['nama']) ?></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-muted">Jabatan</div>
                <div class="fw-bold text-primary"><?= esc($gaji['nama_jabatan']) ?></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-muted">Status / Anak</div>
                <div class="fw-bold"><?= esc($gaji['status_nikah']) ?> (<?= esc($gaji['jumlah_anak']) ?>)</div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <?php if (! empty($presensi)): ?>
            <div class="row g-2 mb-4 text-center" style="font-size: 0.8rem;">
                <div class="col"><div class="p-2 border rounded">Hadir: <strong><?= $presensi['jumlah_hadir'] ?> Hari</strong></div></div>
                <div class="col"><div class="p-2 border rounded">Sakit: <strong><?= $presensi['jumlah_sakit'] ?> Hari</strong></div></div>
                <div class="col"><div class="p-2 border rounded">Izin: <strong><?= $presensi['jumlah_izin'] ?> Hari</strong></div></div>
                <div class="col"><div class="p-2 border rounded text-danger">Alpa: <strong><?= $presensi['jumlah_alpa'] ?> Hari</strong></div></div>
                <div class="col"><div class="p-2 border rounded text-indigo">Lembur: <strong><?= $presensi['jumlah_lembur_jam'] ?> Jam</strong></div></div>
            </div>
        <?php endif; ?>

        <!-- Calculation Tables -->
        <div class="row g-4 mb-4">
            <!-- Income Column -->
            <div class="col-md-6">
                <h6 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="fa-solid fa-plus-circle me-1"></i> Rincian Pendapatan (Gross)</h6>
                <table class="table table-sm table-borderless table-itemized mb-0">
                    <tr>
                        <td>Gaji Pokok</td>
                        <td class="text-end fw-semibold">Rp <?= number_format($gaji['gaji_pokok'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Tunjangan Jabatan</td>
                        <td class="text-end fw-semibold">Rp <?= number_format($gaji['tunj_jabatan'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Tunjangan Kehadiran (Makan+Transport)</td>
                        <td class="text-end fw-semibold">Rp <?= number_format($gaji['tunj_kehadiran'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Tunjangan Keluarga (Istri+Anak)</td>
                        <td class="text-end fw-semibold">Rp <?= number_format($gaji['tunj_keluarga'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Bonus Jam Lembur</td>
                        <td class="text-end fw-semibold text-indigo">Rp <?= number_format($gaji['bonus_lembur'], 0, ',', '.') ?></td>
                    </tr>
                    <tr class="border-top">
                        <th class="pt-2">Total Pendapatan</th>
                        <th class="text-end pt-2 text-success">Rp <?= number_format($gaji['total_pendapatan'], 0, ',', '.') ?></th>
                    </tr>
                </table>
            </div>

            <!-- Deductions Column -->
            <div class="col-md-6">
                <h6 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="fa-solid fa-minus-circle me-1"></i> Rincian Potongan</h6>
                <table class="table table-sm table-borderless table-itemized mb-0">
                    <tr>
                        <td>Potongan BPJS Kesehatan (1%)</td>
                        <td class="text-end fw-semibold text-danger">Rp <?= number_format($gaji['pot_bpjs_ks'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Potongan BPJS Ketenagakerjaan (2%)</td>
                        <td class="text-end fw-semibold text-danger">Rp <?= number_format($gaji['pot_bpjs_tk'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Potongan PPh 21 (Pajak 5%)</td>
                        <td class="text-end fw-semibold text-danger">Rp <?= number_format($gaji['pot_pph21'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Potongan Absensi / Alpa</td>
                        <td class="text-end fw-semibold text-danger">Rp <?= number_format($gaji['pot_absensi'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="border-top">
                        <th class="pt-2">Total Potongan</th>
                        <th class="text-end pt-2 text-danger">Rp <?= number_format($gaji['total_potongan'], 0, ',', '.') ?></th>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Net Total Take Home Pay -->
        <div class="p-3 bg-success-subtle text-success rounded-3 d-flex justify-content-between align-items-center mb-5 border border-success-subtle">
            <div>
                <h6 class="fw-bold mb-0 text-success">GAJI BERSIH (TAKE HOME PAY)</h6>
                <small style="font-size: 0.8rem;">Status Pembayaran: <strong><?= esc($gaji['status_bayar']) ?></strong></small>
            </div>
            <h3 class="fw-extrabold mb-0 text-success">Rp <?= number_format($gaji['gaji_bersih'], 0, ',', '.') ?></h3>
        </div>

        <!-- Signatures -->
        <div class="row text-center mt-4" style="font-size: 0.85rem;">
            <div class="col-6">
                <p class="mb-5 text-muted">Penerima Gaji,</p>
                <div class="fw-bold text-dark text-decoration-underline"><?= esc($gaji['nama']) ?></div>
                <small class="text-muted">NIP: <?= esc($gaji['nip']) ?></small>
            </div>
            <div class="col-6">
                <p class="mb-5 text-muted">Lhokseumawe, <?= date('d F Y', strtotime($gaji['created_at'])) ?><br>Manager Keuangan & HRD,</p>
                <div class="fw-bold text-dark text-decoration-underline">Rizki Suwanda, S.T., M.Kom</div>
                <small class="text-muted">NIP. 19880512 201504 1 002</small>
            </div>
        </div>
    </div>
</div>

</body>
</html>
