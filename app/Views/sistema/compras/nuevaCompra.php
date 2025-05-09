<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<?php echo $this->endSection();?>

<?php echo $this->section('contenido');?>

<?php
if( isset($compra_bd) ){
    echo "<pre>";
    //print_r($compra_bd);
    //print_r($detalle_bd);
    echo "</pre>";
    $nrodoc    = $compra_bd['com_nrodoc'];
    $fecha     = $compra_bd['com_fecha'];
    $proveedor = $compra_bd['com_proveedor'];
    $ruc       = $compra_bd['com_ruc'];
    $idcompra  = $compra_bd['idcompra'];

    $card_title = "Modificar Compra";
    $btn_title  = "MODIFICAR COMPRA";

    $items = [];
    foreach( $detalle_bd as $d ){
        $item = array(
            "id"      => $d['idpieza'],
            "codigo"  => $d['pie_codigo'],
            "text"    => $d['pie_desc'],
            "cant"    => $d['cantidad'],
            "precioc" => $d['preciocom'],
            "preciot" => $d['cantidad'] * $d['preciocom']
        );
        array_push($items, $item);
    }

    $items = json_encode($items, JSON_HEX_APOS);

}else{
    $nrodoc    = "";
    $fecha     = date('Y-m-d');
    $proveedor = "";
    $ruc       = "";
    $idcompra  = "";

    $card_title = "Nueva Compra";
    $btn_title  = "GUARDAR COMPRA";

    $items = json_encode([]);
}
?>

<div class="app-content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Nueva Compra</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="cbody">
                        <form id="frmCompra">
                            <div class="row">
                                <div class="col-sm-2 mb-3">
                                    <label for="nrodoc" class="form-label">N° Doc</label>
                                    <input type="text" class="form-control" id="nrodoc" name="nrodoc" value="<?=$nrodoc?>" maxlength="20">
                                    <div id="msj-nrodoc" class="form-text text-danger"></div>
                                </div>
                                                          
                                <div class="col-sm-3 mb-3">
                                    <label for="fecha" class="form-label">Fecha Compra</label>
                                    <input type="date" class="form-control" name="fecha" id="fecha" value="<?=$fecha?>">
                                    <div id="msj-fecha" class="form-text text-danger"></div>
                                </div>

                                <div class="col-sm-5 mb-3">
                                    <label for="proveedor" class="form-label">Proveedor</label>
                                    <input type="text" class="form-control" id="proveedor" name="proveedor" value="<?=$proveedor?>" maxlength="150">
                                    <div id="msj-proveedor" class="form-text text-danger"></div>
                                </div>

                                <div class="col-sm-2 mb-3">
                                    <label for="ruc" class="form-label">RUC</label>
                                    <input type="text" class="form-control" name="ruc" id="ruc" value="<?=$ruc?>" maxlength="11">
                                    <div id="msj-ruc" class="form-text text-danger"></div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <label for="piezas" class="form-label">Agregar Pieza</label>
                                    <select class="form-select" name="piezas" id="piezas">
                                        <option value="">Seleccione</option>
                                        <?php
                                        foreach($piezas as $p){
                                            $idpieza    = $p['idpieza'];
                                            $pie_codigo = $p['pie_codigo'];
                                            $pie_desc   = $p['pie_desc'];

                                            echo "<option value=$idpieza data-cod='".$pie_codigo."'>$pie_desc</option>";
                                        }
                                        ?>
                                    </select>
                                    <div id="msj-piezas" class="form-text text-danger"></div>
                                </div>

                                <div class="col-sm-2 mb-3">
                                    <label for="cantidad" class="form-label">Cantidad</label>
                                    <input type="number" class="form-control" name="cantidad" id="cantidad" value="" maxlength="11">
                                    <div id="msj-cantidad" class="form-text text-danger"></div>
                                </div>

                                <div class="col-sm-2 mb-3">
                                    <label for="precioc" class="form-label">Precio C</label>
                                    <input type="text" class="form-control numerocondecimal" name="precioc" id="precioc" value="" maxlength="11">
                                    <div id="msj-precioc" class="form-text text-danger"></div>
                                </div>

                                <div class="col-sm-2 mt-2 d-flex align-items-center">
                                    <a class="btn btn-outline-secondary btn-sm" id="btnAdd">Agregar</a>
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-center bg-body-secondary">DETALLE DE COMPRA</p>
                                </div>
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 15px">#</th>
                                                <th style="width: 100px">Código</th>
                                                <th>Pieza</th>
                                                <th style="width: 80px;">Cantidad</th>
                                                <th style="width: 140px;">Precio Compra</th>
                                                <th style="width: 140px;">Precio Total</th>
                                                <th style="width: 80px;">Quitar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl_deta">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="hidden" name="idcom" id="idcom" value="<?=$idcompra?>">
                                    <button id="btnCompra" class="btn btn-warning"><?=$btn_title?></button>
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

$(function(){

    $( '#piezas' ).select2( {
        theme: 'bootstrap-5',
        placeholder: 'Buscar piezas...',
    });

    $("#btnAdd").on('click', function(e){
        let nrodoc = $("#nrodoc").val().trim(),
            fecha     = $("#fecha").val(),
            proveedor = $("#proveedor").val().trim(),
            ruc       = $("#ruc").val().trim(),
            id        = $("#piezas").val(),
            codigo    = $("#piezas option:selected").data('cod'),
            text      = $("#piezas option:selected").text(),
            cant      = $("#cantidad").val(),
            precioc   = $("#precioc").val();
        
        let men = '';
        if( nrodoc == '' ) men = 'Ingrese el nro de documento';
        else if( fecha == '' ) men = 'Seleccione una fecha';
        else if( proveedor == '' ) men = 'Ingrese un proveedor';
        else if( ruc == '' ) men = 'Ingrese un ruc';
        else if( id == '' || id == undefined ) men = 'Seleccione una pieza';
        else if( cant == '' || cant == undefined ) men = 'Ingrese una cantidad';
        else if( precioc == '' || precioc == undefined ) men = 'Ingrese el precio de compra';

        if( men != '' ){
            Swal.fire({title: men, icon: "error"});
            return;
        }

        preciot = cant * precioc;

        let item = {
            id,
            codigo,
            text,
            cant,
            precioc,
            preciot
        }

        let existe = items.find(x => x.id === id);
        if(existe === undefined && id != ''){
            items.push(item);
            //calcular();
            dibujaFilas();
            $("#cantidad").val('');
            $('#piezas').val('').trigger('change');
            $("#precioc").val('');
        }else{
            Swal.fire({title: "La pieza ya fue agregada.", icon: "error"});
        }

    });

    $("#frmCompra").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnCompra'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        if( items.length == 0 ){
            Swal.fire({title: "Faltan datos.", icon: "error"});
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn; 
            return;
        }

        let formData = new FormData(this);
        formData.append('items', JSON.stringify(items));

        $.ajax({
            method: 'POST',
            url: 'registro-compra',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function(data){
                //console.log(data);
                /*$('[id^="msj-"').text("");                
                if( data.errors ){                    
                    let errors = data.errors;
                    for( let err in errors ){
                        $('#msj-' + err).text(errors[err]);
                    }
                }*/
                btn.removeAttribute('disabled');
                btn.innerHTML = txtbtn; 
                $('#msj').html(data);
            }
        });

    });

    $(".numerocondecimal").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

});

function dibujaFilas(){
    let filahtml = '';
    let total = 0;
    let cont = 0;
    for(let i of items){
        total += i.preciot;
        cont++;
        filahtml += `
            <tr style='color: #666'>
            <td id="${i.id}">
                ${cont}
            </td>
            <td>${i.codigo}</td>
            <td>${i.text}</td>
            <td>${i.cant}</td>
            <td>${i.precioc}</td>
            <td>${i.preciot.toFixed(2)}</td>
            <td class='text-center'><a onclick="eliminarItem(${i.id})"><i class='fas fa-trash-alt'></i></a></td>
            </tr>
        `;
    }
    fila.innerHTML = filahtml;
    console.log(total);
}

function eliminarItem(id){
    let indice = items.findIndex(x => x.id == id);
    items.splice(indice, 1);
    $('#tbl_deta').html("");
    dibujaFilas();
    //calcular();
}

<?php
if( isset($compra_bd) ){
    echo "dibujaFilas()";
}
?>

</script>

<?php echo $this->endSection();?>