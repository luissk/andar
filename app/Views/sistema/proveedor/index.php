<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Proveedores del Sistema</h4>
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
                            <div class="col-sm-12 text-end">
                                <a class="btn btn-warning" role="button" data-bs-toggle="modal" data-bs-target="#modalProveedor">Nuevo Proveedor</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive" id="divListar">
                        <table class="table table-bordered w-100" id="tblProveedores">
                            <thead>
                                <tr>
                                    <th style="width: 15px">#</th>
                                    <th>Ruc</th>
                                    <th>Razón</i></th>
                                    <th style="width: 100px">Opciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProveedor" tabindex="-1" aria-labelledby="modalProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Formulario Proveedor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="frmProveedor">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label for="ruc" class="form-label">RUC</label>
                            <input type="text" class="form-control" id="ruc" name="ruc" value="" maxlength="11">
                            <div id="msj-ruc" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-8 mb-3">
                            <label for="razon" class="form-label">Razón</label>
                            <input type="text" class="form-control" id="razon" name="razon" value="" maxlength="150">
                            <div id="msj-razon" class="form-text text-danger"></div>
                        </div>                        
                    </div>
                </div>
                <div id="msj"></div>
                <div class="modal-footer py-2">
                    <button type="submit" class="btn btn-danger" id="btnGuardar">REGISTRAR PROVEEDOR</button>
                    <input type="hidden" class="form-control" id="idproveedor_e" name="idproveedor_e">
                </div>
            </form>
        </div>
    </div>
</div>


<div id="divMsj"></div>

<?php echo $this->endSection();?>

<?php echo $this->section('scripts');?>

<script>
let miTabla;
$(function(){
    miTabla = $('#tblProveedores').DataTable({
        "processing": true, // Muestra el mensaje de "Procesando"
        "pageLength": 50, // <--- Carga 25 por defecto
        "ajax": {
            "url": "listar-proveedores",
            "type": "GET"
        },
        "columns": [
            { "data": null, "render": (data, type, row, meta) => meta.row + 1 }, // Auto-incremento visual
            { "data": "pro_ruc" },
            { "data": "pro_razon" },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <button class="btn btn-sm text-success" onclick="editar(${row.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm text-danger" onclick="eliminar(${row.id})"><i class="fas fa-trash"></i></button>
                    `;
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
    });

    $("#frmProveedor").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardar'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        $.post('registro-proveedor', $(this).serialize(), function(data){
            $('[id^="msj-"').text("");                
            if( data.status === 'error' ){  
                let errors = data.errors;
                for( let err in errors ){
                    $('#msj-' + err).text(errors[err]);
                }
            }else if( data.status === 'ok' ){
                //console.log(data.message);
                Swal.fire({
                    title: data.message,
                    text: "",
                    icon: "success",
                    showConfirmButton: true,
                });
                limpiarCampos();
                miTabla.ajax.reload(null, false);
                $("#modalProveedor").modal("hide");
            }
            $("#msj").html(data);
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        });
    });

    const myModalEl = document.getElementById('modalProveedor')
    myModalEl.addEventListener('hidden.bs.modal', event => {
        $('#tituloModal').text('Formulario Proveedor');
        $("#btnGuardar").text("REGISTRAR PROVEEDOR");
        limpiarCampos();
        $("#msj").html("");
    });

});

function limpiarCampos(){
    $("#frmProveedor")[0].reset();
    $('[id^="msj-"').text("");
    $("#idproveedor_e").val("");
}

function editar(id) {
    // 1. Buscamos la fila en el DataTable usando el ID
    // 'miTabla' es la variable donde inicializaste tu DataTable
    let data = miTabla.rows().data().toArray().find(x => x.id == id);

    if (data) {
        // 2. Limpiamos mensajes de error previos (si usas validaciones)
        $('#frmProveedor')[0].reset();
        $('.form-text').text(''); 

        // 3. Llenamos los campos del modal con los datos del objeto 'data'
        $('#idproveedor_e').val(data.id);
        $('#razon').val(data.pro_razon);
        $('#ruc').val(data.pro_ruc);

        // 4. Cambiamos el estilo del modal para "Modo Edición"
        $('#tituloModal').text('Editar Proveedor');
        $('#btnGuardar').text('ACTUALIZAR PROVEEDOR').removeClass('btn-danger').addClass('btn-success');

        // 5. Mostramos el modal
        $('#modalProveedor').modal('show');
    } else {
        // Usando SweetAlert2 como me indicaste anteriormente
        Swal.fire('Error', 'No se pudieron recuperar los datos del proveedor', 'error');
    }
}

function eliminar(id) {
    Swal.fire({
        title: "¿Vas a eliminar al proveedor?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-proveedor', {
                id
            }, function(data){
                //console.log(data);
                //$('#divMsj').html(data);
                if( data.status === 'error' ){
                    Swal.fire('Error', data.message, 'error');
                }else{
                    Swal.fire({
                        title: data.message,
                        text: "",
                        icon: "success",
                        showConfirmButton: true,
                    });
                    miTabla.ajax.reload(null, false);
                }
            });
        }
    });
}
</script>


<?php echo $this->endSection();?>