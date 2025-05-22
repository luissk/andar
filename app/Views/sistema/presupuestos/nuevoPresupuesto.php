<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<?php echo $this->endSection();?>


<?php echo $this->section('contenido');?>
<?php
$torreModel = model('TorreModel');

if( isset($presu_bd) && $presu_bd ){
    /* echo "<pre>";
    print_r($presu_bd);
    print_r($deta_bd);
    echo "</pre>"; */

    $idpre       = $presu_bd['idpresupuesto'];
    $pre_numero  = $presu_bd['pre_numero'];
    $periodo     = $presu_bd['pre_periodo'];
    $periodonro  = $presu_bd['pre_periodonro'];
    $porcprecio  = $presu_bd['pre_porcenprecio'];
    $porcsem     = $presu_bd['pre_porcsem'];
    $idcliente   = $presu_bd['idcliente'];
    $verP        = $presu_bd['pre_verpiezas'];
    $tcambio     = $presu_bd['pre_tcambio'];
    $pentrega    = $presu_bd['pre_pentrega'];
    $fpago       = $presu_bd['pre_fpago'];
    $voferta     = $presu_bd['pre_voferta'];
    $lentrega    = $presu_bd['pre_lentrega'];
    $preciotrans = $presu_bd['pre_preciotrans'];
    $nrodias     = $presu_bd['pre_nrodiasm'];

    $titulo   = "Modificar";
    $btnTexto = "MODIFICAR PRESUPUESTO";

    $piezas_pre = $presu_bd['pre_piezas'];
    $piezas_enc = json_decode($piezas_pre, true);
    /* echo "<pre>";
    print_r($piezas_enc);
    echo "</pre>"; */
    
    $items = [];
    foreach($deta_bd as $d){
        $to = 0;
        foreach( $piezas_enc as $pe ){
            if( $d['idtorre'] == $pe['idtor'] ){
                $to += $pe['piepre'] * $pe['dtcan'];
            }
        }
        //echo $to;

        $dt_torre   = $torreModel->getDetalleTorre($d['idtorre']);
        $arrDT = [];
        foreach( $dt_torre as $dtt ){
            array_push($arrDT,[$dtt['pie_desc'],$dtt['pie_precio'],$dtt['dt_cantidad'],$dtt['total'],$dtt['pie_peso']]);
        }

        $item = array(
            'id'     => $d['idtorre'],
            'text'   => $d['tor_desc'],
            'cant'   => $d['dp_cant'],
            'total'  => $to,
            'monto'  => $d['dp_precio'] / $d['dp_cant'],
            'tmonto' => $d['dp_precio'],
            'piezas' => $arrDT,
        );
        array_push($items, $item);
    }

    $items = json_encode($items, JSON_HEX_APOS);
    //print_r($items);

}else{
    $idpre       = "";
    $pre_numero  = "";
    $periodo     = "";
    $periodonro  = "";
    $porcprecio  = "";
    $porcsem     = "";
    $idcliente   = "";
    $verP        = "";
    $tcambio     = "";
    $pentrega    = "";
    $fpago       = "";
    $voferta     = "";
    $lentrega    = "";
    $preciotrans = "";
    $nrodias     = "";

    $titulo   = "Realizar";
    $btnTexto = "GENERAR PRESUPUESTO";

    $items = json_encode([]);
}
?>

<div class="app-content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><?=$titulo?> Presupuesto</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="cbody">
                        <form id="frmPresu">
                        <div class="row">
                            <div class="col-sm-2 mb-3">
                                <label for="nropre" class="form-label">N° Presupuesto</label>
                                <input type="text" class="form-control" id="nropre" name="nropre" value="<?=$nroPre?>" maxlength="10">
                                <div id="msj-nropre" class="form-text text-danger"></div>
                            </div>                            
                            <div class="col-sm-1 mb-3">
                                <label for="porcpre" class="form-label">% Pre.</label>
                                <input type="text" class="form-control" id="porcpre" name="porcpre" value="<?=$porcprecio?>" maxlength="5">
                                <div id="msj-porcpre" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-1 mb-3">
                                <label for="tcambio" class="form-label">T. Ca.</label>
                                <input type="text" class="form-control" id="tcambio" name="tcambio" value="<?=$tcambio?>" maxlength="4">
                                <div id="msj-tcambio" class="form-text text-danger"></div>
                            </div> 
                            <div class="col-sm-2 mb-3">
                                <label for="periodo" class="form-label">Periodo</label>
                                <select class="form-select" name="periodo" id="periodo">
                                    <option value=""></option>
                                    <option value="d" <?=$periodo != '' && $periodo == 'd' ? 'selected' : ''?>>Día</option>
                                    <option value="s" <?=$periodo != '' && $periodo == 's' ? 'selected' : ''?>>Semana</option>
                                    <option value="m" <?=$periodo != '' && $periodo == 'm' ? 'selected' : ''?>>Mes</option>
                                </select>
                            </div>
                            <div class="col-sm-1 mb-3">
                                <label for="nroperiodo" class="form-label">N° Per.</label>
                                <select class="form-select" name="nroperiodo" id="nroperiodo">
                                </select>
                            </div>
                            <div class="col-sm-5 mb-3">
                                <label for="cliente" class="form-label">Buscar Cliente</label>
                                <select class="form-select" name="cliente" id="cliente">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach( $clientesCbo as $cli ){
                                        $idcli = $cli['idcliente'];
                                        $nom   = $cli['cli_nombrerazon'];
                                        $dni   = $cli['cli_dniruc'];

                                        $sel_cli = $idcliente == $idcli ? 'selected' : '';

                                        echo "<option value='$idcli' $sel_cli>$nom ($dni)</option>";
                                    }
                                    ?>
                                </select>
                                <div id="msj-cliente" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-5 mb-3">
                                <label for="torre" class="form-label">Buscar Torre</label>
                                <select class="form-select" name="torre" id="torre">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach( $torresCbo as $tor ){
                                        $idtor     = $tor['idtorre'];
                                        $tor_desc  = $tor['tor_desc'];
                                        $tor_total = $tor['total'];
                                        
                                        $dt_torre   = $torreModel->getDetalleTorre($idtor);

                                        $arr = [];
                                        foreach( $dt_torre as $dtt ){
                                            array_push($arr,[$dtt['pie_desc'],$dtt['pie_precio'],$dtt['dt_cantidad'],$dtt['total'],$dtt['pie_peso']]);
                                        }
                                        $arr = json_encode($arr, JSON_HEX_APOS);

                                        echo "<option value='$idtor' data-total='$tor_total' data-pie='$arr'>$tor_desc</option>";
                                    }
                                    ?>
                                </select>
                                <div id="msj-torre" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-1 mb-3">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="text" class="form-control" id="cantidad" name="cantidad" value="" maxlength="3">
                                <div id="msj-cantidad" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-1 mt-2 d-flex align-items-center">
                                <input type="hidden" id="porcsem" name="porcsem" value="<?=$param['par_porcensem']?>">
                                <a class="btn btn-outline-secondary btn-sm" id="btnAdd">Agregar</a>
                            </div>
                            <div class="col-sm-1 mb-3">
                                <label for="dias" class="form-label">Días</label>
                                <input type="text" class="form-control" id="dias" name="dias" value="<?=$nrodias?>" maxlength="3">
                                <div id="msj-dias" class="form-text text-danger"></div>
                            </div>        


                            <div class="col-sm-3 mt-2 d-flex align-items-center">
                                <input name="verP" id="verP" type="checkbox" onclick="$('#tbl_deta .piezasOcultas').toggle()" <?=$verP == 1 ? 'checked': ''?> />&nbsp;Ver piezas de la torre
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-center bg-body-secondary">DETALLE DE TORRES</p>
                            </div>
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 15px">#</th>
                                            <th>Torres</th>
                                            <th style="width: 80px;">Cantidad</th>
                                            <th style="width: 80px;">Peso U.</th>
                                            <th style="width: 80px;">Peso T.</th>
                                            <th style="width: 100px;">Precio U.</th>
                                            <th style="width: 100px;">Total(S/.)</th>
                                            <th style="width: 80px;">Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_deta">
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <label for="preciotrans" class="form-label">Precio Transporte</label>
                                <input type="text" class="form-control numerocondecimal" id="preciotrans" name="preciotrans" value="<?=$preciotrans?>">
                                <div id="msj-preciotrans" class="form-text text-danger"></div>
                            </div>
                            <div id="pesoT" class="fw-bolder"></div>
                            <div class="col-sm-12 text-end">
                                <div class="fw-bolder">SUB TOTAL: S/. <span id="subT">0.00</span></div>
                                <div class="fw-bolder">IGV(18%): S/. <span id="igv">0.00</span></div>
                                <div class="fw-bolder">TOTAL: S/. <span id="total">0.00</span></div>
                            </div>
                        </div>
                        <p>
                            <b>CONDICIONES COMERCIALES</b>
                        </p>
                        <div class="row border my-2"">
                            <div class="col-sm-6 mb-3">
                                <label for="plazoentrega" class="form-label">Plazo de entrega</label>
                                <input type="text" class="form-control" id="plazoentrega" name="plazoentrega" value="<?=$pentrega?>" maxlength="100">
                                <div id="msj-plazoentrega" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="formapago" class="form-label">Forma de Pago</label>
                                <input type="text" class="form-control" id="formapago" name="formapago" value="<?=$fpago?>" maxlength="50">
                                <div id="msj-formapago" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="validezoferta" class="form-label">Validez de oferta</label>
                                <input type="text" class="form-control" id="validezoferta" name="validezoferta" value="<?=$voferta?>" maxlength="50">
                                <div id="msj-validezoferta" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="lugarentrega" class="form-label">Lugar entrega</label>
                                <input type="text" class="form-control" id="lugarentrega" name="lugarentrega" value="<?=$lentrega?>" maxlength="100">
                                <div id="msj-lugarentrega" class="form-text text-danger"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <input type="hidden" name="idpre" id="idpre" value="<?=$idpre?>">
                                <button id="btnPresu" class="btn btn-warning"><?=$btnTexto?></button>
                            </div>
                        </div>

                        <div id="msj"></div>
                        </form>

                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php echo $this->endSection();?>


<?php echo $this->section('scripts');?>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>

<script>
let items = <?=$items?>;
let fila = document.querySelector('#tbl_deta');

function dibujaFilas(){
    let filahtml = '';
    let sumapeso = 0;
    let cont = 0;
    for(let i of items){
        cont++;
        let toPesoUnit = i.piezas.reduce((pv, cv, ci, arr) => pv + Number(cv[4]), 0);
        let toPesoTotal = i.piezas.reduce((pv, cv, ci, arr) => pv + Number(cv[4]) * Number(cv[2]) * i.cant, 0);
        filahtml += `
            <tr style='font-weight:600; color: #666'>
            <td id="${i.id}">
                ${cont}
            </td>
            <td>${i.text}</td>
            <td>${i.cant}</td>
            <td>${toPesoUnit.toFixed(2)}</td>
            <td>${toPesoTotal.toFixed(2)}</td>
            <td>${i.monto}</td>
            <td>${i.tmonto}</td>
            <td class='text-center'><a onclick="eliminarItem(${i.id})"><i class='fas fa-trash-alt'></i></a></td>
            </tr>
        `;
        cont2 = 0;
        for(let j of i.piezas){
            cont2++;
            const isChecked = $("#verP").is(":checked") ? '' : 'none';
            sumapeso += j[4] * (j[2] * i.cant);
            filahtml += `
            <tr style='color:#666; font-size:15px; display:${isChecked}' id='piezas${i.id}' class='piezasOcultas'>
                <td>${cont}.${cont2}</td>
                <td>${j[0]}</td>                
                <td>${j[2] * i.cant}</td>
                <td>${j[4]}</td>
                <td>${(j[4] * (j[2] * i.cant)).toFixed(2)}</td>
                <td>${( (i.cant * j[5])/(j[2] * i.cant) ).toFixed(2)}</td>
                <td>${(i.cant * j[5]).toFixed(2)}</td>
                <td></td>
            </tr>
            `;
        }
    }
    fila.innerHTML = filahtml;
    
    $("#pesoT").html(`* Peso Total: ${(sumapeso / 1000).toFixed(2)} Tn`)
}

function eliminarItem(id){
    let indice = items.findIndex(x => x.id == id);
    items.splice(indice, 1);
    $('#tbl_deta').html("");
    dibujaFilas();
    calcular();
}

function calcularEnPrecioPiezas(callback){
    for( let i of items ){
        for(let j of i.piezas){
            let totalPrecioPiezaTorre = j[3];//del detalle de torre(cant piezas para torre * precio de pieza)
            callback(j, totalPrecioPiezaTorre);
        }
    }
}

function calcular(){//calcularEnPrecioPiezas();
    let periodo    = $("#periodo").val();
    let nroperiodo = $("#nroperiodo").val();
    let porcsem    = $("#porcsem").val();
    let porcpre    = $("#porcpre").val();
    let tcambio    = $("#tcambio").val();
    //let suma       = 0;
    
    let p_pre = (porcpre/100),
        p_sem = (porcsem/100);

    let monto, tmonto; //para los items

    for( let i of items ){
        let pre_cant = i.total * i.cant;  

        if( periodo == 'd' && nroperiodo <= 6 ){
            //suma += pre_cant / 4 * p_pre * p_sem;
            monto  = (pre_cant / 4 * p_pre * p_sem * tcambio) / i.cant;//para items
            tmonto = pre_cant / 4 * p_pre * p_sem * tcambio;//para items

            calcularEnPrecioPiezas( (a, pr) => { a[5] = pr / 4 * p_pre * p_sem * tcambio });
        }else if( periodo == 's' ){
            if( nroperiodo < 4 ){
                //suma += pre_cant / 4 * nroperiodo * p_pre * p_sem;
                monto  = (pre_cant / 4 * nroperiodo * p_pre * p_sem * tcambio) / i.cant;//para items
                tmonto = pre_cant / 4 * nroperiodo * p_pre * p_sem * tcambio;//para items

                calcularEnPrecioPiezas( (a, pr) => { a[5] = pr / 4 * nroperiodo * p_pre * p_sem * tcambio });
            }
            if( nroperiodo % 4 == 0 ){//es mes
                let nromes = nroperiodo / 4;
                //suma += pre_cant * nromes * p_pre;
                monto  = (pre_cant * nromes * p_pre * tcambio) / i.cant;//para items
                tmonto = pre_cant * nromes * p_pre * tcambio;//para items

                calcularEnPrecioPiezas( (a, pr) => { a[5] = pr * nromes * p_pre * tcambio });
            }
            if( nroperiodo > 4 && nroperiodo % 4 != 0 ){
                let res = nroperiodo / 4;
                let mes = Math.trunc(res);
                let dec = res - mes;
                let sem = 4 * dec;

                //suma += (pre_cant * mes * p_pre) + (pre_cant / 4 * sem * p_pre * p_sem);
                monto  = ((pre_cant * mes * p_pre) + (pre_cant / 4 * sem * p_pre * p_sem * tcambio)) / i.cant;//para items
                tmonto = (pre_cant * mes * p_pre) + (pre_cant / 4 * sem * p_pre * p_sem * tcambio);//para items

                calcularEnPrecioPiezas( (a, pr) => { a[5] = (pr * mes * p_pre) + (pr / 4 * sem * p_pre * p_sem * tcambio) });
            }
        }else if( periodo == 'm' ){
            //suma += pre_cant * nroperiodo * p_pre;
            monto  = (pre_cant * nroperiodo * p_pre * tcambio) / i.cant;//para items
            tmonto = pre_cant * nroperiodo * p_pre * tcambio;//para items

            calcularEnPrecioPiezas( (a, pr) => { a[5] = pr * nroperiodo * p_pre * tcambio });
        }

        let indice = items.findIndex(x => x.id == i.id);
        if( indice > -1 ){
            items[indice].monto = monto.toFixed(2);
            items[indice].tmonto = tmonto.toFixed(2);
        }
    }
    let tt = items.reduce((acc,el) => acc + Number(el.tmonto),0);
    let preciotrans = $("#preciotrans").val() == '' ? 0 : $("#preciotrans").val();
    let subtotal = Number(tt.toFixed(2)) + Number(preciotrans);
    $("#subT").text(subtotal);
    $("#igv").text((subtotal * 0.18).toFixed(2));
    $("#total").text((subtotal * 1.18).toFixed(2));
}

function llenaNroPeriodo(n){
    let select = document.querySelector('#nroperiodo');
    let option = "";
    for( let i = 1; i <= n; i++ ){
        option += `<option value=${i}>${i}</option>`;
    }
    select.innerHTML = option;
}

/* function actDesCampos($opt = true){//cuando se tiene agregado items y quiere camabir porcentaje,periodo,periodonro
    if( $opt ){
        $("#porcpre").attr('disabled',true);
        $("#periodo").attr('disabled',true);
        $("#nroperiodo").attr('disabled',true);
    }else{
        $("#porcpre").removeAttr('disabled');
        $("#periodo").removeAttr('disabled');
        $("#nroperiodo").removeAttr('disabled');
    }
} */

$(function(){
    $("#periodo").on('change', function(e){
        let _this = $(this);

        if( items.length > 0 ){
            calcular();
            dibujaFilas();
        }

        if( _this.val() == '' ){
            llenaNroPeriodo(0);
        }else if( _this.val() == 'd' ){
            llenaNroPeriodo(6);
        }else if( _this.val() == 's' ){
            llenaNroPeriodo(52);
        }else if( _this.val() == 'm' ){
            llenaNroPeriodo(12);
        }
    });

    $("#nroperiodo").on('change', function(e){
        if( items.length > 0 ){
            calcular();
            dibujaFilas();
        }
    });

    $("#porcpre, #tcambio").on('input', function(e){
        if( items.length > 0 ){
            calcular();
            dibujaFilas();
        }
    });

    $("#preciotrans").on('input', function(e){
        let preciotrans = $(this).val();
        //console.log(_this.val())
        calcular();
        dibujaFilas();
    });

    $( '#cliente' ).select2( {
        theme: 'bootstrap-5',
        width: '100%',
    });

    $( '#torre' ).select2( {
        theme: 'bootstrap-5',
        width: '100%',
    });

    $("#btnAdd").on('click', function(e){
        let id = $("#torre").val(),
            text = $("#torre option:selected").text(),
            cant = $("#cantidad").val(),
            nropre = $("#nropre").val().trim(),
            total = $("#torre option:selected").data('total'),
            piezas = $("#torre option:selected").data('pie');
        
        let men = '';
        if( $("#porcpre").val().trim() == '' ) men = 'Ingrese un porcentaje de precio';
        else if( $("#tcambio").val() == '' ) men = 'Ingrese el tip ode cambio';
        else if( $("#periodo").val() == '' ) men = 'Seleccione el periodo';
        else if( $("#nroperiodo").val() == '' ) men = 'Seleccione el Numero de periodo';
        else if( $("#cliente").val() == '' ) men = 'Seleccione un cliente';
        else if( id == '' || id == undefined ) men = 'Seleccione una torre';
        else if( cant == '' || cant == undefined ) men = 'Ingrese una cantidad';
        else if( nropre == '' ) men = 'Ingrese el nro de Presupuesto';

        if( men != '' ){
            Swal.fire({title: men, icon: "error"});
            return;
        }

        let item = {
            id,
            text,
            cant,
            total,
            monto:1,
            tmonto:1,
            piezas
        }

        let existe = items.find(x => x.id === id);
        if(existe === undefined && id != ''){
            items.push(item);
            calcular();
            dibujaFilas();
            $("#cantidad").val('');
            $('#torre').val('').trigger('change');
        }else{
            Swal.fire({title: "La torre ya fue agregada.", icon: "error"});
        }

    });

    $("#frmPresu").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnPresu'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        if( items.length == 0 ){
            Swal.fire({title: "Por favor, rellena los campos", icon: "error"});
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
            return;
        }

        let formData = new FormData(this);
        formData.append('items', JSON.stringify(items));

        $.ajax({
            method: 'POST',
            url: 'registro-presu',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function(data){
                console.log(data);
                btn.removeAttribute('disabled');
                btn.innerHTML = txtbtn; 
                $('#msj').html(data);
            }
        });

    });


    //editar
    <?php
    if( $periodo != '' ){
        echo "$('#periodo').val('$periodo').trigger('change');";
        echo "$('#nroperiodo').val($periodonro);";
        echo "
            calcular();
            dibujaFilas();
        ";
    }
    ?>
    //fin editar

    $(".numerocondecimal").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

});

</script>

<?php echo $this->endSection();?>