<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Produtos REST

$routes->get('produtos', 'Produtos::list');
$routes->post('produtos/create', 'Produtos::create');
$routes->put('produtos/(:num)', 'Produtos::update/$1');
$routes->delete('produtos/(:num)', 'Produtos::delete/$1');



