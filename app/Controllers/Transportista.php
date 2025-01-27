<?php

namespace App\Controllers;

class Transportista extends BaseController
{
    protected $modeloUsuario;
    protected $modeloTransportista;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloUsuario = model('UsuarioModel');
        $this->modeloTransportista = model('TransportistaModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Transportista | ".help_nombreWeb();
        $data['transLinkActive'] = 1;

        return view('sistema/transportistas/index', $data);
    }

    public function listarTransportistas(){
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

            $data['transportistas'] = $this->modeloTransportista->getTransportistas($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloTransportista->getTransportistasCount($cri)['total'];

            return view('sistema/transportistas/listar', $data);
        }
    }

    public function registrarTransportista(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            
            $telefono  = trim($this->request->getVar('telefono'));
            $dni       = trim($this->request->getVar('dni'));
            $nombres   = trim($this->request->getVar('nombres'));
            $apellidos = trim($this->request->getVar('apellidos'));
            $idtrans   = $this->request->getVar('id_transe');//para editar

            $validation = \Config\Services::validation();

            $data = [
                'dni'       => $dni,
                'nombres'   => $nombres,
                'apellidos' => $apellidos,
                'telefono'  => $telefono,
            ];

            $rules = [
                'dni' => [
                    'label' => 'DNI', 
                    'rules' => 'required|regex_match[/^[0-9]+$/]|min_length[8]|max_length[8]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'min_length'  => '* Como mínimo 8 caracteres para el {field}.',
                        'max_length'  => '* Como máximo 8 caracteres para el {field}.',
                        'regex_match' => '* El {field} sólo contiene números.'
                    ]
                ],
                'nombres' => [
                    'label' => 'Nombre', 
                    'rules' => 'required|regex_match[/^[a-zA-ZñÑáéíóúÁÉÍÓÚ. 0-9]+$/]|max_length[45]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'regex_match' => '* El {field} no es válido.',
                        'max_length'  => '* El {field} debe contener máximo 45 caracteres.'
                    ]
                ],
                'apellidos' => [
                    'label' => 'Apellido', 
                    'rules' => 'required|regex_match[/^[a-zA-ZñÑáéíóúÁÉÍÓÚ. 0-9]+$/]|max_length[45]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'regex_match' => '* El {field} no es válido.',
                        'max_length'  => '* El {field} debe contener máximo 45 caracteres.'
                    ]
                ],
                'telefono' => [
                    'label' => 'Teléfono', 
                    'rules' => 'required|regex_match[/^[0-9]+$/]|min_length[9]|max_length[12]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'min_length'  => '* Como mínimo 9 caracteres para el {field}.',
                        'max_length'  => '* Como máximo 12 caracteres para el {field}.',
                        'regex_match' => '* El {field} sólo contiene números.'
                    ]
                ],
            ];


            $trans_bd = $this->modeloTransportista->getTransportista($idtrans);

            $validation->setRules($rules);

            if (!$validation->run($data)) {
                return $this->response->setJson(['errors' => $validation->getErrors()]);
            }

            if( $trans_bd ){
                //MODIFICAR
                if( $this->modeloTransportista->modificarTransportista($nombres,$apellidos,$dni,$telefono,$idtrans) ){
                    echo '<script>
                        Swal.fire({
                            title: "Transportista Modificado",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarTransportistas(1);
                        limpiarCampos();
                        $("#modalTransportista").modal("hide");
                    </script>';
                }

            }else{
                //INSERTAR
                if( $this->modeloTransportista->insertarTransportista($nombres,$apellidos,$dni,$telefono,session('idusuario')) ){
                    echo '<script>
                        Swal.fire({
                            title: "Transportista Registrado",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarTransportistas(1);
                        limpiarCampos();
                        $("#modalTransportista").modal("hide");
                    </script>';
                }
            }

        }
    }

    public function eliminarTransportista(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idtrans = $this->request->getVar('id');

            $eliminar = FALSE;
            $mensaje = "";

            $tablas = ['guia'];
            foreach( $tablas as $t ){
                $total = $this->modeloTransportista->verificarTransTieneRegEnTablas($idtrans,$t)['total'];
                if( $total > 0 ){
                    $mensaje .= "<div class='text-start'>El transportista tiene $total registros en la tabla '$t'.</div>";
                    $eliminar = TRUE;
                }
            }

            if( $eliminar ){
                echo '<script>
                    Swal.fire({
                        title: "El transportista no puede ser eliminado",
                        html: "'.$mensaje.'",
                        icon: "warning",
                    });
                </script>';
                exit();
            }

            if( $this->modeloTransportista->eliminarTransportista($idtrans) ){
                echo '<script>
                    Swal.fire({
                        title: "Transportista Eliminado",
                        text: "",
                        icon: "success",
                        showConfirmButton: true,
                    });
                    listarTransportistas(1);
                </script>';
            }
        }
    }



}