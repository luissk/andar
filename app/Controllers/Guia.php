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
                $data['presupuesto']  = $this->modeloPresupuesto->getPresupuesto($idpresu);
                $data['detalle_guia'] = $this->modeloPresupuesto->getDetaPresuParaGuia($idpresu);
                
                $data['title']   = "Editar guía | ".help_nombreWeb();
                $data['guia_bd'] = $guia;
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

            $iddepa    = $this->request->getVar('iddepa');
            $idprov_bd = $this->request->getVar('idprov_bd');

            $provincias = $this->modeloUbigeo->listarProvincias($iddepa);
            if( $provincias ){
                echo "<option value=''>Seleccione</option>";
                foreach( $provincias as $prov ){
                    $idprov    = $prov['idprov'];
                    $provincia = $prov['provincias'];

                    $select_prov = $idprov_bd != '' && $idprov == $idprov_bd ? 'selected' : ''; 
    
                    echo "<option value=$idprov $select_prov>$provincia</option>";
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
            $iddist_bd = $this->request->getVar('iddist_bd');

            $distritos = $this->modeloUbigeo->listarDistritos($idprov,$iddepa);
            if( $distritos ){
                echo "<option value=''>Seleccione</option>";
                foreach( $distritos as $prov ){
                    $iddist   = $prov['iddist'];
                    $distrito = $prov['dist'];

                    $select_dist = $iddist_bd != '' && $iddist == $iddist_bd ? 'selected' : ''; 
    
                    echo "<option value=$iddist $select_dist>$distrito</option>";
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
            echo "</pre>";
            exit(); */

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
            $idguia         = $this->request->getVar('idguia');

            $ubigeop  = $this->modeloUbigeo->getUbigeo($distritop,$provinciap,$departamentop)['idubigeo'];
            $ubigeoll = $this->modeloUbigeo->getUbigeo($distritoll,$provinciall,$departamentoll)['idubigeo'];

            if( $idguia != '' && $guia = $this->modeloGuia->getGuia($idguia) ){
                if( $this->modeloGuia->modificarGuia($idguia,$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$transportista,$opt,2) ){
                    echo '<script>
                        Swal.fire({
                            title: "Guía Modificada",
                            text: "",
                            icon: "success",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                        });
                        setTimeout(function(){location.href="guias"},1500)
                    </script>';
                }                        
            }else{
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

                        /* $nroEntregados = $this->modeloPresupuesto->getStockPieza($pi['idpie'], $estadoPresu = [3],'e');
                        $nroSalidas    = $this->modeloPresupuesto->getStockPieza($pi['idpie'], $estadoPresu = [2,3], 's');
                        $stockAct      = ($stockIni + $nroEntregados - $nroSalidas) <= 0 ? 0 : ($stockIni + $nroEntregados - $nroSalidas); */
                        $stockAct = $pieza['stockActual'];
                        $faltantes     = $cantReq > $stockAct ? abs($stockAct - $cantReq)  : "";

                        $arr_e = array_filter($arr_existentes, fn($pie) => $pie['idpie'] == $pi['idpie']);
                        $arr_e = array_values($arr_e);
                        //print_r($arr_e);
                        if( count($arr_e) > 0 ){
                            $stockAct  = ($stockAct - $arr_e[0]['req']) <= 0 ? 0 : ($stockAct - $arr_e[0]['req']);
                            $faltantes = $cantReq > $stockAct ? abs($stockAct - $cantReq): "";
                        }

                        $stock_que_sale = $cantReq <= $stockAct ? $cantReq : $stockAct;

                        array_push($arr_existentes, array(
                            'idtor'   => $pi['idtor'],
                            'idpie'   => $pi['idpie'],
                            'dtcan'   => $pi['dtcan'],
                            'piepre'  => $pi['piepre'],
                            'dpcant'  => $pi['dpcant'],
                            'req'     => $cantReq,
                            'falt'    => $faltantes,
                            'st_sale' => $stock_que_sale,
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

    public function eliminarGuia(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idguia = $this->request->getVar('id');

            if( $guia = $this->modeloGuia->getGuia($idguia, [1,2]) ){
                $idpresu = $guia['idpresupuesto'];
                $piezas  = json_decode($guia['pre_piezas'],true);

                $piezas_upd = [];
                foreach( $piezas as $pi ){
                    unset($pi['req'],$pi['falt'],$pi['st_sale'],$pi['ingresa']);
                    $piezas_upd[] = $pi;
                }
                
                if( $this->modeloPresupuesto->modificaPresuPiezasEstatus(json_encode($piezas_upd), 1, $idpresu) ){
                    if( $this->modeloGuia->eliminarGuia($idguia) ){
                        echo '<script>
                            Swal.fire({
                                title: "Guía Eliminada",
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

    public function pdfGuia($id,$opt){
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new \Dompdf\Dompdf($options);

        $data['params'] = $this->modeloParametros->getParametros();

        $data['guia'] = $this->modeloGuia->getGuia($id);
        $data['opt'] = $opt;

        $dompdf->loadHtml(view('sistema/guias/pdf', $data));

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream("guia.pdf", array("Attachment" => false));
    }

    /* public function cambiarEstado(){
        if( $this->request->isAJAX() ){
            $idguia   = $this->request->getVar('id');
            $opt      = $this->request->getVar('opt');
            $fechaent = $this->request->getVar('fechaent');
            
            if( $guia = $this->modeloGuia->getGuia($idguia, [2,3]) ){
                if( $opt && $opt != '' ){//PARA PROCESAR LA FECHA DE ENTREGA
                    if( $opt == 'registrar' ){
                        echo "registrar";
                        if( $this->modeloGuia->registrarFechaEntregado($fechaent, 3,$idguia) ){
                            $this->modeloPresupuesto->modificaStatusPre($guia['idpresupuesto'], 3);
                            echo '<script>
                                Swal.fire({
                                    title: "Registrado",
                                    text: "",
                                    icon: "success",
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                });
                                setTimeout(function(){location.href="guias"},1500)
                            </script>';
                        }
                    }else if( $opt == 'modificar' ){
                        if( $this->modeloGuia->registrarFechaEntregado($fechaent, 3,$idguia) ){
                            $this->modeloPresupuesto->modificaStatusPre($guia['idpresupuesto'], 3);
                            echo '<script>
                                Swal.fire({
                                    title: "Registrado",
                                    text: "",
                                    icon: "success",
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                });
                                setTimeout(function(){location.href="guias"},1500)
                            </script>';
                        }
                    }
                }else{
                    //PARA MOSTRAR EL FORMULARIO FECHA DE ENTREGA
                    $data['guia'] = $guia;
                    return view('sistema/guias/cambiarestado', $data);
                }                
            }
        }
    } */

    public function devoluciones(){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Devoluciones del Sistema | ".help_nombreWeb();
        $data['devolLinkActive'] = 1;

        return view('sistema/devolucion/index', $data);
    }

    public function listarGuiasDevo(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            
            $page = $this->request->getVar('page');
            $cri  = trim($this->request->getVar('cri'));

            $desde        = $page * 10 - 10;
            $hasta        = 10;
            $data['page'] = $page;

            if( $cri == '' ) exit();

            $cri = strlen($cri) > 2 ? $cri : '';
            $data['cri'] = $cri;

            $data['guias']   = $this->modeloGuia->getGuias($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloGuia->getGuiasCount($cri)['total'];

            return view('sistema/devolucion/listar', $data);
        }
    }

    public function Devolver($id){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Devoluciones del Sistema | ".help_nombreWeb();
        $data['devolLinkActive'] = 1;

        if( $guia = $this->modeloGuia->getGuia($id,[2,3]) ){
            $idpresu              = $guia['idpresupuesto'];
            $data['nroGuia']      = $guia['gui_nro'];
            $data['presupuesto']  = $this->modeloPresupuesto->getPresupuesto($idpresu);
            $data['detalle_guia'] = $this->modeloPresupuesto->getDetaPresuParaGuia($idpresu);
            $data['guia_bd']      = $guia;
        }else{
            return redirect()->to('/');
        }

        return view('sistema/devolucion/devolver', $data);
    }

    public function generarDevolucion(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $idguia = $this->request->getVar('idguia');
            $fechadevo = $this->request->getVar('fechadevo');
            $items = $this->request->getVar('items');

            if( $guia = $this->modeloGuia->getGuia($idguia,[2,3]) ){
                $piezas = json_decode($guia['pre_piezas'], true);

                $arr_restantes = []; //para guardar el idpieza y cant sobrante, si en caso haya mas items iguales
                $arr_items     = []; //guardar con los ingresos
                foreach( $piezas as $k => $pi ){
                    echo "<pre>";
                    $arr = array_values(array_filter($items, fn($v) => $v['idpieza'] == $pi['idpie']));
                    $cant_t   = $arr[0]['cant'];//cantidad total que ingresa
                    $st_salio = $pi['st_sale'];//stock que salió

                    $arr_r = array_values(array_filter($arr_restantes, fn($v) => $v['idpieza'] == $pi['idpie']));
                    if( count($arr_r) > 0 && $arr_r[0]['idpieza'] == $pi['idpie'] ){
                        $pi['ingresa']= $arr_r[0]['restante'];                  
                    }else{
                        if( $cant_t <= $st_salio ){
                            $pi['ingresa'] = $cant_t;
                            array_push($arr_restantes, ['idpieza' => $pi['idpie'], 'restante' => 0]);//ingresamos ese restante al array
                        }else if( $cant_t > $st_salio ){//quedará un restante, para las demás piezas iguales
                            $restante = $cant_t  - $st_salio;
                            array_push($arr_restantes, ['idpieza' => $pi['idpie'], 'restante' => $restante]);//ingresamos ese restante al array
                            $pi['ingresa'] = $st_salio;
                            //echo "===";
                        }  
                    }
                    array_push($arr_items, $pi);
                    /* print_r($pi);
                    print_r($arr);
                    print_r($arr_r); */
                    echo "</pre>";
                }

                $completo = 1;
                foreach( $items as $i ){
                    if( $i['cant'] < $i['salio'] ) $completo = 0;
                }                
                /* echo "<pre>";
                echo $completo;
                print_r($items);
                print_r($arr_items);
                echo "</pre>"; */
                if( $this->modeloGuia->modificarFechaDevolucionGuia($idguia, $fechadevo, $completo, 3) ){
                    if( $this->modeloPresupuesto->modificaPresuPiezasEstatus(json_encode($arr_items), 3, $guia['idpresupuesto']) ){
                        echo '<script>
                            Swal.fire({
                                title: "Registrado",
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