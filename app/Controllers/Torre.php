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



}