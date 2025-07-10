<?php

use CodeIgniter\Router\Routes;

$routes->get('/', 'Home::index');
$routes->get('/membership', 'Membership::index');
$routes->get('/membership/create', 'Membership::create');
$routes->post('/membership/register', 'Membership::register');
$routes->get('/membership/activate/(:any)', 'Membership::activate/$1');
$routes->get('/membership/create-password', 'Membership::createPassword');
$routes->post('/membership/save-password', 'Membership::savePassword');
$routes->post('language', 'Language::switch');

$routes->get('/membership/login', 'Membership::loginForm');
$routes->post('/membership/login', 'Membership::login');

$routes->post('membership/check', 'Membership::check');
$routes->get('/membership/dashboard', 'Membership::dashboard');

$routes->get('/membership/profile', 'Membership::profile');
$routes->post('/membership/profile', 'Membership::updateProfile');
$routes->get('/logout', 'Membership::logout');
