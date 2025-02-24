<?php

namespace App\Controllers;

use App\Models\ParametrosModel;
use CodeIgniter\Model;
use Dompdf\Dompdf;

class Presupuesto extends BaseController
{
    protected $modeloParametros;
    protected $modeloUsuario;
    protected $modeloTorre;
    protected $modeloPieza;
    protected $modeloCliente;
    protected $modeloPresupuesto;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloParametros  = model('ParametrosModel');
        $this->modeloUsuario     = model('UsuarioModel');
        $this->modeloTorre       = model('TorreModel');
        $this->modeloPieza       = model('PiezaModel');
        $this->modeloCliente     = model('ClienteModel');
        $this->modeloPresupuesto = model('PresupuestoModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Presupuestos del Sistema | ".help_nombreWeb();
        $data['presuLinkActive'] = 1;

        return view('sistema/presupuestos/index', $data);
    }

    public function listarPresupuestos(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            
            $page = $this->request->getVar('page');
            $cri  = trim($this->request->getVar('cri'));

            $desde        = $page * 10 - 10;
            $hasta        = 10;
            $data['page'] = $page;

            $cri = strlen($cri) > 2 ? $cri : '';
            $data['cri'] = $cri;

            $data['presupuestos']   = $this->modeloPresupuesto->getPresupuestos($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloPresupuesto->getPresupuestosCount($cri)['total'];

            return view('sistema/presupuestos/listar', $data);
        }
    }

    public function modalDetallePresu(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $idpresu = $_POST['id'];
            $presupuesto = $this->modeloPresupuesto->getPresupuesto($idpresu);
            $detalle     = $this->modeloPresupuesto->getDetallePresupuesto($idpresu);

            $data['presupuesto'] = $presupuesto;
            $data['detalle']     = $detalle;

            return view('sistema/presupuestos/modalDetalle', $data);

        }
    }

    public function nuevoPresupuesto($id = ''){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }

        if( $id != '' ){
            if( $presu = $this->modeloPresupuesto->getPresupuesto($id,[1]) ){
                
                $data['nroPre']   = $presu['pre_numero'];
                $data['presu_bd'] = $presu;
                $data['deta_bd']  = $this->modeloPresupuesto->getDetallePresupuesto($id);
                $data['title']    = "Editar presupuesto | ".help_nombreWeb();
            }else{
                return redirect()->to('/');
            }
        }else{                       
            $data['nroPre'] = $this->modeloPresupuesto->nroPresupuesto()['nro'];
            $data['title']  = "Nuevo presupuesto | ".help_nombreWeb();  
        } 
        
        $data['presuLinkActive'] = 1;

        $data['clientesCbo'] = $this->modeloCliente->getClientesCbo();//para llebar combobox
        $data['torresCbo']   = $this->modeloTorre->getTorresCbo();//para llebar combobox
        $data['param']       = $this->modeloParametros->getParametros();

        return view('sistema/presupuestos/nuevoPresupuesto', $data);
    }

    /* public function listarClientesAjaxSelect2(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            if( !empty($this->request->getVar('type')) && $this->request->getVar('type') == 'clientes' ){
                $cri = !empty( trim( $this->request->getVar('search') ) ) ? trim( $this->request->getVar('search') ) : '';

                $piezas = $this->modeloCliente->getClientesAjax($cri);

                if( $piezas ){
                    $pData = array();
                    foreach( $piezas as $p ){
                        $data['id']     = $p['idcliente'];
                        $data['text']   = $p['cli_nombrerazon'];
                        $data['dniruc'] = $p['cli_dniruc'];

                        array_push($pData, $data);
                    }

                    echo json_encode($pData);
                }                
            }         

        }
    } */

    /* public function listarTorresAjaxSelect2(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            if( !empty($this->request->getVar('type')) && $this->request->getVar('type') == 'torres' ){
                $cri = !empty( trim( $this->request->getVar('search') ) ) ? trim( $this->request->getVar('search') ) : '';

                $torres = $this->modeloTorre->getTorresAjax($cri);

                if( $torres ){
                    $pData = array();
                    foreach( $torres as $t ){
                        $data['id']    = $t['idtorre'];
                        $data['text']  = $t['tor_desc'];
                        $data['total'] = $t['total'];

                        array_push($pData, $data);
                    }

                    echo json_encode($pData);
                }                
            }         

        }
    } */

    public function registrarPresupuesto(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            /* echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            exit(); */

            $items     = json_decode($this->request->getVar('items'), true);
            $count_items = count($items);
            if( $count_items == 0 ){
                echo "ITEMS VACIO";exit();
            }

            $nroPre  = $this->modeloPresupuesto->nroPresupuesto()['nro'];
            $porcsem = $this->modeloParametros->getParametros()['par_porcensem'];

            $porcpre    = $this->request->getVar('porcpre');
            $periodo    = $this->request->getVar('periodo');
            $nroperiodo = $this->request->getVar('nroperiodo');
            $cliente    = $this->request->getVar('cliente');
            $verP       = $this->request->getVar('verP') ? 1: 0;
            $idpre_e    = $this->request->getVar('idpre');

            //PARA GUARDAR LOS ITEMS DE LA TORRE DE ESE MOMENTO DEL PRESUPUESTO, EN CASO CAMBIE DESPUES
            $arrDT = [];
            foreach( $items as $i ){
                $idtorre = $i['id'];
                $cant    = $i['cant'];
                $tmonto  = $i['tmonto'];

                $dtTorre = $this->modeloTorre->getDetalleTorre($idtorre);
                
                foreach( $dtTorre as $dtT ){
                    $a = [
                        'idtor'  => $dtT['idtorre'],
                        'idpie'  => $dtT['idpieza'],
                        'dtcan'  => $dtT['dt_cantidad'],
                        'piepre' => $dtT['pie_precio'],
                        'dpcant' => $cant
                    ];
                    array_push($arrDT, $a);
                }            
            }
            $arrDT = json_encode($arrDT);
            //FIN PARA GUARDAR LOS ITEMS DE LA TORRE DE ESE MOMENTO DEL PRESUPUESTO, EN CASO CAMBIE DESPUES

            if( $presu_bd = $this->modeloPresupuesto->getPresupuesto($idpre_e) ){
                //EDITAR
                //exit();
                if( $this->modeloPresupuesto->modificarPresupuesto($cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT,$idpre_e,$verP) ){
                    if( $this->modeloPresupuesto->borrarDetallePresupuesto($idpre_e) ){
                        $res = FALSE;
                        foreach( $items as $i ){
                            $idtorre = $i['id'];
                            $cant    = $i['cant'];
                            $tmonto  = $i['tmonto'];
                            
                            if( $this->modeloPresupuesto->insertarDetallePresu($idpre_e,$idtorre,$cant,$tmonto) ){
                                $res = TRUE;
                            }
                        }
                        if( $res ){
                            echo '<script>
                                Swal.fire({
                                    title: "Presupuesto Modificado",
                                    text: "",
                                    icon: "success",
                                    showConfirmButton: true,
                                });
                                setTimeout(function(){location.reload()},1500)
                            </script>';
                        }
                    }
                }
            }else{                

                if( $idpre = $this->modeloPresupuesto->insertarPresupuesto($nroPre,session('idusuario'),$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT,$verP) ){
                    $res = FALSE;
                    foreach( $items as $i ){
                        $idtorre = $i['id'];
                        $cant    = $i['cant'];
                        $tmonto  = $i['tmonto'];
                        
                        if( $this->modeloPresupuesto->insertarDetallePresu($idpre,$idtorre,$cant,$tmonto) ){
                            $res = TRUE;
                        }
                    }
                    if( $res ){
                        echo '<script>
                            Swal.fire({
                                title: "Presupuesto Generado",
                                text: "",
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                            setTimeout(function(){location.reload()},1500)
                        </script>';
                    }
                }

            }          
            
            /* echo "<pre>";
            print_r($_POST);
            print_r($items);
            echo "</pre>"; */

        }
    }


    public function pdfPresu($id){
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new \Dompdf\Dompdf($options);

        $data['params'] = $this->modeloParametros->getParametros();
        $data['presu'] = $this->modeloPresupuesto->getPresupuesto($id);
        $data['detalle'] = $this->modeloPresupuesto->getDetallePresupuesto($id);

        $dompdf->loadHtml(view('sistema/presupuestos/pdf', $data));

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("presupuesto.pdf", array("Attachment" => false));
        //exit();
    }

    public function eliminarPresu(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idpresu = $this->request->getVar('id');

            $eliminar = FALSE;
            $mensaje = "";

            $tablas = ['guia','detalle_factura'];
            foreach( $tablas as $t ){
                $total = $this->modeloPresupuesto->verificarPresuTieneRegEnTablas($idpresu,$t)['total'];
                if( $total > 0 ){
                    $mensaje .= "<div class='text-start'>El presupuesto tiene $total registros en la tabla '$t'.</div>";
                    $eliminar = TRUE;
                }
            }

            if( $eliminar ){
                echo '<script>
                    Swal.fire({
                        title: "El presupuesto no puede ser eliminado",
                        html: "'.$mensaje.'",
                        icon: "warning",
                    });
                </script>';
                exit();
            }
            
            if( $this->modeloPresupuesto->borrarDetallePresupuesto($idpresu) ){
                if( $this->modeloPresupuesto->eliminarPresupuesto($idpresu) ){
                    echo '<script>
                        Swal.fire({
                            title: "Presupuesto eliminado",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarPresupuestos(1);
                    </script>';
                }
            }
        }
    }


}