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

$routes->get('piezas', 'Pieza::index');
$routes->post('listar-piezas', 'Pieza::listarPiezas');
$routes->post('registro-pieza', 'Pieza::registrarPieza');
$routes->post('eliminar-pieza', 'Pieza::eliminarPieza');

$routes->get('torres', 'Torre::index');
$routes->get('piezas-select-ajax', 'Torre::listarPiezasAjaxSelect2');
$routes->post('listar-torres', 'Torre::listarTorres');
$routes->post('registro-torre', 'Torre::registrarTorre');
$routes->post('eliminar-torre', 'Torre::eliminarTorre');
$routes->post('eliminar-plano', 'Torre::eliminarPlano');
$routes->post('detalle-torre-modal', 'Torre::modalDetalleTorre');

$routes->get('presupuestos', 'Presupuesto::index');
$routes->post('listar-presupuestos', 'Presupuesto::listarPresupuestos');
$routes->get('nuevo-presupuesto', 'Presupuesto::nuevoPresupuesto');
$routes->get('clientes-select-ajax', 'Presupuesto::listarClientesAjaxSelect2');
$routes->get('torres-select-ajax', 'Presupuesto::listarTorresAjaxSelect2');
$routes->post('registro-presu', 'Presupuesto::registrarPresupuesto');
$routes->post('detalle-presu-modal', 'Presupuesto::modalDetallePresu');