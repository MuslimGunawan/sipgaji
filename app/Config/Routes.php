<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    return session()->get('isLoggedIn') ? redirect()->to('/dashboard') : redirect()->to('/login');
});

// Authentication Routes
$routes->get('login', 'Auth::index');
$routes->match(['get', 'post'], 'login/process', 'Auth::processLogin');
$routes->get('logout', 'Auth::logout');

// Authenticated Routes
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    $routes->get('presensi', 'Presensi::index');
    $routes->get('penggajian', 'Penggajian::index');
    $routes->get('penggajian/slip/(:num)', 'Penggajian::slip/$1');

    // Admin Only Routes
    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        // Master Data Jabatan
        $routes->get('jabatan', 'Jabatan::index');
        $routes->post('jabatan/store', 'Jabatan::store');
        $routes->post('jabatan/update/(:num)', 'Jabatan::update/$1');
        $routes->get('jabatan/delete/(:num)', 'Jabatan::delete/$1');

        // Master Data Karyawan
        $routes->get('karyawan', 'Karyawan::index');
        $routes->post('karyawan/store', 'Karyawan::store');
        $routes->post('karyawan/update/(:num)', 'Karyawan::update/$1');
        $routes->get('karyawan/delete/(:num)', 'Karyawan::delete/$1');

        // Presensi Input
        $routes->post('presensi/store', 'Presensi::store');

        // Penggajian & Komputasi
        $routes->post('penggajian/hitung', 'Penggajian::hitungOtomatis');
        $routes->post('penggajian/upload-bukti/(:num)', 'Penggajian::uploadBukti/$1');

        // Log Aktivitas System
        $routes->get('activity-logs', 'ActivityLog::index');
        $routes->post('activity-logs/clear', 'ActivityLog::clear');
    });
});
