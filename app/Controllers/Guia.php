<?php

namespace App\Controllers;

use App\Models\ParametrosModel;
use CodeIgniter\Model;
use Dompdf\Dompdf;

class Guia extends BaseController
{
    protected $modeloParametros;
    protected $modeloUsuario;
    protected $modeloTorre;
    protected $modeloPieza;
    protected $modeloCliente;
    protected $modeloPresupuesto;
    protected $modeloGuia;
    protected $modeloTransportista;
    protected $modeloUbigeo;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloParametros    = model('ParametrosModel');
        $this->modeloUsuario       = model('UsuarioModel');
        $this->modeloTorre         = model('TorreModel');
        $this->modeloPieza         = model('PiezaModel');
        $this->modeloCliente       = model('ClienteModel');
        $this->modeloPresupuesto   = model('PresupuestoModel');
        $this->modeloGuia          = model('GuiaModel');
        $this->modeloTransportista = model('TransportistaModel');
        $this->modeloUbigeo = model('UbigeoModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']          = "Guia de Remisión del Sistema | ".help_nombreWeb();
        $data['guiaLinkActive'] = 1;

        return view('sistema/guias/index', $data);
    }

    public function listarGuias(){
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

            $data['guias']   = $this->modeloGuia->getGuias($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloGuia->getGuiasCount($cri)['total'];

            return view('sistema/guias/listar', $data);
        }
    }

    public function nuevaGuia($cri, $id){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }

        if( $cri == 'g' ){
            if( $guia = $this->modeloGuia->getGuia($id) ){
                $idpresu = $guia['idpresupuesto'];
                $data['nroGuia']      = $guia['gui_nro'];
                $data['presupuesto'] = $this->modeloPresupuesto->getPresupuesto($idpresu);
                
                $data['title']    = "Editar guía | ".help_nombreWeb();
            }else{
                return redirect()->to('/');
            }
        }else if( $cri == 'p' ){           
            if( $presu = $this->modeloPresupuesto->getPresupuesto($id,[1]) ){
                $data['presupuesto']  = $presu;
                $data['detalle_guia'] = $this->modeloPresupuesto->getDetaPresuParaGuia($id);
                $data['nroGuia']      = $this->modeloGuia->nroGuia()['nro'];
                $data['title']        = "Nuevo guía | ".help_nombreWeb();  
            }else{
                return redirect()->to('/');
            }
        } 
        
        $data['guiaLinkActive'] = 1;

        $data['transportitas'] = $this->modeloTransportista->getTransportistas(0,100);
        $data['departamentos'] = $this->modeloUbigeo->listarDepartamentos();

        return view('sistema/guias/nuevaGuia', $data);
    }

    public function listarPresu(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            
            $cri  = trim($this->request->getVar('cri'));

            $cri = strlen($cri) > 2 ? $cri : '';

            $data['presupuestos']   = $this->modeloPresupuesto->getPresupuestos(0, 10, $cri, [1]);

            return view('sistema/guias/listarPresu', $data);
        }
    }

    public function listarProvincias(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $iddepa = $this->request->getVar('iddepa');

            $provincias = $this->modeloUbigeo->listarProvincias($iddepa);
            if( $provincias ){
                echo "<option value=''>Seleccione</option>";
                foreach( $provincias as $prov ){
                    $idprov    = $prov['idprov'];
                    $provincia = $prov['provincias'];
    
                    echo "<option value=$idprov>$provincia</option>";
                }
            }else{
                echo "<option value=''>Seleccione</option>";
            }
            
        }
    }

    public function listarDistritos(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $iddepa = $this->request->getVar('iddepa');
            $idprov = $this->request->getVar('idprov');

            $distritos = $this->modeloUbigeo->listarDistritos($idprov,$iddepa);
            if( $distritos ){
                echo "<option value=''>Seleccione</option>";
                foreach( $distritos as $prov ){
                    $iddist   = $prov['iddist'];
                    $distrito = $prov['dist'];
    
                    echo "<option value=$iddist>$distrito</option>";
                }
            }else{
                echo "<option value=''>Seleccione</option>";
            }
            
        }
    }

    public function generarGuia(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            /* echo "<pre>";
            print_r($_POST);
            echo "</pre>"; */

            $transportista  = $this->request->getVar('transportista');
            $fechatrasl     = $this->request->getVar('fechatrasl');
            $motivo         = $this->request->getVar('motivo');
            $desc_trasl     = $this->request->getVar('desc_trasl');
            $departamentop  = $this->request->getVar('departamentop');
            $provinciap     = $this->request->getVar('provinciap');
            $distritop      = $this->request->getVar('distritop');
            $direccionp     = $this->request->getVar('direccionp');
            $departamentoll = $this->request->getVar('departamentoll');
            $provinciall    = $this->request->getVar('provinciall');
            $distritoll     = $this->request->getVar('distritoll');
            $direccionll    = $this->request->getVar('direccionll');
            $placa          = $this->request->getVar('placa');
            $opt            = $this->request->getVar('opt');
            $idpre          = $this->request->getVar('idpre');

            $ubigeop  = $this->modeloUbigeo->getUbigeo($distritop,$provinciap,$departamentop)['idubigeo'];
            $ubigeoll = $this->modeloUbigeo->getUbigeo($distritoll,$provinciall,$departamentoll)['idubigeo'];

            //echo "$ubigeop - $ubigeoll";
            if( $presu = $this->modeloPresupuesto->getPresupuesto($idpre, [1]) ){               

                $piezas = json_decode($presu['pre_piezas'], true);
                /* echo "<pre>";
                print_r($piezas);
                echo "</pre>"; */
                $arr_existentes = [];//cuando hay mas de una pieza que se repite, y asi poder ir restando su stock para el sgte y tbn para modificar item en presupuesto
                foreach( $piezas as $pi ){
                    $pieza    = $this->modeloPieza->getPieza($pi['idpie']);
                    $stockIni = $pieza['pie_cant'];
                    $pie_desc = $pieza['pie_desc'];
                    $cantReq  = $pi['dtcan'] * $pi['dpcant'];                    

                    $nroEntregados = $this->modeloPresupuesto->getStockPieza($pi['idpie'], $estadoPresu = [4]);
                    $nroSalidas    = $this->modeloPresupuesto->getStockPieza($pi['idpie'], $estadoPresu = [2,3]);
                    $stockAct      = ($stockIni + $nroEntregados - $nroSalidas) <= 0 ? 0 : ($stockIni + $nroEntregados - $nroSalidas);
                    $faltantes     = $cantReq > $stockAct ? abs($stockAct - $cantReq)  : "";

                    $arr_e = array_filter($arr_existentes, fn($pie) => $pie['idpie'] == $pi['idpie']);
                    $arr_e = array_values($arr_e);
                    //print_r($arr_e);
                    if( count($arr_e) > 0 ){
                        $stockAct  = ($stockAct - $arr_e[0]['req']) <= 0 ? 0 : ($stockAct - $arr_e[0]['req']);
                        $faltantes = $cantReq > $stockAct ? abs($stockAct - $cantReq): "";
                    }

                    array_push($arr_existentes, array(
                        'idtor'  => $pi['idtor'],
                        'idpie'  => $pi['idpie'],
                        'dtcan'  => $pi['dtcan'],
                        'piepre' => $pi['piepre'],
                        'dpcant' => $pi['dpcant'],
                        'req'    => $cantReq,
                        'falt'   => $faltantes
                    ));
                }

                $nroGuia = $this->modeloGuia->nroGuia()['nro'];

                if( $this->modeloGuia->generarGuia($nroGuia,$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$idpre,$transportista,session('idusuario'),$opt,2) ){
                    if( $this->modeloPresupuesto->modificaPresuPiezasEstatus(json_encode($arr_existentes), 2, $idpre) ){
                        echo '<script>
                            Swal.fire({
                                title: "Guía Generada",
                                text: "",
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                            setTimeout(function(){location.href="guias"},1500)
                        </script>';
                    }
                        
                }

            }
        }
    }

}