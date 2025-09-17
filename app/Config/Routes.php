<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route for the homepage.
// This will be the public-facing homepage showing a list of movies/shows.
// We are pointing this to the customer-facing shows controller.
$routes->get('/', 'Customer\Booking::index');

// Authentication routes (accessible to everyone)
// Register, Login, and Logout functionality.
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('processLogin', 'Auth::processLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('processRegister', 'Auth::processRegister');
    $routes->get('logout', 'Auth::logout');
    // This is the one-time route to create the admin user.
    // It should be removed after initial setup for security.
    $routes->get('createAdminUser', 'Auth::createAdminUser');
});

// Admin routes (accessible only to users with the 'admin' role)
// These routes are protected by the 'admin' filter, which checks for admin privileges.
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    // Admin Dashboard
    $routes->get('dashboard', 'Admin::index');
    
    // Movies CRUD (Create, Read, Update, Delete)
    $routes->get('movies', 'Admin\Movies::index');
    $routes->get('movies/create', 'Admin\Movies::create');
    $routes->post('movies/store', 'Admin\Movies::store');
    $routes->get('movies/edit/(:num)', 'Admin\Movies::edit/$1');
    $routes->post('movies/update/(:num)', 'Admin\Movies::update/$1');
    $routes->get('movies/delete/(:num)', 'Admin\Movies::delete/$1');
    // Quick toggles
    $routes->post('movies/toggle-featured/(:num)', 'Admin\Movies::toggleFeatured/$1');
    $routes->post('movies/toggle-status/(:num)', 'Admin\Movies::toggleStatus/$1');

    // Shows CRUD
    $routes->get('shows', 'Admin\Shows::index');
    $routes->get('shows/create', 'Admin\Shows::create');
    $routes->post('shows/store', 'Admin\Shows::store');
    $routes->get('shows/edit/(:num)', 'Admin\Shows::edit/$1');
    $routes->post('shows/update/(:num)', 'Admin\Shows::update/$1');
    $routes->get('shows/delete/(:num)', 'Admin\Shows::delete/$1');

    $routes->get('cinemas', 'Admin\Cinemas::index');
    $routes->get('cinemas/create', 'Admin\Cinemas::create');
    $routes->post('cinemas/store', 'Admin\Cinemas::store');
    $routes->get('cinemas/edit/(:num)', 'Admin\Cinemas::edit/$1');
    $routes->post('cinemas/update/(:num)', 'Admin\Cinemas::update/$1');
    $routes->get('cinemas/delete/(:num)', 'Admin\Cinemas::delete/$1');

    // Bookings Management
    $routes->get('bookings', 'Admin\Bookings::index');
    $routes->get('bookings/list-data', 'Admin\Bookings::listData');
    $routes->get('bookings/show/(:num)', 'Admin\Bookings::show/$1');
    $routes->post('bookings/confirm/(:num)', 'Admin\Bookings::confirm/$1');
    $routes->post('bookings/update-status/(:num)', 'Admin\Bookings::updateStatus/$1');
    $routes->post('bookings/delete/(:num)', 'Admin\Bookings::delete/$1');

// Temporary route to check database structure - REMOVE IN PRODUCTION
$routes->get('check-movies', 'CheckTable::index');

    $routes->get('screens', 'Admin\Screens::index');
    $routes->get('screens/create', 'Admin\Screens::create');
    $routes->post('screens/store', 'Admin\Screens::store');
    $routes->get('screens/edit/(:num)', 'Admin\Screens::edit/$1');
    $routes->post('screens/update/(:num)', 'Admin\Screens::update/$1');
    $routes->get('screens/delete/(:num)', 'Admin\Screens::delete/$1');
});

// Customer-facing routes (accessible only to logged-in users)
// These routes are protected by the 'auth' filter.
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Booking-related routes
    $routes->get('booking', 'Customer\Booking::index');
    $routes->get('booking/shows/(:num)', 'Customer\Booking::shows/$1');
    $routes->get('booking/seats/(:num)', 'Customer\Booking::seats/$1');
    $routes->post('booking/process', 'Customer\Booking::process');
    // Dummy payment step routes
    $routes->get('booking/payment/(:any)', 'Customer\Booking::payment/$1');
    $routes->get('booking/payment/confirm/(:any)', 'Customer\Booking::paymentConfirm/$1');
    $routes->get('booking/confirmation/(:any)', 'Customer\Booking::confirmation/$1');
    $routes->get('booking/ticket/(:any)', 'Customer\Booking::downloadTicket/$1');
    $routes->get('my-bookings', 'Customer\Bookings::list');
});

// New API route (can be outside the admin group if you don't mind it being public)
$routes->get('api/screens-by-cinema/(:num)', 'Admin\Screens::getScreensByCinema/$1');

// Note: The public shows list is already defined by the root route `get('/')`
// If you want a separate list page, you can define it like this:
// $routes->get('shows', 'Customer\Shows::index');