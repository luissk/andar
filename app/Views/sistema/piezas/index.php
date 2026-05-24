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
                                <a href="piezas-a-excel" title="Reporte a Excel" class="text-success fs-4" target="_blank"><i class="fa-solid fa-file-excel"></i></a> REHACER
                            </div>
                            <div class="col-sm-7 text-end">
                                <a class="btn btn-warning" role="button" data-bs-toggle="modal" data-bs-target="#modalPieza">Nueva Pieza</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive" id="divListar">
                        <table class="table table-bordered w-100" id="tblPiezas">
                            <thead>
                                <tr>
                                    <th style="width: 15px">#</th>
                                    <th>Código</th>
                                    <th>Descripción de la pieza</i></th>
                                    <th>Peso</th>
                                    <th>Precio</th>
                                    <th>Stock Ini. </th>
                                    <th>Stock Act.</th>
                                    <th>Stock Alqui.</th>
                                    <th style="width: 100px">Opciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div id="pesos" class="alert alert-info mt-3">
                            <div class="row text-center">
                                <div class="col-md-6">
                                    <strong>Peso Cantidad:</strong> <span id="pesoInicial">0.00</span> Tn
                                </div>
                                <div class="col-md-6">
                                    <strong>Peso Stock (Actual):</strong> <span id="pesoActual">0.00</span> Tn
                                </div>
                            </div>
                        </div>
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
                    <button type="submit" class="btn btn-danger" id="btnGuardar">REGISTRAR PIEZA</button>
                    <input type="hidden" class="form-control" id="id_piezae" name="id_piezae">
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
    miTabla = $('#tblPiezas').DataTable({
        "processing": true, // Muestra el mensaje de "Procesando"
        "pageLength": 50, // <--- Carga 25 por defecto
        "ajax": {
            "url": "listar-piezas",
            "type": "GET"
        },
        "columns": [
            { "data": null, "render": (data, type, row, meta) => meta.row + 1 }, // Auto-incremento visual
            { "data": "codigo" },
            { "data": "descripcion" },
            { "data": "peso" },
            { "data": "precio" },
            { "data": "inicial" },
            { 
                "data": "stock_actual",
                "render": function(data, type, row) {
                    // Si el stock actual es 0, lo pintamos de rojo para alertar
                    let color = (data <= 0) ? 'text-danger fw-bold' : '';
                    return `<span class="${color}">${data}</span>`;
                }
            },
            { "data": "alquilado" },
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
        // ESTO ES CLAVE: Filtra lo que el usuario escribe en el buscador
        "initComplete": function() {
            /* let api = this.api();
            $('.dataTables_filter input').off('.DT').on('keyup.DT', function() {
                api.search(quitarAcentos(this.value)).draw();
            }); */
        },
        "drawCallback": function(settings) {
            // 1. Obtenemos la API de DataTables
            let api = this.api();
            // 2. Obtener todos los datos que están visibles (o filtrados): 'apllied', sino 'none'
            let datos = api.rows({ search: 'none' }).data();            
            let totalPesoInicial = 0;
            let totalPesoActual = 0;
            // 3. Recorrer los registros y sumar (Peso * Cantidad)
            datos.each(function(row) {
                let pesoUnidad = parseFloat(row.peso) || 0;
                let cantInicial = parseInt(row.cantidad) || 0;
                let cantActual  = parseInt(row.stock_act) || 0;

                totalPesoInicial += (pesoUnidad * cantInicial);
                totalPesoActual  += (pesoUnidad * cantActual);
            });
            // Convertimos a toneladas
            let toneladasInicial = totalPesoInicial / 1000;
            let toneladasActual  = totalPesoActual / 1000;
            // 4. Mostrar los resultados formateados a 2 decimales
            $('#pesoInicial').text(toneladasInicial.toFixed(2));
            $('#pesoActual').text(toneladasActual.toFixed(2));
        },
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
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
        $('#tituloModal').text('Formulario Pieza');
        $("#btnGuardar").text("REGISTRAR PIEZA");
        limpiarCampos();
        $("#msj").html("");
    });
});

function limpiarCampos(){
    $("#frmPieza")[0].reset();
    $('[id^="msj-"').text("");
    $("#id_piezae").val("");
}

function editar(id) {
    // 1. Buscamos la fila en el DataTable usando el ID
    // 'miTabla' es la variable donde inicializaste tu DataTable
    let data = miTabla.rows().data().toArray().find(x => x.id == id);

    if (data) {
        // 2. Limpiamos mensajes de error previos (si usas validaciones)
        $('#frmPieza')[0].reset();
        $('.form-text').text(''); 

        // 3. Llenamos los campos del modal con los datos del objeto 'data'
        $('#id_piezae').val(data.id);
        $('#desc').val(data.descripcion);
        $('#codigo').val(data.codigo);
        $('#peso').val(data.peso);
        $('#precio').val(data.precio);
        $('#cantidad').val(data.cantidad);

        // 4. Cambiamos el estilo del modal para "Modo Edición"
        $('#tituloModal').text('Editar Pieza');
        $('#btnGuardar').text('ACTUALIZAR PIEZA').removeClass('btn-danger').addClass('btn-success');

        // 5. Mostramos el modal
        $('#modalPieza').modal('show');
    } else {
        // Usando SweetAlert2 como me indicaste anteriormente
        Swal.fire('Error', 'No se pudieron recuperar los datos de la pieza', 'error');
    }
}

function eliminar(id) {
    Swal.fire({
        title: "¿Vas a eliminar la pieza?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-pieza', {
                id
            }, function(data){
                //console.log(data);
                $('#divMsj').html(data);
            });
        }
    });
}
</script>

<?php echo $this->endSection();?>