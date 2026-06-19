<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/menu', 'Home::menu');
$routes->get('/add-to-cart/(:num)', 'Home::addToCart/$1');
$routes->get('/cart', 'Home::cart');
$routes->get('/clear-cart', 'Home::clearCart');

$routes->get('/login', 'Auth::login');
$routes->post('/login/attempt', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

// Admin routes (protected by controller check)
$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->get('/admin/form-menu', 'Admin::formMenu');
$routes->get('/admin/form-menu/(:num)', 'Admin::formMenu/$1');
$routes->post('/admin/save-menu', 'Admin::saveMenu');
$routes->get('/admin/delete-menu/(:num)', 'Admin::deleteMenu/$1');