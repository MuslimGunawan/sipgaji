<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPGAJI CodeIgniter 4</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }

        .login-header {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            padding: 2.5rem 2rem 2rem;
            color: #fff;
            text-align: center;
        }

        .login-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .btn-primary-custom {
            background: #4f46e5;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-primary-custom:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="login-icon">
            <i class="fa-solid fa-calculator text-white"></i>
        </div>
        <h4 class="fw-bold mb-1">SIPGAJI</h4>
        <p class="text-white-50 mb-0" style="font-size: 0.875rem;">Sistem Informasi Penggajian Otomatis</p>
    </div>

    <div class="p-4 p-md-5">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="fa-solid fa-circle-exclamation me-1"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="fa-solid fa-circle-check me-1"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login/process') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Username / Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Masukkan username" value="<?= old('username') ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom btn-primary w-100 mb-3">
                Masuk Ke Sistem <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
            </button>
        </form>

        <div class="p-3 bg-light rounded-3 text-muted mt-3" style="font-size: 0.78rem;">
            <div class="fw-bold mb-1 text-dark"><i class="fa-solid fa-key text-warning me-1"></i> Akun Login Default Demo:</div>
            <div><strong>Admin:</strong> <code>admin</code> / <code>password123</code></div>
            <div><strong>Karyawan:</strong> <code>karyawan1</code> / <code>password123</code></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
