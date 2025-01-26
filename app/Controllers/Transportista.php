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

        if( session('idtipousuario') != 1 ) return redirect()->to('sistema');
        
        $data['title']           = "Usuarios del Sistema | ".help_nombreWeb();
        $data['usersLinkActive'] = 1;

        $data['perfiles'] = $this->modeloUsuario->getPerfiles();

        return view('sistema/usuarios/index', $data);
    }



}