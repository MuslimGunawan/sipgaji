<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-custom p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1">Data Karyawan</h5>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Pengelolaan data profil karyawan dan akun pengguna sistem</p>
        </div>
        <div class="d-flex flex-column flex-sm-row header-action-group gap-2">
            <!-- Search Form -->
            <form action="<?= base_url('karyawan') ?>" method="GET" class="d-flex gap-2 w-100">
                <div class="input-group w-100 flex-nowrap">
                    <input type="text" name="search" class="form-control rounded-start-pill border-end-0" placeholder="Cari nama/NIP..." value="<?= esc($search ?? '') ?>">
                    <button class="btn btn-outline-secondary rounded-end-pill border-start-0" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
            <button class="btn btn-primary rounded-pill px-4 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalAddKaryawan">
                <i class="fa-solid fa-user-plus me-1"></i> Tambah Karyawan
            </button>
        </div>
    </div>

    <!-- Employee Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-size: 0.875rem;">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">Foto</th>
                    <th>NIP & Nama</th>
                    <th>Jabatan</th>
                    <th>L/P</th>
                    <th>Status Nikah</th>
                    <th>Anak</th>
                    <th>No. Telp</th>
                    <th>Tgl Masuk</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($karyawanList)): ?>
                    <?php foreach ($karyawanList as $k): ?>
                        <tr>
                            <td>
                                <img src="<?= base_url('uploads/karyawan/' . (! empty($k['foto']) ? $k['foto'] : 'default.png')) ?>" class="rounded-circle shadow-sm border" style="width: 42px; height: 42px; object-fit: cover;" alt="Avatar">
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($k['nama']) ?></div>
                                <small class="text-muted" style="font-size: 0.78rem;">NIP: <?= esc($k['nip']) ?></small>
                            </td>
                            <td><span class="badge bg-indigo-subtle text-primary fw-semibold"><?= esc($k['nama_jabatan'] ?? '-') ?></span></td>
                            <td><?= esc($k['jenis_kelamin']) ?></td>
                            <td><?= esc($k['status_nikah']) ?></td>
                            <td><?= esc($k['jumlah_anak']) ?></td>
                            <td><?= esc($k['no_telp'] ?? '-') ?></td>
                            <td><?= date('d/m/Y', strtotime($k['tanggal_masuk'])) ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-warning rounded-circle" data-bs-toggle="modal" data-bs-target="#modalEditKaryawan<?= $k['id'] ?>" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="<?= base_url('karyawan/delete/' . $k['id']) ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Yakin ingin menghapus data karyawan ini beserta akun penggunanya?')" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Data Karyawan tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-end mt-3">
        <?= $pager->links('default', 'bootstrap_full') ?>
    </div>
</div>

<!-- All Edit Modals (Placed OUTSIDE table for 100% Valid HTML Form Parsing) -->
<?php if (! empty($karyawanList)): ?>
    <?php foreach ($karyawanList as $k): ?>
        <div class="modal fade" id="modalEditKaryawan<?= $k['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4 border-0">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Edit Data Karyawan: <?= esc($k['nama']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url('karyawan/update/' . $k['id']) ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIP</label>
                                    <input type="text" name="nip" class="form-control" value="<?= esc($k['nip']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" value="<?= esc($k['nama']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="L" <?= $k['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="P" <?= $k['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jabatan</label>
                                    <select name="jabatan_id" class="form-select" required>
                                        <?php foreach ($jabatanList as $j): ?>
                                            <option value="<?= $j['id'] ?>" <?= $k['jabatan_id'] == $j['id'] ? 'selected' : '' ?>><?= esc($j['nama_jabatan']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status Pernikahan</label>
                                    <select name="status_nikah" class="form-select" required>
                                        <option value="Belum Menikah" <?= $k['status_nikah'] === 'Belum Menikah' ? 'selected' : '' ?>>Belum Menikah</option>
                                        <option value="Menikah" <?= $k['status_nikah'] === 'Menikah' ? 'selected' : '' ?>>Menikah</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jumlah Anak</label>
                                    <input type="number" name="jumlah_anak" class="form-control" value="<?= $k['jumlah_anak'] ?>" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">No. Telp / WA</label>
                                    <input type="text" name="no_telp" class="form-control" value="<?= esc($k['no_telp']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Masuk Kerja</label>
                                    <input type="date" name="tanggal_masuk" class="form-control" value="<?= $k['tanggal_masuk'] ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control" value="<?= esc($k['tempat_lahir']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= $k['tanggal_lahir'] ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                                    <textarea name="alamat" class="form-control" rows="2"><?= esc($k['alamat']) ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Ganti Foto Profil (JPG/PNG/WEBP, Max 2MB)</label>
                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                    <small class="text-muted" style="font-size: 0.78rem;">Kosongkan jika tidak ingin mengganti foto saat ini.</small>
                                </div>
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

<!-- Modal Add Karyawan -->
<div class="modal fade" id="modalAddKaryawan" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Data Karyawan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('karyawan/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-key me-1"></i> Informasi Akun Login System</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="username123" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@domain.com" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Password Default</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 6 Karakter" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-id-card me-1"></i> Data Biodata & Kepegawaian</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP (Nomor Induk Pegawai)</label>
                            <input type="text" name="nip" class="form-control" placeholder="NIP2026..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap Karyawan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <select name="jabatan_id" class="form-select" required>
                                <option value="">-- Pilih Jabatan --</option>
                                <?php foreach ($jabatanList as $j): ?>
                                    <option value="<?= $j['id'] ?>"><?= esc($j['nama_jabatan']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Pernikahan</label>
                            <select name="status_nikah" class="form-select" required>
                                <option value="Belum Menikah">Belum Menikah</option>
                                <option value="Menikah">Menikah</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Anak</label>
                            <input type="number" name="jumlah_anak" class="form-control" value="0" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. Telp / WA</label>
                            <input type="text" name="no_telp" class="form-control" placeholder="0812...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Masuk Kerja</label>
                            <input type="date" name="tanggal_masuk" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota Kelahiran">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Tempat Tinggal</label>
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Upload Foto Profil (JPG/PNG/WEBP, Max 2MB)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Karyawan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
