<?php

require 'vendor/autoload.php';

// Bootstrap CI4
define('FCPATH', __DIR__ . '/../public/');
$app = \Config\Services::codeigniter();
$app->initialize();

$karyawanModel = new \App\Models\KaryawanModel();
$k2Before = $karyawanModel->find(2);
echo "Before Update - Nama: " . $k2Before['nama'] . ", Foto: " . $k2Before['foto'] . "\n";

$result = $karyawanModel->update(2, [
    'nama' => 'Budi Santoso',
    'foto' => 'default.png'
]);

echo "Update result: " . ($result ? "SUCCESS" : "FAILED") . "\n";

$k2After = $karyawanModel->find(2);
echo "After Update - Nama: " . $k2After['nama'] . ", Foto: " . $k2After['foto'] . "\n";
