<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Auto-sync session data for logged-in Karyawan to reflect live DB changes (e.g. photo updated by Admin)
        $session = service('session');
        if ($session->get('isLoggedIn') && $session->get('role') === 'karyawan') {
            $userId = $session->get('userId');
            $db = \Config\Database::connect();
            $karyawan = $db->table('karyawan')->where('user_id', $userId)->get()->getRowArray();
            if ($karyawan) {
                $session->set('foto', !empty($karyawan['foto']) ? $karyawan['foto'] : 'default.png');
                $session->set('namaLengkap', $karyawan['nama']);
            }
        }
    }
}
