<?php

use CodeIgniter\Router\Routes;

// Exclude feature
$routes->post('language', 'Language::switch');

// First Home
$routes->get('/', 'Home::index');

// Membership home & All Feature
$routes->get('/membership', 'Membership::index');
$routes->get('/membership/create', 'Membership::create');
$routes->post('/membership/register', 'Membership::register');
$routes->get('/membership/activate/(:any)', 'Membership::activate/$1');
$routes->get('/membership/create-password', 'Membership::createPassword');
$routes->post('/membership/save-password', 'Membership::savePassword');
// Membership login
$routes->get('/membership/login', 'Membership::loginForm');
$routes->post('/membership/login', 'Membership::login');
$routes->post('membership/check', 'Membership::check');
$routes->get('/membership/dashboard', 'Membership::dashboard');
$routes->get('/membership/profile', 'Membership::profile');
$routes->post('/membership/profile', 'Membership::updateProfile');
$routes->get('/logout', 'Membership::logout');

// Waiver
$routes->get('waiver', 'Waiver::index');
$routes->post('waiver/save', 'Waiver::save');
$routes->get('/api/countries', 'Api\CountryApi::index');
$routes->post('/api/cities', 'Api\CityApi::index');
