<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
$routes->get('/tos', 'Page::tos');

$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/materi/(:segment)', 'Artikel::materi/$1');
$routes->get('/artikel/download/(:segment)', 'Artikel::downloadMateri/$1');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

$routes->match(['GET', 'POST'], '/user/login', 'User::login');
$routes->match(['GET', 'POST'], '/user/register', 'User::register');
$routes->match(['GET', 'POST'], '/user/forgot-password', 'User::forgotPassword');
$routes->get('/user', 'User::index');
$routes->get('/user/logout', 'User::logout');

$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->match(['GET', 'POST'], 'artikel/add', 'Artikel::add');
    $routes->match(['GET', 'POST'], 'artikel/edit/(:any)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:any)', 'Artikel::delete/$1');
});
