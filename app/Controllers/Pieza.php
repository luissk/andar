<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pieza extends BaseController
{
    protected $modeloUsuario;
    protected $modeloPieza;
    protected $modeloPresupuesto;
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloUsuario     = model('UsuarioModel');
        $this->modeloPieza       = model('PiezaModel');
        $this->modeloPresupuesto = model('PresupuestoModel');
        $this->session;
    }

    public function index()
    {
        if( !session('idusuario') ){
            return redirect()->to('/');
        }
        
        $data['title']           = "Piezas del Sistema | ".help_nombreWeb();
        $data['piezasLinkActive'] = 1;

        return view('sistema/piezas/index', $data);
    }

    public function listarPiezas(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }
            
            $page  = $this->request->getVar('page');
            $cri   = trim($this->request->getVar('cri'));
            $campo = trim($this->request->getVar('campo'));
            $order = trim($this->request->getVar('order'));
            //print_r($_POST);

            $desde        = $page * 50 - 50;
            $hasta        = 50;
            $data['page'] = $page;

            $cri = strlen($cri) > 2 ? $cri : '';
            $data['cri']   = $cri;
            $data['campo'] = $campo;
            $data['order'] = $order;

            $data['piezas']         = $this->modeloPieza->getPiezas($desde, $hasta, $cri, $campo, $order);
            $data['totalRegistros'] = $this->modeloPieza->getPiezasCount($cri)['total'];

            return view('sistema/piezas/listar', $data);
        }
    }

    public function registrarPieza(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')){
                exit();
            }

            //print_r($_POST);exit();
            
            $desc     = trim($this->request->getVar('desc'));
            $codigo   = trim($this->request->getVar('codigo'));
            $peso     = $this->request->getVar('peso');
            $precio   = $this->request->getVar('precio');
            $cantidad = $this->request->getVar('cantidad');
            $idpieza  = $this->request->getVar('id_piezae');//para editar

            $validation = \Config\Services::validation();

            $data = [
                'desc'     => $desc,
                'codigo'   => $codigo,
                'peso'     => $peso,
                'precio'   => $precio,
                'cantidad' => $cantidad,
            ];

            $rules = [
                'desc' => [
                    'label' => 'Descripción', 
                    'rules' => 'required|regex_match[/^[a-zA-ZñÑáéíóúÁÉÍÓÚ.\-\",\/ 0-9]+$/]|max_length[200]',
                    'errors' => [
                        'required'    => '* La {field} es requerida.',
                        'regex_match' => '* La {field} no es válida.',
                        'max_length'  => '* La {field} debe contener máximo 200 caracteres.'
                    ]
                ],
                'codigo' => [
                    'label' => 'Código', 
                    'rules' => 'required|regex_match[/^[0-9]+$/]|max_length[12]',
                    'errors' => [
                        'required'    => '* El {field} es requerido.',
                        'max_length'  => '* El {field} 12 número máximo.',
                        'regex_match' => '* El {field} sólo contiene números.'
                    ]
                ],
                'peso' => [
                    'label' => 'Nombre', 
                    'rules' => 'required|decimal|max_length[8]',
                    'errors' => [
                        'required'   => '* El {field} es requerido.',
                        'decimal'    => '* El {field} sólo contiene números y punto decimal',
                        'max_length' => '* El {field} debe contener máximo 8 caracteres.'
                    ]
                ],
                'precio' => [
                    'label' => 'Precio', 
                    'rules' => 'required[nomostrar]|decimal|max_length[10]',
                    'errors' => [
                        'required'   => '* El {field} es requerido.',
                        'decimal'          => '* El {field} sólo contiene números y punto decimal',
                        'max_length'       => '* El {field} debe contener máximo 10 caracteres.'
                    ]
                ],
                'cantidad' => [
                    'label' => 'Cantidad', 
                    'rules' => 'required|regex_match[/^[0-9]+$/]|max_length[8]',
                    'errors' => [
                        'required'    => '* La {field} es requerido.',
                        'regex_match' => '* La {field} sólo contiene números.',
                        'max_length'  => '* La {field} debe contener máximo 8 caracteres.'
                    ]
                ],
            ];


            $pieza_bd = $this->modeloPieza->getPieza($idpieza);

            $validation->setRules($rules);

            if (!$validation->run($data)) {
                return $this->response->setJson(['errors' => $validation->getErrors()]);
            }

            if( $pieza_bd ){
                //MODIFICAR
                //print_r($_POST);
                $cod_bd = $pieza_bd['pie_codigo'];
                if( $codigo != $cod_bd ){
                    if( $this->modeloPieza->getPiezaPorCodigo($codigo) ){
                        echo '<script>
                            Swal.fire({
                                title: "Ya existe el código de pieza",
                                icon: "error"
                            });
                        </script>';
                        exit();
                    }
                }
                if( $this->modeloPieza->modificarPieza($codigo,$desc,$peso,$precio,$cantidad,$idpieza) ){
                    echo '<script>
                        Swal.fire({
                            title: "Pieza Modificada",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarPiezas(1);
                        limpiarCampos();
                        $("#modalPieza").modal("hide");
                    </script>';
                }

            }else{
                //INSERTAR
                if( $this->modeloPieza->getPiezaPorCodigo($codigo) ){
                    echo '<script>
                        Swal.fire({
                            title: "Ya existe el código de pieza",
                            icon: "error"
                        });
                    </script>';
                    exit();
                }

                if( $this->modeloPieza->insertarPieza($codigo,$desc,$peso,$precio,$cantidad,session('idusuario')) ){
                    echo '<script>
                        Swal.fire({
                            title: "Pieza Registrada",
                            text: "",
                            icon: "success",
                            showConfirmButton: true,
                        });
                        listarPiezas(1);
                        limpiarCampos();
                        $("#modalPieza").modal("hide");
                    </script>';
                }
            }

        }
    }

    public function eliminarPieza(){
        if( $this->request->isAJAX() ){
            if(!session('idusuario')) exit();

            $idpieza = $this->request->getVar('id');

            $eliminar = FALSE;
            $mensaje = "";

            $tablas = ['detalle_torre'];
            foreach( $tablas as $t ){
                $total = $this->modeloPieza->verificarPieTieneRegEnTablas($idpieza,$t)['total'];
                if( $total > 0 ){
                    $mensaje .= "<div class='text-start'>La pieza tiene $total registros en la tabla '$t'.</div>";
                    $eliminar = TRUE;
                }
            }

            if( $eliminar ){
                echo '<script>
                    Swal.fire({
                        title: "La pieza no puede ser eliminada",
                        html: "'.$mensaje.'",
                        icon: "warning",
                    });
                </script>';
                exit();
            }

            if( $this->modeloPieza->eliminarPieza($idpieza) ){
                echo '<script>
                    Swal.fire({
                        title: "Pieza Eliminada",
                        text: "",
                        icon: "success",
                        showConfirmButton: true,
                    });
                    listarPiezas(1);
                </script>';
            }
        }
    }


    public function reporteExcel(){
        if(!session('idusuario')){
            return redirect()->to('/');
        }
        $piezas = $this->modeloPieza->getPiezas(0, 200);        

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NRO');
        $sheet->setCellValue('B1', 'CODIGO');
        $sheet->setCellValue('C1', 'PIEZA');
        $sheet->setCellValue('D1', 'PESO');
        $sheet->setCellValue('E1', 'PRECIO');
        $sheet->setCellValue('F1', 'STOCK INI');
        $sheet->setCellValue('G1', 'STOCK ACT');
        
        $cont = 0;
        $rows = 2;
        foreach($piezas as $p){
            $cont++;
            $idpieza  = $p['idpieza'];
            $codigo   = $p['pie_codigo'];
            $desc     = $p['pie_desc'];
            $fecha    = $p['pie_fechareg'];
            $peso     = $p['pie_peso'];
            $precio   = $p['pie_precio'];
            $stockIni = $p['pie_cant'];
            $stockAct = $p['stockActual'];

            $sheet->setCellValue('A'.$rows, $cont);
            $sheet->setCellValue('B'.$rows, $codigo);
            //$sheet->getCell('B'.$rows, $codigo)->getStyle()->getNumberFormat()->setFormatCode('#');
            $sheet->setCellValue('C'.$rows, $desc);
            $sheet->setCellValue('D'.$rows, $peso);
            $sheet->setCellValue('E'.$rows, $precio);
            $sheet->setCellValue('F'.$rows, $stockIni);
            $sheet->setCellValue('G'.$rows, $stockAct);
            $rows++;
        }
        foreach (range('A','G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        /* 
        $file_name = 'public/reporte-piezas.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($file_name);
        $ruta = base_url().'/'.$file_name;                
        echo "<script>window.open('".$ruta."','_blank' )</script>"; */

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="piezas.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        
    }



}