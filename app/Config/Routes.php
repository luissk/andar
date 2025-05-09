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
$routes->get('piezas-a-excel', 'Pieza::reporteExcel');

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
$routes->get('editar-presupuesto-(:num)', 'Presupuesto::nuevoPresupuesto/$1');
$routes->get('pdf-presupuesto-(:num)', 'Presupuesto::pdfPresu/$1');
$routes->post('eliminar-presupuesto', 'Presupuesto::eliminarPresu');

$routes->get('guias', 'Guia::index');
$routes->post('listar-guias', 'Guia::listarGuias');
$routes->post('listar-presu', 'Guia::listarPresu');
$routes->get('nueva-guia-(:any)-(:num)', 'Guia::nuevaGuia/$1/$2');
$routes->get('editar-guia-(:any)-(:num)', 'Guia::nuevaGuia/$1/$2');
$routes->post('listar-provincias', 'Guia::listarProvincias');
$routes->post('listar-distritos', 'Guia::listarDistritos');
$routes->post('generar-guia', 'Guia::generarGuia');
$routes->post('eliminar-guia', 'Guia::eliminarGuia');
$routes->get('pdf-guia-(:num)-(:num)', 'Guia::pdfGuia/$1/$2');

//$routes->post('cambiar-estado', 'Guia::cambiarEstado');

$routes->get('devoluciones', 'Guia::devoluciones');
$routes->post('listar-guias-devo', 'Guia::listarGuiasDevo');
$routes->get('devolver-(:num)', 'Guia::Devolver/$1');
$routes->post('generar-devolucion', 'Guia::generarDevolucion');
$routes->get('pdf-guia-ingreso/(:num)/(:any)', 'Guia::pdfGuiaIngreso/$1/$2');
$routes->post('eliminar-devolucion', 'Guia::eliminarDevolucion');

//compras
$routes->get('compras', 'Compra::index');
$routes->post('listar-compras', 'Compra::listarCompras');
$routes->get('nueva-compra', 'Compra::nuevaCompra');
$routes->post('registro-compra', 'Compra::guardarCompra');
$routes->get('editar-compra-(:num)', 'Compra::nuevaCompra/$1');
$routes->post('detalle-compra-modal', 'Compra::modalDetalleCompra');
$routes->post('eliminar-compra', 'Compra::eliminarCompra');

//ventas
$routes->get('ventas', 'Venta::index');
$routes->post('listar-ventas', 'Venta::listarVentas');
$routes->get('nueva-venta', 'Venta::nuevaVenta');
$routes->post('registro-venta', 'Venta::guardarVenta');
$routes->get('editar-venta-(:num)', 'Venta::nuevaVenta/$1');
$routes->post('detalle-venta-modal', 'Venta::modalDetalleVenta');
$routes->post('eliminar-venta', 'Venta::eliminarVenta');