<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Set default controller
$routes->get('/', 'Grades::index');
$routes->post('/', 'Grades::index');
$routes->match(['get', 'post'], 'grades', 'Grades::index');