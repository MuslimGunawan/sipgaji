<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'SIPGAJI - Sistem Perhitungan Gaji Otomatis') ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-light: #f8fafc;
            --dark-sidebar: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #334155;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Desktop Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 100%);
            color: #f8fafc;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
            overflow-y: auto;
        }

        #sidebar .brand {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        #sidebar .brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        #sidebar .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            margin: 4px 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            color: #ffffff;
            background: rgba(99, 102, 241, 0.15);
            border-left: 4px solid #6366f1;
        }

        #sidebar .nav-link i {
            font-size: 1.1rem;
            width: 24px;
        }

        /* Main Content */
        #content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .top-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            sticky: top;
            z-index: 99;
        }

        .main-container {
            padding: 1.5rem;
            flex-grow: 1;
        }

        /* Mobile Responsiveness adjustments */
        @media (max-width: 991.98px) {
            #sidebar {
                display: none;
            }
            #content {
                margin-left: 0;
            }
        }

        /* Mobile Offcanvas Sidebar */
        .offcanvas-sidebar {
            background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 100%);
            color: #fff;
            width: 280px !important;
        }

        .offcanvas-sidebar .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            margin: 4px 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .offcanvas-sidebar .nav-link:hover, .offcanvas-sidebar .nav-link.active {
            color: #ffffff;
            background: rgba(99, 102, 241, 0.15);
            border-left: 4px solid #6366f1;
        }

        /* Custom Cards */
        .card-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-custom:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        }

        .stat-card {
            padding: 1.5rem;
            border-radius: 16px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
        .bg-gradient-emerald { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .bg-gradient-amber { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .bg-gradient-rose { background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); }

        .stat-icon {
            position: absolute;
            right: 15px;
            bottom: 15px;
            font-size: 3.5rem;
            opacity: 0.2;
        }

        .badge-role {
            padding: 0.35em 0.75em;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            font-size: 0.85rem;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Desktop Sidebar -->
    <nav id="sidebar">
        <div class="brand">
            <div class="brand-logo"><i class="fa-solid fa-calculator"></i></div>
            <div>
                <h6 class="mb-0 fw-bold text-white">SIPGAJI</h6>
                <small class="text-white-50" style="font-size: 11px;">System Payroll CI4</small>
            </div>
        </div>

        <div class="mt-3">
            <div class="px-3 mb-2 text-uppercase font-monospace text-white-50" style="font-size: 10px;">Menu Utama</div>
            <a href="<?= base_url('dashboard') ?>" class="nav-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="<?= base_url('profile') ?>" class="nav-link <?= uri_string() === 'profile' ? 'active' : '' ?>">
                <i class="fa-solid fa-user-gear"></i> Edit Profil Saya
            </a>

            <?php if (session()->get('role') === 'admin'): ?>
                <div class="px-3 mt-4 mb-2 text-uppercase font-monospace text-white-50" style="font-size: 10px;">Master Data</div>
                <a href="<?= base_url('jabatan') ?>" class="nav-link <?= uri_string() === 'jabatan' ? 'active' : '' ?>">
                    <i class="fa-solid fa-briefcase"></i> Data Jabatan
                </a>
                <a href="<?= base_url('karyawan') ?>" class="nav-link <?= uri_string() === 'karyawan' ? 'active' : '' ?>">
                    <i class="fa-solid fa-users"></i> Data Karyawan
                </a>

                <div class="px-3 mt-4 mb-2 text-uppercase font-monospace text-white-50" style="font-size: 10px;">Transaksi & Komputasi</div>
                <a href="<?= base_url('presensi') ?>" class="nav-link <?= uri_string() === 'presensi' ? 'active' : '' ?>">
                    <i class="fa-solid fa-calendar-check"></i> Rekap Presensi
                </a>
                <a href="<?= base_url('penggajian') ?>" class="nav-link <?= uri_string() === 'penggajian' ? 'active' : '' ?>">
                    <i class="fa-solid fa-money-bill-wave"></i> Perhitungan Gaji
                </a>
            <?php else: ?>
                <div class="px-3 mt-4 mb-2 text-uppercase font-monospace text-white-50" style="font-size: 10px;">Layanan Karyawan</div>
                <a href="<?= base_url('presensi') ?>" class="nav-link <?= uri_string() === 'presensi' ? 'active' : '' ?>">
                    <i class="fa-solid fa-calendar-days"></i> Presensi Saya
                </a>
                <a href="<?= base_url('penggajian') ?>" class="nav-link <?= uri_string() === 'penggajian' ? 'active' : '' ?>">
                    <i class="fa-solid fa-receipt"></i> Slip Gaji Saya
                </a>
            <?php endif; ?>

            <div class="px-3 mt-4 mb-2 text-uppercase font-monospace text-white-50" style="font-size: 10px;">Akun</div>
            <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
            </a>
        </div>
    </nav>

    <!-- Mobile Offcanvas Sidebar Drawer -->
    <div class="offcanvas offcanvas-start offcanvas-sidebar" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-logo" style="width:36px; height:36px; background:#4f46e5; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff;">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <h6 class="mb-0 fw-bold text-white">SIPGAJI Mobile</h6>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0 pt-3">
            <a href="<?= base_url('dashboard') ?>" class="nav-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="<?= base_url('profile') ?>" class="nav-link <?= uri_string() === 'profile' ? 'active' : '' ?>">
                <i class="fa-solid fa-user-gear"></i> Edit Profil Saya
            </a>

            <?php if (session()->get('role') === 'admin'): ?>
                <div class="px-3 mt-3 mb-2 text-uppercase font-monospace text-white-50" style="font-size: 10px;">Master Data</div>
                <a href="<?= base_url('jabatan') ?>" class="nav-link <?= uri_string() === 'jabatan' ? 'active' : '' ?>">
                    <i class="fa-solid fa-briefcase"></i> Data Jabatan
                </a>
                <a href="<?= base_url('karyawan') ?>" class="nav-link <?= uri_string() === 'karyawan' ? 'active' : '' ?>">
                    <i class="fa-solid fa-users"></i> Data Karyawan
                </a>

                <div class="px-3 mt-3 mb-2 text-uppercase font-monospace text-white-50" style="font-size: 10px;">Transaksi & Komputasi</div>
                <a href="<?= base_url('presensi') ?>" class="nav-link <?= uri_string() === 'presensi' ? 'active' : '' ?>">
                    <i class="fa-solid fa-calendar-check"></i> Rekap Presensi
                </a>
                <a href="<?= base_url('penggajian') ?>" class="nav-link <?= uri_string() === 'penggajian' ? 'active' : '' ?>">
                    <i class="fa-solid fa-money-bill-wave"></i> Perhitungan Gaji
                </a>
            <?php else: ?>
                <div class="px-3 mt-3 mb-2 text-uppercase font-monospace text-white-50" style="font-size: 10px;">Layanan Karyawan</div>
                <a href="<?= base_url('presensi') ?>" class="nav-link <?= uri_string() === 'presensi' ? 'active' : '' ?>">
                    <i class="fa-solid fa-calendar-days"></i> Presensi Saya
                </a>
                <a href="<?= base_url('penggajian') ?>" class="nav-link <?= uri_string() === 'penggajian' ? 'active' : '' ?>">
                    <i class="fa-solid fa-receipt"></i> Slip Gaji Saya
                </a>
            <?php endif; ?>

            <div class="px-3 mt-3 mb-2 text-uppercase font-monospace text-white-50" style="font-size: 10px;">Akun</div>
            <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
            </a>
        </div>
    </div>

    <!-- Content -->
    <div id="content">
        <!-- Header -->
        <header class="top-header">
            <div class="d-flex align-items-center gap-3">
                <!-- Mobile Toggle Button -->
                <button class="btn btn-light d-lg-none rounded-circle border shadow-sm p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                    <i class="fa-solid fa-bars fs-5"></i>
                </button>
                <h5 class="fw-bold mb-0 text-dark"><?= esc($title ?? 'Dashboard') ?></h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-semibold text-dark mb-0"><?= esc(session()->get('namaLengkap') ?? 'User') ?></div>
                    <span class="badge bg-primary-subtle text-primary badge-role"><?= esc(session()->get('role') ?? 'Guest') ?></span>
                </div>
                <a href="<?= base_url('profile') ?>" class="text-decoration-none">
                    <?php if (session()->get('foto') && session()->get('foto') !== 'default.png'): ?>
                        <img src="<?= base_url('uploads/karyawan/' . session()->get('foto')) ?>" class="rounded-circle border border-2 border-primary shadow-sm" style="width: 42px; height: 42px; object-fit: cover;" alt="Avatar">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 42px; height: 42px;">
                            <?= strtoupper(substr(session()->get('username') ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="main-container">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>Terjadi Kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

        <!-- Footer -->
        <footer class="footer d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <div>&copy; 2026 <strong>SIPGAJI</strong> - Universitas Malikussaleh</div>
            <div>Built with CodeIgniter 4 & Bootstrap 5</div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
