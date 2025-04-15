<?php

namespace App\Controllers;

class Cliente extends BaseController
{
    protected $modeloUsuario;
    protected $modeloCliente;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloUsuario = model('UsuarioModel');
        $this->modeloCliente = model('ClienteModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']              = "Clientes del Sistema | ".help_nombreWeb();
        $data['clientesLinkActive'] = 1;

        return view('sistema/clientes/index', $data);
    }

    public function listarClientes(){
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

            $data['clientes']       = $this->modeloCliente->getClientes($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloCliente->getClientesCount($cri)['total'];

            return view('sistema/clientes/listar', $data);
        }
    }

    public function registrarCliente(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            
            $dniruc    = trim($this->request->getVar('dniruc'));
            $nombrer   = trim($this->request->getVar('nombrer'));
            $nombrec   = trim($this->request->getVar('nombrec'));
            $correoc   = trim($this->request->getVar('correoc'));
            $telefc    = trim($this->request->getVar('telefc'));
            $idcliente = $this->request->getVar('id_clientee');//para editar

            $validation = \Config\Services::validation();

            $data = [
                'dniruc'  => $dniruc,
                'nombrer' => $nombrer,
                'nombrec' => $nombrec,
                'correoc' => $correoc,
                'telefc'  => $telefc,
            ];

            $rules = [
                'dniruc' => [
                    'label' => 'DNI o RUC', 
                    'rules' => 'permit_empty|regex_match[/^[0-9]+$/]|min_length[8]|max_length[11]',
                    'errors' => [
                        //'required'    => '* El {field} es requerido.',
                        'min_length'  => '* Como mínimo 8 caracteres para el {field}.',
                        'max_length'  => '* Como máximo 11 caracteres para el {field}.',
                        'regex_match' => '* El {field} sólo contiene números.'
                    ]
                ],
                'nombrer' => [
                    'label' => 'Nombre o Razón', 
                    'rules' => 'required|regex_match[/^[a-zA-ZñÑáéíóúÁÉÍÓÚ. 0-9]+$/]|max_length[100]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'regex_match' => '* El {field} no es válido.',
                        'max_length'  => '* El {field} debe contener máximo 100 caracteres.'
                    ]
                ],
                'nombrec' => [
                    'label' => 'Nombre de contacto', 
                    'rules' => 'permit_empty|regex_match[/^[a-zA-ZñÑáéíóúÁÉÍÓÚ. 0-9]+$/]|max_length[100]',
                    'errors' => [
                        //'required'    => '* El {field} es requerido.',
                        'regex_match' => '* El {field} no es válido.',
                        'max_length'  => '* El {field} debe contener máximo 100 caracteres.'
                    ]
                ],
                'correoc' => [
                    'label' => 'Correo de contacto',
                    'rules' => 'permit_empty|valid_email|max_length[100]',
                    'errors' => [
                        //'required'    => '* El {field} es requerido.',
                        'valid_email' => '* El {field} no es válido.',
                        'max_length'  => '* Como máximo 100 caracteres para el {field}.',
                    ]
                ],
                'telefc' => [
                    'label' => 'Teléfono de contacto', 
                    'rules' => 'permit_empty|regex_match[/^[0-9]+$/]|min_length[9]|max_length[12]',
                    'errors' => [
                        //'required'    => '* El {field} es requerido.',
                        'min_length'  => '* Como mínimo 9 caracteres para el {field}.',
                        'max_length'  => '* Como máximo 12 caracteres para el {field}.',
                        'regex_match' => '* El {field} sólo contiene números.'
                    ]
                ],
            ];

            $cliente_bd = $this->modeloCliente->getCliente($idcliente);

            $validation->setRules($rules);

            if (!$validation->run($data)) {
                return $this->response->setJson(['errors' => $validation->getErrors()]);
            }

            if( $cliente_bd ){
                //MODIFICAR
                if( $dniruc != '' ){
                    $dniruc_bd = $cliente_bd['cli_dniruc'];
                    if( $dniruc != $dniruc_bd ){
                        if( $this->modeloCliente->getClientePorDniRuc($dniruc) ){
                            echo '<script>
                                Swal.fire({
                                    title: "El Dni o Ruc ya existe",
                                    icon: "error"
                                });
                            </script>';
                            exit();
                        }
                    }
                }
                if( $this->modeloCliente->modificarCliente($dniruc,$nombrer,$nombrec,$correoc,$telefc,$idcliente) ){
                    echo '<script>
                        Swal.fire({
                            title: "Cliente Modificado",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarClientes(1);
                        limpiarCampos();
                        $("#modalCliente").modal("hide");
                    </script>';
                }

            }else{
                //INSERTAR
                if( $dniruc != '' ){
                    if( $this->modeloCliente->getClientePorDniRuc($dniruc) ){
                        echo '<script>
                            Swal.fire({
                                title: "El Dni o Ruc ya existe",
                                icon: "error"
                            });
                        </script>';
                        exit();
                    }
                }

                if( $this->modeloCliente->insertarCliente($dniruc,$nombrer,$nombrec,$correoc,$telefc,session('idusuario')) ){
                    echo '<script>
                        Swal.fire({
                            title: "Cliente Registrado",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarClientes(1);
                        limpiarCampos();
                        $("#modalCliente").modal("hide");
                    </script>';
                }
            }

        }
    }

    public function eliminarCliente(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idcliente = $this->request->getVar('id');

            $eliminar = FALSE;
            $mensaje = "";

            $tablas = ['presupuesto'];
            foreach( $tablas as $t ){
                $total = $this->modeloCliente->verificarCliTieneRegEnTablas($idcliente,$t)['total'];
                if( $total > 0 ){
                    $mensaje .= "<div class='text-start'>El cliente tiene $total registros en la tabla '$t'.</div>";
                    $eliminar = TRUE;
                }
            }

            if( $eliminar ){
                echo '<script>
                    Swal.fire({
                        title: "El cliente no puede ser eliminado",
                        html: "'.$mensaje.'",
                        icon: "warning",
                    });
                </script>';
                exit();
            }

            if( $this->modeloCliente->eliminarCliente($idcliente) ){
                echo '<script>
                    Swal.fire({
                        title: "Usuario Eliminado",
                        text: "",
                        icon: "success",
                        showConfirmButton: true,
                    });
                    listarClientes(1);
                </script>';
            }
        }
    }

    

}
