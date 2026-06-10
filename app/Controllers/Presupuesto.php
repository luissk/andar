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

            $desde        = $page * 40 - 40;
            $hasta        = 40;
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
            $presupuesto    = $this->modeloPresupuesto->getPresupuesto($idpresu);
            $detalle        = $this->modeloPresupuesto->getDetallePresupuesto($idpresu);
            $detalle_piezas = $this->modeloPresupuesto->getDetallePresupuestoPiezas($idpresu);

            $data['presupuesto']     = $presupuesto;
            $data['detalle']         = $detalle;
            $data['deta_pre_pie_bd'] = $detalle_piezas;

            return view('sistema/presupuestos/modalDetalle', $data);

        }
    }

    public function nuevoPresupuesto($id = ''){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }

        if( $id != '' ){
            if( $presu = $this->modeloPresupuesto->getPresupuesto($id,[1]) ){
                
                $data['nroPre']          = $presu['pre_numero'];
                $data['presu_bd']        = $presu;
                $data['deta_bd']         = $this->modeloPresupuesto->getDetallePresupuesto($id);
                $data['deta_pre_pie_bd'] = $this->modeloPresupuesto->getDetallePresupuestoPiezas($id);

                $data['huboCambiosEnMaestro'] = $this->modeloPresupuesto->verificarCambiosMaestro($id);
                
                $data['title']    = "Editar presupuesto | ".help_nombreWeb();
            }else{
                return redirect()->to('/');
            }
        }else{                       
            $data['nroPre'] = $this->modeloPresupuesto->nroPresupuesto()['nro'];
            $data['title']  = "Nuevo presupuesto | ".help_nombreWeb();  
        } 
        
        $data['presuLinkActive'] = 1;

        $data['clientesCbo'] = $this->modeloCliente->getClientesCbo();//para llenar combobox
        $data['torresCbo']   = $this->modeloTorre->getTorresCbo();//para llenar combobox
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

            //$nroPre  = $this->modeloPresupuesto->nroPresupuesto()['nro'];
            $porcsem = $this->modeloParametros->getParametros()['par_porcensem'];

            $porcpre    = $this->request->getVar('porcpre');
            $tcambio    = $this->request->getVar('tcambio');
            $periodo    = $this->request->getVar('periodo');
            $nroperiodo = $this->request->getVar('nroperiodo');
            $cliente    = $this->request->getVar('cliente');
            $verP       = $this->request->getVar('verP') ? 1: 0;
            $idpre_e    = $this->request->getVar('idpre');
            $nroPre     = trim($this->request->getVar('nropre'));

            $pentrega    = trim($this->request->getVar('plazoentrega'));
            $fpago       = trim($this->request->getVar('formapago'));
            $voferta     = trim($this->request->getVar('validezoferta'));
            $lentrega    = trim($this->request->getVar('lugarentrega'));
            $preciotrans = $this->request->getVar('preciotrans');
            $nrodias     = $this->request->getVar('dias');
            $preciomyd   = $this->request->getVar('preciomyd');
            $pre_ruc     = $this->request->getVar('pre_ruc');

            $arrDT = [];
            
            foreach( $items as $i ){
                $idtorre = $i['id'];
                $cant    = $i['cant'];
                $tmonto  = $i['tmonto'];

                /* $dtTorre = $this->modeloTorre->getDetalleTorre($idtorre);
                //print_r($dtTorre);
                
                foreach( $dtTorre as $dtT ){
                    $a = [
                        'idtor'    => $dtT['idtorre'],
                        'idpie'    => $dtT['idpieza'],
                        'codigo'   => $dtT['pie_codigo'],
                        'pie_desc' => $dtT['pie_desc'],
                        'pie_peso' => $dtT['pie_peso'],
                        'dtcan'    => $dtT['dt_cantidad'],
                        'piepre'   => $dtT['pie_precio'],
                        'dpcant'   => $cant
                    ];
                    array_push($arrDT, $a);
                } */ 
               
                //AHORA GRABARA TODO LO QUE VIENE EN ITEMS
                foreach( $i['piezas'] as $pi ){
                    $a = [
                        'idtor'    => $idtorre,
                        'idpie'    => $pi[6],
                        'codigo'   => $pi[5],
                        'pie_desc' => $pi[0],
                        'pie_peso' => $pi[4],
                        'dtcan'    => $pi[2],
                        'piepre'   => $pi[1],
                        'dpcant'   => $cant
                    ];
                    array_push($arrDT, $a);
                }
            }
            //$arrDT = json_encode($arrDT); //ya no se registrara directo en json items de la tabla, sino en la tabla detalle_presupuesto_peizas
            //
            /* echo "<pre>";print_r($arrDT);echo "</pre>";
            echo "<pre>";print_r($items);echo "</pre>";exit(); */

            if( $presu_bd = $this->modeloPresupuesto->getPresupuesto($idpre_e) ){
                //EDITAR
                //exit();
                $nroPre_bd = $presu_bd['pre_numero'];
                if( $nroPre != $nroPre_bd ){
                    if( $this->modeloPresupuesto->getPresu_x_nroPresu($nroPre) ){
                        echo '<script>
                            Swal.fire({
                                title: "Ya existe el número de Presupuesto",
                                icon: "error"
                            });
                        </script>';
                        exit();
                    }
                }

                if( $this->modeloPresupuesto->modificarPresupuesto($cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$idpre_e,$verP,$nroPre,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias,$preciomyd,$pre_ruc) ){
                    if( $this->modeloPresupuesto->borrarDetallePresupuesto($idpre_e) ){
                        if( $this->modeloPresupuesto->borrarDetallePresuPiezas($idpre_e) ){
                            $res = FALSE;
                            foreach( $items as $i ){
                                $idtorre    = $i['id'];
                                $desc_torre = $i['text'];
                                $cant       = $i['cant'];
                                $tmonto     = $i['tmonto'];
                                
                                if( $this->modeloPresupuesto->insertarDetallePresu($idpre_e,$idtorre,$cant,$tmonto,$desc_torre) ){
                                    $res = TRUE;
                                }
                            }

                            if( $res ){
                                foreach( $arrDT as $ap ){
                                    if( $this->modeloPresupuesto->insertarDetallePresuPiezas($idpre_e, $ap) ){
                                        $res = TRUE;
                                    }
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
                                    setTimeout(function(){location.href="editar-presupuesto-'.$idpre_e.'"},1500)
                                </script>';
                            }
                        }
                    }
                }
            }else{
                
                if( $this->modeloPresupuesto->getPresu_x_nroPresu($nroPre) ){
                    echo '<script>
                        Swal.fire({
                            title: "Ya existe el número de Presupuesto",
                            icon: "error"
                        });
                    </script>';
                    exit();
                }

                if( $idpre = $this->modeloPresupuesto->insertarPresupuesto($nroPre,session('idusuario'),$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$verP,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias,$preciomyd,$pre_ruc) ){
                    $res = FALSE;
                    foreach( $items as $i ){
                        $idtorre    = $i['id'];
                        $desc_torre = $i['text'];
                        $cant       = $i['cant'];
                        $tmonto     = $i['tmonto'];
                        
                        if( $this->modeloPresupuesto->insertarDetallePresu($idpre,$idtorre,$cant,$tmonto,$desc_torre) ){
                            $res = TRUE;                 
                        }
                    }
                    if( $res ){
                        foreach( $arrDT as $ap ){
                            if( $this->modeloPresupuesto->insertarDetallePresuPiezas($idpre, $ap) ){
                                $res = TRUE;
                            }
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

        $data['params']          = $this->modeloParametros->getParametros();
        $data['presu']           = $this->modeloPresupuesto->getPresupuesto($id);
        $data['detalle']         = $this->modeloPresupuesto->getDetallePresupuesto($id);
        $data['deta_pre_pie_bd'] = $this->modeloPresupuesto->getDetallePresupuestoPiezas($id);

        $dompdf->loadHtml(view('sistema/presupuestos/pdf', $data));

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("presupuesto.pdf", array("Attachment" => false));
        exit();
    }

    public function eliminarPresu(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idpresu = $this->request->getVar('id');

            if( $presupuesto = $this->modeloPresupuesto->getPresupuesto($idpresu) ){
                if( $presupuesto['pre_status'] != 1 ){
                    echo '<script>
                        Swal.fire({
                            title: "El presupuesto no puede ser eliminado",
                            icon: "warning",
                        });
                    </script>';
                    exit();
                }
            }

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
                if( $this->modeloPresupuesto->borrarDetallePresuPiezas($idpresu) ){
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


}