<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-4">
    <!-- User Info Card -->
    <div class="col-12 col-md-4">
        <div class="card card-custom p-4 text-center">
            <div class="mb-3">
                <?php 
                    $profileFoto = ($karyawan && !empty($karyawan['foto'])) ? $karyawan['foto'] : (session()->get('foto') ?: 'default.png');
                    $profileFotoPath = FCPATH . 'uploads/karyawan/' . $profileFoto;
                    if ($profileFoto !== 'default.png' && !file_exists($profileFotoPath)) {
                        $profileFoto = 'default.png';
                    }
                ?>
                <img src="<?= base_url('uploads/karyawan/' . $profileFoto) ?>" class="rounded-circle shadow-sm border border-2 border-primary" style="width: 120px; height: 120px; object-fit: cover;" alt="Avatar">
            </div>
            <h5 class="fw-bold mb-1"><?= esc(session()->get('namaLengkap') ?? session()->get('username')) ?></h5>
            <p class="text-muted mb-2" style="font-size: 0.85rem;"><i class="fa-solid fa-envelope me-1"></i> <?= esc(session()->get('email')) ?></p>
            <div>
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill"><?= strtoupper(esc(session()->get('role'))) ?></span>
            </div>

            <?php if ($karyawan): ?>
                <hr class="my-3">
                <div class="text-start text-muted" style="font-size: 0.85rem;">
                    <div class="mb-2"><i class="fa-solid fa-id-card me-2 text-primary"></i> <strong>NIP:</strong> <?= esc($karyawan['nip']) ?></div>
                    <div class="mb-2"><i class="fa-solid fa-briefcase me-2 text-primary"></i> <strong>Jabatan:</strong> <?= esc($karyawan['nama_jabatan']) ?></div>
                    <div class="mb-2"><i class="fa-solid fa-ring me-2 text-primary"></i> <strong>Status Nikah:</strong> <?= esc($karyawan['status_nikah']) ?> (Anak: <?= esc($karyawan['jumlah_anak']) ?>)</div>
                    <div class="alert alert-warning py-2 mb-0 mt-3" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-circle-info me-1"></i> Data kepegawaian (NIP, Jabatan, Gaji Pokok, Status) dikelola penuh oleh Administrator.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Profile Edit Form -->
    <div class="col-12 col-md-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-user-pen text-primary me-2"></i> Edit Informasi Pribadi</h5>
            <p class="text-muted mb-4" style="font-size: 0.85rem;">Perbarui foto profil, nomor kontak, alamat tempat tinggal, atau ubah kata sandi akun Anda.</p>

            <form action="<?= base_url('profile/update') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Username (Akun)</label>
                        <input type="text" class="form-control bg-light" value="<?= esc($user['username']) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email (Akun)</label>
                        <input type="email" class="form-control bg-light" value="<?= esc($user['email']) ?>" disabled>
                    </div>

                    <?php if ($karyawan): ?>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. Telepon / WhatsApp</label>
                            <input type="text" name="no_telp" class="form-control" value="<?= esc($karyawan['no_telp']) ?>" placeholder="0812...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto Profil Avatar (JPG/PNG/WEBP, Max 2MB)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Tempat Tinggal</label>
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap..."><?= esc($karyawan['alamat']) ?></textarea>
                        </div>
                    <?php endif; ?>
                </div>

                <h6 class="fw-bold text-dark border-top pt-4 mb-3"><i class="fa-solid fa-lock text-warning me-2"></i> Keamanan & Ganti Password</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Password Lama</label>
                        <input type="password" name="old_password" class="form-control" placeholder="Password lama saat ini">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Min. 6 Karakter">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light rounded-pill px-4">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
