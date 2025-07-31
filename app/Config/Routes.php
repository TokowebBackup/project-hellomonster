<?php

/**
 * @modify by PujiErmanto<pujiermanto@gmail.com> | AKA Nasi Goreng Ma Yayah | AKA Agus Kolega iwan fals | AKA DADANG
 */

use CodeIgniter\Router\Routes;

/*
|--------------------------------------------------------------------------
| Global Routes
|--------------------------------------------------------------------------
*/

$routes->get('/', 'Home::homeIndex');
$routes->get('/start-waiver', 'Home::waiverIndex');
$routes->post('language', 'Language::switch');

/*
|--------------------------------------------------------------------------
| Dashboard & Admin 
|--------------------------------------------------------------------------
*/
$routes->group('admin', function ($routes) {
    $routes->get('login', 'Admin::login');
    $routes->post('login', 'Admin::doLogin');
    $routes->get('logout', 'Admin::logout');

    // Notification
    $routes->get('notifications', 'Admin::notifications');
    $routes->post('notifications/mark-read', 'Admin::markNotificationRead');
    $routes->post('notifications/mark-all-read', 'Admin::markAllNotificationsRead');

    // Dashboard
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('members', 'Admin::members');
    $routes->get('member/edit/(:num)', 'Admin::memberEdit/$1');
    $routes->post('member/update/(:num)', 'Admin::memberUpdate/$1');
    $routes->get('member/delete/(:num)', 'Admin::memberDelete/$1');
    $routes->get('member/search', 'Admin::searchMembers');

    // Waiver Children CRUD
    $routes->get('children', 'Admin::children');
    $routes->get('children/edit/(:num)', 'Admin::editChild/$1');
    $routes->post('children/update/(:num)', 'Admin::updateChild/$1');
    $routes->post('children/delete/(:num)', 'Admin::deleteChild/$1');
    $routes->get('children/search', 'Admin::searchChildren');

    // Waiver Signature CRUD
    $routes->get('sign', 'Admin::signList');
    $routes->get('sign/view/(:num)', 'Admin::viewSign/$1');
    $routes->post('sign/delete/(:num)', 'Admin::deleteSign/$1');
    $routes->get('sign/search', 'Admin::searchSignatures');

    // Settings
    $routes->get('settings', 'Admin::settings');
    $routes->get('settings/add', 'Admin::addSetting');
    $routes->post('settings/save', 'Admin::saveSetting');
    $routes->get('settings/edit/(:num)', 'Admin::editSetting/$1');
    $routes->post('settings/update/(:num)', 'Admin::updateSetting/$1');
    $routes->post('settings/upload-image', 'Admin::uploadImage');
});

/*
|--------------------------------------------------------------------------
| Membership Routes
|--------------------------------------------------------------------------
*/
$routes->group('membership', function ($routes) {
    // $routes->get('/', 'Membership::index');
    // $routes->get('create', 'Membership::create');
    // $routes->post('register', 'Membership::register');
    // $routes->get('activate/(:any)', 'Membership::activate/$1');
    // $routes->get('create-password', 'Membership::createPassword');
    // $routes->post('save-password', 'Membership::savePassword');

    // // Login & Auth
    // $routes->get('login', 'Membership::loginForm');
    // $routes->post('login', 'Membership::login');
    $routes->post('check', 'Membership::check');

    // Member Dashboard & Profile
    // $routes->get('dashboard', 'Membership::dashboard');
    // $routes->get('profile', 'Membership::profile');
    // $routes->post('profile', 'Membership::updateProfile');
});

// $routes->get('logout', 'Membership::logout');

/*
|--------------------------------------------------------------------------
| Waiver Routes
|--------------------------------------------------------------------------
*/
$routes->group('waiver', function ($routes) {
    $routes->get('/', 'Waiver::index');
    $routes->post('save', 'Waiver::save');
    $routes->get('children', 'Waiver::children');
    $routes->post('children/add', 'Waiver::addChild');

    // Crud Children
    $routes->get('children/get/(:num)', 'Waiver::getChild/$1');
    $routes->post('children/update/(:num)', 'Waiver::updateChild/$1');
    $routes->post('children/delete/(:num)', 'Waiver::deleteChild/$1');

    // Waiver Sign
    $routes->get('sign', 'Waiver::sign');
    $routes->post('sign/save', 'Waiver::saveSignature');
    $routes->get('decline', 'Waiver::decline');
    $routes->get('success', 'Waiver::success');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
$routes->group('api', function ($routes) {
    $routes->get('countries', 'Api\CountryApi::index');
    $routes->post('cities', 'Api\CityApi::index');
    $routes->get('country-code-from-name/(:segment)', 'Api\CountryApi::getCodeFromName/$1');
});


// $routes->post('/membership/payment-callback', 'Membership::paymentCallback');

$routes->set404Override(function () {
    // Jika ingin menampilkan custom view
    return view('errors/html/custom_404');
});
