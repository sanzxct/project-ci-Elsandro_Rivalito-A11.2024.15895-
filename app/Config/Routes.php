<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'auth']);
$routes->get('/profil', 'ProfileController::profil', ['filter' => 'auth']);
$routes->get('/faq', 'Home::faq', ['filter' => 'auth']);
$routes->get('/contact', 'Home::contact', ['filter' => 'auth']);

//login dan logout
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

//product
$routes->group('produk', ['filter' => ['auth', 'admin']], function($routes) {
    $routes->get('/', 'ProdukController::produk');
    $routes->post('/', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

//keranjang
$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'KeranjangController::index');
    $routes->post('', 'KeranjangController::cart_add');
    $routes->post('edit', 'KeranjangController::cart_edit');
    $routes->get('delete/(:any)', 'KeranjangController::cart_delete/$1');
    $routes->get('clear', 'KeranjangController::cart_clear');
});

$routes->get('/keranjang', 'KeranjangController::keranjang', ['filter' => 'auth']);


//checkout
$routes->get('checkout', 'KeranjangController::checkout', ['filter' => 'auth']);

//search
$routes->get('ajax/destinations','KeranjangController::destinations', ['filter' => 'auth']);
$routes->get('ajax/costs','KeranjangController::costs', ['filter' => 'auth']);

$routes->post('buy', 'KeranjangController::buy', ['filter' => 'auth']);


//history
$routes->get('history', 'KeranjangController::history', ['filter' => 'auth']);


//api
$routes->resource('api/products', ['controller' => 'Api\ProdukController']);


$routes->get('api/transactions', 'Api\TransaksiController::index');