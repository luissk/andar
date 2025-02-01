<?php

namespace App\Controllers;

class Presupuesto extends BaseController
{
    protected $modeloParametros;
    protected $modeloUsuario;
    protected $modeloTorre;
    protected $modeloPieza;
    protected $modeloCliente;
    protected $modeloPresupuesto;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloParametros  = model('ParametrosModel');
        $this->modeloUsuario     = model('UsuarioModel');
        $this->modeloTorre       = model('TorreModel');
        $this->modeloPieza       = model('PiezaModel');
        $this->modeloCliente     = model('ClienteModel');
        $this->modeloPresupuesto = model('PresupuestoModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Presupuestos del Sistema | ".help_nombreWeb();
        $data['presuLinkActive'] = 1;

        return view('sistema/presupuestos/index', $data);
    }

    public function nuevoPresupuesto(){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Nuevo presupuesto | ".help_nombreWeb();
        $data['presuLinkActive'] = 1;

        return view('sistema/presupuestos/nuevoPresupuesto', $data);
    }




}