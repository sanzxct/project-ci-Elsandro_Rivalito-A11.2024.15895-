<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'auth']);
$routes->get('/keranjang', 'KeranjangController::keranjang', ['filter' => 'auth']);
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
});

