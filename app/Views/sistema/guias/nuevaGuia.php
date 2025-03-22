<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<?php echo $this->endSection();?>

<?php echo $this->section('contenido');?>
<?php
if( isset($guia_bd) && $guia_bd ){
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

    $pre_piezas_bd = json_decode($guia_bd['pre_piezas'], true);

    $titulo   = "Modificar";
}else{
    $idguia_bd     = "";
    $idtrans_bd    = "";
    $fechatrasl_bd = "";
    $motivo_bd     = "";
    $motivodesc_bd = "";
    $iddepap_bd    = "";
    $idprovp_bd    = "0";
    $iddistp_bd    = "0";
    $iddepall_bd   = "";
    $idprovll_bd   = "0";
    $iddistll_bd   = "0";
    $direcp_bd     = "";
    $direcll_bd    = "";
    $placa_bd      = "";

    $pre_piezas_bd = json_decode($presupuesto['pre_piezas'], true);

    $titulo   = "Generar";
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
                                Fecha Presu: <b><?=date("d/m/Y h:i a", strtotime($presupuesto['pre_fechareg']))?></b>
                            </div>                            
                        </div>

                        <div class="row pt-3">
                            <div class="col-sm-4 mb-3">
                                <label for="dias" class="form-label">Transportista</label>
                                <select class="form-select" name="transportista" id="transportista">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach( $transportitas as $trans ){
                                        $idtrans = $trans['idtransportista'];
                                        $trans_nombres = $trans['tra_nombres']." ".$trans['tra_apellidos'];
                                        $trans_dni = $trans['tra_dni'];

                                        $select_trans = $idtrans == $idtrans_bd ? 'selected' : '';

                                        echo "<option value=$idtrans $select_trans>$trans_dni - $trans_nombres</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <label for="fechatrasl" class="form-label">Fecha Traslado</label>
                                <input type="date" class="form-control" name="fechatrasl" id="fechatrasl" value="<?=$fechatrasl_bd?>">
                            </div>
                            <div class="col-sm-2 mb-3">
                                <label for="motivo" class="form-label">Motivo Traslado</label>
                                <select class="form-select" name="motivo" id="motivo">
                                    <option value="">Seleccione</option>
                                    <option value="v" <?=$motivo_bd == 'v' ? 'selected' : ''?> >Venta</option>
                                    <option value="i" <?=$motivo_bd == 'i' ? 'selected' : ''?> >Importación</option>
                                    <option value="e" <?=$motivo_bd == 'e' ? 'selected' : ''?> >Exportación</option>
                                    <option value="o" <?=$motivo_bd == 'o' ? 'selected' : ''?> >Otros</option>
                                </select>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <label for="desc_trasl" class="form-label">Descripción Traslado</label>
                                <input type="text" class="form-control" name="desc_trasl" id="desc_trasl" disabled maxlength="100" value="<?=$motivodesc_bd?>">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="departamentop" class="form-label">Departamento (partida)</label>
                                <select class="form-select" name="departamentop" id="departamentop">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach( $departamentos as $depa ){
                                        $iddepa = $depa['iddepa'];
                                        $dpto = $depa['departamentos'];

                                        $select_depa = $iddepa == $iddepap_bd ? 'selected' : '';

                                        echo "<option value=$iddepa $select_depa>$dpto</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="provinciap" class="form-label">Provincia (partida)</label>
                                <select class="form-select" name="provinciap" id="provinciap">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="distritop" class="form-label">Distrito (partida)</label>
                                <select class="form-select" name="distritop" id="distritop">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="direccionp" class="form-label">Dirección (partida)</label>
                                <input type="text" class="form-control" name="direccionp" id="direccionp" maxlength="100" value="<?=$direcp_bd?>">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="departamentoll" class="form-label">Departamento (llegada)</label>
                                <select class="form-select" name="departamentoll" id="departamentoll">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach( $departamentos as $depa ){
                                        $iddepa = $depa['iddepa'];
                                        $dpto = $depa['departamentos'];

                                        $select_depa = $iddepa == $iddepall_bd ? 'selected' : '';

                                        echo "<option value=$iddepa $select_depa>$dpto</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="provinciall" class="form-label">Provincia (llegada)</label>
                                <select class="form-select" name="provinciall" id="provinciall">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="distritoll" class="form-label">Distrito (llegada)</label>
                                <select class="form-select" name="distritoll" id="distritoll">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="direccionll" class="form-label">Dirección (llegada)</label>
                                <input type="text" class="form-control" name="direccionll" id="direccionll" maxlength="100" value="<?=$direcll_bd?>">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="placa" class="form-label">Placa del vehículo</label>
                                <input type="text" class="form-control" name="placa" id="placa" maxlength="20" value="<?=$placa_bd?>">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="nroguia" class="form-label">Nro de Guía</label>
                                <input type="text" class="form-control" name="nroguia" id="nroguia" maxlength="20" value="<?=$nroGuia?>">
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
                                $torreModel = model('TorreModel');
                                $piezaModel = model('PiezaModel');
                                
                                /* $arr_tor = [];//para separar las torres, sin que se repitan
                                foreach( $pre_piezas_bd as $pie_bd ){
                                    $idtor = $pie_bd['idtor'];
                                    $torre_bd = $torreModel->getTorre($idtor);
                                    array_push($arr_tor, array($torre_bd['idtorre'],$torre_bd['tor_desc']));
                                }
                                $arr_tor = array_unique($arr_tor, SORT_REGULAR); */
                                echo "<pre>";
                                //print_r($presupuesto);
                                //print_r($detalle_guia);
                                //print_r($transportitas);
                                //print_r($departamentos);
                                //print_r($pre_piezas_bd);
                                
                                $arr_aux = array_map(function($v){
                                    if( array_key_exists('falt', $v) && array_key_exists('st_sale', $v) ){//editar
                                        return ['idpie' => $v['idpie'], 'req' => $v['dtcan'] * $v['dpcant'],'falt' => $v['falt'], 'st_sale' => $v['st_sale']];
                                    }else{
                                        return ['idpie' => $v['idpie'], 'req' => $v['dtcan'] * $v['dpcant']];
                                    }
                                    
                                }, $pre_piezas_bd);

                                $newarr = [];
                                foreach( $arr_aux as $ax ){
                                    if( in_array($ax['idpie'], array_column($newarr, 'idpie')) ){
                                        $aa = array_filter($newarr, fn($v) => $v['idpie'] == $ax['idpie']);
                                        $aa = array_keys($aa)[0];
                                        $newarr[$aa]['req'] = $newarr[$aa]['req'] + $ax['req'];

                                        if( array_key_exists('falt', $ax) && array_key_exists('st_sale', $ax) ){//editar
                                            $e_falt = $newarr[$aa]['falt'] == '' ? 0 : $newarr[$aa]['falt'];
                                            $e_stsale = $newarr[$aa]['st_sale'] == '' ? 0 : $newarr[$aa]['st_sale'];
                                            $newarr[$aa]['falt'] = $e_falt + $ax['falt'];
                                            $newarr[$aa]['st_sale'] = $e_stsale + $ax['st_sale'];
                                            //echo $aa;
                                            //print_r($ax);
                                        }

                                        continue;
                                    }
                                    $newarr[] = $ax;
                                }
                                //print_r($newarr);
                                echo "</pre>";

                                //$arr_existentes = [];//cuando hay mas de una pieza que se repite, y asi poder ir restando su stock para el sgte
                                $guiacompleta = TRUE;

                                echo "<tr>";
                                echo "<th>N°</th>";
                                echo "<th>Piezas</th>";                                    
                                echo "<td class='text-center'>Requerido</td>";
                                if( $idguia_bd == '' )
                                    echo "<td class='text-center'>Stock Act</td>";                                    
                                echo "<td class='text-center'>Stock sale</td>";
                                echo "<td class='text-center'>Faltantes</td>";
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
                                    
                                    /* $arr_e = array_filter($arr_existentes, fn($pie) => $pie[0] == $idpieza);
                                    $arr_e = array_values($arr_e);
                                    if( count($arr_e) > 0 ){
                                        $stockAct = ($stockAct - $arr_e[0][1]) <= 0 ? 0 : ($stockAct - $arr_e[0][1]);
                                        $faltantes     = $cantReq > $stockAct ? abs($stockAct - $cantReq)  : "";
                                    } */

                                    $resaltar = "";
                                    if( $faltantes != "" ){
                                        $resaltar = "bg-danger-subtle";
                                        $guiacompleta = FALSE;
                                    }                                           

                                    //array_push($arr_existentes, [$idpieza, $cantReq]);
                                    
                                    //editar, de frfente las cantidades
                                    if( array_key_exists('req', $pi) && array_key_exists('falt', $pi) ){
                                        $cantReq = $pi['req'];
                                        $faltantes = $pi['falt'];

                                        $resaltar = $faltantes != '' ? 'bg-danger-subtle' : '';
                                    }

                                    $stock_que_sale = $cantReq <= $stockAct ? $cantReq : $stockAct;

                                    $stock_que_sale = array_key_exists('st_sale', $pi) ? $pi['st_sale'] : $stock_que_sale;

                                    echo "<tr class='$resaltar'>";
                                    echo "<td>$cont</td>";
                                    echo "<td>$piedesc</td>";                                            
                                    echo "<td class='text-center'>$cantReq</td>";
                                    if( $idguia_bd == '' )
                                        echo "<td class='text-center'>$stockAct</td>";                                           
                                    echo "<td class='text-center'>$stock_que_sale</td>";
                                    echo "<td class='text-center'>$faltantes</td>";
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
                                <input type="hidden" id="idpre" value="<?=$presupuesto['idpresupuesto']?>">

                                <input type="hidden" id="idguia_bd" value="<?=$idguia_bd?>">
                                <button class="btn btn-warning btnGuia" data-opt="<?=$guiacompleta ? 1 : 0?>"><?=strtoupper($titulo)?> GUIA</button>
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
    $("#motivo").on('change', function(e){
        if( $(this).val() == 'o' ){
            $("#desc_trasl").removeAttr('disabled');
        }else{
            $("#desc_trasl").val('');
            $("#desc_trasl").attr('disabled', true);
        }
    });

    $("#departamentop").on('change', function(e){
        $.post('listar-provincias', {
            iddepa: $(this).val(),
            idprov_bd: <?=$idprovp_bd?>
        }, function(data){
            $("#provinciap").html(data);
            $("#provinciap").trigger('change');
        });
    });

    $("#provinciap").on('change', function(e){
        $.post('listar-distritos', {
            iddepa: $("#departamentop").val(),
            idprov: $(this).val(),
            iddist_bd: <?=$iddistp_bd?>
        }, function(data){
            $("#distritop").html(data);
        });
    });

    $("#departamentoll").on('change', function(e){
        $.post('listar-provincias', {
            iddepa: $(this).val(),
            idprov_bd: <?=$idprovll_bd?>
        }, function(data){
            $("#provinciall").html(data);
            $("#provinciall").trigger('change');
        });
    });

    $("#provinciall").on('change', function(e){
        $.post('listar-distritos', {
            iddepa: $("#departamentoll").val(),
            idprov: $(this).val(),
            iddist_bd: <?=$iddistll_bd?>
        }, function(data){
            $("#distritoll").html(data);
        });
    });

    $(".btnGuia").on('click', function(e){
        e.preventDefault();
        let _this = $(this);
        let textBtn = _this.text();
        $(".btnGuia").attr('disabled', 'disabled');
        _this.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> PROCESANDO...`);

        let transportista = $("#transportista").val(),
        fechatrasl     = $("#fechatrasl").val(),
        motivo         = $("#motivo").val(),
        desc_trasl     = $("#desc_trasl").val().trim(),
        departamentop  = $("#departamentop").val(),
        provinciap     = $("#provinciap").val(),
        distritop      = $("#distritop").val(),
        direccionp     = $("#direccionp").val().trim(),
        departamentoll = $("#departamentoll").val(),
        provinciall    = $("#provinciall").val(),
        distritoll     = $("#distritoll").val(),
        direccionll    = $("#direccionll").val().trim(),
        placa = $("#placa").val().trim(),
        idpre = $("#idpre").val(),
        idguia = $("#idguia_bd").val(),
        opt = $(this).data('opt'),
        nroguia = $("#nroguia").val().trim();

        let men = '';
        if( transportista == '' ) men = 'Seleccione un transportista';
        else if( fechatrasl == '' ) men = 'Seleccione la fecha de traslado';
        else if( motivo == '' ) men = 'Seleccione el motivo de traslado';
        else if( motivo == 'o' && desc_trasl == '' ) men = 'Ingrese una descripcion de traslado';
        else if( departamentop == '' ) men = 'Seleccione un departamento de partida';
        else if( provinciap == '' ) men = 'Seleccione una provincia de partida';
        else if( distritop == '' ) men = 'Seleccione un distrito de partida';
        else if( direccionp == '' ) men = 'Seleccione una dirección de partida';
        else if( departamentoll == '' ) men = 'Seleccione un departamento de llegada';
        else if( provinciall == '' ) men = 'Seleccione una provincia de llegada';
        else if( distritoll == '' ) men = 'Seleccione un distrito de llegada';
        else if( direccionll == '' ) men = 'Seleccione una dirección de llegada';
        else if( placa == '' ) men = 'Ingrese la placa del vehículo';
        else if( nroguia == '' ) men = 'Ingrese el Nro de Guía';

        if( men != '' ){
            Swal.fire({title: men, icon: "error"});
            $(".btnGuia").removeAttr('disabled');
            _this.text(textBtn);
            return;
        }

        $.post('generar-guia',{
            transportista,fechatrasl,motivo,desc_trasl,departamentop,provinciap,distritop,direccionp,
            departamentoll,provinciall,distritoll,direccionll,placa,opt,idpre,idguia,nroguia
        }, function(data){
            $(".btnGuia").removeAttr('disabled');
            _this.text(textBtn);
            $("#msj").html(data);       
        });

    });

    <?php //cuando es editar
    if( isset($guia_bd) && $guia_bd ){
    ?>
    $("#motivo").trigger('change');

    $("#departamentop").trigger('change');

    $("#departamentoll").trigger('change');
    <?php
    }
    ?>
});
</script>

<?php echo $this->endSection();?>