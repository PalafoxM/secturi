<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('generarFormatoFIC', 'Inicio::generarFormatoFIC');
$routes->post('generarFormatoFIC', 'Inicio::generarFormatoFIC');
// $routes->get('pdfTurno/(:num)', 'Inicio::pdfTurno/$1');

