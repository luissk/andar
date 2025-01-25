<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Inicio::index');
$routes->post('/', 'Inicio::index');
$routes->get('salir', 'Inicio::salir');
$routes->get('sistema', 'Inicio::sistema');

$routes->get('parametros', 'Parametros::index');
$routes->post('modificar-parametros', 'Parametros::modificarParametros');
$routes->post('eliminar-imagen', 'Parametros::eliminarImagen');
$routes->get('perfiles', 'Parametros::perfiles');

$routes->get('usuarios', 'Usuario::index');
$routes->post('listar-usuarios', 'Usuario::listarUsuarios');
$routes->post('registro-usuario', 'Usuario::registrarUsuario');
