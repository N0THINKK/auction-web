<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

$routes->get('/', 'Lelang::index');
$routes->get('lelang/view/(:num)', 'Lelang::view/$1');
$routes->post('lelang/placeBid/(:num)', 'Lelang::placeBid/$1');
$routes->get('lelang/closeAuction/(:num)', 'Lelang::closeAuction/$1');
$routes->post('lelang/makePayment/(:num)', 'Lelang::makePayment/$1');

$routes->get('auth/register', 'Auth::register');
$routes->post('auth/doRegister', 'Auth::doRegister');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin');
$routes->get('auth/logout', 'Auth::logout');

$routes->get('lelang/transactionHistory', 'Lelang::transactionHistory');

$routes->post('bids/create/(:num)', 'Bids::create/$1');

$routes->get('/', 'Home::index');
$routes->get('products', 'Products::index');
$routes->get('profile', 'Profile::index');
$routes->get('logout', 'Auth::logout');
$routes->get('login', 'Auth::login');

$routes->get('products', 'Products::index'); // Menampilkan daftar produk
$routes->get('products/create', 'Products::create'); // Menampilkan form create produk
$routes->post('products/store', 'Products::store'); // Menyimpan produk baru

$routes->get('/profile/history', 'Profile::history');
$routes->get('/profile/history/item/(:num)', 'Profile::itemDetail/$1');


$routes->get('transactions/history', 'TransactionController::history');  // Rute untuk riwayat transaksi
$routes->get('transactions/complete/(:num)', 'TransactionController::completeTransaction/$1');  // Rute untuk menyelesaikan transaksi

$routes->post('/items/store', 'ItemController::store');

$routes->get('/items', 'ItemController::index');

$routes->get('/payment', 'Payments::index');

$routes->post('/payment/choose/(:num)', 'Payment::choose/$1');
$routes->post('/payment/process/(:num)', 'Payment::process/$1');
$routes->get('/payment/status/(:num)', 'Payment::status/$1');

$routes->post('/payment/confirm_transfer/(:num)', 'Payment::confirmTransfer/$1');
$routes->get('/admin/verifications', 'AdminController::verifications');
$routes->get('/admin/verification/(:num)', 'AdminController::verification/$1');
$routes->post('/admin/verify/(:num)', 'AdminController::verify/$1');

$routes->match(['get', 'post'], '/profile/edit', 'Profile::edit');
