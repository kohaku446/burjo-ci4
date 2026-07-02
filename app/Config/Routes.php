<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─── Halaman Publik ───
$routes->get('/', 'Home::index');
$routes->get('/menu', 'Home::menu');

// ─── SOAL 3: Export PDF ───
$routes->get('/menu/export-pdf', 'Home::exportMenuPdf');

// ─── SOAL 4: Cart Library ───
$routes->get('/add-to-cart/(:num)', 'Home::addToCart/$1');      // insert()
$routes->post('/cart/update', 'Home::updateCart');               // update()
$routes->get('/cart/remove/(:num)', 'Home::removeFromCart/$1'); // remove()
$routes->get('/cart', 'Home::cart');                             // total() + contents()
$routes->get('/clear-cart', 'Home::clearCart');                  // destroy()

// ─── Auth ───
$routes->get('/login', 'Auth::login');
$routes->post('/login/attempt', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

// ─── Admin (protected by controller check) ───
$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->get('/admin/form-menu', 'Admin::formMenu');
$routes->get('/admin/form-menu/(:num)', 'Admin::formMenu/$1');
$routes->post('/admin/save-menu', 'Admin::saveMenu');
$routes->get('/admin/delete-menu/(:num)', 'Admin::deleteMenu/$1');