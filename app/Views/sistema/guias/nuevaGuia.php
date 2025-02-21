<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<?php echo $this->endSection();?>

<?php echo $this->section('contenido');?>
<?php
if( isset($presu_bd) && $presu_bd ){
    $titulo   = "Modificar";
    $btnTexto = "MODIFICAR GUIA";
}else{
    $titulo   = "Realizar";
    $btnTexto = "MODIFICAR GUIA";
}
?>

<div class="app-content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><?=$titulo?> Guía</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="cbody">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="">
                                Presupuesto: <b><?=$presupuesto['pre_numero']?></b>
                            </div>
                            <div class="">
                                Cliente: <b><?=$presupuesto['cli_nombrerazon']?></b>
                            </div>
                            <div class="">
                                Ruc o Dni: <b><?=$presupuesto['cli_dniruc']?></b>
                            </div>
                            <div class="">
                                Fecha: <b><?=date("d/m/Y h:i a", strtotime($presupuesto['pre_fechareg']))?></b>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <p class="mt-4 fw-bolder border-bottom border-black">PIEZAS REQUERIDAS</p>
                            </div>
                            <div class="col-sm-12 table-responsive">                           
                                <table class="table">
                                <?php
                                $presuModel = model('PresupuestoModel');

                                $nomTorres = [];
                                foreach( $detalle_guia as $dg ){
                                    $nomTorres[] = [$dg['idtorre'],$dg['tor_desc']];
                                }
                                //echo "<pre>";
                                //print_r(array_unique($nomTorres, SORT_REGULAR));
                                //print_r($presupuesto);
                                //print_r($detalle_guia);
                                //print_r($transportitas);
                                //echo "</pre>";
                                $arr_existentes = [];//cuando hay mas de una pieza que se repite, y asi poder ir restando su stock para el sgte
                                foreach( array_unique($nomTorres, SORT_REGULAR) as $nT){
                                    $idtorre  = $nT[0];
                                    $nomtorre = $nT[1];
                                    echo "<tr>";
                                    echo "<th colspan='2'>$nomtorre</th>";
                                    echo "<td class='text-center'>Requerido</td>";
                                    echo "<td class='text-center'>Faltantes</td>";
                                    echo "</tr>";
                                    $cont = 0;
                                    foreach( $detalle_guia as $dg ){
                                        $cont++;
                                        $idpieza       = $dg['idpieza'];
                                        $piecodigo     = $dg['pie_codigo'];
                                        $piedesc       = $dg['pie_desc'];
                                        $stockIni      = $dg['stock_ini'];
                                        $cantReq       = $dg['cant_req'];                                        
                                        $nroEntregados = $presuModel->getStockPieza($idpieza, $estadoPresu = [4]);
                                        $nroSalidas    = $presuModel->getStockPieza($idpieza, $estadoPresu = [2,3]);
                                        $stockAct      = $stockIni + $nroEntregados - $nroSalidas;
                                        $faltantes     = $cantReq > $stockAct ? abs($stockAct - $cantReq)  : "";
                                        
                                        

                                        if( $idtorre == $dg['idtorre'] ){
                                            $resaltar = "";
                                            if( $faltantes != "" ){
                                                $resaltar = "bg-danger-subtle";
                                            }

                                            $arr_e = array_filter($arr_existentes, fn($pie) => $pie[0] == $idpieza);
                                            $arr_e = array_values($arr_e);
                                            if( count($arr_e) > 0 ){
                                                $stockAct = $stockAct - $arr_e[0][1];
                                                //print_r($arr_e);
                                            }

                                            array_push($arr_existentes, [$idpieza, $cantReq]);
                                            

                                            echo "<tr class='$resaltar'>";
                                            echo "<td>$idpieza - $cont</td>";
                                            echo "<td>$piedesc</td>";
                                            echo "<td class='text-center'>$cantReq - $stockAct</td>";
                                            echo "<td class='text-center'>$faltantes</td>";
                                            echo "</tr>";
                                        }
                                    }
                                }
                                //print_r($arr_existentes);                                                                        
                                ?>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php echo $this->endSection();?>