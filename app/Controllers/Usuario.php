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
        
        $data['title']           = "Usuarios del Sistema | ".help_nombreWeb();
        $data['usersLinkActive'] = 1;

        $data['perfiles'] = $this->modeloUsuario->getPerfiles();

        return view('sistema/usuarios/index', $data);
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
            
            print_r($_POST);
        }
    }

}
