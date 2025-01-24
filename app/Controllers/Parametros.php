<?php

namespace App\Controllers;

class Parametros extends BaseController
{
    protected $modeloUsuario;
    protected $modeloParametros;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloUsuario    = model('UsuarioModel');
        $this->modeloParametros = model('ParametrosModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }

        $data['title']           = "Parámetros | ".help_nombreWeb();
        $data['paramLinkActive'] = 1;

        $data['parametro'] = $this->modeloParametros->getParametros();

        return view('sistema/parametros/index', $data);
    }

    public function modificarParametros(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            /* echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            echo "<pre>";
            print_r($_FILES);
            echo "</pre>"; */

            $validation = \Config\Services::validation();

            $data = [
                'porcentaje' => trim($this->request->getVar('porcentaje')),
                'direccion'  => trim($this->request->getVar('direccion')),
                'telefono'   => trim($this->request->getVar('telefono')),
                'correo'     => trim($this->request->getVar('correo')),
                'logo'       => $this->request->getFile('logo'),
                'firma'      => $this->request->getFile('firma'),
            ];

            $rules = [
                'porcentaje' => [
                    'label' => 'Porcentaje', 
                    'rules' => 'required|regex_match[/^[0-9]+$/]|min_length[1]|max_length[5]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'regex_match' => '* El {field} sólo contiene números.',
                        'min_length'  => '* El {field} debe contener mínimo 1 caracteres.',
                        'max_length'  => '* El {field} debe contener máximo 5 caracteres.'
                    ]
                ],
                'direccion' => [
                    'label' => 'Dirección', 
                    'rules' => 'required|alpha_numeric_punct|max_length[150]',
                    'errors' => [
                        'required'            => '* La {field} es requerida.',
                        'alpha_numeric_punct' => '* La {field} no es válida.',
                        'max_length'          => '* La {field} debe contener máximo 150 caracteres.'
                    ]
                ],
                'telefono' => [
                    'label' => 'Teléfono', 
                    'rules' => 'required|regex_match[/^[0-9]+$/]|max_length[12]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'max_length'  => '* Como máximo 12 caracteres para el {field}.',
                        'regex_match' => '* El {field} sólo contiene números.'
                    ]
                ],
                'correo' => [
                    'label' => 'Correo', 
                    'rules' => 'required|valid_email|max_length[100]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'valid_email' => '* El {field} no es válido.',
                        'max_length'  => '* Como máximo 100 caracteres para el {field}.',
                    ]
                ],
                'logo' => [
                    'label' => 'Logo', 
                    'rules' => 'uploaded[logo]|max_size[logo,2048]|mime_in[logo,image/jpg,image/jpeg]',
                    'errors' => [
                        'uploaded' => '* El {field} es requerido.',
                        'max_size' => '* El imagen no deber ser mayor a 2 MB.',
                        'mime_in'  => '* El extensión es inválida.',
                    ]
                ],
                'firma' => [
                    'label' => 'Firma', 
                    'rules' => 'uploaded[firma]|max_size[firma,2048]|mime_in[firma,image/jpg,image/jpeg]',
                    'errors' => [
                        'uploaded' => '* La {field} es requerida.',
                        'max_size' => '* La imagen no deber ser mayor a 2 MB.',
                        'mime_in'  => '* La extensión es inválida.',
                    ]
                ]
            ];

            $params_bd = $this->modeloParametros->getParametros();

            if( $params_bd ){
                if( $params_bd['par_logo'] != '' ){
                    if( $data['logo']->getError() !== 0 ){
                        $rules['logo']['rules'] = 'max_size[logo,2048]|mime_in[logo,image/jpg,image/jpeg]';
                    }
                }
                if( $params_bd['par_firma'] != '' ){
                    if( $data['firma']->getError() !== 0 ){
                        $rules['firma']['rules'] = 'max_size[firma,2048]|mime_in[firma,image/jpg,image/jpeg]';
                    }
                }
            }

            $validation->setRules($rules);

            if (!$validation->run($data)) {
                return $this->response->setJson(['errors' => $validation->getErrors()]); 
            }

            //$us = $this->modeloUsuario->getUsuario(session('idusuario'));

            if( $params_bd ){
                //MODIFICAR
                $idparam = $params_bd['idparametros'];
                echo "MODIFICAR";
            }else{
                //INSERTAR POR PRIMERA VEZ
                if( $data['logo']->getError() === 0 && $data['firma']->getError() === 0 ){
                    $logo  = $data['logo'];
                    $firma = $data['firma'];
    
                    $log_ext    = $logo->getClientExtension();
                    $log_nombre = 'logo.'.$log_ext;
                    $log_folder = "public/images/logo/";

                    $fir_ext    = $firma->getClientExtension();
                    $fir_nombre = 'firma.'.$fir_ext;
                    $fir_folder = "public/images/firma/";
    
                    $image = \Config\Services::image();
                    $image->withFile($logo)
                        ->resize(200, 200, true, 'width')
                        ->save($log_folder.$log_nombre);

                    $image->withFile($firma)
                        ->resize(200, 200, true, 'width')
                        ->save($fir_folder.$fir_nombre);

                    $data['logo']  = $log_nombre;
                    $data['firma'] = $fir_nombre;

                    if( $this->modeloParametros->guardarParametro(1,$data,null) ){
                        echo '<script>
                            Swal.fire({
                                title: "Datos Actualizados.",
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
    }

    function eliminarImagen(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            /* echo "<pre>";
            print_r($_POST);
            echo "</pre>"; */
            $params_bd = $this->modeloParametros->getParametros();

            if( $_POST['opt'] == 'logo' ){
                unlink('public/images/logo/'.$params_bd['par_logo']);
                if( $this->modeloParametros->eliminarImagen($_POST['opt']) ){
                    echo "<script>location.reload()</script>";
                }
            }else if( $_POST['opt'] == 'firma' ){
                unlink('public/images/firma/'.$params_bd['par_firma']);
                if( $this->modeloParametros->eliminarImagen($_POST['opt']) ){
                    echo "<script>location.reload()</script>";
                }
            }
        }
    }
}
