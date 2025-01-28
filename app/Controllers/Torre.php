<?php

namespace App\Controllers;

class Torre extends BaseController
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
        
        $data['title']           = "Torres del Sistema | ".help_nombreWeb();
        $data['torresLinkActive'] = 1;

        return view('sistema/torres/index', $data);
    }



}