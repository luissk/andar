<?php

namespace App\Controllers;

use App\Models\ParametrosModel;
use CodeIgniter\Model;
use Dompdf\Dompdf;

class Compra extends BaseController
{
    protected $modeloParametros;
    protected $modeloUsuario;
    protected $modeloTorre;
    protected $modeloPieza;
    protected $modeloCliente;
    protected $modeloPresupuesto;
    protected $modeloCompra;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloParametros  = model('ParametrosModel');
        $this->modeloUsuario     = model('UsuarioModel');
        $this->modeloTorre       = model('TorreModel');
        $this->modeloPieza       = model('PiezaModel');
        $this->modeloCliente     = model('ClienteModel');
        $this->modeloPresupuesto = model('PresupuestoModel');
        $this->modeloCompra      = model('CompraModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Compras del Sistema | ".help_nombreWeb();
        $data['comprasLinkActive'] = 1;

        return view('sistema/compras/index', $data);
    }

    public function listarCompras(){
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

            $data['compras']        = $this->modeloCompra->getCompras($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloCompra->getComprasCount($cri)['total'];

            return view('sistema/compras/listar', $data);
        }
    }

    public function modalDetalleCompra(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $id = $_POST['id'];
            $data['compra_bd']  = $this->modeloCompra->getCompra($id);
            $data['detalle_bd'] = $this->modeloCompra->getDetalleCompra($id);

            return view('sistema/compras/modalDetalle', $data);

        }
    }

    public function nuevaCompra($id = ''){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        $data['title']           = "Nueva Compra | ".help_nombreWeb();
        $data['comprasLinkActive'] = 1;

        if( $id != '' && $compra_bd = $this->modeloCompra->getCompra($id) ){
            //PARA EDITAR COMPRA
            $data['title']      = "Modificar Compra | ".help_nombreWeb();
            $data['compra_bd']  = $compra_bd;
            $data['detalle_bd'] = $this->modeloCompra->getDetalleCompra($id);
        }else if( $id != '' &&  !$compra_bd = $this->modeloCompra->getCompra($id) ){
            return redirect()->to('compras');
        }     
        
        $data['piezas'] = $this->modeloPieza->getPiezasAjax();

        return view('sistema/compras/nuevaCompra', $data);
    }

    public function guardarCompra(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            /* echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            exit(); */

            $nrodoc    = trim($this->request->getVar('nrodoc'));
            $fecha     = $this->request->getVar('fecha');
            $proveedor = trim($this->request->getVar('proveedor'));
            $ruc       = trim($this->request->getVar('ruc'));
            $items     = json_decode($this->request->getVar('items'), true);
            $idcompra  = $this->request->getVar('idcom');

            if( $idcompra != '' && $compra_bd = $this->modeloCompra->getCompra($idcompra) ){
                if( $this->modeloCompra->modificarCompra($idcompra,$nrodoc,$fecha,$proveedor,$ruc) ){
                    if( $this->modeloCompra->eliminarDetalle($idcompra) ){
                        $res = FALSE;
                        foreach( $items as $i ){
                            $idpieza = $i['id'];
                            $cant    = $i['cant'];
                            $precioc = $i['precioc'];

                            if( $this->modeloCompra->insertarDetalleCompra($idcompra,$idpieza,$cant,$precioc) ){
                                $res = TRUE;
                            }
                        }

                        if( $res ){
                            echo '<script>
                                Swal.fire({
                                    title: "Compra Modificada",
                                    text: "",
                                    icon: "success",
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                });
                                setTimeout(function(){location.href="compras"}, 1500);
                            </script>';
                        }
                    }
                }
            }else{
                if( $idcompra_i = $this->modeloCompra->insertarCompra($nrodoc,$fecha,$proveedor,$ruc,session('idusuario')) ){//insertar
                    //INSERTAR DETALLE
                    $res = FALSE;
                    foreach( $items as $i ){
                        $idpieza = $i['id'];
                        $cant    = $i['cant'];
                        $precioc = $i['precioc'];

                        if( $this->modeloCompra->insertarDetalleCompra($idcompra_i,$idpieza,$cant,$precioc) ){
                            $res = TRUE;
                        }
                    }

                    if( $res ){
                        echo '<script>
                            Swal.fire({
                                title: "Compra Registrada",
                                text: "",
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                            setTimeout(function(){location.href="compras"}, 1500);
                        </script>';
                    }
                }
            }           

        }
    }

    public function eliminarCompra(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idcompra = $this->request->getVar('id');

            $eliminar = FALSE;
            $mensaje = "";

            if( $this->modeloCompra->getCompra($idcompra)  ){
                if( $this->modeloCompra->eliminarDetalle($idcompra) ){
                    if( $this->modeloCompra->eliminarCompra($idcompra) ){
                        echo '<script>
                            Swal.fire({
                                title: "Compra Eliminada",
                                text: "",
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                            setTimeout(function(){location.href="compras"}, 1500);
                        </script>';
                    }
                }
            }
        }
    }


}