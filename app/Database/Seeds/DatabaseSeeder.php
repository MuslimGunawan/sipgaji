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

        // 1. Seed Users (1 Admin + 50 Karyawan)
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

        for ($i = 1; $i <= 50; $i++) {
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

        // 2. Seed Jabatan (Master Skema Gaji & Tunjangan)
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

        // 3. Seed 50 Karyawan Real & Presisi
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
            ['NIP2026016', 'Putri Anggraini', 'P', 'Banda Aceh', '1995-03-11', 'Jl. Syiah Kuala No. 18, Banda Aceh', '081269016666', 3, 17, 'Menikah', 1],
            ['NIP2026017', 'Qori Hidayat', 'L', 'Lhokseumawe', '1993-08-22', 'Jl. Elang No. 4, Lhokseumawe', '081269017777', 4, 18, 'Belum Menikah', 0],
            ['NIP2026018', 'Rahmi Sahara', 'P', 'Lhokseumawe', '2001-04-10', 'Jl. Malikussaleh No. 1, Lhokseumawe', '081269018888', 1, 19, 'Menikah', 2],
            ['NIP2026019', 'Rian Ardiansyah', 'L', 'Medan', '1992-12-19', 'Jl. Asia No. 55, Medan', '081269019999', 2, 20, 'Menikah', 1],
            ['NIP2026020', 'Sinta Bella', 'P', 'Langsa', '1996-07-07', 'Jl. Sudirman No. 12, Langsa', '081269020000', 5, 21, 'Belum Menikah', 0],
            ['NIP2026021', 'Taufik Hidayat', 'L', 'Bireuen', '1991-02-28', 'Jl. Batee Timoh No. 8, Bireuen', '081269021111', 2, 22, 'Menikah', 2],
            ['NIP2026022', 'Umar Faruq', 'L', 'Sigli', '1994-10-14', 'Jl. Keuniree No. 3, Sigli', '081269022222', 3, 23, 'Menikah', 1],
            ['NIP2026023', 'Vina Panduwinata', 'P', 'Takengon', '1997-01-05', 'Jl. Sengeda No. 9, Takengon', '081269023333', 4, 24, 'Belum Menikah', 0],
            ['NIP2026024', 'Wahyu Ramadhan', 'L', 'Lhokseumawe', '1993-09-16', 'Jl. Stadion No. 2, Lhokseumawe', '081269024444', 5, 25, 'Menikah', 2],
            ['NIP2026025', 'Xavier Iskandar', 'L', 'Medan', '1990-06-30', 'Jl. Putri Hijau No. 10, Medan', '081269025555', 1, 26, 'Menikah', 3],
            ['NIP2026026', 'Yulia Syahrini', 'P', 'Banda Aceh', '1996-11-21', 'Jl. Lueng Bata No. 4, Banda Aceh', '081269026666', 3, 27, 'Belum Menikah', 0],
            ['NIP2026027', 'Zahra', 'P', 'Lhokseumawe', '2002-05-15', 'Jl. Lancang Garam No. 7, Lhokseumawe', '081269027777', 2, 28, 'Belum Menikah', 0],
            ['NIP2026028', 'Aditia Maulana', 'L', 'Langsa', '1994-03-08', 'Jl. Kenanga No. 11, Langsa', '081269028888', 4, 29, 'Menikah', 1],
            ['NIP2026029', 'Bayu Skak', 'L', 'Bireuen', '1995-08-19', 'Jl. Juli No. 5, Bireuen', '081269029999', 5, 30, 'Belum Menikah', 0],
            ['NIP2026030', 'Cut Meyriska', 'P', 'Banda Aceh', '1993-12-01', 'Jl. Lampineung No. 20, Banda Aceh', '081269030000', 3, 31, 'Menikah', 2],
            ['NIP2026031', 'Dian Sastro', 'P', 'Medan', '1991-04-18', 'Jl. Imam Bonjol No. 15, Medan', '081269031111', 2, 32, 'Menikah', 2],
            ['NIP2026032', 'Erpan Kurnia', 'L', 'Lhokseumawe', '1996-02-27', 'Jl. H. Agus Salim No. 8, Lhokseumawe', '081269032222', 4, 33, 'Belum Menikah', 0],
            ['NIP2026033', 'Fitri Carlina', 'P', 'Meulaboh', '1995-10-10', 'Jl. Manek Roo No. 14, Meulaboh', '081269033333', 5, 34, 'Menikah', 1],
            ['NIP2026034', 'Gilang Dirga', 'L', 'Sigli', '1992-07-03', 'Jl. Benteng No. 6, Sigli', '081269034444', 1, 35, 'Menikah', 2],
            ['NIP2026035', 'Hanif Sjahbandi', 'L', 'Takengon', '1997-09-14', 'Jl. Simpang Lima No. 3, Takengon', '081269035555', 2, 36, 'Belum Menikah', 0],
            ['NIP2026036', 'Irma Darmawangsa', 'P', 'Sabang', '1994-01-22', 'Jl. Iboih No. 1, Sabang', '081269036666', 3, 37, 'Belum Menikah', 0],
            ['NIP2026037', 'Jefri Nichol', 'L', 'Lhokseumawe', '1998-05-09', 'Jl. Perintis Kemerdekaan No. 17, Lhokseumawe', '081269037777', 5, 38, 'Belum Menikah', 0],
            ['NIP2026038', 'Kevin Sanjaya', 'L', 'Medan', '1995-11-25', 'Jl. Krakatau No. 40, Medan', '081269038888', 2, 39, 'Belum Menikah', 0],
            ['NIP2026039', 'Luna Maya', 'P', 'Banda Aceh', '1992-06-13', 'Jl. Peunayong No. 8, Banda Aceh', '081269039999', 4, 40, 'Menikah', 1],
            ['NIP2026040', 'Nicoiwan Adha Kobat', 'L', 'Lhokseumawe', '2001-08-17', 'Jl. Unimal Utama No. 9, Lhokseumawe', '081269040000', 2, 41, 'Belum Menikah', 0],
            ['NIP2026041', 'Nabila Syakieb', 'P', 'Langsa', '1993-04-06', 'Jl. Rel Kereta No. 2, Langsa', '081269041111', 3, 42, 'Menikah', 2],
            ['NIP2026042', 'Oka Antara', 'L', 'Bireuen', '1990-10-31', 'Jl. Simpang Empat No. 12, Bireuen', '081269042222', 1, 43, 'Menikah', 3],
            ['NIP2026043', 'Prilly Latuconsina', 'P', 'Medan', '1996-12-15', 'Jl. Ring Road No. 88, Medan', '081269043333', 5, 44, 'Belum Menikah', 0],
            ['NIP2026044', 'Raditya Dika', 'L', 'Lhokseumawe', '1989-07-28', 'Jl. Teuku Hamzah No. 5, Lhokseumawe', '081269044444', 4, 45, 'Menikah', 2],
            ['NIP2026045', 'Syafiq Riza', 'L', 'Banda Aceh', '1992-03-03', 'Jl. Jeulingke No. 19, Banda Aceh', '081269045555', 2, 46, 'Menikah', 1],
            ['NIP2026046', 'Teuku Ryan', 'L', 'Sigli', '1994-09-18', 'Jl. Kuta Asan No. 7, Sigli', '081269046666', 3, 47, 'Menikah', 1],
            ['NIP2026047', 'Usman Harun', 'L', 'Meulaboh', '1991-05-24', 'Jl. Iskandar Muda No. 33, Meulaboh', '081269047777', 4, 48, 'Menikah', 2],
            ['NIP2026048', 'Vicky Shu', 'P', 'Takengon', '1995-02-12', 'Jl. Kebun Kopi No. 4, Takengon', '081269048888', 5, 49, 'Belum Menikah', 0],
            ['NIP2026049', 'Wafda Saifan', 'L', 'Langsa', '1993-11-09', 'Jl. Kebun Kelapa No. 16, Langsa', '081269049999', 2, 50, 'Belum Menikah', 0],
            ['NIP2026050', 'Azkal Azkiya', 'L', 'Lhokseumawe', '2001-01-01', 'Jl. Bukit Indah No. 10, Lhokseumawe', '081269050000', 3, 51, 'Belum Menikah', 0],
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

        // 4. Seed Presensi & Penggajian (Bulan 7, Tahun 2026) untuk 50 Karyawan
        $presensiData = [];
        $penggajianData = [];

        $jabatanMap = [];
        foreach ($jabatanData as $j) {
            $jabatanMap[$j['id']] = $j;
        }

        for ($kId = 1; $kId <= 50; $kId++) {
            // Randomize attendance & overtime realistically
            $hadir  = rand(20, 22);
            $sakit  = rand(0, 1);
            $izin   = rand(0, 1);
            $alpa   = 22 - ($hadir + $sakit + $izin);
            if ($alpa < 0) $alpa = 0;
            $lembur = rand(2, 16);

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
            
            $tunjKehadiran = ($hadir * (float)$jabatan['tunj_makan_per_hari']) + ($hadir * (float)$jabatan['tunj_transport_per_hari']);

            $tunjKeluarga = 0.00;
            if ($karyawan['status_nikah'] === 'Menikah') {
                $tunjKeluarga += 0.10 * $gajiPokok;
                $anakCount = min(2, (int)$karyawan['jumlah_anak']);
                $tunjKeluarga += (0.05 * $gajiPokok * $anakCount);
            }

            $bonusLembur = $lembur * (1.5 * ($gajiPokok / 173));

            $totalPendapatan = $gajiPokok + $tunjJabatan + $tunjKehadiran + $tunjKeluarga + $bonusLembur;

            $potBpjsKs = 0.01 * $gajiPokok;
            $potBpjsTk = 0.02 * $gajiPokok;
            $potPph21  = 0.05 * $gajiPokok;
            $potAbsensi= $alpa * ($gajiPokok / 22.0);

            $totalPotongan = $potBpjsKs + $potBpjsTk + $potPph21 + $potAbsensi;
            $gajiBersih    = $totalPendapatan - $totalPotongan;

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
