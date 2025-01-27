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
$routes->get('mis-datos', 'Usuario::misDatos');
$routes->post('cambiar-password', 'Usuario::cambiarPassword');
$routes->post('listar-usuarios', 'Usuario::listarUsuarios');
$routes->post('registro-usuario', 'Usuario::registrarUsuario');
$routes->post('eliminar-usuario', 'Usuario::eliminarUsuario');

$routes->get('transportistas', 'Transportista::index');
$routes->post('listar-transportistas', 'Transportista::listarTransportistas');
$routes->post('registro-transportista', 'Transportista::registrarTransportista');
$routes->post('eliminar-transportista', 'Transportista::eliminarTransportista');

$routes->get('clientes', 'Cliente::index');
$routes->post('listar-clientes', 'Cliente::listarClientes');
$routes->post('registro-cliente', 'Cliente::registrarCliente');
$routes->post('eliminar-cliente', 'Cliente::eliminarCliente');
