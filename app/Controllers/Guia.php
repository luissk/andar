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
    protected $modeloProveedor;
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
        $this->modeloProveedor = model('ProveedorModel');
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

            $desde        = $page * 40 - 40;
            $hasta        = 40;
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
            if( $guia = $this->modeloGuia->getGuia($id,[2]) ){
                $idpresu                 = $guia['idpresupuesto'];
                $data['nroGuia']         = $guia['gui_nro'];
                $data['presupuesto']     = $this->modeloPresupuesto->getPresupuesto($idpresu);
                $data['detalle_guia']    = $this->modeloPresupuesto->getDetallePresupuesto($idpresu);
                $data['deta_pre_pie_bd'] = $this->modeloPresupuesto->getDetallePresupuestoPiezas($idpresu);

                $idsPiezas = [];
                foreach( $this->modeloPresupuesto->getDetallePresupuestoPiezas($idpresu) as $p ){
                    $idsPiezas[] = $p['idpieza'];
                }
                $idsPiezasUnicos = array_unique($idsPiezas);
                $data['stockDePiezasUnicas'] = $this->modeloPieza->listarStockDePiezas($idsPiezasUnicos);

                $data['guia_guardada'] = $this->modeloGuia->guiaGuardadaDetalle($id);//para mostrar en la tablita de tabla  guisa_salida_detalle
                
                $data['title']   = "Editar guía | ".help_nombreWeb();
                $data['guia_bd'] = $guia;
            }else{
                return redirect()->to('/');
            }
        }else if( $cri == 'p' ){           
            if( $presu = $this->modeloPresupuesto->getPresupuesto($id,[1]) ){
                $data['presupuesto']     = $presu;
                $data['detalle_guia']    = $this->modeloPresupuesto->getDetallePresupuesto($id);
                $data['deta_pre_pie_bd'] = $this->modeloPresupuesto->getDetallePresupuestoPiezas($id);
                
                //STOCK DE PIEZAS UNICAS (por temas de performance, de una vez obtener los stocks en vez de consultarlos en el bucle 1 por uno)
                $idsPiezas = [];
                foreach( $this->modeloPresupuesto->getDetallePresupuestoPiezas($id) as $p ){
                    $idsPiezas[] = $p['idpieza'];
                }
                $idsPiezasUnicos = array_unique($idsPiezas);
                $data['stockDePiezasUnicas'] = $this->modeloPieza->listarStockDePiezas($idsPiezasUnicos);
                //FIN STOCK DE PIEZAS UNICAS                

                $data['nroGuia']         = $this->modeloGuia->nroGuia()['nro'];
                $data['title']           = "Nuevo guía | ".help_nombreWeb();  
            }else{
                return redirect()->to('/');
            }
        } 
        
        $data['guiaLinkActive'] = 1;

        $data['transportitas'] = $this->modeloTransportista->getTransportistas(0,100);
        $data['departamentos'] = $this->modeloUbigeo->listarDepartamentos();

        $data['proveedores'] = $this->modeloProveedor->getProveedores();

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
            $opt            = 1; //$this->request->getVar('opt'); Siempre sería 1, porque la guia ya saldría completa con los alquileres.
            $idpre          = $this->request->getVar('idpre');
            $idguia         = $this->request->getVar('idguia');
            $nroGuia        = trim($this->request->getVar('nroguia'));
            $clienterecoge  = $this->request->getVar('clienterecoge');

            $ubigeop  = $this->modeloUbigeo->getUbigeo($distritop,$provinciap,$departamentop)['idubigeo'];
            $ubigeoll = $this->modeloUbigeo->getUbigeo($distritoll,$provinciall,$departamentoll)['idubigeo'];

            // El array de materiales que procesamos en JS
            $piezas = $this->request->getPost('piezas');

            if (empty($piezas) || !is_array($piezas)) {
                echo '<script>Swal.fire("Error", "No hay piezas seleccionadas para procesar.", "error");</script>';
                return;
            }

            // Conexión limpia para queries directas y transacciones
            $db = \Config\Database::connect();
            $db->transStart();

            if( $idguia != '' && $guia = $this->modeloGuia->getGuia($idguia, [2]) ){
                $nroGuia_bd = $guia['gui_nro'];
                if( $nroGuia != $nroGuia_bd ){
                    if( $this->modeloGuia->getGuia_x_nroGuia($nroGuia) ){
                        echo '<script>
                            Swal.fire({
                                title: "Ya existe el número de Guía",
                                icon: "error"
                            });
                        </script>';
                        exit();
                    }
                }

                $sql_update = "update guia set gui_fechatraslado=?,gui_motivo=?,gui_motivodesc=?,gui_ptopartida=?,gui_direccionp=?,gui_ptollegada=?,gui_direccionll=?,gui_placa=?,idtransportista=?,gui_completa=?,gui_status=?,gui_nro=?,gui_clienterecoge=? where idguia = ?";

                $db->query($sql_update, [$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$transportista,$opt,2,$nroGuia,$clienterecoge,$idguia]);

                $db->query("DELETE FROM guia_salida_detalle WHERE idguia = ?", [$idguia]);

                /* if( $this->modeloGuia->modificarGuia($idguia,$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$transportista,$opt,2,$nroGuia,$clienterecoge) ){
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
                }          */               
            }else{
                //echo "$ubigeop - $ubigeoll";
                if( $presu = $this->modeloPresupuesto->getPresupuesto($idpre, [1]) ){        
                    
                    if( $this->modeloGuia->getGuia_x_nroGuia($nroGuia) ){
                        echo '<script>
                            Swal.fire({
                                title: "Ya existe el número de Guía",
                                icon: "error"
                            });
                        </script>';
                        exit();
                    }             
                 
                    $query = "insert into guia(gui_nro,gui_fecha,gui_fechatraslado,gui_motivo,gui_motivodesc,gui_ptopartida,gui_direccionp,gui_ptollegada,gui_direccionll,gui_placa,idpresupuesto,idtransportista,idusuario2,gui_completa,gui_status,gui_clienterecoge) values(?,now(),?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                    $db->query($query, [$nroGuia,$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$idpre,$transportista,session('idusuario'),$opt,2,$clienterecoge]);

                    // Rescatamos el ID de la guía recién creada
                    $idguia = $db->insertID();                    

                    /* if( $this->modeloGuia->generarGuia($nroGuia,$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$idpre,$transportista,session('idusuario'),$opt,2,$clienterecoge) ){
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
                            
                    } */

                    $this->modeloPresupuesto->modificaStatusPre($idpre, 2);

                }
            }

            // 3. PROCESAR DETALLES (TABLA: guia_salida_detalle)
            foreach ($piezas as $idpieza => $datos) {
                
                // Buscamos el 'idtorre' original asociado a esta pieza dentro del presupuesto en ejecución
                // (Esto resuelve el problema de las piezas agrupadas en la interfaz)
                $sql_torre = "SELECT idtorre FROM detalle_presupuesto_piezas WHERE idpresupuesto = ? AND idpieza = ? LIMIT 1";
                $res_torre = $db->query($sql_torre, [$idpre, $idpieza])->getRow();
                
                // Si por algún motivo no encuentra relación, le asignamos 0 o un valor controlado para evitar fallos de clave foránea
                $idtorre = (!empty($res_torre)) ? $res_torre->idtorre : 0;

                // A. GUARDAR STOCK PROPIO (Si es mayor a 0)
                $cant_propia = isset($datos['propio']) ? intval($datos['propio']) : 0;
                if ($cant_propia > 0) {
                    $sql_det_propio = "INSERT INTO guia_salida_detalle (
                        idguia, idtorre, idpieza, cantidad_enviada, dp_origen, idproveedor
                    ) VALUES (?, ?, ?, ?, 'propio', NULL)";

                    $db->query($sql_det_propio, [$idguia, $idtorre, $idpieza, $cant_propia]);
                }

                // B. GUARDAR STOCK EXTERNO / ALQUILERES (Si existen)
                if (isset($datos['externo']) && is_array($datos['externo'])) {
                    foreach ($datos['externo'] as $alquiler) {
                        $id_prov      = intval($alquiler['id_proveedor']);
                        $cant_alquiler = intval($alquiler['cantidad']);

                        if ($cant_alquiler > 0) {
                            $sql_det_externo = "INSERT INTO guia_salida_detalle (
                                idguia, idtorre, idpieza, cantidad_enviada, dp_origen, idproveedor
                            ) VALUES (?, ?, ?, ?, 'externo', ?)";

                            $db->query($sql_det_externo, [$idguia, $idtorre, $idpieza, $cant_alquiler, $id_prov]);
                        }
                    }
                }
            }

            // 4. CIERRE Y VERIFICACIÓN DE TRANSACCIÓN
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                $error = $db->error(); 

                // Creamos un mensaje detallado con el código y la descripción del error de MySQL
                $mensaje_error = "Error DB (" . $error['code'] . "): " . $error['message'];
                
                // Escapamos comillas por seguridad para que no rompa el JS de SweetAlert
                $mensaje_error = addslashes($mensaje_error);

                echo '<script>Swal.fire("Error en Base de Datos", "' . $mensaje_error . '", "error");</script>';
            } else {
                // Retornamos un script para que tu contenedor div "#msj" lo ejecute y limpie o redireccione
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

    public function eliminarGuia(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idguia = $this->request->getVar('id');

            if( $guia = $this->modeloGuia->getGuia($idguia, [1,2]) ){
                $idpresu = $guia['idpresupuesto'];
                /* $piezas  = json_decode($guia['pre_piezas'],true);

                $piezas_upd = [];
                foreach( $piezas as $pi ){
                    unset($pi['req'],$pi['falt'],$pi['st_sale'],$pi['ingresa']);
                    $piezas_upd[] = $pi;
                } */
                
                if( $this->modeloPresupuesto->modificaStatusPre($idpresu, 1) ){
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
        
        $guia_bd = $this->modeloGuia->getGuia($id);
        $idpresu = $guia_bd['idpresupuesto'];

        $data['guia'] = $guia_bd;
        $data['opt']  = $opt;

        //$data['presupuesto']     = $this->modeloPresupuesto->getPresupuesto($idpresu);
        $data['detalle_guia']    = $this->modeloPresupuesto->getDetallePresupuesto($idpresu);
        $data['deta_pre_pie_bd'] = $this->modeloPresupuesto->getDetallePresupuestoPiezas($idpresu);

        $idsPiezas = [];
        foreach( $this->modeloPresupuesto->getDetallePresupuestoPiezas($idpresu) as $p ){
            $idsPiezas[] = $p['idpieza'];
        }
        $idsPiezasUnicos = array_unique($idsPiezas);
        $data['stockDePiezasUnicas'] = $this->modeloPieza->listarStockDePiezas($idsPiezasUnicos);

        //$data['guia_guardada'] = $this->modeloGuia->guiaGuardadaDetalle($id);

        $dompdf->loadHtml(view('sistema/guias/pdf', $data));

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream("guia.pdf", array("Attachment" => false));
        exit();
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

        if( $guia = $this->modeloGuia->getGuia($id,[2,3]) ){            
            $idpresu              = $guia['idpresupuesto'];

            $data = [
                'title' => "Devoluciones del Sistema | ".help_nombreWeb(),
                'devolLinkActive' => 1,
                'guia_bd'      => $guia,
                'idguia_bd'        => $guia['idguia'],
                'idpresupuesto_bd' => $idpresu,
                'guia_cabecera'    => $guia,
                'detalle_piezas_obra' => $this->modeloGuia->paraDevolver($idpresu, $guia['idguia'])// Contiene piezas, stock propio, externos e historial
            ];
        }else{
            return redirect()->to('/');
        }

        return view('sistema/devolucion/devolver', $data);
    }

    public function generarDevolucion(){//ELIMINAR ESTA FUNCION
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
                    //echo "<pre>";
                    foreach( $items as $i ){
                        if( $pi['idpie'] == $i['idpieza'] ){
                            $cant_total = $i['cant']; //total que ingresa
                            $cant_salio = $pi['st_sale']; // cant que sale por pieza

                            //echo "$pi[idpie] - $cant_total / $cant_salio<br>";

                            if( count($arr_restantes) > 0 ){
                                foreach( $arr_restantes as $arr_r ){
                                    if( $pi['idpie'] == $arr_r['idpieza'] ){
                                        $cant_total = $arr_r['restante'];
                                        if( $cant_total > $cant_salio ){
                                            $restante = $cant_total - $cant_salio;
                                            $pi['ingresa'] = $cant_salio;
                                            array_push($arr_restantes, [ 'idpieza' => $pi['idpie'], 'restante' => $restante ]);
                                            continue;
                                        }
                                    }
                                }
                            }

                            if( $cant_total > $cant_salio ){
                                $restante = $cant_total - $cant_salio;
                                $pi['ingresa'] = $cant_salio;
                                array_push($arr_restantes, [ 'idpieza' => $pi['idpie'], 'restante' => $restante ]);
                                continue;
                            }

                            if( $cant_total <= $cant_salio ){
                                $pi['ingresa'] = $cant_total;
                                array_push($arr_restantes, ['idpieza' => $pi['idpie'], 'restante' => 0]);
                                continue;                                
                            }
                        }                        
                    }
                    //print_r($arr_restantes);
                    array_push($arr_items, $pi);
                    //echo "</pre>";


                    /* $arr = array_values(array_filter($items, fn($v) => $v['idpieza'] == $pi['idpie']));
                    $cant_t   = $arr[0]['cant'];//cantidad total que ingresa
                    $st_salio = $pi['st_sale'];//stock que salió

                    $arr_r = array_values(array_filter($arr_restantes, fn($v) => $v['idpieza'] == $pi['idpie']));
                    if( count($arr_r) > 0 && $arr_r[0]['idpieza'] == $pi['idpie'] ){
                        $pi['ingresa']= $arr_r[0]['restante'];                  
                    //}else{
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
                    array_push($arr_items, $pi);*/
                    /* print_r($pi);
                    print_r($arr);
                    print_r($arr_r); */
                    //echo "</pre>";
                }

                /* echo "<pre>";
                print_r($arr_items);
                echo "</pre>"; 
                exit(); */

                $completo = 1;
                foreach( $items as $i ){
                    if( $i['cant'] < $i['salio'] ) $completo = 0;
                }                
                /* echo "<pre>";
                echo $completo;
                //print_r($items);
                print_r($arr_items);
                echo "</pre>"; */

                //para track guia
                $fecha_track = date('d/m/Y h:i:s a');

                if( $guia['guia_track'] != '' ){
                    $arr_track = json_decode($guia['guia_track'], true);
                }
                $arr_track[] = array(
                    'fecha' => $fecha_track,
                    'items' => $items
                );
                /* echo "<pre>";
                print_r($arr_track);
                echo $guia['guia_track'];
                echo "</pre>";
                exit(); */ 
                //fin track guia

                if( $this->modeloGuia->modificarFechaDevolucionGuia($idguia, $fechadevo, $completo, json_encode($arr_track), 3) ){
                    if( $this->modeloPresupuesto->modificaPresuPiezasEstatus(json_encode($arr_items), 3, $guia['idpresupuesto']) ){
                        echo '<script>
                            Swal.fire({
                                title: "Registrado",
                                text: "",
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                            setTimeout(function(){location.href="devoluciones"},1500)
                        </script>';
                    }
                }

            }           

        }
    }

    public function eliminarDevolucion(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idguia = $this->request->getVar('id');

            if( $guia = $this->modeloGuia->getGuia($idguia, [3]) ){
                $idpresu = $guia['idpresupuesto'];

                $db = \Config\Database::connect();

                $db->transStart();

                if( $this->modeloGuia->eliminarDevolucionCompleta($idguia) ){
                    $this->modeloGuia->modificarFechaDevolucionGuia($idguia, null, 0, 2);//cambiar el estado a la guia
                    $this->modeloPresupuesto->modificaStatusPre($idpresu, 2);//cambiar el estado al presupuesto
                }           
                
                $db->transComplete();
                
                if ($db->transStatus() === TRUE) {
                    echo '<script>
                            Swal.fire({
                                title: "Devolución Eliminada",
                                text: "",
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                            setTimeout(function(){location.href="devoluciones"},1500)
                        </script>';
                }
            }
        }
    }

   /*  public function pdfGuiaIngreso($id,$fecha){//ELIMINAR ESTA FUNCION
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new \Dompdf\Dompdf($options);

        //echo "$id - $fecha";
        if( $guia = $this->modeloGuia->getGuia($id,[3]) ){
            $data['params'] = $this->modeloParametros->getParametros();

            $data['guia']  = $guia;
            $data['fecha'] = $fecha;
            $dompdf->loadHtml(view('sistema/devolucion/pdf', $data));

            $dompdf->setPaper('A4', 'portrait');

            $dompdf->render();

            $dompdf->stream("ingreso-$fecha.pdf", array("Attachment" => false));
            exit();
        }

        
    } */






    public function guardar_devolucion()
    {
        // 1. Capturamos los datos clave de la vista
        $idguia        = $this->request->getPost('idguia'); 
        $idpresupuesto = $this->request->getPost('idpresupuesto');
        $piezas        = $this->request->getPost('piezas'); 

        if (empty($idguia) || empty($piezas) || !is_array($piezas)) {
            echo '<script>Swal.fire("Error", "No se recibieron datos válidos para procesar.", "error");</script>';
            return;
        }

        $db = \Config\Database::connect();
        //$db->DBDebug = true;

        try {
            $db->transStart();

            // 2. Recorremos e insertamos los nuevos reingresos parciales en caliente
            foreach ($piezas as $idpieza => $datos) {

                // Buscamos el idtorre correspondiente en el presupuesto para mantener la consistencia
                $sql_torre = "SELECT idtorre FROM detalle_presupuesto_piezas WHERE idpresupuesto = ? AND idpieza = ? LIMIT 1";
                $res_torre = $db->query($sql_torre, [$idpresupuesto, $idpieza])->getRow();
                $idtorre = (!empty($res_torre)) ? $res_torre->idtorre : 0;

                // A. REGISTRAR REINGRESO DE STOCK PROPIO
                $cant_propia = isset($datos['propio']) ? intval($datos['propio']) : 0;
                if ($cant_propia > 0) {
                    $sql_ins_propio = "INSERT INTO guia_devolucion_detalle (
                        idguia, idtorre, idpieza, cantidad_devuelta, dp_origen, idproveedor
                    ) VALUES (?, ?, ?, ?, 'propio', NULL)";

                    $db->query($sql_ins_propio, [$idguia, $idtorre, $idpieza, $cant_propia]);
                }

                // B. REGISTRAR REINGRESO DE PROVEEDORES EXTERNOS (ALQUILERES)
                if (isset($datos['externo']) && is_array($datos['externo'])) {
                    foreach ($datos['externo'] as $alquiler) {
                        $id_prov       = intval($alquiler['id_proveedor']);
                        $cant_alquiler = intval($alquiler['cantidad']);

                        if ($cant_alquiler > 0) {
                            $sql_ins_externo = "INSERT INTO guia_devolucion_detalle (
                                idguia, idtorre, idpieza, cantidad_devuelta, dp_origen, idproveedor
                            ) VALUES (?, ?, ?, ?, 'externo', ?)";

                            $db->query($sql_ins_externo, [$idguia, $idtorre, $idpieza, $cant_alquiler, $id_prov]);
                        }
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                throw new \Exception("Error al procesar la transacción en guia_devolucion_detalle.");
            }

            // =========================================================================
            // 🔍 AUDITORÍA POST-GUARDADO: ¿QUEDA ALGO FLOTANDO EN LA OBRA?
            // =========================================================================
            
            // A. Sumamos todo lo enviado originalmente en esta guía
            $sql_tot_enviado = "SELECT IFNULL(SUM(cantidad_enviada), 0) AS total FROM guia_salida_detalle WHERE idguia = ?";
            $tot_enviado = $db->query($sql_tot_enviado, [$idguia])->getRow()->total;

            // B. Sumamos todo lo devuelto históricamente acumulado hasta hoy
            $sql_tot_devuelto = "SELECT IFNULL(SUM(cantidad_devuelta), 0) AS total FROM guia_devolucion_detalle WHERE idguia = ?";
            $tot_devuelto = $db->query($sql_tot_devuelto, [$idguia])->getRow()->total;

            // C. Evaluamos saldos para decidir el mensaje de SweetAlert
            if (intval($tot_devuelto) >= intval($tot_enviado)) {
                // Caso A: Obra en cero. Todo el andamiaje y piezas regresaron al almacén.

                $this->modeloGuia->modificarFechaDevolucionGuia($idguia, date('Y-m-d'), 1, 3);//cambiar el estado a la guia
                $this->modeloPresupuesto->modificaStatusPre($idpresupuesto, 3);//cambiar el estado al presupuesto

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "¡Retorno Completado!",
                        text: "Se ha completado toda la devolución. No quedan saldos pendientes en obra para esta guía.",
                        confirmButtonColor: "#198754"
                    }).then(() => {
                        window.location.reload();
                    });
                </script>';
            } else {
                // Caso B: Devolución parcial (Aún quedan piezas en el proyecto)
                $pendientes = intval($tot_enviado) - intval($tot_devuelto);

                $this->modeloGuia->modificarFechaDevolucionGuia($idguia, date('Y-m-d'), 0, 3);//cambiar el estado a la guia
                $this->modeloPresupuesto->modificaStatusPre($idpresupuesto, 3);//cambiar el estado al presupuesto
                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "¡Reingreso Parcial Registrado!",
                        text: "Se guardaron los cambios correctamente. Aún quedan ' . $pendientes . ' unidades pendientes en obra.",
                        confirmButtonColor: "#0d6efd"
                    }).then(() => {
                        window.location.reload();
                    });
                </script>';
            }

        } catch (\Throwable $e) {
            $db->transRollback();
            $error_mensaje = addslashes($e->getMessage());
            echo '<script>Swal.fire("Error", "' . $error_mensaje . '", "error");</script>';
        }
    }


    public function eliminar_devolucion_item()
    {
        $idguia_dev_det = $this->request->getPost('idguia_dev_det');
        $idguia = $this->request->getPost('idguia');

        if (empty($idguia_dev_det)) {
            echo '<script>Swal.fire("Error", "ID de registro no válido.", "error");</script>';
            return;
        }

        $db = \Config\Database::connect();
        //$db->DBDebug = true;

        try {
            $db->transStart();

            // Ejecutamos la eliminación usando parámetros "?" apuntando al campo PK real de tu captura
            $sql_delete = "DELETE FROM guia_devolucion_detalle WHERE idguia_dev_det = ?";
            $db->query($sql_delete, [$idguia_dev_det]);

            $this->modeloGuia->modificarFechaDevolucionGuia($idguia, date('Y-m-d'), 0, 3);//cambiar el estado a la guia

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                throw new \Exception("No se pudo eliminar el registro de la base de datos.");
            }

            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "¡Registro Anulado!",
                    text: "El reingreso fue eliminado del historial y los saldos fueron recalculados.",
                    confirmButtonColor: "#198754"
                }).then(() => {
                    window.location.reload();
                });
            </script>';

        } catch (\Throwable $e) {
            $db->transRollback();
            $error_mensaje = addslashes($e->getMessage());
            echo '<script>Swal.fire("Error al eliminar", "' . $error_mensaje . '", "error");</script>';
        }
    }



}