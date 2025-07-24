<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('auto-login/(:any)','Home::autoLogin/$1');
$routes->get('logout','Home::logout');
//ajax
$routes->post('save-request','Save::saveRequest');
$routes->post('upload-file','Save::uploadFile');
$routes->post('save-role','Save::saveRole');
$routes->get('fetch-permission','Save::fetchPermission');
$routes->get('edit-role','Save::editRole');
$routes->post('modify-role','Save::modifyRole');
$routes->get('fetch-codes','Save::fetchCodes');
$routes->post('save-code','Save::saveCode');
//review
$routes->get('incoming-request','Save::incomingRequest');
$routes->get('process-request','Save::processRequest');
$routes->get('fetch-request','Save::fetchRequest');
$routes->post('accept-request','Save::acceptRequest');
$routes->post('reject-request','Save::rejectRequest');
$routes->get('view-request','Save::viewRequest');
$routes->post('edit-status','Save::editStatus');
//report
$routes->get('fetch-report','Save::fetchReport');
$routes->get('print/(:any)','Save::print/$1');

$routes->group('',['filter'=>'AuthCheck'],function($routes)
{
    $routes->get('dashboard','Home::dashboard');
    $routes->get('create-request','Home::createRequest');
    $routes->get('my-request','Home::myRequest');
    $routes->get('review','Home::Review');
    $routes->get('settings','Home::settings');
    $routes->get('reports','Home::reports');
});
