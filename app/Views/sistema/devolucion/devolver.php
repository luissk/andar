<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>
<?php
/* echo "<pre>";
print_r($guia_bd);
echo "</pre>"; */
$idguia_bd     = $guia_bd['idguia'];
$idtrans_bd    = $guia_bd['idtransportista'];
$fechatrasl_bd = $guia_bd['gui_fechatraslado'];
$motivo_bd     = $guia_bd['gui_motivo'];
$motivodesc_bd = $guia_bd['gui_motivodesc'];
$iddepap_bd    = $guia_bd['iddepap'];
$idprovp_bd    = $guia_bd['idprovp'];
$iddistp_bd    = $guia_bd['iddistp'];
$iddepall_bd   = $guia_bd['iddepall'];
$idprovll_bd   = $guia_bd['idprovll'];
$iddistll_bd   = $guia_bd['iddistll'];
$direcp_bd     = $guia_bd['gui_direccionp'];
$direcll_bd    = $guia_bd['gui_direccionll'];
$placa_bd      = $guia_bd['gui_placa'];
$fechadev_bd      = $guia_bd['gui_fechadev'];

$status = $guia_bd['gui_status'];

$pre_piezas_bd = json_decode($guia_bd['pre_piezas'], true);

$fecha_dev = $fechadev_bd == '' ? date('Y-m-d') : $fechadev_bd;
?>

<div class="app-content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Devolución de Piezas</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="cbody">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="">
                                Guía: <b><?=$nroGuia?></b>
                            </div>
                            <div class="">
                                Cliente: <b><?=$presupuesto['cli_nombrerazon']?></b>
                            </div>
                            <div class="">
                                Ruc o Dni: <b><?=$presupuesto['cli_dniruc']?></b>
                            </div>
                            <div class="">
                                Fecha Traslado: <b><?=date("d/m/Y", strtotime($fechatrasl_bd))?></b>
                            </div>                            
                        </div>

                        <div class="row pt-3">
                            <div class="col-sm-3 mb-3">
                                <label for="fechadevo" class="form-label">Fecha Devoclución</label>
                                <input type="date" class="form-control" name="fechadevo" id="fechadevo" value="<?=$fecha_dev?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <p class="mt-4 fw-bolder border-bottom border-black">PIEZAS</p>
                            </div>
                            <div class="col-sm-12 table-responsive">                           
                                <table class="table">
                                <?php
                                $presuModel = model('PresupuestoModel');
                                $torreModel = model('TorreModel');
                                $piezaModel = model('PiezaModel');
                                
                                echo "<pre>";                                
                                $arr_aux = array_map(function($v){
                                    if( array_key_exists('ingresa', $v) ){//editar
                                        return ['idpie' => $v['idpie'], 'req' => $v['dtcan'] * $v['dpcant'],'falt' => $v['falt'], 'st_sale' => $v['st_sale'], 'ingresa' => $v['ingresa']];
                                    }
                                    return ['idpie' => $v['idpie'], 'req' => $v['dtcan'] * $v['dpcant'],'falt' => $v['falt'], 'st_sale' => $v['st_sale']];
                                                                       
                                }, $pre_piezas_bd);

                                $newarr = [];
                                foreach( $arr_aux as $ax ){
                                    if( in_array($ax['idpie'], array_column($newarr, 'idpie')) ){
                                        $aa = array_filter($newarr, fn($v) => $v['idpie'] == $ax['idpie']);
                                        $aa = array_keys($aa)[0];
                                        $newarr[$aa]['req'] = $newarr[$aa]['req'] + $ax['req'];

                                        if( array_key_exists('falt', $ax) && array_key_exists('st_sale', $ax) ){
                                            $e_falt = $newarr[$aa]['falt'] == '' ? 0 : $newarr[$aa]['falt'];
                                            $e_stsale = $newarr[$aa]['st_sale'] == '' ? 0 : $newarr[$aa]['st_sale'];
                                            $newarr[$aa]['falt'] = $e_falt + $ax['falt'];
                                            $newarr[$aa]['st_sale'] = $e_stsale + $ax['st_sale'];
                                            //echo $aa;
                                            //print_r($ax);
                                        }

                                        if( array_key_exists('ingresa', $ax) ){//editar
                                            $newarr[$aa]['ingresa'] = $newarr[$aa]['ingresa'] + $ax['ingresa'];
                                        }

                                        continue;
                                    }
                                    $newarr[] = $ax;
                                }
                                //print_r($newarr);
                                echo "</pre>";

                                echo "<tr>";
                                echo "<th>N°</th>";
                                echo "<th>Piezas</th>";                                    
                                echo "<td class='text-center'>Cant. Salió</td>";
                                echo "<td class='text-center'>Cant. Ingresa</td>";
                                echo "</tr>";
                                $cont = 0;
                                foreach( $newarr as $pi ){                                                        
                                    $cont++;
                                    $idpieza  = $pi['idpie'];
                                    $pieza_bd = $piezaModel->getPieza($idpieza);

                                    $piecodigo     = $pieza_bd['pie_codigo'];
                                    $piedesc       = $pieza_bd['pie_desc'];
                                    $stockIni      = $pieza_bd['pie_cant'];
                                    $cantReq       = $pi['req'];
                
                                    /* $nroEntregados = $presuModel->getStockPieza($idpieza, $estadoPresu = [3], 'e');
                                    $nroSalidas    = $presuModel->getStockPieza($idpieza, $estadoPresu = [2,3], 's');
                                    $stockAct      = ($stockIni + $nroEntregados - $nroSalidas) <= 0 ? 0 : ($stockIni + $nroEntregados - $nroSalidas); */
                                    $stockAct      = $pieza_bd['stockActual'];
                                    $faltantes     = $cantReq > $stockAct ? abs($stockAct - $cantReq)  : "";

                                    $stock_que_sale = $pi['st_sale'];
                                    $stock_input_default = $pi['st_sale'];

                                    if( array_key_exists('ingresa', $pi) ){//editar
                                        $stock_input_default = $pi['ingresa'];
                                    }

                                    echo "<tr>";
                                    echo "<td>$cont</td>";
                                    echo "<td>$piedesc</td>";                                                                                 
                                    echo "<td class='text-center'>$stock_que_sale</td>";
                                    echo "<td class='text-center'><input type='text' size='2' class='numerosindecimal' data-sale=$stock_que_sale id='cant-$idpieza' value=$stock_input_default /></td>";
                                    echo "</tr>";
                                    
                                }
                                //print_r($arr_existentes);                                                                        
                                ?>
                                </table>
                            </div>
                            <div class="col-sm-12 pb-3 fw-bolder">
                            <?php
                            $detalle_presu = $presuModel->getDetallePresupuesto($presupuesto['idpresupuesto']);
                            foreach($detalle_presu as $d){
                                $idtorre  = $d['idtorre'];
                                $dp_cant  = $d['dp_cant'];
                                $tor_desc = $d['tor_desc'];
                                echo "$dp_cant $tor_desc.<br>";
                            }
                            ?>
                            </div>                      
                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <input type="hidden" id="idguia_bd" value="<?=$idguia_bd?>">
                                <button class="btn btn-warning btnGuia" data-opt="">REGISTRAR DEVOLUCION</button>
                            </div>
                            <div id="msj"></div>
                        </div>

                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php echo $this->endSection();?>

<?php echo $this->section('scripts');?>

<script>
$(function(){
    $(".numerosindecimal").on("keypress keyup blur",function (event) {    
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $(".btnGuia").on('click', function(e){
        e.preventDefault();
        let _this = $(this);
        let textBtn = _this.text();
        $(".btnGuia").attr('disabled', 'disabled');
        _this.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> PROCESANDO...`);

        let fechadevo = $("#fechadevo").val(),
        idguia = $("#idguia_bd").val(),
        opt = $(this).data('opt');

        let men = '';
        if( fechadevo == '' ) men = 'Seleccione la fecha de devolución';

        let items = [];
        $('[id^="cant-"').each(function(i, v){
            let idpieza = v.id.split("-")[1],
                cant = Number(v.value),
                salio = Number(v.getAttribute('data-sale'));

            if( cant == '' ){
                men = 'Ingrese las cantidades que ingresan';
                Swal.fire({title: men, icon: "error"});
                return;
            }

            if( cant > salio ){
                men = 'La cantidad que ingresa no puede ser mayor a lo que salió';
                Swal.fire({title: men, icon: "error"});
                return;
            }

            items.push({idpieza,cant,salio});
        });
        

        if( men != '' ){
            Swal.fire({title: men, icon: "error"});
            $(".btnGuia").removeAttr('disabled');
            _this.text(textBtn);
            return;
        }
        
        console.log(items)

        $.post('generar-devolucion',{
            idguia, fechadevo, items
        }, function(data){
            $(".btnGuia").removeAttr('disabled');
            _this.text(textBtn);
            $("#msj").html(data);       
        });

    });

});
</script>

<?php echo $this->endSection();?>