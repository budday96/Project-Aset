<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// $routes->set404Override(function () {
//     return view('errors/html/custom_404');
// });

$routes->get('login', 'Auth\LoginController::login');
$routes->post('login', 'Auth\LoginController::attemptLogin');
$routes->get('logout', 'Auth\LoginController::logout');


$routes->group('superadmin', ['filter' => 'role:superadmin'], function ($routes) {

    $routes->get('aset/getMasterAset', 'Superadmin\Aset::getMasterAset');
    $routes->get('aset/detailMaster/(:num)', 'Superadmin\Aset::detailMaster/$1');

    $routes->get('superadmin/aset/qr/(:num)', 'Barcode::qr/$1');

    // Dashboard
    $routes->get('dashboard', 'Superadmin\Dashboard::index');

    // User Management
    $routes->get('user', 'Superadmin\UserManagement::index');
    $routes->get('user/create', 'Superadmin\UserManagement::create');
    $routes->post('user/store', 'Superadmin\UserManagement::store');
    $routes->get('user/edit', 'Superadmin\UserManagement::edit');
    $routes->get('user/detail', 'Superadmin\UserManagement::detail');
    $routes->get('user/detail/(:num)', 'Superadmin\UserManagement::detail/$1');
    $routes->get('user/edit/(:num)', 'Superadmin\UserManagement::edit/$1');
    $routes->post('user/update/(:num)', 'Superadmin\Usermanagement::update/$1');
    $routes->post('user/delete/(:num)', 'Superadmin\Usermanagement::delete/$1');
    $routes->post('user/toggle/(:num)', 'Superadmin\UserManagement::toggleActive/$1');

    // Profile
    $routes->get('profile', 'Superadmin\Profile::index');
    $routes->post('profile/update', 'Superadmin\Profile::update');

    // User Approval
    $routes->get('userapproval', 'Superadmin\UserApproval::index');
    $routes->post('userapproval/setrole/(:num)', 'Superadmin\UserApproval::setRole/$1');

    // Kelola Cabang
    $routes->get('cabang', 'Superadmin\Cabang::index');
    $routes->get('cabang/trash', 'Superadmin\Cabang::trash');
    $routes->get('cabang/create', 'Superadmin\Cabang::create');
    $routes->post('cabang/store', 'Superadmin\Cabang::store');
    $routes->get('cabang/edit', 'Superadmin\Cabang::edit');
    $routes->get('cabang/edit/(:num)', 'Superadmin\Cabang::edit/$1');
    $routes->post('cabang/update/(:num)', 'Superadmin\Cabang::update/$1');
    $routes->post('cabang/delete/(:num)', 'Superadmin\Cabang::delete/$1');
    $routes->get('cabang/restore/(:num)', 'Superadmin\Cabang::restore/$1');
    $routes->get('cabang/purge/(:num)',   'Superadmin\Cabang::purge/$1');

    // Kelola Kategori
    $routes->get('kategori', 'Superadmin\KategoriAset::index');
    $routes->get('kategori/trash', 'Superadmin\KategoriAset::trash');
    $routes->get('kategori/create', 'Superadmin\KategoriAset::create');
    $routes->post('kategori/store', 'Superadmin\KategoriAset::store');
    $routes->get('kategori/edit', 'Superadmin\KategoriAset::edit');
    $routes->get('kategori/edit/(:num)', 'Superadmin\KategoriAset::edit/$1');
    $routes->post('kategori/update/(:num)', 'Superadmin\KategoriAset::update/$1');
    $routes->post('kategori/delete/(:num)', 'Superadmin\KategoriAset::delete/$1');
    $routes->get('kategori/restore/(:num)', 'Superadmin\KategoriAset::restore/$1');
    $routes->get('kategori/purge/(:num)',   'Superadmin\KategoriAset::purge/$1');

    // AJAX
    $routes->get('subkategori/by-kategori/(:num)', 'Superadmin\AsetAjax::subkategoriByKategori/$1');
    $routes->get('atribut/by-subkategori/(:num)',  'Superadmin\AsetAjax::atributBySubkategori/$1');

    // CRUD Subkategori
    $routes->get('subkategori',                'Superadmin\Subkategori::index');
    $routes->get('subkategori/trash',          'Superadmin\Subkategori::trash');
    $routes->get('subkategori/create',         'Superadmin\Subkategori::create');
    $routes->post('subkategori/store',         'Superadmin\Subkategori::store');
    $routes->get('subkategori/(:num)/edit',    'Superadmin\Subkategori::edit/$1');
    $routes->post('subkategori/(:num)/update', 'Superadmin\Subkategori::update/$1');
    $routes->post('subkategori/(:num)/delete', 'Superadmin\Subkategori::delete/$1');
    $routes->get('subkategori/restore/(:num)', 'Superadmin\Subkategori::restore/$1');
    $routes->get('subkategori/purge/(:num)',   'Superadmin\Subkategori::purge/$1');

    // CRUD Atribut
    $routes->get('atribut/(:num)',         'Superadmin\Atribut::index/$1');   // $1=id_subkategori
    $routes->get('atribut/(:num)/create',  'Superadmin\Atribut::create/$1');
    $routes->post('atribut/store',         'Superadmin\Atribut::store');
    $routes->get('atribut/edit/(:num)',    'Superadmin\Atribut::edit/$1');    // $1=id_atribut
    $routes->post('atribut/update/(:num)', 'Superadmin\Atribut::update/$1');
    $routes->post('atribut/delete/(:num)', 'Superadmin\Atribut::delete/$1');

    // Kelola Aset
    $routes->get('aset', 'Superadmin\Aset::index');
    $routes->get('aset/create', 'Superadmin\Aset::create');
    $routes->post('aset/store', 'Superadmin\Aset::store');
    $routes->get('aset/edit/(:num)', 'Superadmin\Aset::edit/$1');
    $routes->post('aset/update/(:num)', 'Superadmin\Aset::update/$1');
    $routes->get('aset/detail/(:num)', 'Superadmin\Aset::detail/$1');
    $routes->post('aset/delete/(:num)', 'Superadmin\Aset::delete/$1');

    // Kelompok Harta
    $routes->get('kelompokharta', 'Superadmin\KelompokHarta::index');
    $routes->get('kelompokharta/create', 'Superadmin\KelompokHarta::create');
    $routes->post('kelompokharta/store', 'Superadmin\KelompokHarta::store');
    $routes->get('kelompokharta/edit/(:num)', 'Superadmin\KelompokHarta::edit/$1');
    $routes->post('kelompokharta/update/(:num)', 'Superadmin\KelompokHarta::update/$1');
    $routes->get('kelompokharta/delete/(:num)', 'Superadmin\KelompokHarta::delete/$1');

    // Penyusutan Aset
    $routes->get('penyusutan', 'Superadmin\PenyusutanLaporan::index');
    $routes->post('penyusutan/generate', 'Superadmin\PenyusutanAset::generateBulanan');

    // Laporan Penyusutan Aset
    $routes->get('penyusutan/laporan', 'Superadmin\PenyusutanLaporan::index');
    $routes->get('penyusutan/detail/(:num)', 'Superadmin\PenyusutanLaporan::detail/$1');


    // Arsip aset
    $routes->get('aset/trash', 'Superadmin\Aset::trash');
    $routes->post('aset/restore/(:num)', 'Superadmin\Aset::restore/$1');
    $routes->post('aset/purge/(:num)', 'Superadmin\Aset::purge/$1');

    // AJAX Master-Detail Aset
    $routes->get('aset/ajaxMasterDetail/(:num)', 'Superadmin\Aset::ajaxMasterDetail/$1');


    // Master Aset
    $routes->get('master-aset',                 'Superadmin\MasterAset::index');
    $routes->get('master-aset/trash',           'Superadmin\MasterAset::trash');
    $routes->get('master-aset/create',          'Superadmin\MasterAset::create');
    $routes->post('master-aset/store',          'Superadmin\MasterAset::store');
    $routes->get('master-aset/detail/(:num)',   'Superadmin\MasterAset::detail/$1');
    $routes->get('master-aset/edit/(:num)',     'Superadmin\MasterAset::edit/$1');
    $routes->post('master-aset/update/(:num)',  'Superadmin\MasterAset::update/$1');
    $routes->post('master-aset/delete/(:num)',  'Superadmin\MasterAset::delete/$1');

    $routes->post('master-aset/restore/(:num)', 'Superadmin\MasterAset::restore/$1');
    $routes->post('master-aset/purge/(:num)',   'Superadmin\MasterAset::purge/$1');

    // Master Aset Pintasan
    $routes->get('master-aset/subkategori/(:num)', 'Superadmin\MasterAset::ajaxSubkategori/$1');   // GET list subkategori by kategori
    $routes->post('master-aset/quick-store', 'Superadmin\MasterAset::quickStore');                  // POST create master cepat (AJAX)

    // Mutasi Aset
    $routes->get('mutasi',              'Superadmin\MutasiAset::index');
    $routes->get('mutasi/create',       'Superadmin\MutasiAset::create');
    $routes->post('mutasi/store',       'Superadmin\MutasiAset::store');
    $routes->get('mutasi/(:num)',       'Superadmin\MutasiAset::show/$1');
    $routes->post('mutasi/kirim-header/(:num)', 'Superadmin\MutasiAset::kirimHeader/$1');
    $routes->post('mutasi/batal-header/(:num)', 'Superadmin\MutasiAset::batalHeader/$1');
    $routes->post('mutasi/terima-header/(:num)', 'Superadmin\MutasiAset::terimaHeader/$1');


    // $routes->get('mutasi',     'Superadmin\MutasiAset::index');
    // $routes->get('mutasi/create',     'Superadmin\MutasiAset::create');
    // $routes->post('mutasi/store',     'Superadmin\MutasiAset::store');
    // $routes->post('mutasi/kirim-header/(:num)', 'Superadmin\MutasiAset::kirimHeader/$1');
    // $routes->post('mutasi/batal-header/(:num)', 'Superadmin\MutasiAset::batalHeader/$1');
    // $routes->post('mutasi/terima-header/(:num)', 'Superadmin\MutasiAset::terimaHeader/$1');

    // AJAX dropdown aset berdasarkan cabang asal
    // $routes->get('mutasi/assets-by-cabang/(:num)', 'Superadmin\MutasiAset::assetsByCabang/$1');

    // Riwayat Mutasi Aset
    $routes->get('riwayat-mutasi', 'Superadmin\RiwayatMutasi::index');
});

// QR code
// Rute publik (tanpa login)
$routes->group('p', function ($routes) {
    $routes->get('aset/(:segment)', 'PublicAset::detail/$1'); // $1 = qr_token
    $routes->get('qr/(:segment)', 'PublicBarcode::qr/$1');    // gambar QR untuk token
});



$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Kelola Aset
    $routes->get('aset', 'Admin\Aset::index');
    $routes->get('aset/create', 'Admin\Aset::create');
    $routes->post('aset/store', 'Admin\Aset::store');
    $routes->get('aset/edit', 'Admin\Aset::edit');
    $routes->get('aset/detail', 'Admin\Aset::detail');
    $routes->get('aset/edit/(:num)', 'Admin\Aset::edit/$1');
    $routes->post('aset/update/(:num)', 'Admin\Aset::update/$1');
    $routes->get('aset/detail/(:num)', 'Admin\Aset::detail/$1');
    $routes->post('aset/delete/(:num)', 'Admin\Aset::delete/$1');

    // Kategori Aset
    $routes->get('kategori', 'Admin\KategoriAset::index');
    $routes->get('kategori/trash', 'Admin\KategoriAset::trash');
    $routes->get('kategori/create', 'Admin\KategoriAset::create');
    $routes->post('kategori/store', 'Admin\KategoriAset::store');
    $routes->get('kategori/edit/(:num)', 'Admin\KategoriAset::edit/$1');
    $routes->post('kategori/update/(:num)', 'Admin\KategoriAset::update/$1');
    $routes->post('kategori/delete/(:num)', 'Admin\KategoriAset::delete/$1');
    $routes->get('kategori/restore/(:num)', 'Admin\KategoriAset::restore/$1');

    // AJAX
    $routes->get('subkategori/by-kategori/(:num)', 'Admin\AsetAjax::subkategoriByKategori/$1');
    $routes->get('atribut/by-subkategori/(:num)',  'Admin\AsetAjax::atributBySubkategori/$1');

    // CRUD Subkategori
    $routes->get('subkategori',                'Admin\Subkategori::index');
    $routes->get('subkategori/trash',          'Admin\Subkategori::trash');
    $routes->get('subkategori/create',         'Admin\Subkategori::create');
    $routes->post('subkategori/store',         'Admin\Subkategori::store');
    $routes->get('subkategori/(:num)/edit',    'Admin\Subkategori::edit/$1');
    $routes->post('subkategori/(:num)/update', 'Admin\Subkategori::update/$1');
    $routes->post('subkategori/(:num)/delete', 'Admin\Subkategori::delete/$1');
    $routes->get('subkategori/restore/(:num)', 'Admin\Subkategori::restore/$1');
    $routes->get('subkategori/purge/(:num)',   'Admin\Subkategori::purge/$1');

    // CRUD Atribut
    $routes->get('atribut/(:num)',         'Admin\Atribut::index/$1');   // $1=id_subkategori
    $routes->get('atribut/(:num)/create',  'Admin\Atribut::create/$1');
    $routes->post('atribut/store',         'Admin\Atribut::store');
    $routes->get('atribut/edit/(:num)',    'Admin\Atribut::edit/$1');    // $1=id_atribut
    $routes->post('atribut/update/(:num)', 'Admin\Atribut::update/$1');
    $routes->post('atribut/delete/(:num)', 'Admin\Atribut::delete/$1');

    // Master Aset
    $routes->get('master-aset',                 'Admin\MasterAset::index');
    $routes->get('master-aset/trash',           'Admin\MasterAset::trash');
    $routes->get('master-aset/create',          'Admin\MasterAset::create');
    $routes->post('master-aset/store',          'Admin\MasterAset::store');
    $routes->get('master-aset/detail/(:num)',   'Admin\MasterAset::detail/$1');
    $routes->get('master-aset/edit/(:num)',     'Admin\MasterAset::edit/$1');
    $routes->post('master-aset/update/(:num)',  'Admin\MasterAset::update/$1');
    $routes->post('master-aset/delete/(:num)',  'Admin\MasterAset::delete/$1');

    $routes->post('master-aset/restore/(:num)', 'Admin\MasterAset::restore/$1');
    $routes->post('master-aset/purge/(:num)',   'Admin\MasterAset::purge/$1');

    // AJAX Master-Detail Aset
    $routes->get('aset/ajaxMasterDetail/(:num)', 'Admin\Aset::ajaxMasterDetail/$1');

    $routes->get('aset/getMasterAset', 'Admin\Aset::getMasterAset');
    $routes->get('aset/detailMaster/(:num)', 'Admin\Aset::detailMaster/$1');

    // Kelola Aset
    $routes->get('aset', 'Admin\Aset::index');
    $routes->get('aset/create', 'Admin\Aset::create');
    $routes->post('aset/store', 'Admin\Aset::store');
    $routes->get('aset/edit/(:num)', 'Admin\Aset::edit/$1');
    $routes->post('aset/update/(:num)', 'Admin\Aset::update/$1');
    $routes->get('aset/detail/(:num)', 'Admin\Aset::detail/$1');
    $routes->post('aset/delete/(:num)', 'Admin\Aset::delete/$1');

    // Master Aset Pintasan
    $routes->get('master-aset/subkategori/(:num)', 'Admin\MasterAset::ajaxSubkategori/$1');   // GET list subkategori by kategori
    $routes->post('master-aset/quick-store', 'Admin\MasterAset::quickStore');                  // POST create master cepat (AJAX)

    $routes->get('laporan', 'Admin\LaporanAset::index');
    $routes->get('laporan/(:num)', 'Admin\LaporanAset::detail/$1');
    $routes->post('laporan/approve/(:num)', 'Admin\LaporanAset::approve/$1');
    $routes->post('laporan/reject/(:num)', 'Admin\LaporanAset::reject/$1');
    $routes->get('laporan/assign/(:num)', 'Admin\LaporanAset::assignForm/$1');
    $routes->post('laporan/assign/(:num)', 'Admin\LaporanAset::assign/$1');
    $routes->get('laporan/progress/(:num)', 'Admin\LaporanAset::progressForm/$1');
    $routes->post('laporan/progress/(:num)', 'Admin\LaporanAset::updateProgress/$1');

    $routes->get('vendor', 'Admin\Vendor::index');
    $routes->get('vendor/create', 'Admin\Vendor::create');
    $routes->post('vendor/store', 'Admin\Vendor::store');
    $routes->get('vendor/edit/(:num)', 'Admin\Vendor::edit/$1');
    $routes->post('vendor/update/(:num)', 'Admin\Vendor::update/$1');
    $routes->get('vendor/toggle/(:num)', 'Admin\Vendor::toggle/$1');


    // Mutasi Aset
    $routes->get('mutasi',              'Admin\MutasiAset::index');
    $routes->get('mutasi/create',       'Admin\MutasiAset::create');
    $routes->post('mutasi/store',       'Admin\MutasiAset::store');
    $routes->get('mutasi/edit/(:num)', 'Admin\MutasiAset::edit/$1');
    $routes->post('mutasi/update/(:num)', 'Admin\MutasiAset::update/$1');
    $routes->get('mutasi/(:num)',       'Admin\MutasiAset::show/$1');
    $routes->post('mutasi/kirim-header/(:num)', 'Admin\MutasiAset::kirimHeader/$1');
    $routes->post('mutasi/batal-header/(:num)', 'Admin\MutasiAset::batalHeader/$1');
    $routes->post('mutasi/terima-header/(:num)', 'Admin\MutasiAset::terimaHeader/$1');

    // Surat Jalan Mutasi Aset
    $routes->get('mutasi/surat-jalan/(:num)', 'Admin\MutasiAset::suratJalan/$1');

    // Notifikasi
    $routes->get('notifikasi', 'Admin\Notifikasi::index');
    $routes->get('notifikasi/read-all', 'Admin\Notifikasi::markAllRead');
    $routes->get('notifikasi/open/(:num)', 'Admin\Notifikasi::open/$1');



    // Profile
    $routes->get('profile', 'Admin\Profile::index');
    $routes->post('profile/update', 'Admin\Profile::update');

    // User Management

    // User Management - Admin Cabang
    $routes->get('user', 'Admin\UserManagement::index');
    $routes->get('user/create', 'Admin\UserManagement::create');
    $routes->post('user/store', 'Admin\UserManagement::store');
    $routes->get('user/edit/(:num)', 'Admin\UserManagement::edit/$1');
    $routes->get('user/detail/(:num)', 'Admin\UserManagement::detail/$1');
    $routes->post('user/update/(:num)', 'Admin\UserManagement::update/$1');
    $routes->post('user/delete/(:num)', 'Admin\UserManagement::delete/$1');
    $routes->post('user/toggle/(:num)', 'Admin\UserManagement::toggleActive/$1');
});

$routes->group('user', ['filter' => 'role:user'], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'User\Dashboard::index');

    // Kelola Aset
    $routes->get('aset', 'User\Aset::index');
    $routes->get('aset/create', 'User\Aset::create');
    $routes->post('aset/store', 'User\Aset::store');
    $routes->get('aset/edit', 'User\Aset::edit');
    $routes->get('aset/detail', 'User\Aset::detail');
    $routes->get('aset/edit/(:num)', 'User\Aset::edit/$1');
    $routes->post('aset/update/(:num)', 'User\Aset::update/$1');
    $routes->get('aset/detail/(:num)', 'User\Aset::detail/$1');
    $routes->post('aset/delete/(:num)', 'User\Aset::delete/$1');

    // Kategori Aset
    $routes->get('kategori', 'User\KategoriAset::index');
    $routes->get('kategori/trash', 'User\KategoriAset::trash');
    $routes->get('kategori/create', 'User\KategoriAset::create');
    $routes->post('kategori/store', 'User\KategoriAset::store');
    $routes->get('kategori/edit/(:num)', 'User\KategoriAset::edit/$1');
    $routes->post('kategori/update/(:num)', 'User\KategoriAset::update/$1');
    $routes->post('kategori/delete/(:num)', 'User\KategoriAset::delete/$1');
    $routes->get('kategori/restore/(:num)', 'User\KategoriAset::restore/$1');

    // AJAX
    $routes->get('subkategori/by-kategori/(:num)', 'User\AsetAjax::subkategoriByKategori/$1');
    $routes->get('atribut/by-subkategori/(:num)',  'User\AsetAjax::atributBySubkategori/$1');

    // CRUD Subkategori
    $routes->get('subkategori',                'User\Subkategori::index');
    $routes->get('subkategori/trash',          'User\Subkategori::trash');
    $routes->get('subkategori/create',         'User\Subkategori::create');
    $routes->post('subkategori/store',         'User\Subkategori::store');
    $routes->get('subkategori/(:num)/edit',    'User\Subkategori::edit/$1');
    $routes->post('subkategori/(:num)/update', 'User\Subkategori::update/$1');
    $routes->post('subkategori/(:num)/delete', 'User\Subkategori::delete/$1');
    $routes->get('subkategori/restore/(:num)', 'User\Subkategori::restore/$1');
    $routes->get('subkategori/purge/(:num)',   'User\Subkategori::purge/$1');

    // CRUD Atribut
    $routes->get('atribut/(:num)',         'User\Atribut::index/$1');   // $1=id_subkategori
    $routes->get('atribut/(:num)/create',  'User\Atribut::create/$1');
    $routes->post('atribut/store',         'User\Atribut::store');
    $routes->get('atribut/edit/(:num)',    'User\Atribut::edit/$1');    // $1=id_atribut
    $routes->post('atribut/update/(:num)', 'User\Atribut::update/$1');
    $routes->post('atribut/delete/(:num)', 'User\Atribut::delete/$1');

    // Master Aset
    $routes->get('master-aset',                 'User\MasterAset::index');
    $routes->get('master-aset/trash',           'User\MasterAset::trash');
    $routes->get('master-aset/create',          'User\MasterAset::create');
    $routes->post('master-aset/store',          'User\MasterAset::store');
    $routes->get('master-aset/detail/(:num)',   'User\MasterAset::detail/$1');
    $routes->get('master-aset/edit/(:num)',     'User\MasterAset::edit/$1');
    $routes->post('master-aset/update/(:num)',  'User\MasterAset::update/$1');
    $routes->post('master-aset/delete/(:num)',  'User\MasterAset::delete/$1');

    $routes->post('master-aset/restore/(:num)', 'User\MasterAset::restore/$1');
    $routes->post('master-aset/purge/(:num)',   'User\MasterAset::purge/$1');

    // AJAX Master-Detail Aset
    $routes->get('aset/ajaxMasterDetail/(:num)', 'User\Aset::ajaxMasterDetail/$1');

    $routes->get('aset/getMasterAset', 'User\Aset::getMasterAset');
    $routes->get('aset/detailMaster/(:num)', 'User\Aset::detailMaster/$1');

    // Kelola Aset
    $routes->get('aset', 'User\Aset::index');
    $routes->get('aset/create', 'User\Aset::create');
    $routes->post('aset/store', 'User\Aset::store');
    $routes->get('aset/edit/(:num)', 'User\Aset::edit/$1');
    $routes->post('aset/update/(:num)', 'User\Aset::update/$1');
    $routes->get('aset/detail/(:num)', 'User\Aset::detail/$1');
    $routes->post('aset/delete/(:num)', 'User\Aset::delete/$1');

    // Master Aset Pintasan
    $routes->get('master-aset/subkategori/(:num)', 'User\MasterAset::ajaxSubkategori/$1');   // GET list subkategori by kategori
    $routes->post('master-aset/quick-store', 'User\MasterAset::quickStore');                  // POST create master cepat (AJAX)


    // Laporan Aset
    $routes->get('laporan', 'User\LaporanAset::index');
    $routes->get('laporan/create', 'User\LaporanAset::create');
    $routes->post('laporan/store', 'User\LaporanAset::store');
    $routes->get('laporan/detail/(:num)', 'User\LaporanAset::detail/$1');


    $routes->post('laporan/confirm/(:num)', 'User\LaporanAset::confirm/$1');

    $routes->get('laporan/aset/(:num)', 'User\LaporanAset::getAsetDetail/$1');



    // Mutasi Aset
    $routes->get('mutasi',              'User\MutasiAset::index');
    $routes->get('mutasi/create',       'User\MutasiAset::create');
    $routes->post('mutasi/store',       'User\MutasiAset::store');
    $routes->get('mutasi/edit/(:num)', 'User\MutasiAset::edit/$1');
    $routes->post('mutasi/update/(:num)', 'User\MutasiAset::update/$1');
    $routes->get('mutasi/(:num)',       'User\MutasiAset::show/$1');
    $routes->post('mutasi/kirim-header/(:num)', 'User\MutasiAset::kirimHeader/$1');
    $routes->post('mutasi/batal-header/(:num)', 'User\MutasiAset::batalHeader/$1');
    $routes->post('mutasi/terima-header/(:num)', 'User\MutasiAset::terimaHeader/$1');

    // Surat Jalan Mutasi Aset
    $routes->get('mutasi/surat-jalan/(:num)', 'User\MutasiAset::suratJalan/$1');

    // Notifikasi
    $routes->get('notifikasi', 'User\Notifikasi::index');
    $routes->get('notifikasi/read-all', 'User\Notifikasi::markAllRead');
    $routes->get('notifikasi/open/(:num)', 'User\Notifikasi::open/$1');



    // Profile
    $routes->get('profile', 'User\Profile::index');
    $routes->post('profile/update', 'User\Profile::update');

    // User Management

    // User Management - User Cabang
    $routes->get('user', 'User\UserManagement::index');
    $routes->get('user/create', 'User\UserManagement::create');
    $routes->post('user/store', 'User\UserManagement::store');
    $routes->get('user/edit/(:num)', 'User\UserManagement::edit/$1');
    $routes->get('user/detail/(:num)', 'User\UserManagement::detail/$1');
    $routes->post('user/update/(:num)', 'User\UserManagement::update/$1');
    $routes->post('user/delete/(:num)', 'User\UserManagement::delete/$1');
    $routes->post('user/toggle/(:num)', 'User\UserManagement::toggleActive/$1');
});
