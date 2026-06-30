<?php

namespace App\Controllers;

use App\Models\ParametrosModel;
use CodeIgniter\Model;
use Dompdf\Dompdf;

class Proveedor extends BaseController
{
    protected $modeloParametros;
   /*  protected $modeloUsuario;
    protected $modeloTorre;
    protected $modeloPieza;
    protected $modeloCliente;
    protected $modeloPresupuesto;
    protected $modeloGuia;
    protected $modeloTransportista;
    protected $modeloUbigeo; */
    protected $modeloProveedor;
    protected $helpers = ['funciones'];

    public function __construct(){
        /* $this->modeloParametros    = model('ParametrosModel');
        $this->modeloUsuario       = model('UsuarioModel');
        $this->modeloTorre         = model('TorreModel');
        $this->modeloPieza         = model('PiezaModel');
        $this->modeloCliente       = model('ClienteModel');
        $this->modeloPresupuesto   = model('PresupuestoModel');
        $this->modeloGuia          = model('GuiaModel');
        $this->modeloTransportista = model('TransportistaModel');
        $this->modeloUbigeo = model('UbigeoModel'); */
        $this->modeloProveedor = model('ProveedorModel');
        $this->session;
    }

    function index(){

        if( !session('idusuario') ) return redirect()->to('/');
        
        $data['title']          = "Proveedores del Sistema | ".help_nombreWeb();
        $data['provLinkActive'] = 1;

        return view('sistema/proveedor/index', $data);

    }

    function listarProveedores(){
        if ($this->request->isAJAX()) {
            if (!session('idusuario')) {
                exit();
            }

            $proveedores = $this->modeloProveedor->getProveedores();
            
            $data = [];

            foreach ($proveedores as $row) {
                $data[] = [
                    "id"        => $row['idproveedor'],
                    "pro_ruc"   => $row['pro_ruc'],
                    "pro_razon" => $row['pro_razon'],
                ];
            }

            echo json_encode(["data" => $data]);
        }
    }

    public function registrarProveedor(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            //print_r($_POST);exit();
            $ruc         = trim($this->request->getVar('ruc'));
            $razon       = trim($this->request->getVar('razon'));
            $idproveedor = $this->request->getVar('idproveedor_e');//para editar

            $validation = \Config\Services::validation();

            $data = [
                'ruc'     => $ruc,
                'razon'   => $razon,
            ];

            $regla_ruc = 'required|regex_match[/^[0-9]+$/]|max_length[11]|is_unique[proveedor.pro_ruc]';
            if( !empty($idproveedor) ){
                $regla_ruc = "required|regex_match[/^[0-9]+$/]|max_length[11]|is_unique[proveedor.pro_ruc,idproveedor,$idproveedor]";
            }

            $rules = [
                'razon' => [
                    'label' => 'Razón', 
                    'rules' => 'required|max_length[150]',
                    'errors' => [
                        'required'    => '* La {field} es requerida.',
                        'max_length'  => '* La {field} debe contener máximo 150 caracteres.'
                    ]
                ],
                'ruc' => [
                    'label' => 'RUC', 
                    'rules' => $regla_ruc,
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'max_length'  => '* El {field} 11 número máximo.',
                        'regex_match' => '* El {field} es numérico.',
                        'is_unique' => '* El {field} es único.'
                    ]
                ],
            ];

            $validation->setRules($rules);

            if (!$validation->run($data)) {
                return $this->response->setJson(['status' => 'error', 'errors' => $validation->getErrors()]);
            }

            $provee_bd = $this->modeloProveedor->getProveedor($idproveedor);

            if( $provee_bd ){
                if( $this->modeloProveedor->modificarProveedor($data, $idproveedor) ){
                    return $this->response->setJson(['status' => 'ok', 'message' => 'Proveedor Modificado']);
                }
            }else{
                if( $this->modeloProveedor->insertarProveedor($data) ){
                    return $this->response->setJson(['status' => 'ok', 'message' => 'Proveedor Registrado']);
                }
            }           

        }

    }


    public function eliminarProveedor(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idprov = $this->request->getVar('id');

            $eliminar = FALSE;
            $mensaje = "";

            $tablas = ['guia_salida_detalle','guia_devolucion_detalle'];
            foreach( $tablas as $t ){
                $total = $this->modeloProveedor->verificarProTieneRegEnTablas($idprov,$t)['total'];
                if( $total > 0 ){
                    $mensaje .= "<div class='text-start'>El proveedor tiene $total registros en la tabla '$t'.</div>";
                    $eliminar = TRUE;
                }
            }

            if( $eliminar ){
                return $this->response->setJson(['status' => 'error', 'message' => $mensaje]);
            }

            if( $this->modeloProveedor->eliminarProveedor($idprov) ){
                return $this->response->setJson(['status' => 'ok', 'message' => 'Proveedor Eliminado.']);
            }

        }
    }


}