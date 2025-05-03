<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Piezas del Sistema</h4>
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
                                    <input type="search" class="form-control search-input" placeholder="Buscar por pieza o codigo..." id="txtBuscar">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                            <div class="col-sm-7 text-end">
                                <a class="btn btn-warning" role="button" data-bs-toggle="modal" data-bs-target="#modalPieza">Nueva Pieza</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive" id="divListar">
                        
                    </div>
                    <div class="card-footer">
                        Peso Cantidad = <?=$peso_cant?> Tn. <br>
                        Peso Stock Actual = <?=$peso_stock?> Tn.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalPieza" tabindex="-1" aria-labelledby="modalPiezaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Formulario Pieza</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="frmPieza">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <label for="desc" class="form-label">Descripción de la pieza</label>
                            <input type="text" class="form-control" id="desc" name="desc" value="" maxlength="200">
                            <div id="msj-desc" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="codigo" class="form-label">Código de Pieza</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" value="" maxlength="12">
                            <div id="msj-codigo" class="form-text text-danger"></div>
                        </div>                        
                        <div class="col-sm-6 mb-3">
                            <label for="peso" class="form-label">Peso (kg)</label>
                            <input type="text" class="form-control" id="peso" name="peso" value="" maxlength="8">
                            <div id="msj-peso" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="precio" class="form-label">Precio (s/.)</label>
                            <input type="text" class="form-control" id="precio" name="precio" value="" maxlength="10">
                            <div id="msj-precio" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="text" class="form-control" id="cantidad" name="cantidad" value="" maxlength="6">
                            <div id="msj-cantidad" class="form-text text-danger"></div>
                        </div>
                    </div>
                </div>
                <div id="msj"></div>
                <div class="modal-footer py-2">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-danger" id="btnGuardar">REGISTRAR PIEZA</button>
                    <input type="hidden" class="form-control" id="id_piezae" name="id_piezae">
                </div>
            </form>
        </div>
    </div>
</div>

<?php echo $this->endSection();?>

<?php echo $this->section('scripts');?>

<script>
function listarPiezas(page, cri = '', campo = 'pie_desc', order = 'ASC'){
    $("#divListar").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO DATOS');
    $.post('listar-piezas', {
        page,cri,campo,order
    }, function(data){
        $("#divListar").html(data);
    })
}

listarPiezas(1, cri = '', campo = 'pie_desc', order = 'ASC');

function limpiarCampos(){
    $("#frmPieza")[0].reset();
    $('[id^="msj-"').text("");
    $("#id_piezae").val("");
}

$(function(){
    let timeout;
    $("#txtBuscar").on('input', function(e){
        let cri = $(this).val();
        //console.log(cri);
        clearTimeout(timeout);
        timeout = setTimeout(() => {
        	if( cri.length > 2 ){            
                listarPiezas(1,cri);
            }else if( cri.length == 0 ){
                listarPiezas(1);
            }
      	}, 600);
    });

    $("#frmPieza").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardar'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        $.post('registro-pieza', $(this).serialize(), function(data){
            $('[id^="msj-"').text("");                
            if( data.errors ){  
                let errors = data.errors;
                for( let err in errors ){
                    $('#msj-' + err).text(errors[err]);
                }
            }
            $("#msj").html(data);
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        });
    });

    const myModalEl = document.getElementById('modalPieza')
    myModalEl.addEventListener('hidden.bs.modal', event => {
        $("#btnGuardar").text("REGISTRAR PIEZA");
        limpiarCampos();
        $("#msj").html("");
    })
})
</script>

<?php echo $this->endSection();?>