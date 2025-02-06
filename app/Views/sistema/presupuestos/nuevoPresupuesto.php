<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<?php echo $this->endSection();?>


<?php echo $this->section('contenido');?>

<div class="app-content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Realizar Presupuesto</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="cbody">
                        <div class="row">
                            <div class="col-sm-3 mb-3">
                                <label for="nropre" class="form-label">Nro Presupuesto</label>
                                <input type="text" class="form-control" id="nropre" name="nropre" value="<?=$nroPre['nro']?>" maxlength="10" disabled>
                                <div id="msj-nropre" class="form-text text-danger"></div>
                            </div>                            
                            <div class="col-sm-2 mb-3">
                                <label for="porcpre" class="form-label">% de Precio</label>
                                <input type="text" class="form-control" id="porcpre" name="porcpre" value="" maxlength="2">
                                <div id="msj-porcpre" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <label for="dias" class="form-label">N° Días</label>
                                <input type="text" class="form-control" id="dias" name="dias" value="" maxlength="3">
                                <div id="msj-dias" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-5 mb-3">
                                <label for="cliente" class="form-label">Buscar Cliente</label>
                                <select class="form-select" name="cliente" id="cliente">
                                </select>
                                <div id="msj-cliente" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-5 mb-3">
                                <label for="torre" class="form-label">Buscar Torre</label>
                                <select class="form-select" name="torre" id="torre">
                                </select>
                                <div id="msj-torre" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="text" class="form-control" id="cantidad" name="cantidad" value="" maxlength="3">
                                <div id="msj-cantidad" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-2 mt-2 d-flex align-items-center">
                                <input type="hidden" id="porcsem" name="porcsem" value="<?=$param['par_porcensem']?>">
                                <a class="btn btn-outline-secondary btn-sm" id="btnAdd">Agregar</a>
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
                                            <th style="width: 100px;">Cantidad</th>
                                            <th style="width: 80px;">Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_deta">
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 text-end">
                                SUB TOTAL: S/. <span id="subT">0.00</span>
                            </div>
                        </div>

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
let items = [];
let fila = document.querySelector('#tbl_deta');

function dibujaFilas(){
    let filahtml = '';
    
    let cont = 0;
    for(let i of items){
        cont++;
        filahtml += `
            <tr>
            <td id="${i.id}">
                ${cont}
            </td>
            <td>${i.text}</td>
            <td>${i.cant}</td>
            <td class='text-center'><a onclick="eliminarItem(${i.id})"><i class='fas fa-trash-alt'></i></a></td>
            </tr>
        `;
    }
    fila.innerHTML = filahtml;    
}

function eliminarItem(id){
    let indice = items.findIndex(x => x.id == id);
    items.splice(indice, 1);
    $('#tbl_deta').html("");
    dibujaFilas();
    calcular();
}

function calcular(){
    let suma = 0.00;
    for( let i of items ){
        suma += i.total * i.cant;   
    }
    $("#subT").text(suma.toFixed(2));
}

$(function(){
    $( '#cliente' ).select2( {
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: true,
        ajax: {
            url: "clientes-select-ajax",
            dataType: 'json',
            data: function(params){
                let query = {
                    search: params.term,
                    type: 'clientes'
                };
                return query;
            },
            processResults: function(data){
                return {
                    results: data
                }
            }
        },
        cache: true,
        placeholder: 'Buscar clientes...',
        templateResult: function(data){
            //console.log(data);
            if (!data.id) {
                    return data.text;
            }
            var $state = $(
                `<div>${data.text}<br>${data.dniruc}</div>`
            );
            return $state;
        }
    });

    $( '#torre' ).select2( {
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: true,
        ajax: {
            url: "torres-select-ajax",
            dataType: 'json',
            data: function(params){
                let query = {
                    search: params.term,
                    type: 'torres'
                };
                return query;
            },
            processResults: function(data){
                //console.log(data)
                return {
                    results: data
                }
            }
        },
        cache: true,
        placeholder: 'Buscar torres...',
    });

    $('#torre').on("select2:select", function(e) {
        let data = $("#torre").select2('data')[0];
        $("#torre option[value="+data.id+"]").attr('data-total', data.total);
    });

    $("#btnAdd").on('click', function(e){
        let id = $("#torre").val(),
            text = $("#torre option:selected").text(),
            cant = $("#cantidad").val(),
            total = $("#torre option:selected").data('total');
        
        let men = '';
        if( $("#porcpre").val().trim() == '' ) men = 'Ingrese un porcentaje de precio';
        else if( $("#dias").val().trim() == '' ) men = 'Ingrese el Número de días';
        else if( $("#cliente option:selected").text() == '' ) men = 'Seleccione un cliente';
        else if( id == '' || id == undefined ) men = 'Seleccione una torre';
        else if( cant == '' || cant == undefined ) men = 'Ingrese una cantidad';

        if( men != '' ){
            Swal.fire({title: men, icon: "error"});
            return;
        }

        let item = {
            id,
            text,
            cant,
            total,
        }

        let existe = items.find(x => x.id === id);
        if(existe === undefined && id != ''){
            items.push(item);
            dibujaFilas();
            calcular();
            $("#cantidad").val('');
            $('#torre').val('').trigger('change');
        }else{
            Swal.fire({title: "La torre ya fue agregada.", icon: "error"});
        }

    });

});

</script>

<?php echo $this->endSection();?>