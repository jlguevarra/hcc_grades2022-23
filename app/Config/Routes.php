<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Change the default route to Grades::index
$routes->get('/', 'Grades::index');

// 2. Keep this line so the form submission (which goes to /grades) still works
$routes->match(['get', 'post'], 'grades', 'Grades::index');