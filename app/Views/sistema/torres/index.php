<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<?php echo $this->endSection();?>


<?php echo $this->section('contenido');?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Módulo Despiece</h4>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
    <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="search-container">
                                    <input type="search" class="form-control search-input" placeholder="Buscar por torre..." id="txtBuscar">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                            <div class="col-sm-7 text-end">
                                <a class="btn btn-warning" role="button" data-bs-toggle="modal" data-bs-target="#modalTorre">Nueva Torre</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive" id="divListar">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTorre" tabindex="-1" aria-labelledby="modalTorreLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Formulario Torre</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="frmTorre">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <label for="desc" class="form-label">Descripción de la torre</label>
                            <input type="text" class="form-control" id="desc" name="desc" value="" maxlength="200">
                            <div id="msj-desc" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="plano" class="form-label">Plano de torre <span id="divplano"></span></label>
                            <input type="file" class="form-control" id="plano" name="plano" accept="application/pdf">
                            <div id="msj-plano" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="piezas" class="form-label">Agregar Pieza</label>
                            <select class="form-select" name="piezas" id="piezas">
                            </select>
                            <div id="msj-piezas" class="form-text text-danger"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-center bg-body-secondary">DETALLE DE TORRE</p>
                        </div>
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 15px">#</th>
                                        <th>Pieza</th>
                                        <th style="width: 100px;">Cantidad</th>
                                        <th style="width: 80px;">Quitar</th>
                                    </tr>
                                </thead>
                                <tbody id="tbl_deta">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="msj"></div>
                <div class="modal-footer py-2">
                    <button type="submit" class="btn btn-danger" id="btnGuardar">REGISTRAR TORRE</button>
                    <input type="hidden" class="form-control" id="id_torree" name="id_torree">
                </div>
            </form>
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
            <td><input type="text" value="${i.cant}" id="c${i.id}" data-id=${i.id} class="form-control form-control-sm numerosindecimal"></td>
            <td class='text-center'><a onclick="eliminarItem(${i.id})"><i class='fas fa-trash-alt'></i></a></td>
            </tr>
        `;
    }
    fila.innerHTML = filahtml;    

    $(".numerosindecimal").on("keypress keyup blur",function (event) {    
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $(".numerosindecimal").on("input",function (event) {    
        let id = event.currentTarget.dataset.id;
        let indice = items.findIndex(x => x.id == id);
        items[indice].cant = $("#c"+id).val();
    });
}

function eliminarItem(id){
    let indice = items.findIndex(x => x.id == id);
    items.splice(indice, 1);
    $('#tbl_deta').html("");
    dibujaFilas();
}

function listarTorres(page, cri = ''){
    $("#divListar").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO DATOS');
    $.post('listar-torres', {
        page,cri
    }, function(data){
        $("#divListar").html(data);
    })
}

listarTorres(1);

function limpiarCampos(){
    items = [];
    dibujaFilas();

    $("#frmTorre")[0].reset();
    $('#piezas').val(null).trigger('change');
    $('[id^="msj-"').text("");
    $("#id_torree").val("");   
    
    $("#divplano").html("");
}

$(function(){
    let timeout;
    $("#txtBuscar").on('input', function(e){
        let cri = $(this).val();
        //console.log(cri);
        clearTimeout(timeout);
        timeout = setTimeout(() => {
        	if( cri.length > 2 ){            
                listarTorres(1,cri);
            }else if( cri.length == 0 ){
                listarTorres(1);
            }
      	}, 600);
    });

    $( '#piezas' ).select2( {
        theme: 'bootstrap-5',
        dropdownParent: $("#modalTorre"),
        //minimumInputLength: 2,
        //minimumResultsForSearch: 10,
        ajax: {
            url: "piezas-select-ajax",
            dataType: 'json',
            data: function(params){
                let query = {
                    search: params.term,
                    type: 'piezas'
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
        placeholder: 'Buscar piezas...',
    });

    $("#piezas").on('change', function(e){
        let id = $(this).val(),
            text = $("#piezas option:selected").text();

        if( id == '' || id == undefined ) return;

        let item = {
            id,
            text,
            cant:1
        }

        let existe = items.find(x => x.id === id);
        if(existe === undefined && id != ''){
            items.push(item);
            dibujaFilas();         
        }else{
            Swal.fire({
                title: "La pieza ya fue agregada.",
                icon: "error"
            });
        }        
    });


    $("#frmTorre").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardar'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        let formData = new FormData(this);
        formData.append('items', JSON.stringify(items));

        $.ajax({
            method: 'POST',
            url: 'registro-torre',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function(data){
                //console.log(data);
                $('[id^="msj-"').text("");                
                if( data.errors ){                    
                    let errors = data.errors;
                    for( let err in errors ){
                        $('#msj-' + err).text(errors[err]);
                    }
                }
                btn.removeAttribute('disabled');
                btn.innerHTML = txtbtn; 
                $('#msj').html(data);
            }
        });

    });

    const myModalEl = document.getElementById('modalTorre')
    myModalEl.addEventListener('hidden.bs.modal', event => {
        $("#btnGuardar").text("REGISTRAR TORRE");
        limpiarCampos();
        $("#msj").html("");
    });

    $('#plano').on('change', function(){
        let tipos = ['application/pdf'];
        let file = this.files[0];
        let tipofile = file.type;
        let sizefile = file.size;

        if(!tipos.includes(tipofile)){
            Swal.fire({
                text: "El plano debe ser un pdf",
                icon: "info"
            });
            $(this).val('');
            return false;
        }
        if(sizefile >= 2097152){
            Swal.fire({
                text: "El documento de debe ser mayor 2MB",
                icon: "info"
            });
            $(this).val('');
            return false;
        }
    });

});
</script>

<?php echo $this->endSection();?>