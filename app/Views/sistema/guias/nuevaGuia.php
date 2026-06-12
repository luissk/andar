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

    $clienterecoge = $guia_bd['gui_clienterecoge'] == 1 ? 'checked' : '';

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

    $clienterecoge = '';

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
                            <div class="col-sm-4 d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="clienterecoge" name="clienterecoge" <?=$clienterecoge?> >
                                    <label class="form-check-label" for="clienterecoge">
                                        Cliente recoge
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <p class="mt-4 fw-bolder border-bottom border-black">PIEZAS REQUERIDAS</p>
                            </div>
                            <div class="col-sm-12 table-responsive">                           
                                <table class="table">
                                <?php
                                /* $presuModel = model('PresupuestoModel');
                                $torreModel = model('TorreModel');
                                $piezaModel = model('PiezaModel'); */

                                echo "<pre>";
                                $piezas_acumuladas = array_values(array_reduce($deta_pre_pie_bd, function ($acumulador, $item) {
                                    // Creamos la llave única
                                    $llave = $item['idpieza'];                                    
                                    // Sumamos la cantidad si ya existe, si no, inicializamos el registro
                                    isset($acumulador[$llave]) ? $acumulador[$llave]['dp_cant_hist'] += $item['dp_cant_hist'] : $acumulador[$llave] = $item;                                    
                                    return $acumulador;
                                }, []));

                                /* print_r($piezas_acumuladas);
                                print_r($stockDePiezasUnicas);
                                print_r($detalle_guia);
                                print_r($deta_pre_pie_bd); */

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
                                foreach( $piezas_acumuladas as $pa ){
                                    $cont++;
                                    $stockAct = $stockDePiezasUnicas[ array_search($pa['idpieza'], array_column($stockDePiezasUnicas, 'idpieza')) ]['stock_actual_real'];

                                    $stock_que_sale = $stockAct >= $pa['dp_cant_hist'] ? $pa['dp_cant_hist'] : $pa['dp_cant_hist'] - $stockAct;
                                    $faltantes = $stockAct < $pa['dp_cant_hist'] ? abs($stockAct - $pa['dp_cant_hist']) : 0;

                                    $resaltar = "";
                                    $boton_faltantes = "";
                                    if( $faltantes > 0 ){
                                        $resaltar = "bg-danger-subtle";
                                        $guiacompleta = FALSE;
                                        $boton_faltantes = "<a class='btn btn-sm btn-danger ml-2' data-idpieza=".$pa['idpieza']." onclick='alquilar(".$pa['idpieza'].",\"".$pa['dp_desc_hist']."\",$faltantes,$stockAct)'>A</a>";
                                    }

                                    echo "<tr class='$resaltar' id='fila_pieza_".$pa['idpieza']."'>";
                                    echo "<td>$cont</td>";
                                    echo "<td>".$pa['dp_desc_hist']."</td>";                                            
                                    echo "<td class='text-center columna-requerido'>".$pa['dp_cant_hist']."</td>";
                                    if( $idguia_bd == '' )
                                        echo "<td class='text-center'>$stockAct</td>";                                          
                                    echo "<td class='text-center'>$stock_que_sale</td>";
                                    echo "<td class='text-center columna-faltante'>$faltantes $boton_faltantes</td>";
                                    echo "</tr>";
                                }

                                //exit();
                                
                                /* $arr_tor = [];//para separar las torres, sin que se repitan
                                foreach( $pre_piezas_bd as $pie_bd ){
                                    $idtor = $pie_bd['idtor'];
                                    $torre_bd = $torreModel->getTorre($idtor);
                                    array_push($arr_tor, array($torre_bd['idtorre'],$torre_bd['tor_desc']));
                                }
                                $arr_tor = array_unique($arr_tor, SORT_REGULAR); */
                               
                                //print_r($presupuesto);
                                //print_r($detalle_guia);
                                //print_r($transportitas);
                                //print_r($departamentos);
                                //print_r($pre_piezas_bd);
                                
                                /* $arr_aux = array_map(function($v){
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
                                            $newarr[$aa]['falt'] = $e_falt + ($ax['falt'] == '' ? 0 : $ax['falt']);
                                            $newarr[$aa]['st_sale'] = $e_stsale + $ax['st_sale'];
                                            //echo $aa;
                                            //print_r($ax);
                                        }

                                        continue;
                                    }
                                    $newarr[] = $ax;
                                } */
                                //print_r($newarr);
                                echo "</pre>";

                                //$arr_existentes = [];//cuando hay mas de una pieza que se repite, y asi poder ir restando su stock para el sgte
                                /* $guiacompleta = TRUE;

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
                
                                    $stockAct      = $pieza_bd['stockActual'];
                                    $faltantes     = $cantReq > $stockAct ? abs($stockAct - $cantReq)  : "";
                    

                                    $resaltar = "";
                                    if( $faltantes != "" ){
                                        $resaltar = "bg-danger-subtle";
                                        $guiacompleta = FALSE;
                                    }                                           
                                    
                                    //editar, de frfente las cantidades
                                    if( array_key_exists('req', $pi) && array_key_exists('falt', $pi) ){
                                        $cantReq = $pi['req'];
                                        $faltantes = $pi['falt'] == 0 ? '' : $pi['falt'];

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
                                    
                                } */                                                      
                                ?>
                                </table>
                            </div>
                            <div class="col-sm-12 pb-3 fw-bolder">
                            <?php

                            foreach($detalle_guia as $d){
                                $idtorre  = $d['idtorre'];
                                $dp_cant  = $d['dp_cant'];
                                $tor_desc = $d['dp_torredesc'];
                                echo "$dp_cant $tor_desc.<br>";
                            }
                            ?>
                            </div>                      
                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <input type="hidden" id="idpre" value="<?=$presupuesto['idpresupuesto']?>">
                                <input type="hidden" id="idguia_bd" value="<?=$idguia_bd?>">
                                <button <?=$guiacompleta ? '' : 'disabled'?> id="btn_generar_guia" class="btn btn-warning btnGuia" data-opt="<?=$guiacompleta ? 1 : 0?>"><?=strtoupper($titulo)?> GUIA</button>
                            </div>
                            <div id="msj"></div>
                        </div>

                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<div class="modal fade" id="modalAlquiler" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAlquilerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-dark text-white py-2">
        <h5 class="modal-title fs-6" id="modalAlquilerLabel">Asignar Alquiler Externo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="modal_idpieza" value="">
        <input type="hidden" id="modal_limite_faltante" value="0">

        <div class="alert alert-secondary py-2 mb-3 shadow-sm">
          <div class="fw-bold" id="modal_txt_pieza">PIEZA LUIS X</div>
          <small class="text-danger fw-bold">Cantidad requerida por cubrir: <span id="modal_txt_faltante">0</span> und.</small>
        </div>

        <div class="row g-2 align-items-end mb-3 p-2 bg-light rounded border">
          <div class="col-md-7">
            <label class="form-label small fw-bold mb-1">Seleccionar Proveedor</label>
            <select class="form-select form-select-sm" id="modal_select_proveedor">
              <option value="">-- Seleccione --</option>
              <?php foreach ($proveedores as $prov): ?>
                <option value="<?= $prov['idproveedor']; ?>">
                  <?= $prov['pro_ruc'] . ' - ' . $prov['pro_razon']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-bold mb-1">Cantidad</label>
            <input type="number" class="form-select text-start form-control-sm" id="modal_input_cantidad" min="1" value="1">
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-primary btn-sm w-100" id="btn_modal_agregar_lista" title="Agregar">
              <i class="bi bi-plus-lg"></i> +
            </button>
          </div>
        </div>

        <div class="table-responsive border rounded" style="max-height: 200px; overflow-y: auto;">
          <table class="table table-sm table-striped mb-0" id="tabla_modal_desglose">
            <thead class="table-light sticky-top">
              <tr>
                <th class="small py-1">Proveedor</th>
                <th class="small py-1 text-end" style="width: 80px;">Cant.</th>
                <th class="small py-1 text-center" style="width: 50px;">Acción</th>
              </tr>
            </thead>
            <tbody>
              <tr id="fila_modal_vacia">
                <td colspan="3" class="text-center text-muted small py-2">Ningún proveedor asignado aún.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer bg-light py-2 d-flex justify-content-between align-items-center">
        <div class="small fw-bold border p-1 rounded bg-white">
          Progreso: <span id="modal_txt_progreso" class="text-primary">0</span> / <span id="modal_txt_total_esperado">0</span>
        </div>
        <div>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success btn-sm" id="btn_modal_confirmar" disabled>Confirmar y Aplicar</button>
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
        clienterecoge = $("#clienterecoge").is(':checked') ? 1 : 0;

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
            departamentoll,provinciall,distritoll,direccionll,placa,opt,idpre,idguia,nroguia,clienterecoge
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


/////
// ==========================================
// SOURCE OF TRUTH (CEREBRO DE LA PANTALLA)
// ==========================================
// Validamos si la variable viene precargada desde PHP (Modo Edición), sino inicia vacía
let guia_materiales_db = <?= isset($guia_guardada) ? json_encode($guia_guardada) : 'null'; ?>;
let guia_materiales = (typeof guia_materiales_db !== 'undefined' && guia_materiales_db !== null) ? guia_materiales_db : {};

$(document).ready(function() {
    // Si ya viene data de la base de datos, pintamos la pantalla de inmediato al cargar
    renderizarTablaPrincipal();

    // ==========================================
    // INTERACCIÓN INTERNA DEL MODAL
    // ==========================================

    // --- BOTÓN: AGREGAR PROVEEDOR A LA LISTA TEMPORAL DEL MODAL ---
    $('#btn_modal_agregar_lista').on('click', function() {
        let idprov = $('#modal_select_proveedor').val();
        let textProv = $('#modal_select_proveedor option:selected').text().trim();
        let cant = parseInt($('#modal_input_cantidad').val());
        let limite = parseInt($('#modal_limite_faltante').val());

        if (!idprov) { alert('Debe seleccionar un proveedor.'); return; }
        if (isNaN(cant) || cant <= 0) { alert('Ingrese una cantidad válida mayor a 0.'); return; }

        // Validar si el proveedor ya está en la lista del modal
        let existe = false;
        $('#tabla_modal_desglose tbody tr').each(function() {
            if ($(this).data('idprov') == idprov) { existe = true; }
        });
        if (existe) { alert('Este proveedor ya está en la lista.'); return; }

        // Calcular la suma de lo que ya se va agregando en el modal
        let sumaActual = 0;
        $('#tabla_modal_desglose tbody tr').each(function() {
            let c = parseInt($(this).data('cant'));
            if (!isNaN(c)) { sumaActual += c; }
        });

        // Validar que no pase el límite estricto de los faltantes
        if ((sumaActual + cant) > limite) {
            alert(`No puedes superar los faltantes requeridos (${limite} und). Quedan disponibles: ${limite - sumaActual} und.`);
            return;
        }

        // Quitar fila vacía si existe
        $('#fila_modal_vacia').remove();

        // Inyectar fila en la tablita del modal
        $('#tabla_modal_desglose tbody').append(`
            <tr data-idprov="${idprov}" data-cant="${cant}">
                <td class="small align-middle">${textProv}</td>
                <td class="small align-middle text-end fw-bold text-secondary">${cant}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-link btn-sm text-danger p-0 btn-eliminar-prov-modal">X</button>
                </td>
            </tr>
        `);

        // Resetear campos de entrada básicos
        $('#modal_select_proveedor').val('');
        $('#modal_input_cantidad').val(1);

        calcularProgresoModal();
    });

    // --- BOTÓN: ELIMINAR PROVEEDOR DE LA TABLITA DEL MODAL ---
    $(document).on('click', '.btn-eliminar-prov-modal', function() {
        $(this).closest('tr').remove();
        
        if ($('#tabla_modal_desglose tbody tr').length === 0) {
            ponerFilaVaciaModal();
        }
        calcularProgresoModal();
    });

    // --- BOTÓN VERDE: CONFIRMAR Y APLICAR CAMBIOS AL OBJETO GLOBAL ---
    $('#btn_modal_confirmar').on('click', function() {
        let idpieza = $('#modal_idpieza').val();

        // Vaciamos el sub-array externo para reconstruirlo con lo que quedó en la tablita
        guia_materiales[idpieza].externo = [];

        $('#tabla_modal_desglose tbody tr').each(function() {
            let idprov = $(this).data('idprov');
            let cant = $(this).data('cant');
            
            if (idprov && cant) {
                guia_materiales[idpieza].externo.push({
                    id_proveedor: idprov,
                    cantidad: cant
                });
            }
        });

        // El modal CUMPLE su único objetivo: Alterar la data. Ahora mandamos a redibujar la pantalla.
        renderizarTablaPrincipal();

        // Cerramos el modal limpiamente
        let modalEl = document.getElementById('modalAlquiler');
        bootstrap.Modal.getInstance(modalEl).hide();
    });
});

// ==========================================
// FUNCIONES CENTRALES (LOGICA LOGÍSTICA)
// ==========================================

// 1. FUNCIÓN QUE ACTIVA TU BOTÓN DE FALTANTES EN LA TABLA PRINCIPAL
function alquilar(idpieza, pieza, faltantes, stockActual) {
    idpieza = parseInt(idpieza);
    faltantes = parseInt(faltantes);
    stockActual = parseInt(stockActual);

    // PASO CLAVE: Si la pieza no existe en nuestro objeto global, la inicializamos sin romper data existente
    if (!guia_materiales[idpieza]) {
        guia_materiales[idpieza] = {
            propio: stockActual, // Captura dinámicamente tu parámetro real
            externo: []          // Nace vacío listo para recibir proveedores
        };
    }

    // Setear controles ocultos y textos estéticos del modal
    $('#modal_idpieza').val(idpieza);
    $('#modal_limite_faltante').val(faltantes);
    $('#modal_txt_pieza').text(pieza.toUpperCase());
    $('#modal_txt_faltante').text(faltantes);
    $('#modal_txt_total_esperado').text(faltantes);

    // Limpiar tabla interna del modal
    ponerFilaVaciaModal();
    $('#modal_select_proveedor').val('');
    $('#modal_input_cantidad').val(1);

    // SI EL OBJETO YA TIENE PROVEEDORES (Cargados por edición o aperturas previas), LOS DIBUJAMOS EN EL MODAL
    if (guia_materiales[idpieza].externo.length > 0) {
        $('#fila_modal_vacia').remove();
        
        guia_materiales[idpieza].externo.forEach(item => {
            let textProveedor = $(`#modal_select_proveedor option[value="${item.id_proveedor}"]`).text().trim();
            
            $('#tabla_modal_desglose tbody').append(`
                <tr data-idprov="${item.id_proveedor}" data-cant="${item.cantidad}">
                    <td class="small align-middle">${textProveedor}</td>
                    <td class="small align-middle text-end fw-bold text-secondary">${item.cantidad}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link btn-sm text-danger p-0 btn-eliminar-prov-modal">X</button>
                    </td>
                </tr>
            `);
        });
    }

    calcularProgresoModal();

    // Abrir Modal
    let myModal = new bootstrap.Modal(document.getElementById('modalAlquiler'));
    myModal.show();
}

// 2. LA FUNCIÓN SUPREMA: Lee el objeto global 'guia_materiales' y dibuja la interfaz completa
function renderizarTablaPrincipal() {
    let piezasConFaltantes = 0;

    Object.keys(guia_materiales).forEach(idpieza => {
        let dataPieza = guia_materiales[idpieza];
        let filaPrincipal = $(`#fila_pieza_${idpieza}`);

        // 1. Limpieza absoluta de sub-filas hijas previas
        $(`.hija_pieza_${idpieza}`).remove();

        // 2. EVALUACIÓN DE ESTADO
        // Buscamos cualquier elemento con la clase .btn (sea <a> o <button>) dentro de la columna faltante
        let celdaFaltante = filaPrincipal.find('.columna-faltante');
        let botonModal = celdaFaltante.find('.btn').detach();

        if (dataPieza.externo.length > 0) {
            // ESCENARIO A: Ya se asignaron proveedores externos
            filaPrincipal.removeClass('table-danger bg-danger-subtle'); 
            
            // Cambiamos el color del botón "A" a gris para denotar que ya está gestionado
            botonModal.removeClass('btn-danger').addClass('btn-secondary');
            
            // Seteamos el valor 0 y le regresamos el botón
            celdaFaltante.html('0 ').append(botonModal);           
        } else {
            // ESCENARIO B: No hay proveedores externos asignados
            let textoRequerido = filaPrincipal.find('.columna-requerido').text().trim();
            let requerido = parseInt(textoRequerido) || 0;
            
            let faltanteOriginal = requerido - dataPieza.propio;

            if (faltanteOriginal > 0) {
                filaPrincipal.addClass('table-danger bg-danger-subtle'); 
                botonModal.removeClass('btn-secondary').addClass('btn-danger');
                
                celdaFaltante.html(faltanteOriginal + ' ').append(botonModal);
                piezasConFaltantes++; 
            } else {
                filaPrincipal.removeClass('table-danger bg-danger-subtle');
                botonModal.removeClass('btn-danger').addClass('btn-secondary');
                
                celdaFaltante.html('0 ').append(botonModal);
            }
        }

        // 3. Dibujamos las sub-filas
        dataPieza.externo.forEach(item => {
            let textProveedor = $(`#modal_select_proveedor option[value="${item.id_proveedor}"]`).text().trim();

            let subFilaHTML = `
                <tr class="table-light hija_pieza_${idpieza} text-muted border-top-0">
                    <td></td>
                    <td class="small py-1 ps-4 text-start"><span class="text-secondary">↳ Alquiler:</span> ${textProveedor}</td>
                    <td></td>
                    <td></td>
                    <td class="small py-1 text-center font-monospace fw-bold">${item.cantidad}</td>
                    <td>
                        <input type="hidden" name="piezas[${idpieza}][externo][${item.id_proveedor}]" value="${item.cantidad}">
                    </td>
                </tr>
            `;
            filaPrincipal.after(subFilaHTML);
        });

        if ($(`#input_propio_${idpieza}`).length === 0) {
            filaPrincipal.append(`<input type="hidden" id="input_propio_${idpieza}" name="piezas[${idpieza}][propio]" value="${dataPieza.propio}">`);
        } else {
            $(`#input_propio_${idpieza}`).val(dataPieza.propio);
        }
    });

    // =================================================================
    // CONTROL INTELIGENTE DEL BOTÓN PRINCIPAL
    // =================================================================
    let cantidadPiezasInteractuadas = Object.keys(guia_materiales).length;

    if (cantidadPiezasInteractuadas === 0) {
        console.log("No hay interacción aún. Se respeta el estado inicial de PHP.");
        return; 
    }

    let faltantesRealesEnPantalla = 0;
    
    // Validamos usando la clase fija que agregamos en el PHP
    $('.columna-faltante').each(function() {
        let textoFaltante = $(this).text().trim(); 
        let valor = parseInt(textoFaltante) || 0;
        
        if (valor > 0) {
            faltantesRealesEnPantalla++;
        }
    });

    console.log("Piezas con faltantes reales en la pantalla:", faltantesRealesEnPantalla);

    if (faltantesRealesEnPantalla > 0) {
        $('#btn_generar_guia').prop('disabled', true).addClass('disabled');
    } else {
        $('#btn_generar_guia').prop('disabled', false).removeClass('disabled');
    }
}

// ==========================================
// HELPERS / AUXILIARES
// ==========================================
function calcularProgresoModal() {
    let limite = parseInt($('#modal_limite_faltante').val());
    let sumaTotal = 0;

    $('#tabla_modal_desglose tbody tr').each(function() {
        let c = parseInt($(this).data('cant'));
        if (!isNaN(c)) { sumaTotal += c; }
    });

    $('#modal_txt_progreso').text(sumaTotal);

    // NUEVA REGLA: El botón se activa si completaste el límite estricto O si limpiaste por completo la tabla (sumaTotal === 0)
    if (sumaTotal === limite && limite > 0) {
        $('#modal_txt_progreso').removeClass('text-primary text-danger').addClass('text-success');
        $('#btn_modal_confirmar').prop('disabled', false); // Candado abierto por completar saldo
    } else if (sumaTotal === 0) {
        $('#modal_txt_progreso').removeClass('text-success text-primary').addClass('text-danger');
        $('#btn_modal_confirmar').prop('disabled', false); // ¡Candado abierto para permitir limpiar/resetear la fila!
    } else {
        // Si está a medias (ej: faltan 8 y va sumando 3) se bloquea
        $('#modal_txt_progreso').removeClass('text-success text-danger').addClass('text-primary');
        $('#btn_modal_confirmar').prop('disabled', true);  
    }
}

function ponerFilaVaciaModal() {
    $('#tabla_modal_desglose tbody').html(`
        <tr id="fila_modal_vacia">
            <td colspan="3" class="text-center text-muted small py-2">Ningún proveedor asignado aún.</td>
        </tr>
    `);
}
</script>

<?php echo $this->endSection();?>