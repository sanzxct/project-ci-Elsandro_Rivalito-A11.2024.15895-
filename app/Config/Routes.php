<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'auth']);
$routes->get('/produk', 'ProdukController::produk', ['filter' => ['auth', 'admin']]);
$routes->get('/keranjang', 'KeranjangController::keranjang', ['filter' => 'auth']);
$routes->get('/profil', 'ProfileController::profil', ['filter' => 'auth']);
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');