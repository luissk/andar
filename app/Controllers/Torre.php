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

                        array_push($pData, $data);
                    }

                    echo json_encode($pData);
                }                
            }           

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
                    'rules' => 'required|regex_match[/^[a-zA-ZñÑáéíóúÁÉÍÓÚ.\-\",\/ 0-9]+$/]|max_length[200]',
                    'errors' => [
                        'required'    => '* La {field} es requerida.',
                        'regex_match' => '* La {field} no es válida.',
                        'max_length'  => '* La {field} debe contener máximo 200 caracteres.'
                    ]
                ],
                'piezas' => [
                    'label' => 'Pieza',
                    'rules' => 'required|regex_match[/^[0-9]+$/]',
                    'errors' => [
                        'required'    => '* Seleccione unas {field}.',
                        'regex_match' => '* El campo {field} sólo contiene números.'
                    ]
                ],
                'plano' => [
                    'label' => 'Logo', 
                    'rules' => 'uploaded[plano]|max_size[plano,2048]|mime_in[plano,application/pdf]',
                    'errors' => [
                        'uploaded' => '* El {field} es requerido.',
                        'max_size' => '* El {field} no deber ser mayor a 2 MB.',
                        'mime_in'  => '* El extensión es inválida.',
                    ]
                ],
            ];

            $count_items = count($items);
            //validar que haya seleccionado al menos una pieza
            if( $count_items == 0 ){
                $validation->setError('piezas', 'Debe agregar al menos una pieza.');
            }
            //validar que las cantidades sean válidas (>0)
            if( $count_items > 0 ){
                foreach( $items as $i ){
                    if( $i['cant'] < 1 ) $validation->setError('piezas', 'Ingrese una cantidad válida');
                }
            }

            $validation->setRules($rules);

            if (!$validation->run($data)) {
                return $this->response->setJson(['errors' => $validation->getErrors()]);
            }

            //plano
            $carpeta = 'public/uploads/planos/';
            $nombre_plano = 'plano_'.help_stringRandom(10,2).".pdf";
            
            //INSERTAR TORRE
            if( $idtorre_i = $this->modeloTorre->insertarTorre($desc, $nombre_plano, session('idusuario')) ){
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
                        $("#modalTorre").modal("hide");
                    </script>';
                }
            }

            

        }
    }



}