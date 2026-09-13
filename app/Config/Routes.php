<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('auth', ['filter' => 'student_guest'], static function ($routes) {
    $routes->get('login', 'Auth\AuthenticationStudent::login');
    $routes->post('loginProcess', 'Auth\AuthenticationStudent::processLogin');

    $routes->get('register', 'Auth\AuthenticationStudent::register');
    $routes->post('processRegister', 'Auth\AuthenticationStudent::processRegister');

    $routes->get('verify-otp', 'Auth\AuthenticationStudent::verifyOtp');
    $routes->post('processOtp', 'Auth\AuthenticationStudent::processOtp');
    $routes->get('resendOtpAction', 'Auth\AuthenticationStudent::resendOtpAction');

    $routes->get('forget-password', 'Auth\AuthenticationStudent::forgetPassword');
    $routes->post('processForgetPassword', 'Auth\AuthenticationStudent::processForgetPassword');

    $routes->get('reset-password', 'Auth\AuthenticationStudent::resetPassword');
    $routes->post('processResetPassword', 'Auth\AuthenticationStudent::processResetPassword');

    $routes->get('resend-verification', 'Auth\AuthenticationStudent::resendVerification');
    $routes->post('processResendVerification', 'Auth\AuthenticationStudent::processResendVerification');
});

$routes->group('auth', ['filter' => 'admin_guest'], static function ($routes) {
    $routes->get('login-admin', 'Auth\AuthenticationAdmin::login');
    $routes->post('login-process', 'Auth\AuthenticationAdmin::processLogin');
});

$routes->get('auth/logout', 'Auth\AuthenticationStudent::logout');
$routes->get('auth/logout-admin', 'Auth\AuthenticationAdmin::logout');

$routes->group('student', ['filter' => 'student_auth'], static function ($routes) {
    $routes->get('dashboard', 'Student\Dashboard::index');
    $routes->get('/', 'Student\Dashboard::index');
});

$routes->group('admin', ['filter' => 'admin_auth'], function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
});
