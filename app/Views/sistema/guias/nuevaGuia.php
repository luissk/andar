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

                                        echo "<option value=$idtrans>$trans_dni - $trans_nombres</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <label for="fechatrasl" class="form-label">Fecha Traslado</label>
                                <input type="date" class="form-control" name="fechatrasl" id="fechatrasl">
                            </div>
                            <div class="col-sm-2 mb-3">
                                <label for="motivo" class="form-label">Motivo Traslado</label>
                                <select class="form-select" name="motivo" id="motivo">
                                    <option value="">Seleccione</option>
                                    <option value="v">Venta</option>
                                    <option value="i">Importación</option>
                                    <option value="e">Exportación</option>
                                    <option value="o">Otros</option>
                                </select>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <label for="desc_trasl" class="form-label">Descripción Traslado</label>
                                <input type="text" class="form-control" name="desc_trasl" id="desc_trasl" disabled maxlength="200">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="departamentop" class="form-label">Departamento (partida)</label>
                                <select class="form-select" name="departamentop" id="departamentop">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach( $departamentos as $depa ){
                                        $iddepa = $depa['iddepa'];
                                        $dpto = $depa['departamentos'];
                                        echo "<option value=$iddepa>$dpto</option>";
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
                                <input type="text" class="form-control" name="direccionp" id="direccionp">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="departamentoll" class="form-label">Departamento (llegada)</label>
                                <select class="form-select" name="departamentoll" id="departamentoll">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach( $departamentos as $depa ){
                                        $iddepa = $depa['iddepa'];
                                        $dpto = $depa['departamentos'];
                                        echo "<option value=$iddepa>$dpto</option>";
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
                                <input type="text" class="form-control" name="direccionll" id="direccionll">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="placa" class="form-label">Placa del vehículo</label>
                                <input type="text" class="form-control" name="placa" id="placa">
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
                                echo "<pre>";
                                //print_r(array_unique($nomTorres, SORT_REGULAR));
                                //print_r($presupuesto);
                                //print_r($detalle_guia);
                                //print_r($transportitas);
                                //print_r($departamentos);
                                echo "</pre>";
                                $arr_existentes = [];//cuando hay mas de una pieza que se repite, y asi poder ir restando su stock para el sgte
                                $guiacompleta = TRUE;
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
                                            $arr_e = array_filter($arr_existentes, fn($pie) => $pie[0] == $idpieza);
                                            $arr_e = array_values($arr_e);
                                            if( count($arr_e) > 0 ){
                                                $stockAct = $stockAct - $arr_e[0][1];
                                                $faltantes     = $cantReq > $stockAct ? abs($stockAct - $cantReq)  : "";
                                            }

                                            $resaltar = "";
                                            if( $faltantes != "" ){
                                                $resaltar = "bg-danger-subtle";
                                                $guiacompleta = FALSE;
                                            }                                           

                                            array_push($arr_existentes, [$idpieza, $cantReq]);
                                            

                                            echo "<tr class='$resaltar'>";
                                            echo "<td>$cont</td>";
                                            echo "<td>$piedesc</td>";
                                            echo "<td class='text-center'>$cantReq</td>";
                                            echo "<td class='text-center'>$faltantes</td>";
                                            echo "</tr>";
                                        }
                                    }
                                }
                                //print_r($arr_existentes);                                                                        
                                ?>
                                </table>
                            </div>                           
                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <input type="hidden" id="idpre" value="<?=$presupuesto['idpresupuesto']?>">
                                <button class="btn btn-warning btnGuia" data-opt="1">GENERAR GUIA COMPLETA</button>
                                <?php 
                                if( $guiacompleta === FALSE ){
                                    echo '&nbsp;&nbsp;<button class="btn btn-danger btnGuia" data-opt="0">GENERAR GUIA INCOMPLETA</button>';
                                }
                                ?>
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
            iddepa: $(this).val()
        }, function(data){
            $("#provinciap").html(data);
            $("#provinciap").trigger('change');
        });
    });

    $("#provinciap").on('change', function(e){
        $.post('listar-distritos', {
            iddepa: $("#departamentop").val(),
            idprov: $(this).val()
        }, function(data){
            $("#distritop").html(data);
        });
    });

    $("#departamentoll").on('change', function(e){
        $.post('listar-provincias', {
            iddepa: $(this).val()
        }, function(data){
            $("#provinciall").html(data);
            $("#provinciall").trigger('change');
        });
    });

    $("#provinciall").on('change', function(e){
        $.post('listar-distritos', {
            iddepa: $("#departamentoll").val(),
            idprov: $(this).val()
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
        desc_trasl     = $("#desc_trasl").val(),
        departamentop  = $("#departamentop").val(),
        provinciap     = $("#provinciap").val(),
        distritop      = $("#distritop").val(),
        direccionp     = $("#direccionp").val(),
        departamentoll = $("#departamentoll").val(),
        provinciall    = $("#provinciall").val(),
        distritoll     = $("#distritoll").val(),
        direccionll    = $("#direccionll").val(),
        placa = $("#placa").val(),
        idpre = $("#idpre").val(),
        opt = $(this).data('opt');

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

        if( men != '' ){
            Swal.fire({title: men, icon: "error"});
            return;
        }

        $.post('generar-guia',{
            transportista,fechatrasl,motivo,desc_trasl,departamentop,provinciap,distritop,direccionp,
            departamentoll,provinciall,distritoll,direccionll,placa,opt,idpre
        }, function(data){
            $(".btnGuia").removeAttr('disabled');
            _this.text(textBtn);
            $("#msj").html(data);            
        });

    });
});
</script>

<?php echo $this->endSection();?>