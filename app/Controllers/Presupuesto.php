<?php

namespace App\Controllers;

use App\Models\ParametrosModel;
use CodeIgniter\Model;
use Fpdf\Fpdf;

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

    public function listarPresupuestos(){
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

            $data['presupuestos']   = $this->modeloPresupuesto->getPresupuestos($desde, $hasta, $cri);
            $data['totalRegistros'] = $this->modeloPresupuesto->getPresupuestosCount($cri)['total'];

            return view('sistema/presupuestos/listar', $data);
        }
    }

    public function modalDetallePresu(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $idpresu = $_POST['id'];
            $presupuesto = $this->modeloPresupuesto->getPresupuesto($idpresu);
            $detalle     = $this->modeloPresupuesto->getDetallePresupuesto($idpresu);

            $data['presupuesto'] = $presupuesto;
            $data['detalle']     = $detalle;

            return view('sistema/presupuestos/modalDetalle', $data);

        }
    }

    public function nuevoPresupuesto($id = ''){
        if( !session('idusuario') ){
            return redirect()->to('/');
        }

        if( $id != '' ){
            if( $presu = $this->modeloPresupuesto->getPresupuesto($id) ){
                
                $data['nroPre']   = $presu['pre_numero'];
                $data['presu_bd'] = $presu;
                $data['deta_bd']  = $this->modeloPresupuesto->getDetallePresupuesto($id);
                $data['title']    = "Editar presupuesto | ".help_nombreWeb();
            }else{
                return redirect()->to('/');
            }
        }else{                       
            $data['nroPre'] = $this->modeloPresupuesto->nroPresupuesto()['nro'];
            $data['title']  = "Nuevo presupuesto | ".help_nombreWeb();  
        } 
        
        $data['presuLinkActive'] = 1;

        $data['clientesCbo'] = $this->modeloCliente->getClientesCbo();//para llebar combobox
        $data['torresCbo']   = $this->modeloTorre->getTorresCbo();//para llebar combobox
        $data['param']       = $this->modeloParametros->getParametros();

        return view('sistema/presupuestos/nuevoPresupuesto', $data);
    }

    /* public function listarClientesAjaxSelect2(){
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
    } */

    /* public function listarTorresAjaxSelect2(){
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
    } */

    public function registrarPresupuesto(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            $items     = json_decode($this->request->getVar('items'), true);
            $count_items = count($items);
            if( $count_items == 0 ){
                echo "ITEMS VACIO";exit();
            }

            $nroPre  = $this->modeloPresupuesto->nroPresupuesto()['nro'];
            $porcsem = $this->modeloParametros->getParametros()['par_porcensem'];

            $porcpre    = $this->request->getVar('porcpre');
            $periodo    = $this->request->getVar('periodo');
            $nroperiodo = $this->request->getVar('nroperiodo');
            $cliente    = $this->request->getVar('cliente');
            $idpre_e    = $this->request->getVar('idpre');

            //PARA GUARDAR LOS ITEMS DE LA TORRE DE ESE MOMENTO DEL PRESUPUESTO, EN CASO CAMBIE DESPUES
            $arrDT = [];
            foreach( $items as $i ){
                $idtorre = $i['id'];
                $cant    = $i['cant'];
                $tmonto  = $i['tmonto'];

                $dtTorre = $this->modeloTorre->getDetalleTorre($idtorre);
                
                foreach( $dtTorre as $dtT ){
                    $a = [
                        'idtor'  => $dtT['idtorre'],
                        'idpie'  => $dtT['idpieza'],
                        'dtcan'  => $dtT['dt_cantidad'],
                        'piepre' => $dtT['pie_precio'],
                    ];
                    array_push($arrDT, $a);
                }            
            }
            $arrDT = json_encode($arrDT);
            //FIN PARA GUARDAR LOS ITEMS DE LA TORRE DE ESE MOMENTO DEL PRESUPUESTO, EN CASO CAMBIE DESPUES

            if( $presu_bd = $this->modeloPresupuesto->getPresupuesto($idpre_e) ){
                //EDITAR
                //exit();
                if( $this->modeloPresupuesto->modificarPresupuesto($cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT,$idpre_e) ){
                    if( $this->modeloPresupuesto->borrarDetallePresupuesto($idpre_e) ){
                        $res = FALSE;
                        foreach( $items as $i ){
                            $idtorre = $i['id'];
                            $cant    = $i['cant'];
                            $tmonto  = $i['tmonto'];
                            
                            if( $this->modeloPresupuesto->insertarDetallePresu($idpre_e,$idtorre,$cant,$tmonto) ){
                                $res = TRUE;
                            }
                        }
                        if( $res ){
                            echo '<script>
                                Swal.fire({
                                    title: "Presupuesto Modificado",
                                    text: "",
                                    icon: "success",
                                    showConfirmButton: true,
                                });
                                setTimeout(function(){location.reload()},1500)
                            </script>';
                        }
                    }
                }
            }else{                

                if( $idpre = $this->modeloPresupuesto->insertarPresupuesto($nroPre,session('idusuario'),$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT) ){
                    $res = FALSE;
                    foreach( $items as $i ){
                        $idtorre = $i['id'];
                        $cant    = $i['cant'];
                        $tmonto  = $i['tmonto'];
                        
                        if( $this->modeloPresupuesto->insertarDetallePresu($idpre,$idtorre,$cant,$tmonto) ){
                            $res = TRUE;
                        }
                    }
                    if( $res ){
                        echo '<script>
                            Swal.fire({
                                title: "Presupuesto Generado",
                                text: "",
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                            setTimeout(function(){location.reload()},1500)
                        </script>';
                    }
                }

            }

            
            
            /* echo "<pre>";
            print_r($_POST);
            print_r($items);
            echo "</pre>"; */

        }
    }


    public function pdfPresu($idpresu){     
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->SetTopMargin(15);
        $pdf->SetLeftMargin(15);
        $pdf->SetRightMargin(10);

        $presu = $this->modeloPresupuesto->getPresupuesto($idpresu);
        $prenum     = $presu['pre_numero'];
        $prefecha   = $presu['pre_fechareg'];
        $periodo    = $presu['pre_periodo'];
        $nperiodo   = $presu['pre_periodonro'];
        $porcprecio = $presu['pre_porcenprecio'];
        $porcsem    = $presu['pre_porcsem'];
        $piezas     = $presu['pre_piezas'];

        $cliente    = $presu['cli_nombrerazon'];
        $dniruc     = $presu['cli_dniruc'];
        $nomcontact = $presu['cli_nombrecontact'];
        $corcontact = $presu['cli_correocontact'];
        $telcontact = $presu['cli_telefcontact'];

        $nomusuario = $presu['usu_nombres']." ".$presu['usu_apellidos'];
        $dniusu     = $presu['usu_dni'];

        $peri = '';
        if( $periodo == 'd' ) $peri = 'Día';
        if( $periodo == 's' ) $peri = 'Semana';
        if( $periodo == 'm' ) $peri = 'Mes';

        $detalle = $this->modeloPresupuesto->getDetallePresupuesto($idpresu);
        //print_r($presu);exit;

        $pdf->setY(40);$pdf->setX(135);
            $pdf->Ln();
        //CABECERAS
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20, 5, utf8_decode('Cliente:'),0,0,'L',0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(80, 5, utf8_decode($cliente),0,0,'L',0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20, 5, utf8_decode('Vendedor:'),0,0,'L',0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0, 5, utf8_decode($nomusuario),0,1,'L',0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20, 5, utf8_decode('Dni/Ruc:'),0,0,'L',0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(80, 5, utf8_decode($dniruc),0,0,'L',0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20, 5, utf8_decode('Dni:'),0,0,'L',0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0, 5, utf8_decode($dniusu),0,1,'L',0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20, 5, utf8_decode('Contacto:'),0,0,'L',0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(80, 5, utf8_decode($nomcontact),0,0,'L',0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20, 5, utf8_decode('Fecha:'),0,0,'L',0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(80, 5, date('d/m/Y',strtotime($prefecha)),0,1,'L',0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20, 5, utf8_decode('Correo:'),0,0,'L',0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0, 5, utf8_decode($corcontact),0,1,'L',0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20, 5, utf8_decode('Teléfono:'),0,0,'L',0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0, 5, utf8_decode($telcontact),0,1,'L',0);

        $pdf->Ln();
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0, 5, utf8_decode('Estimado Cliente, le hacemos llegar el siguiente presupuesto.'),0,1,'L',0);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10, 7, utf8_decode('Item'),1,0,'L',0);
        $pdf->Cell(90, 7, utf8_decode('Descripción'),1,0,'C',0);
        $pdf->Cell(16, 7, utf8_decode($peri),1,0,'C',0);
        $pdf->Cell(12, 7, utf8_decode('Cant.'),1,0,'C',0);
        $pdf->Cell(25, 7, utf8_decode('Precio Unit.'),1,0,'C',0);
        $pdf->Cell(0, 7, utf8_decode('Precio Total'),1,1,'C',0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10, 7, utf8_decode('Item'),1,0,'L',0);
        $pdf->MultiCell(90, 8, utf8_decode('Estimado Cliente, le hacemos llegar el siguiente presupuesto.'),1,'T');
        $pdf->Cell(16, 7, utf8_decode($peri),1,0,'C',0);
        $pdf->Cell(12, 7, utf8_decode('Cant.'),1,0,'C',0);
        $pdf->Cell(25, 7, utf8_decode('Precio Unit.'),1,0,'C',0);
        $pdf->Cell(0, 7, utf8_decode('Precio Total'),1,0,'C',0);
        

        $pdf->Ln();



        //// Apartir de aqui esta la tabla con los subtotales y totales

        $pdf->Ln(10);

                $pdf->setX(95);
                $pdf->Cell(40,6,'Subtotal',1,0);
                $pdf->Cell(60,6,'4000','1',1,'R');
                $pdf->setX(95);
                $pdf->Cell(40,6,'Descuento',1,0);
                $pdf->Cell(60,6,'4000','1',1,'R');
                $pdf->setX(95);
                $pdf->Cell(40,6,'Impuesto',1,0);
                $pdf->Cell(60,6,'4000','1',1,'R');
                $pdf->setX(95);
                $pdf->Cell(40,6,'Total',1,0);
                $pdf->Cell(60,6,'4000','1',1,'R');
        



        $pdf->Output();
        exit();
    }


}


class PDF extends Fpdf{

    function Header()
    {

        $this->setY(18);
        $this->setX(10);
        
        $params = model('ParametrosModel')->getParametros();
        $logo   = $params['par_logo'];
        $direc  = $params['par_direcc'];
        $telef  = $params['par_telef'];
        $correo = $params['par_correo'];

        $this->Image('public/images/logo/'.$logo,10,5,50);
        
        $this->SetFont('Arial', 'B', 16); 
        $this->Text(78, 15, utf8_decode('ANDAMIOS ANDAR'));

        $this->SetFont('Arial', '', 10);
        $this->Text(60, 21, utf8_decode($direc));
        $this->Text(86,26, utf8_decode('Celular: '. $telef));
        $this->Text(72,31, utf8_decode('Correo: '.$correo));   
                
        $this->Ln(20);
    }

    function Footer()
    {
        $this->SetFont('helvetica', 'B', 8);
        $this->SetY(-15);
        $this->Cell(95,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
        /* $this->Cell(95,5,date('d/m/Y | g:i:a') ,00,1,'R');
        $this->Line(10,287,200,287);
        $this->Cell(0,5,utf8_decode("© Todos los derechos reservados."),0,0,"C"); */
            
    }
}