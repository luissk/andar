<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Clientes del Sistema</h4>
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
                                    <input type="search" class="form-control search-input" placeholder="Buscar por nombres o dni..." id="txtBuscar">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                            <div class="col-sm-7 text-end">
                                <a class="btn btn-warning" role="button" data-bs-toggle="modal" data-bs-target="#modalCliente">Nuevo Cliente</a>
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

<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Formulario Cliente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="frmCliente">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="dniruc" class="form-label">DNI o RUC</label>
                            <input type="text" class="form-control" id="dniruc" name="dniruc" value="" maxlength="11">
                            <div id="msj-dniruc" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="telefc" class="form-label">Teléfono de contacto</label>
                            <input type="text" class="form-control" id="telefc" name="telefc" value="" maxlength="12">
                            <div id="msj-telefc" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label for="nombrer" class="form-label">Nombre o Razón</label>
                            <input type="text" class="form-control" id="nombrer" name="nombrer" value="" maxlength="100">
                            <div id="msj-nombrer" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label for="nombrec" class="form-label">Nombre de contacto</label>
                            <input type="text" class="form-control" id="nombrec" name="nombrec" value="" maxlength="100">
                            <div id="msj-nombrec" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label for="correoc" class="form-label">Correo de contacto</label>
                            <input type="email" class="form-control" id="correoc" name="correoc" value="" maxlength="100">
                            <div id="msj-correoc" class="form-text text-danger"></div>
                        </div>
                          
                    </div>
                </div>
                <div id="msj"></div>
                <div class="modal-footer py-2">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-danger" id="btnGuardar">REGISTRAR CLIENTE</button>
                    <input type="hidden" class="form-control" id="id_clientee" name="id_clientee">
                </div>
            </form>
        </div>
    </div>
</div>

<?php echo $this->endSection();?>


<?php echo $this->section('scripts');?>

<script>
function listarClientes(page, cri = ''){
    $("#divListar").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO DATOS');
    $.post('listar-clientes', {
        page,cri
    }, function(data){
        $("#divListar").html(data);
    })
}

listarClientes(1);

function limpiarCampos(){
    $("#frmCliente")[0].reset();
    $('[id^="msj-"').text("");
    $("#id_clientee").val("");
}

$(function(){
    let timeout;
    $("#txtBuscar").on('input', function(e){
        let cri = $(this).val();
        //console.log(cri);
        clearTimeout(timeout);
        timeout = setTimeout(() => {
        	if( cri.length > 2 ){            
                listarClientes(1,cri);
            }else if( cri.length == 0 ){
                listarClientes(1);
            }
      	}, 600);
    });

    $("#frmCliente").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardar'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        $.post('registro-cliente', $(this).serialize(), function(data){
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

    const myModalEl = document.getElementById('modalCliente')
    myModalEl.addEventListener('hidden.bs.modal', event => {
        $("#btnGuardar").text("REGISTRAR CLIENTE");
        limpiarCampos();
        $("#msj").html("");
    })
})
</script>

<?php echo $this->endSection();?>