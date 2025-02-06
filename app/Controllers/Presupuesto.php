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

        $data['nroPre'] = $this->modeloPresupuesto->nroPresupuesto();
        $data['param']  = $this->modeloParametros->getParametros();

        return view('sistema/presupuestos/nuevoPresupuesto', $data);
    }

    public function listarClientesAjaxSelect2(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            if( !empty($this->request->getVar('type')) && $this->request->getVar('type') == 'clientes' ){
                $cri = !empty( trim( $this->request->getVar('search') ) ) ? trim( $this->request->getVar('search') ) : '';

                $piezas = $this->modeloCliente->getClientesAjax($cri);

                if( $piezas ){
                    $pData = array();
                    foreach( $piezas as $p ){
                        $data['id']     = $p['idcliente'];
                        $data['text']   = $p['cli_nombrerazon'];
                        $data['dniruc'] = $p['cli_dniruc'];

                        array_push($pData, $data);
                    }

                    echo json_encode($pData);
                }                
            }         

        }
    }

    public function listarTorresAjaxSelect2(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            if( !empty($this->request->getVar('type')) && $this->request->getVar('type') == 'torres' ){
                $cri = !empty( trim( $this->request->getVar('search') ) ) ? trim( $this->request->getVar('search') ) : '';

                $torres = $this->modeloTorre->getTorresAjax($cri);

                if( $torres ){
                    $pData = array();
                    foreach( $torres as $t ){
                        $data['id']    = $t['idtorre'];
                        $data['text']  = $t['tor_desc'];
                        $data['total'] = $t['total'];

                        array_push($pData, $data);
                    }

                    echo json_encode($pData);
                }                
            }         

        }
    }


}