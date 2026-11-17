<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('generarFormatoFIC', 'Inicio::generarFormatoFIC');
$routes->post('generarFormatoFIC', 'Inicio::generarFormatoFIC');
$routes->post('pdfPagoFIC', 'Inicio::pdfPagoFIC');
$routes->get('pdfPagoFIC', 'Inicio::pdfPagoFIC');
$routes->get('Principal/verArchivoS3', 'Principal::verArchivoS3');
$routes->get('Principal/descargarArchivoAlmacenado', 'Principal::descargarArchivoAlmacenado');
$routes->get('Login/captcha', 'Login::captcha');
$routes->post('Login/validar_usuario', 'Login::validar_usuario');
// $routes->get('pdfTurno/(:num)', 'Inicio::pdfTurno/$1');

