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
$fechadev_bd   = $guia_bd['gui_fechadev'];
$track_bd      = $guia_bd['guia_track'];

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
                                <label for="fechadevo" class="form-label">Fecha Devolución</label>
                                <input type="date" class="form-control" name="fechadevo" id="fechadevo" value="<?=$fecha_dev?>">
                            </div>

                            <?php
                            if( $track_bd != '' ){
                                $ingresos = json_decode($track_bd, true);
                            ?>
                            <div class="col-sm-3 mb-3">
                                <span>Ya ingresados</span>
                                <ul class="list-group">
                                    <?php
                                    foreach( $ingresos as $in ){
                                        $fecha = $in['fecha'];
                                        $fecha_url = date('d-m-Y h:i:s a', strtotime(str_replace("/","-",$fecha)));
                                        echo "<li class='list-group-item'><a href='javascript:void(0);' class='btn-link' onclick='verEnPdf($idguia_bd,\"$fecha_url\")'>$fecha_url</a></li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <p class="mt-4 fw-bolder border-bottom border-black">PIEZAS</p>
                            </div>
                            <div class="col-sm-12 table-responsive">                           
                                <table class="table" id="tbl_piezas">
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
                                $ya_ingreso = FALSE;//para saber si el array tiene ingresos. Sirve para editar
                                foreach( $arr_aux as $ax ){
                                    if( in_array($ax['idpie'], array_column($newarr, 'idpie')) ){
                                        $aa = array_filter($newarr, fn($v) => $v['idpie'] == $ax['idpie']);
                                        $aa = array_keys($aa)[0];
                                        $newarr[$aa]['req'] = $newarr[$aa]['req'] + $ax['req'];

                                        if( array_key_exists('falt', $ax) && array_key_exists('st_sale', $ax) ){
                                            $e_falt = $newarr[$aa]['falt'] == '' ? 0 : $newarr[$aa]['falt'];
                                            $e_stsale = $newarr[$aa]['st_sale'] == '' ? 0 : $newarr[$aa]['st_sale'];
                                            $newarr[$aa]['falt'] = $e_falt + ($ax['falt'] == '' ? 0 : $ax['falt']);
                                            $newarr[$aa]['st_sale'] = $e_stsale + $ax['st_sale'];
                                            //echo $aa;
                                            //print_r($ax);
                                        }

                                        if( array_key_exists('ingresa', $ax) ){//editar
                                            $newarr[$aa]['ingresa'] = $newarr[$aa]['ingresa'] + $ax['ingresa'];
                                            $ya_ingreso = TRUE;
                                        }

                                        continue;
                                    }
                                    $newarr[] = $ax;
                                }
                                //print_r($newarr);
                                echo "</pre>";

                                echo "<tr>";
                                echo "<th>N°</th>";
                                echo "<th>Código</th>";
                                echo "<th>Piezas</th>";                                    
                                echo "<td class='text-center'>Cant. Salió</td>";
                                if( $ya_ingreso )
                                    echo "<td class='text-center'>Cant. Pend.</td>";
                                else
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
                
                                    $stockAct      = $pieza_bd['stockActual'];
                                    $faltantes     = $cantReq > $stockAct ? abs($stockAct - $cantReq)  : "";

                                    $stock_que_sale = $pi['st_sale'];
                                    $stock_input_default = $pi['st_sale'];

                                    $readonly = $stock_que_sale == 0 ? 'readonly' : '';//cuando lo que salio es cero, el input debe estar READONLY
                                    $checkbox = FALSE;//para visualizar el checkbox
                                    $pintar_fila = '';//para resaltar la fila que aun tiene pendiente de stock
                                    $stock_ya_ingresado = 0; //para saber cuanto ya ingresó anteriormente

                                    if( array_key_exists('ingresa', $pi) ){//editar
                                        $stock_ya_ingresado = $pi['ingresa'];                                        

                                        if( $stock_que_sale > 0 && $stock_ya_ingresado < $stock_que_sale ){//pinta lo que falta
                                            $pintar_fila = 'class="bg-danger-subtle"';
                                        }

                                        if( $stock_que_sale > 0 && $stock_ya_ingresado == $stock_que_sale ){//si lo que salio y lo que ingresa es igual, readonly a la caja y un checkbox
                                            $readonly = 'readonly';
                                            $checkbox = TRUE;                                            
                                        }

                                        $stock_input_default = $stock_que_sale - $stock_ya_ingresado;
                                    }                                    

                                    echo "<tr $pintar_fila>";
                                    echo "<td>$cont</td>";
                                    echo "<td>$piecodigo</td>";
                                    echo "<td>$piedesc</td>";                                                                                 
                                    echo "<td class='text-center'>$stock_que_sale</td>";
                                    echo "<td class='text-center'>";
                                    echo "<input type='text' size='2' class='numerosindecimal' data-sale=$stock_que_sale data-yaingresado=$stock_ya_ingresado id='cant-$idpieza' value=$stock_input_default $readonly />";
                                    if( $checkbox )
                                        echo "&nbsp;<input type='checkbox' id='chk-$idpieza' onclick='habilitarCaja($idpieza, this)' />";
                                    echo "</td>";
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


<div class="modal fade" id="modalPdfGuia" tabindex="-1" aria-labelledby="modalPdfGuiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Ingreso PDF</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="pdf_div">

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
                salio = Number(v.getAttribute('data-sale'))
                yaingresado = Number(v.getAttribute('data-yaingresado'));

            let resetear = 0;
            if ( $("#chk-"+idpieza).length && $("#chk-"+idpieza).is(":checked") ) {//si existe el checkobox y esta checkeado
                resetear    = 1;
                yaingresado = 0;
            }

            if( (cant + yaingresado) == '' && salio > 0 ){
                men = 'Coloque las cantidades que ingresan';
                Swal.fire({title: men, icon: "error"});
                return;
            }

            if( (cant + yaingresado) > salio ){
                men = 'La cantidad que ingresa no puede ser mayor a lo que salió';
                Swal.fire({title: men, icon: "error"});
                return;
            }

            let nuevoingreso = cant;

            items.push({idpieza,cant:(cant + yaingresado),salio,nuevoingreso,yaingresado,resetear});
        });//HACER LOGICA CHECK BOX RESETEARRR
        

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

function habilitarCaja(idpieza, event){
    //console.dir(event.checked)
    //console.log(idpieza);
    if( event.checked ){
        $("#cant-" + idpieza).removeAttr('readonly');
    }else{
        $("#cant-" + idpieza).attr('readonly', true);
        $("#cant-" + idpieza).val(0);
    }
}

function verEnPdf(id, fecha){
    $("#modalPdfGuia").modal('show');
    
    var iframe = $('<iframe width="100%" height="100%">');
    iframe.attr('src','pdf-guia-ingreso/'+id+'/'+fecha);
    $('#pdf_div').html(iframe);
}
</script>

<?php echo $this->endSection();?>