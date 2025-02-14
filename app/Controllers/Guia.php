<?php

namespace App\Controllers;

use App\Models\ParametrosModel;
use CodeIgniter\Model;
use Dompdf\Dompdf;

class Guia extends BaseController
{
    protected $modeloParametros;
    protected $modeloUsuario;
    protected $modeloTorre;
    protected $modeloPieza;
    protected $modeloCliente;
    protected $modeloPresupuesto;
    protected $modeloGuia;
    protected $modeloTransportista;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloParametros    = model('ParametrosModel');
        $this->modeloUsuario       = model('UsuarioModel');
        $this->modeloTorre         = model('TorreModel');
        $this->modeloPieza         = model('PiezaModel');
        $this->modeloCliente       = model('ClienteModel');
        $this->modeloPresupuesto   = model('PresupuestoModel');
        $this->modeloGuia          = model('GuiaModel');
        $this->modeloTransportista = model('TransportistaModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']          = "Guia de Remisión del Sistema | ".help_nombreWeb();
        $data['guiaLinkActive'] = 1;

        return view('sistema/guias/index', $data);
    }

    public function listarGuias(){
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

            $data['guias']   = $this->modeloGuia->getGuias($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloGuia->getGuiasCount($cri)['total'];

            return view('sistema/guias/listar', $data);
        }
    }

    public function nuevaGuia($cri, $id){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }

        if( $cri == 'g' ){
            if( $guia = $this->modeloGuia->getGuia($id) ){
                
                $data['title']    = "Editar guía | ".help_nombreWeb();
            }else{
                return redirect()->to('/');
            }
        }else if( $cri == 'p' ){
            $data['presupuesto']  = $this->modeloPresupuesto->getPresupuesto($id, [1]);
            $data['detalle_guia'] = $this->modeloPresupuesto->getDetaPresuParaGuia($id);
            $data['title']        = "Nuevo guía | ".help_nombreWeb();

            $data['transportitas'] = $this->modeloTransportista->getTransportistas(0,100);
        } 
        
        $data['guiaLinkActive'] = 1;

       /*  $data['clientesCbo'] = $this->modeloCliente->getClientesCbo();//para llebar combobox
        $data['torresCbo']   = $this->modeloTorre->getTorresCbo();//para llebar combobox
        $data['param']       = $this->modeloParametros->getParametros(); */

        return view('sistema/guias/nuevaGuia', $data);
    }

    public function listarPresu(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            
            $cri  = trim($this->request->getVar('cri'));

            $cri = strlen($cri) > 2 ? $cri : '';

            $data['presupuestos']   = $this->modeloPresupuesto->getPresupuestos(0, 10, $cri, [1]);

            return view('sistema/guias/listarPresu', $data);
        }
    }



}