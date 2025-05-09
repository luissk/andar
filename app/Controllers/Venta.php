<?php

namespace App\Controllers;

use App\Models\ParametrosModel;
use CodeIgniter\Model;
use Dompdf\Dompdf;

class Venta extends BaseController
{
    protected $modeloParametros;
    protected $modeloUsuario;
    protected $modeloTorre;
    protected $modeloPieza;
    protected $modeloCliente;
    protected $modeloPresupuesto;
    protected $modeloVenta;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloParametros  = model('ParametrosModel');
        $this->modeloUsuario     = model('UsuarioModel');
        $this->modeloTorre       = model('TorreModel');
        $this->modeloPieza       = model('PiezaModel');
        $this->modeloCliente     = model('ClienteModel');
        $this->modeloPresupuesto = model('PresupuestoModel');
        $this->modeloVenta      = model('VentaModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Ventas del Sistema | ".help_nombreWeb();
        $data['ventasLinkActive'] = 1;

        return view('sistema/ventas/index', $data);
    }

    public function listarVentas(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            
            $page = $this->request->getVar('page');
            $cri  = trim($this->request->getVar('cri'));

            $desde        = $page * 40 - 40;
            $hasta        = 40;
            $data['page'] = $page;

            $cri = strlen($cri) > 2 ? $cri : '';
            $data['cri'] = $cri;

            $data['ventas']         = $this->modeloVenta->getVentas($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloVenta->getVentasCount($cri)['total'];

            return view('sistema/ventas/listar', $data);
        }
    }

    public function modalDetalleVenta(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $id = $_POST['id'];
            $data['venta_bd']   = $this->modeloVenta->getVenta($id);
            $data['detalle_bd'] = $this->modeloVenta->getDetalleVenta($id);

            return view('sistema/ventas/modalDetalle', $data);

        }
    }

    public function nuevaVenta($id = ''){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        $data['title']           = "Nueva Venta | ".help_nombreWeb();
        $data['ventasLinkActive'] = 1;

        if( $id != '' && $venta_bd = $this->modeloVenta->getVenta($id) ){
            //PARA EDITAR VENTA
            $data['title']      = "Modificar Venta | ".help_nombreWeb();
            $data['venta_bd']  = $venta_bd;
            $data['detalle_bd'] = $this->modeloVenta->getDetalleVenta($id);
        }else if( $id != '' &&  !$venta_bd = $this->modeloVenta->getVenta($id) ){
            return redirect()->to('ventas');
        }     
        
        $data['piezas'] = $this->modeloPieza->getPiezasAjax();

        return view('sistema/ventas/nuevaVenta', $data);
    }

    public function guardarVenta(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            /* echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            exit(); */

            $nrodoc  = trim($this->request->getVar('nrodoc'));
            $fecha   = $this->request->getVar('fecha');
            $cliente = trim($this->request->getVar('cliente'));
            $ruc     = trim($this->request->getVar('ruc'));
            $items   = json_decode($this->request->getVar('items'), true);
            $idventa = $this->request->getVar('idven');

            if( $idventa != '' && $venta_bd = $this->modeloVenta->getVenta($idventa) ){
                if( $this->modeloVenta->modificarVenta($idventa,$nrodoc,$fecha,$cliente,$ruc) ){
                    if( $this->modeloVenta->eliminarDetalle($idventa) ){
                        $res = FALSE;
                        foreach( $items as $i ){
                            $idpieza = $i['id'];
                            $cant    = $i['cant'];
                            $preciov = $i['preciov'];

                            if( $this->modeloVenta->insertarDetalleVenta($idventa,$idpieza,$cant,$preciov) ){
                                $res = TRUE;
                            }
                        }

                        if( $res ){
                            echo '<script>
                                Swal.fire({
                                    title: "Venta Modificada",
                                    text: "",
                                    icon: "success",
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                });
                                setTimeout(function(){location.href="ventas"}, 1500);
                            </script>';
                        }
                    }
                }
            }else{
                if( $idventa_i = $this->modeloVenta->insertarVenta($nrodoc,$fecha,$cliente,$ruc,session('idusuario')) ){//insertar
                    //INSERTAR DETALLE
                    $res = FALSE;
                    foreach( $items as $i ){
                        $idpieza = $i['id'];
                        $cant    = $i['cant'];
                        $preciov = $i['preciov'];

                        if( $this->modeloVenta->insertarDetalleVenta($idventa_i,$idpieza,$cant,$preciov) ){
                            $res = TRUE;
                        }
                    }

                    if( $res ){
                        echo '<script>
                            Swal.fire({
                                title: "Venta Registrada",
                                text: "",
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                            setTimeout(function(){location.href="ventas"}, 1500);
                        </script>';
                    }
                }
            }           

        }
    }

    public function eliminarVenta(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idventa = $this->request->getVar('id');

            $eliminar = FALSE;
            $mensaje = "";

            if( $this->modeloVenta->getVenta($idventa)  ){
                if( $this->modeloVenta->eliminarDetalle($idventa) ){
                    if( $this->modeloVenta->eliminarVenta($idventa) ){
                        echo '<script>
                            Swal.fire({
                                title: "Venta Eliminada",
                                text: "",
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                            setTimeout(function(){location.href="ventas"}, 1500);
                        </script>';
                    }
                }
            }
        }
    }


}