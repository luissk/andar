<?php

namespace App\Controllers;

class Usuario extends BaseController
{
    protected $modeloUsuario;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloUsuario = model('UsuarioModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }

        if( session('idtipousuario') != 1 ) return redirect()->to('sistema');
        
        $data['title']           = "Usuarios del Sistema | ".help_nombreWeb();
        $data['usersLinkActive'] = 1;

        $data['perfiles'] = $this->modeloUsuario->getPerfiles();

        return view('sistema/usuarios/index', $data);
    }

    public function misDatos(){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title'] = "Mis Datos | ".help_nombreWeb();

        $data['misdatos'] = $this->modeloUsuario->getUsuario(session('idusuario'));

        return view('sistema/usuarios/misDatos', $data);
    }

    public function cambiarPassword(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $password  = trim($this->request->getVar('password'));

            $validation = \Config\Services::validation();

            $data = [
                'password'  => $password,
            ];

            $rules = [
                'password' => [
                    'label' => 'Password', 
                    'rules' => 'required|min_length[8]|max_length[15]',
                    'errors' => [
                        'required'   => '* El {field} es requerido.',
                        'min_length' => '* El {field} debe tener al menos 8 caracteres.',
                        'max_length' => '* El {field} debe tener hasta 15 caracteres.'
                    ]
                ]
            ];
            $validation->setRules($rules);
            if (!$validation->run($data)) {
                return $this->response->setJson(['errors' => $validation->getErrors()]);
            }

            $usuario_bd = $this->modeloUsuario->getUsuario(session('idusuario'));
            if( $usuario_bd ){
                $hash = '$2a$12$YmtIBS/VsxVywSQHV4A2.upBWJxS2VSqFzUwo1eMU5.tIGOgne6YG';
                $password = crypt($password, $hash);

                if( $this->modeloUsuario->cambiarPassword(session('idusuario'), $password) ){
                    echo '<script>
                        Swal.fire({
                            title: "Se cambió el password",
                            text: "",
                            icon: "success",
                            showConfirmButton: false,
                        });
                        setTimeout(function(){ location.reload() },1500);
                    </script>';
                }
            }
        }
    }

    public function listarUsuarios(){
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

            $data['usuarios']       = $this->modeloUsuario->getUsuarios($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloUsuario->getUsuariosCount($cri)['total'];

            return view('sistema/usuarios/listar', $data);
        }
    }

    public function registrarUsuario(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            
            $usuario   = trim($this->request->getVar('usuario'));
            $dni       = trim($this->request->getVar('dni'));
            $nombres   = trim($this->request->getVar('nombres'));
            $apellidos = trim($this->request->getVar('apellidos'));
            $perfil    = $this->request->getVar('perfil');
            $password  = trim($this->request->getVar('password'));
            $idusuario = $this->request->getVar('id_usuarioe');//para editar

            $validation = \Config\Services::validation();

            $data = [
                'usuario'   => $usuario,
                'dni'       => $dni,
                'nombres'   => $nombres,
                'apellidos' => $apellidos,
                'perfil'    => $perfil,
                'password'  => $password,
            ];

            $rules = [
                'usuario' => [
                    'label' => 'Usuario', 
                    'rules' => 'required|regex_match[/^[a-zA-Z_]+$/]|max_length[40]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'regex_match' => '* El {field} no es válido.',
                        'max_length'  => '* El {field} debe contener máximo 40 caracteres.'
                    ]
                ],
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
                'perfil' => [
                    'label' => 'Perfil', 
                    'rules' => 'required|regex_match[/^[0-9]+$/]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'regex_match' => '* El {field} sólo contiene números.'
                    ]
                ],
                'password' => [
                    'label' => 'Password', 
                    'rules' => 'required|min_length[8]|max_length[15]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'min_length' => '* El {field} debe tener al menos 8 caracteres.',
                        'max_length' => '* El {field} debe tener hasta 15 caracteres.'
                    ]
                ],
            ];

            $hash = '$2a$12$YmtIBS/VsxVywSQHV4A2.upBWJxS2VSqFzUwo1eMU5.tIGOgne6YG';
            $password = $password != '' ? crypt($password, $hash) : '';

            $usuario_bd = $this->modeloUsuario->getUsuario($idusuario);
            if( $usuario_bd ){
                if( $password == '' ){
                    $rules['password']['rules'] = 'permit_empty|min_length[8]|max_length[15]'; //permitir vacio
                    array_splice($rules['password']['errors'], 0, 1); //remover msj error requerido
                    $password = $usuario_bd['usu_password'];
                }
            }

            $validation->setRules($rules);

            if (!$validation->run($data)) {
                return $this->response->setJson(['errors' => $validation->getErrors()]);
            }

            if( $usuario_bd ){
                //MODIFICAR
                //print_r($_POST);
                $user_bd = $usuario_bd['usu_usuario'];
                if( strtoupper($usuario) != strtoupper($user_bd) ){
                    if( $this->modeloUsuario->validarLogin($usuario) ){
                        echo '<script>
                            Swal.fire({
                                title: "El usuario ya existe",
                                icon: "error"
                            });
                        </script>';
                        exit();
                    }
                }
                if( $this->modeloUsuario->modificarUsuario($usuario,$dni,$nombres,$apellidos,$perfil,$password,$idusuario) ){
                    echo '<script>
                        Swal.fire({
                            title: "Usuario Modificado",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarUsuarios(1);
                        limpiarCampos();
                        $("#modalUsuario").modal("hide");
                    </script>';
                }

            }else{
                //INSERTAR
                if( $this->modeloUsuario->validarLogin($usuario) ){
                    echo '<script>
                        Swal.fire({
                            title: "El usuario ya existe",
                            icon: "error"
                        });
                    </script>';
                    exit();
                }

                if( $this->modeloUsuario->insertarUsuario($usuario,$dni,$nombres,$apellidos,$perfil,$password,session('idusuario')) ){
                    echo '<script>
                        Swal.fire({
                            title: "Usuario Registrado",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarUsuarios(1);
                        limpiarCampos();
                        $("#modalUsuario").modal("hide");
                    </script>';
                }
            }

        }
    }

    public function eliminarUsuario(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idusuario = $this->request->getVar('id');

            if( $idusuario == 1 ) exit();

            $eliminar = FALSE;
            $mensaje = "";

            $tablas = ['usuario','transportista','cliente','pieza','torre','presupuesto','guia','factura'];
            foreach( $tablas as $t ){
                $total = $this->modeloUsuario->verificarUsuTieneRegEnTablas($idusuario,$t)['total'];
                if( $total > 0 ){
                    $mensaje .= "<div class='text-start'>El usuario tiene $total registros en la tabla '$t'.</div>";
                    $eliminar = TRUE;
                }
            }

            if( $eliminar ){
                echo '<script>
                    Swal.fire({
                        title: "El usuario no puede ser eliminado",
                        html: "'.$mensaje.'",
                        icon: "warning",
                    });
                </script>';
                exit();
            }

            if( $this->modeloUsuario->eliminarUsuario($idusuario) ){
                echo '<script>
                    Swal.fire({
                        title: "Usuario Eliminado",
                        text: "",
                        icon: "success",
                        showConfirmButton: true,
                    });
                    listarUsuarios(1);
                </script>';
            }
        }
    }

    

}
