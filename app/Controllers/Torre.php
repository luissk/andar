<?php

namespace App\Controllers;

class Torre extends BaseController
{
    protected $modeloUsuario;
    protected $modeloTorre;
    protected $modeloPieza;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloUsuario = model('UsuarioModel');
        $this->modeloTorre   = model('TorreModel');
        $this->modeloPieza   = model('PiezaModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Torres del Sistema | ".help_nombreWeb();
        $data['torresLinkActive'] = 1;

        return view('sistema/torres/index', $data);
    }

    public function listarPiezasAjaxSelect2(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            if( !empty($this->request->getVar('type')) && $this->request->getVar('type') == 'piezas' ){
                $cri = !empty( trim( $this->request->getVar('search') ) ) ? trim( $this->request->getVar('search') ) : '';

                $piezas = $this->modeloPieza->getPiezasAjax($cri);

                if( $piezas ){
                    $pData = array();
                    foreach( $piezas as $p ){
                        $data['id']     = $p['idpieza'];
                        $data['text']   = $p['pie_desc'];
                        $data['codigo'] = $p['pie_codigo'];
                        $data['cant']   = $p['pie_cant'];

                        array_push($pData, $data);
                    }

                    echo json_encode($pData);
                }                
            }           

        }
    }

    public function listarTorres(){
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

            $data['torres']         = $this->modeloTorre->getTorres($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloTorre->getTorresCount($cri)['total'];

            return view('sistema/torres/listar', $data);
        }
    }

    public function registrarTorre(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            /* echo "<pre>";
            print_r($_POST);
            print_r($_FILES);
            echo "</pre>";
            exit(); */
            $desc      = trim($this->request->getVar('desc'));
            $plano     = $this->request->getFile('plano');
            $piezas    = $this->request->getVar('piezas');
            $items     = json_decode($this->request->getVar('items'), true);
            $id_torree = $this->request->getVar('id_torree');//para editar

            //print_r($items);

            $validation = \Config\Services::validation();

            $data = [
                'desc'   => $desc,
                'piezas' => $piezas,
                'plano'  => $plano,
            ];

            $rules = [
                'desc' => [
                    'label' => 'Descripción', 
                    //'rules' => 'required|regex_match[/^[a-zA-ZñÑáéíóúÁÉÍÓÚ.\-\",\/ 0-9]+$/]|max_length[200]',
                    'rules' => 'required|max_length[200]',
                    'errors' => [
                        'required'    => '* La {field} es requerida.',
                        //'regex_match' => '* La {field} no es válida.',
                        'max_length'  => '* La {field} debe contener máximo 200 caracteres.'
                    ]
                ],
                /* 'piezas' => [
                    'label' => 'Pieza',
                    'rules' => 'required|regex_match[/^[0-9]+$/]',
                    'errors' => [
                        'required'    => '* Seleccione una(s) {field}.',
                        'regex_match' => '* El campo {field} sólo contiene números.'
                    ]
                ], */
                'plano' => [
                    'label' => 'Plano', 
                    'rules' => 'max_size[plano,2048]|mime_in[plano,application/pdf]',
                    'errors' => [
                        //'uploaded' => '* El {field} es requerido.',
                        'max_size' => '* El {field} no deber ser mayor a 2 MB.',
                        'mime_in'  => '* El extensión es inválida.',
                    ]
                ],
            ];

            if( $torre_bd = $this->modeloTorre->getTorre($id_torree) ){//hacer validaciones cuando es para modificar
                if( $torre_bd['tor_plano'] != '' ){
                    if( $plano->getError() !== 0 ){
                        $rules['plano']['rules'] = 'max_size[plano,2048]|mime_in[plano,application/pdf]';
                    }
                    if( $plano->getError() === 0 ){
                        $validation->setError('plano', 'Ya tienes un plano guardado.');
                    }
                }
            }

            $count_items = count($items);//validar que haya seleccionado al menos una pieza            
            if( $count_items == 0 ){
                $validation->setError('piezas', 'Debe agregar al menos una pieza.');
            }            
            if( $count_items > 0 ){//validar que las cantidades sean válidas (>0)
                foreach( $items as $i ){
                    if( $i['cant'] < 1 ) $validation->setError('piezas', 'Ingrese una cantidad válida');
                }
            }

            $validation->setRules($rules);
            if (!$validation->run($data)) {
                return $this->response->setJson(['errors' => $validation->getErrors()]);
            }

            if( $torre_bd ){//MODIFICAR
                //verificar si ya esta presupuesto, si esta no se puede eliminar ni agregar piezas
                $t = 'detalle_presupuesto';
                $total = $this->modeloTorre->verificarTorTieneRegEnTablas($id_torree,$t)['total'];
                if( $total > 0 ){
                    echo '<script>
                        Swal.fire({
                            title: "",
                            text: "No puedes agregar ni quitar piezas de la torre, porque ya tiene presupuesto(s).",
                            icon: "error",
                            showConfirmButton: true,
                        });
                        listarTorres(1);
                        limpiarCampos();
                        $("#modalTorre").modal("hide");
                    </script>';
                    exit();
                }

                $detalle_bd = $this->modeloTorre->getDetalleTorre($id_torree);
                $nombre_plano = $plano->getError() === 0 ? 'plano_'.help_stringRandom(10,2).".pdf" : $torre_bd['tor_plano'];
                if( $plano->getError() === 0 ){
                    $carpeta = 'public/uploads/planos/';
                    $plano->move($carpeta, $nombre_plano);
                }
                if( $this->modeloTorre->modificarTorre($desc, $nombre_plano, $id_torree) ){
                    if( $this->modeloTorre->eliminarDetalle($id_torree) ){
                        $res = FALSE;
                        foreach( $items as $i ){
                            $id_i   = $i['id'];
                            $cant_i = $i['cant'];

                            if( $this->modeloTorre->insertarDetalleTorre($id_torree,$id_i,$cant_i) ){
                                $res = TRUE;
                            }
                        }

                        if( $res ){
                            echo '<script>
                                Swal.fire({
                                    title: "Torre Modificada",
                                    text: "",
                                    icon: "success",
                                    showConfirmButton: true,
                                });
                                listarTorres(1);
                                limpiarCampos();
                                $("#modalTorre").modal("hide");
                            </script>';
                        }
                    }
                }
            }else{//INSERTAR
                //plano
                $carpeta = 'public/uploads/planos/';
                $nombre_plano = $plano->getError() === 0 ? 'plano_'.help_stringRandom(10,2).".pdf" : '';
                
                //INSERTAR TORRE
                if( $idtorre_i = $this->modeloTorre->insertarTorre($desc, $nombre_plano, session('idusuario')) ){
                    if( $plano->getError() === 0 )
                        $plano->move($carpeta, $nombre_plano);//plano a carpeta

                    //INSERTAR DETALLE
                    $res = FALSE;
                    foreach( $items as $i ){
                        $id_i   = $i['id'];
                        $cant_i = $i['cant'];

                        if( $this->modeloTorre->insertarDetalleTorre($idtorre_i,$id_i,$cant_i) ){
                            $res = TRUE;
                        }
                    }

                    if( $res ){
                        echo '<script>
                            Swal.fire({
                                title: "Torre Registrada",
                                text: "",
                                icon: "success",
                                showConfirmButton: true,
                            });
                            listarTorres(1);
                            limpiarCampos();
                            $("#modalTorre").modal("hide");
                        </script>';
                    }
                } 
            }              

        }
    }

    public function eliminarTorre(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idtorre = $this->request->getVar('id');

            $eliminar = FALSE;
            $mensaje = "";

            $tablas = ['detalle_presupuesto'];
            foreach( $tablas as $t ){
                $total = $this->modeloTorre->verificarTorTieneRegEnTablas($idtorre,$t)['total'];
                if( $total > 0 ){
                    $mensaje .= "<div class='text-start'>La torre tiene $total registros en la tabla '$t'.</div>";
                    $eliminar = TRUE;
                }
            }

            if( $eliminar ){
                echo '<script>
                    Swal.fire({
                        title: "La torre no puede ser eliminada",
                        html: "'.$mensaje.'",
                        icon: "warning",
                    });
                </script>';
                exit();
            }

            
            if( $torre_bd = $this->modeloTorre->getTorre($idtorre) ){
                if( $torre_bd['tor_plano'] != '' ){
                    unlink('public/uploads/planos/'.$torre_bd['tor_plano']);
                }
            }
            
            if( $this->modeloTorre->eliminarDetalle($idtorre) ){
                if( $this->modeloTorre->eliminarTorre($idtorre) ){
                    echo '<script>
                        Swal.fire({
                            title: "Torre eliminada",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarTorres(1);
                        limpiarCampos();
                        $("#modalTorre").modal("hide");
                    </script>';
                }
            }
        }
    }

    function eliminarPlano(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            /* echo "<pre>";
            print_r($_POST);
            echo "</pre>"; */
            $cri = $_POST['cri'];
            $idtorre = $_POST['id'];
            $torre = $this->modeloTorre->getTorre($idtorre);

            if( $torre ){
                unlink('public/uploads/planos/'.$torre['tor_plano']);
                if( $this->modeloTorre->eliminarPlano($idtorre) ){
                    echo '<script>
                        listarTorres(1,"'.$cri.'");
                        limpiarCampos();
                        $("#modalTorre").modal("hide");
                    </script>';
                }
            }
        }
    }

    function modalDetalleTorre(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $idtorre = $_POST['id'];
            $torre   = $this->modeloTorre->getTorre($idtorre);
            $detalle = $this->modeloTorre->getDetalleTorre($idtorre);

            $data['torre'] = $torre;
            $data['detalle'] = $detalle;

            return view('sistema/torres/modalDetalle', $data);

        }
    }

}