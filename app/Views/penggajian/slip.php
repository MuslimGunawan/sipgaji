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
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            padding: 2rem 1rem;
            margin: 0;
        }

        .slip-card {
            background: #ffffff;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            word-wrap: break-word;
            overflow: hidden;
        }

        .company-title {
            font-weight: 800;
            font-size: 1.4rem;
            color: #4f46e5;
        }

        .slip-header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .table-itemized td, .table-itemized th {
            font-size: 0.875rem;
            padding: 0.4rem 0;
        }

        /* Attendance Grid Symmetrical Alignment */
        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
        }

        /* Perfect Equal-Height Signature Alignment */
        .signature-header {
            min-height: 65px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
        }

        .signature-space {
            height: 55px;
            width: 100%;
        }

        /* Mobile Responsive Layout Overhaul */
        @media (max-width: 575.98px) {
            body {
                padding: 0.75rem 0.5rem;
            }
            .slip-card {
                padding: 1.25rem 0.85rem;
                border-radius: 16px;
            }
            .company-title {
                font-size: 1.2rem;
            }
            .slip-header {
                text-align: center !important;
            }
            .slip-header-right {
                text-align: center !important;
                width: 100%;
            }
            .attendance-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .attendance-grid .item-last {
                grid-column: span 2;
            }
            .take-home-pay-box {
                text-align: center !important;
                justify-content: center !important;
                align-items: center !important;
            }
            .table-itemized td, .table-itemized th {
                font-size: 0.8rem;
            }
            .signature-header {
                min-height: 75px;
            }
            .signature-space {
                height: 45px;
            }
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

<div class="container-fluid px-0 px-md-3">
    <!-- Top Action Buttons -->
    <div class="mb-4 text-center no-print d-flex flex-wrap justify-content-center gap-2">
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4">
            <i class="fa-solid fa-print me-1"></i> Cetak / Simpan PDF
        </button>
        <a href="<?= base_url('penggajian') ?>" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="slip-card">
        <!-- Header (Centered on Mobile, Split on Desktop) -->
        <div class="slip-header d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <div class="text-center text-sm-start">
                <div class="company-title"><i class="fa-solid fa-calculator me-2"></i> SIPGAJI CORPORATION</div>
                <div class="text-muted" style="font-size: 0.85rem;">Sistem Penggajian Karyawan Terintegrasi</div>
                <small class="text-muted d-block mt-1">Jl. Universitas Malikussaleh No. 1, Reuleut, Aceh Utara</small>
            </div>
            <div class="slip-header-right text-center text-sm-end">
                <h5 class="fw-bold text-dark mb-1">SLIP GAJI KARYAWAN</h5>
                <span class="badge bg-light text-dark border px-3 py-2 fw-mono text-break"><?= esc($gaji['kode_transaksi']) ?></span>
                <div class="text-muted mt-1" style="font-size: 0.8rem;">Periode: Bulan <?= $gaji['bulan'] ?> / <?= $gaji['tahun'] ?></div>
            </div>
        </div>

        <!-- Employee Info Grid -->
        <div class="row g-3 mb-4 p-3 bg-light rounded-3 text-center text-sm-start" style="font-size: 0.875rem;">
            <div class="col-6 col-md-3">
                <div class="text-muted small">NIP</div>
                <div class="fw-bold text-break"><?= esc($gaji['nip']) ?></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-muted small">Nama Karyawan</div>
                <div class="fw-bold text-break"><?= esc($gaji['nama']) ?></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-muted small">Jabatan</div>
                <div class="fw-bold text-primary text-break"><?= esc($gaji['nama_jabatan']) ?></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-muted small">Status / Anak</div>
                <div class="fw-bold text-break"><?= esc($gaji['status_nikah']) ?> (<?= esc($gaji['jumlah_anak']) ?>)</div>
            </div>
        </div>

        <!-- Attendance Summary (Symmetrical Grid) -->
        <?php if (! empty($presensi)): ?>
            <div class="attendance-grid mb-4 text-center" style="font-size: 0.8rem;">
                <div class="p-2 border rounded bg-white">Hadir: <strong><?= $presensi['jumlah_hadir'] ?> Hari</strong></div>
                <div class="p-2 border rounded bg-white">Sakit: <strong><?= $presensi['jumlah_sakit'] ?> Hari</strong></div>
                <div class="p-2 border rounded bg-white">Izin: <strong><?= $presensi['jumlah_izin'] ?> Hari</strong></div>
                <div class="p-2 border rounded bg-white text-danger">Alpa: <strong><?= $presensi['jumlah_alpa'] ?> Hari</strong></div>
                <div class="p-2 border rounded bg-white text-indigo item-last">Lembur: <strong><?= $presensi['jumlah_lembur_jam'] ?> Jam</strong></div>
            </div>
        <?php endif; ?>

        <!-- Calculation Tables -->
        <div class="row g-4 mb-4">
            <!-- Income Column -->
            <div class="col-12 col-md-6">
                <h6 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="fa-solid fa-plus-circle me-1"></i> Rincian Pendapatan (Gross)</h6>
                <table class="table table-sm table-borderless table-itemized mb-0 w-100">
                    <tr>
                        <td>Gaji Pokok</td>
                        <td class="text-end fw-semibold text-nowrap">Rp <?= number_format($gaji['gaji_pokok'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Tunjangan Jabatan</td>
                        <td class="text-end fw-semibold text-nowrap">Rp <?= number_format($gaji['tunj_jabatan'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Tunjangan Kehadiran</td>
                        <td class="text-end fw-semibold text-nowrap">Rp <?= number_format($gaji['tunj_kehadiran'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Tunjangan Keluarga</td>
                        <td class="text-end fw-semibold text-nowrap">Rp <?= number_format($gaji['tunj_keluarga'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Bonus Jam Lembur</td>
                        <td class="text-end fw-semibold text-indigo text-nowrap">Rp <?= number_format($gaji['bonus_lembur'], 0, ',', '.') ?></td>
                    </tr>
                    <tr class="border-top">
                        <th class="pt-2">TOTAL PENDAPATAN</th>
                        <th class="text-end pt-2 text-success text-nowrap">Rp <?= number_format($gaji['total_pendapatan'], 0, ',', '.') ?></th>
                    </tr>
                </table>
            </div>

            <!-- Deductions Column -->
            <div class="col-12 col-md-6">
                <h6 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="fa-solid fa-minus-circle me-1"></i> Rincian Potongan</h6>
                <table class="table table-sm table-borderless table-itemized mb-0 w-100">
                    <tr>
                        <td>BPJS Kesehatan (1%)</td>
                        <td class="text-end fw-semibold text-danger text-nowrap">Rp <?= number_format($gaji['pot_bpjs_ks'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>BPJS Ketenagakerjaan (2%)</td>
                        <td class="text-end fw-semibold text-danger text-nowrap">Rp <?= number_format($gaji['pot_bpjs_tk'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>PPh 21 (Pajak 5%)</td>
                        <td class="text-end fw-semibold text-danger text-nowrap">Rp <?= number_format($gaji['pot_pph21'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Potongan Absensi / Alpa</td>
                        <td class="text-end fw-semibold text-danger text-nowrap">Rp <?= number_format($gaji['pot_absensi'], 0, ',', '.') ?></td>
                    </tr>
                    <tr class="border-top">
                        <th class="pt-2">TOTAL POTONGAN</th>
                        <th class="text-end pt-2 text-danger text-nowrap">Rp <?= number_format($gaji['total_potongan'], 0, ',', '.') ?></th>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Net Total Take Home Pay -->
        <div class="p-3 bg-success-subtle text-success rounded-3 d-flex flex-column flex-sm-row justify-content-between align-items-center text-center text-sm-start gap-2 mb-5 border border-success-subtle take-home-pay-box">
            <div>
                <h6 class="fw-bold mb-0 text-success">GAJI BERSIH (TAKE HOME PAY)</h6>
                <small style="font-size: 0.8rem;">Status Pembayaran: <strong><?= esc($gaji['status_bayar']) ?></strong></small>
            </div>
            <h3 class="fw-extrabold mb-0 text-success text-nowrap">Rp <?= number_format($gaji['gaji_bersih'], 0, ',', '.') ?></h3>
        </div>

        <!-- Signatures (Synchronized Baseline & Equal Signature Space) -->
        <div class="row text-center mt-4" style="font-size: 0.85rem;">
            <div class="col-6">
                <div class="signature-header">
                    <p class="mb-0 text-muted">Penerima Gaji,</p>
                </div>
                <div class="signature-space"></div>
                <div class="signature-footer">
                    <div class="fw-bold text-dark text-decoration-underline text-break"><?= esc($gaji['nama']) ?></div>
                    <small class="text-muted text-break">NIP: <?= esc($gaji['nip']) ?></small>
                </div>
            </div>
            <div class="col-6">
                <div class="signature-header">
                    <p class="mb-0 text-muted">Lhokseumawe, <?= date('d F Y', strtotime($gaji['created_at'])) ?></p>
                    <p class="mb-0 text-muted">Manager Keuangan & HRD,</p>
                </div>
                <div class="signature-space"></div>
                <div class="signature-footer">
                    <div class="fw-bold text-dark text-decoration-underline text-break">Rizki Suwanda, S.T., M.Kom</div>
                    <small class="text-muted text-break">NIP. 19910917 202203 1 006</small>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
