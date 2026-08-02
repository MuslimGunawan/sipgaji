<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Truncate tables with foreign key check disabled
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $db->table('penggajian')->truncate();
        $db->table('presensi')->truncate();
        $db->table('karyawan')->truncate();
        $db->table('jabatan')->truncate();
        $db->table('users')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');

        $now = date('Y-m-d H:i:s');
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);

        // 1. Seed Users
        // Admin
        $usersData = [
            [
                'id'         => 1,
                'username'   => 'admin',
                'email'      => 'admin@sipgaji.com',
                'password'   => $hashedPassword,
                'role'       => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];

        // 15 Karyawan Users
        for ($i = 1; $i <= 15; $i++) {
            $usersData[] = [
                'id'         => $i + 1,
                'username'   => 'karyawan' . $i,
                'email'      => 'karyawan' . $i . '@sipgaji.com',
                'password'   => $hashedPassword,
                'role'       => 'karyawan',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        $db->table('users')->insertBatch($usersData);

        // 2. Seed Jabatan (5 Master Data Jabatan)
        $jabatanData = [
            [
                'id'                      => 1,
                'nama_jabatan'            => 'Manager IT',
                'gaji_pokok'              => 8500000.00,
                'tunj_jabatan'            => 2500000.00,
                'tunj_makan_per_hari'     => 40000.00,
                'tunj_transport_per_hari' => 30000.00,
                'created_at'              => $now,
                'updated_at'              => $now,
            ],
            [
                'id'                      => 2,
                'nama_jabatan'            => 'Senior Software Engineer',
                'gaji_pokok'              => 7000000.00,
                'tunj_jabatan'            => 1800000.00,
                'tunj_makan_per_hari'     => 35000.00,
                'tunj_transport_per_hari' => 25000.00,
                'created_at'              => $now,
                'updated_at'              => $now,
            ],
            [
                'id'                      => 3,
                'nama_jabatan'            => 'HRD & Legal Staff',
                'gaji_pokok'              => 5500000.00,
                'tunj_jabatan'            => 1200000.00,
                'tunj_makan_per_hari'     => 30000.00,
                'tunj_transport_per_hari' => 20000.00,
                'created_at'              => $now,
                'updated_at'              => $now,
            ],
            [
                'id'                      => 4,
                'nama_jabatan'            => 'Financial Analyst',
                'gaji_pokok'              => 6000000.00,
                'tunj_jabatan'            => 1500000.00,
                'tunj_makan_per_hari'     => 30000.00,
                'tunj_transport_per_hari' => 20000.00,
                'created_at'              => $now,
                'updated_at'              => $now,
            ],
            [
                'id'                      => 5,
                'nama_jabatan'            => 'Marketing & Sales Executive',
                'gaji_pokok'              => 4800000.00,
                'tunj_jabatan'            => 1000000.00,
                'tunj_makan_per_hari'     => 25000.00,
                'tunj_transport_per_hari' => 20000.00,
                'created_at'              => $now,
                'updated_at'              => $now,
            ],
        ];
        $db->table('jabatan')->insertBatch($jabatanData);

        // 3. Seed Karyawan (15 Karyawan)
        $rawKaryawan = [
            ['NIP2026001', 'Ahmad Rizki', 'L', 'Lhokseumawe', '1992-05-14', 'Jl. Merdeka No. 12, Lhokseumawe', '081269001111', 1, 2, 'Menikah', 2],
            ['NIP2026002', 'Budi Santoso', 'L', 'Banda Aceh', '1994-08-20', 'Jl. T. Umar No. 45, Banda Aceh', '081269002222', 2, 3, 'Menikah', 1],
            ['NIP2026003', 'Citra Dewi', 'P', 'Medan', '1995-11-03', 'Jl. Gatot Subroto No. 88, Medan', '081269003333', 3, 4, 'Belum Menikah', 0],
            ['NIP2026004', 'Dedi Kurniawan', 'L', 'Lhokseumawe', '1993-01-15', 'Jl. Samudra No. 05, Lhokseumawe', '081269004444', 4, 5, 'Menikah', 2],
            ['NIP2026005', 'Eka Putri', 'P', 'Langsa', '1996-04-25', 'Jl. Ahmad Yani No. 10, Langsa', '081269005555', 5, 6, 'Belum Menikah', 0],
            ['NIP2026006', 'Fajar Pratama', 'L', 'Bireuen', '1991-09-12', 'Jl. Medan-Banda Aceh Km 2, Bireuen', '081269006666', 1, 7, 'Menikah', 3],
            ['NIP2026007', 'Gita Gutawa', 'P', 'Takengon', '1997-02-18', 'Jl. Yos Sudarso No. 3, Takengon', '081269007777', 2, 8, 'Belum Menikah', 0],
            ['NIP2026008', 'Hendra Wijaya', 'L', 'Lhokseumawe', '1990-12-05', 'Jl. Pase No. 19, Lhokseumawe', '081269008888', 3, 9, 'Menikah', 1],
            ['NIP2026009', 'Indah Permata', 'P', 'Meulaboh', '1995-07-30', 'Jl. Gajah Mada No. 7, Meulaboh', '081269009999', 4, 10, 'Menikah', 0],
            ['NIP2026010', 'Joko Susilo', 'L', 'Sigli', '1993-03-22', 'Jl. Iskandar Muda No. 14, Sigli', '081269010000', 5, 11, 'Belum Menikah', 0],
            ['NIP2026011', 'Kiki Amalia', 'P', 'Sabang', '1998-06-10', 'Jl. Perdagangan No. 2, Sabang', '081269011111', 2, 12, 'Belum Menikah', 0],
            ['NIP2026012', 'Lukman Hakim', 'L', 'Lhokseumawe', '1992-10-08', 'Jl. Darussalam No. 21, Lhokseumawe', '081269012222', 3, 13, 'Menikah', 2],
            ['NIP2026013', 'Maya Sari', 'P', 'Banda Aceh', '1996-01-29', 'Jl. Diponegoro No. 9, Banda Aceh', '081269013333', 4, 14, 'Belum Menikah', 0],
            ['NIP2026014', 'Naufal Alamsyah', 'L', 'Medan', '1994-05-17', 'Jl. Sizingamangaraja No. 30, Medan', '081269014444', 5, 15, 'Menikah', 1],
            ['NIP2026015', 'Oki Setiana', 'P', 'Lhokseumawe', '1997-09-04', 'Jl. Cipto Mangunkusumo No. 6, Lhokseumawe', '081269015555', 2, 16, 'Belum Menikah', 0],
        ];

        $karyawanData = [];
        foreach ($rawKaryawan as $idx => $item) {
            $karyawanData[] = [
                'id'            => $idx + 1,
                'user_id'       => $item[8],
                'nip'           => $item[0],
                'nama'          => $item[1],
                'jenis_kelamin' => $item[2],
                'tempat_lahir'  => $item[3],
                'tanggal_lahir' => $item[4],
                'alamat'        => $item[5],
                'no_telp'       => $item[6],
                'foto'          => 'default.png',
                'jabatan_id'    => $item[7],
                'tanggal_masuk' => '2022-01-10',
                'status_nikah'  => $item[9],
                'jumlah_anak'   => $item[10],
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }
        $db->table('karyawan')->insertBatch($karyawanData);

        // 4. Seed Presensi & Penggajian (Bulan 7, Tahun 2026)
        $presensiData = [];
        $penggajianData = [];

        // Map Jabatan Info by ID
        $jabatanMap = [];
        foreach ($jabatanData as $j) {
            $jabatanMap[$j['id']] = $j;
        }

        // Variasi kehadiran 15 karyawan untuk Bulan 7 Tahun 2026 (22 Hari Kerja Efektif)
        $rawPresensi = [
            // [karyawan_id, hadir, sakit, izin, alpa, lembur_jam]
            [1,  22, 0, 0, 0, 10],
            [2,  21, 1, 0, 0, 8],
            [3,  20, 0, 2, 0, 5],
            [4,  22, 0, 0, 0, 12],
            [5,  19, 1, 1, 1, 0],
            [6,  22, 0, 0, 0, 15],
            [7,  21, 0, 1, 0, 4],
            [8,  20, 1, 1, 0, 6],
            [9,  22, 0, 0, 0, 10],
            [10, 18, 2, 0, 2, 0],
            [11, 22, 0, 0, 0, 8],
            [12, 21, 0, 1, 0, 6],
            [13, 20, 1, 0, 1, 2],
            [14, 22, 0, 0, 0, 10],
            [15, 21, 1, 0, 0, 4],
        ];

        foreach ($rawPresensi as $p) {
            $kId   = $p[0];
            $hadir = $p[1];
            $sakit = $p[2];
            $izin  = $p[3];
            $alpa  = $p[4];
            $lembur= $p[5];

            $presensiData[] = [
                'id'                => $kId,
                'karyawan_id'       => $kId,
                'bulan'             => 7,
                'tahun'             => 2026,
                'jumlah_hadir'      => $hadir,
                'jumlah_sakit'      => $sakit,
                'jumlah_izin'       => $izin,
                'jumlah_alpa'       => $alpa,
                'jumlah_lembur_jam' => $lembur,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];

            // Calculation Logic
            $karyawan = $karyawanData[$kId - 1];
            $jabatan  = $jabatanMap[$karyawan['jabatan_id']];

            $gajiPokok   = (float)$jabatan['gaji_pokok'];
            $tunjJabatan = (float)$jabatan['tunj_jabatan'];
            
            // Tunjangan Kehadiran = (Hadir * Makan/Hari) + (Hadir * Transport/Hari)
            $tunjKehadiran = ($hadir * (float)$jabatan['tunj_makan_per_hari']) + ($hadir * (float)$jabatan['tunj_transport_per_hari']);

            // Tunjangan Keluarga = (10% jika Menikah) + (5% per Anak, Max 2)
            $tunjKeluarga = 0.00;
            if ($karyawan['status_nikah'] === 'Menikah') {
                $tunjKeluarga += 0.10 * $gajiPokok;
                $anakCount = min(2, (int)$karyawan['jumlah_anak']);
                $tunjKeluarga += (0.05 * $gajiPokok * $anakCount);
            }

            // Bonus Lembur = Jam Lembur * (1.5 * Gaji Pokok / 173)
            $bonusLembur = $lembur * (1.5 * ($gajiPokok / 173));

            // Total Pendapatan
            $totalPendapatan = $gajiPokok + $tunjJabatan + $tunjKehadiran + $tunjKeluarga + $bonusLembur;

            // Potongan BPJS Kesehatan (1%) & Ketenagakerjaan (2%)
            $potBpjsKs = 0.01 * $gajiPokok;
            $potBpjsTk = 0.02 * $gajiPokok;

            // Potongan PPh 21 (5% Progresif Gaji Pokok)
            $potPph21 = 0.05 * $gajiPokok;

            // Potongan Absensi = Alpa * (Gaji Pokok / 22)
            $potAbsensi = $alpa * ($gajiPokok / 22.0);

            // Total Potongan
            $totalPotongan = $potBpjsKs + $potBpjsTk + $potPph21 + $potAbsensi;

            // Gaji Bersih
            $gajiBersih = $totalPendapatan - $totalPotongan;

            $kodeTrx = 'TRX-PAY-202607-' . str_pad($kId, 3, '0', STR_PAD_LEFT);

            $penggajianData[] = [
                'id'                  => $kId,
                'kode_transaksi'      => $kodeTrx,
                'karyawan_id'         => $kId,
                'bulan'               => 7,
                'tahun'               => 2026,
                'gaji_pokok'          => round($gajiPokok, 2),
                'tunj_jabatan'        => round($tunjJabatan, 2),
                'tunj_kehadiran'      => round($tunjKehadiran, 2),
                'tunj_keluarga'       => round($tunjKeluarga, 2),
                'bonus_lembur'        => round($bonusLembur, 2),
                'total_pendapatan'    => round($totalPendapatan, 2),
                'pot_bpjs_ks'         => round($potBpjsKs, 2),
                'pot_bpjs_tk'         => round($potBpjsTk, 2),
                'pot_pph21'           => round($potPph21, 2),
                'pot_absensi'         => round($potAbsensi, 2),
                'total_potongan'      => round($totalPotongan, 2),
                'gaji_bersih'         => round($gajiBersih, 2),
                'foto_bukti_transfer' => null,
                'tanggal_dibayar'     => $now,
                'status_bayar'        => 'Lunas',
                'created_at'          => $now,
                'updated_at'          => $now,
            ];
        }

        $db->table('presensi')->insertBatch($presensiData);
        $db->table('penggajian')->insertBatch($penggajianData);
    }
}
