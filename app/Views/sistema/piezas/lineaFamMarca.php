<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Lineas Familias y Marcas del Sistema</h4>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="card card-default card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="mantenedoresTab" role="tablist">                
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-lineas" data-bs-toggle="tab"
                                data-bs-target="#pane-lineas" type="button" role="tab">
                        Líneas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-familias" data-bs-toggle="tab"
                                data-bs-target="#pane-familias" type="button" role="tab">
                        Familias
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-marcas" data-bs-toggle="tab"
                                data-bs-target="#pane-marcas" type="button" role="tab">
                        Marcas
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="mantenedoresTabContent">
                    <!-- LÍNEAS -->
                    <div class="tab-pane fade show active" id="pane-lineas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Líneas</h5>
                            <button class="btn btn-primary btn-sm" data-entidad="lineas" id="btnNuevaLinea" role="button" data-bs-toggle="modal" data-bs-target="#modalLinea">
                                + Nueva Línea
                            </button>
                        </div>
                        <table id="tablaLineas" class="table table-bordered table-striped table-sm w-100">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th>Linea</th>
                                    <th>Descripción</th>
                                    <th style="width: 14%;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- FAMILIAS -->
                    <div class="tab-pane fade" id="pane-familias" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Familias</h5>
                            <button class="btn btn-primary btn-sm" data-entidad="familias" id="btnNuevaFamilia" role="button" data-bs-toggle="modal" data-bs-target="#modalFamilia">
                                + Nueva Familia
                            </button>
                        </div>
                        <table id="tablaFamilias" class="table table-bordered table-striped table-sm w-100">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th>Familia</th>
                                    <th>Descripción</th>
                                    <th style="width: 14%;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- MARCAS -->
                    <div class="tab-pane fade" id="pane-marcas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Marcas</h5>
                            <button class="btn btn-primary btn-sm" data-entidad="marcas" id="btnNuevaMarca" role="button" data-bs-toggle="modal" data-bs-target="#modalMarca">
                                + Nueva Marca
                            </button>
                        </div>
                        <table id="tablaMarcas" class="table table-bordered table-striped table-sm w-100">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th>Marca</th>
                                    <th style="width: 14%;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modalLinea" tabindex="-1" aria-labelledby="modalLineaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModalLinea">Formulario Lineas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="frmLinea">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label for="linea" class="form-label">Linea</label>
                            <input type="number" class="form-control" id="linea" name="linea" value="" max="99" min="10">
                            <div id="msj-linea" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-8 mb-3">
                            <label for="desclinea" class="form-label">Descripción de Linea</label>
                            <input type="text" class="form-control" id="desclinea" name="desclinea" value="" maxlength="100">
                            <div id="msj-desclinea" class="form-text text-danger"></div>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="submit" class="btn btn-danger" id="btnGuardarLinea">REGISTRAR LINEA</button>
                    <input type="hidden" class="form-control" id="idlinea_e" name="idlinea_e">
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFamilia" tabindex="-1" aria-labelledby="modalFamiliaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModalFamilia">Formulario Familias</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="frmFamilia">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label for="familia" class="form-label">Familia</label>
                            <input type="number" class="form-control" id="familia" name="familia" value="" max="999" min="100">
                            <div id="msj-familia" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-8 mb-3">
                            <label for="descfamilia" class="form-label">Descripción de Linea</label>
                            <input type="text" class="form-control" id="descfamilia" name="descfamilia" value="" maxlength="200">
                            <div id="msj-descfamilia" class="form-text text-danger"></div>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="submit" class="btn btn-danger" id="btnGuardarFamilia">REGISTRAR FAMILIA</button>
                    <input type="hidden" class="form-control" id="idfamilia_e" name="idfamilia_e">
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMarca" tabindex="-1" aria-labelledby="modalMarcaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModalMarca">Formulario Marcas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="frmMarca">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="texto" class="form-control" id="marca" name="marca" value="" maxlength="50">
                            <div id="msj-marca" class="form-text text-danger"></div>
                        </div>                       
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="submit" class="btn btn-danger" id="btnGuardarMarca">REGISTRAR MARCA</button>
                    <input type="hidden" class="form-control" id="idmarca_e" name="idmarca_e">
                </div>
            </form>
        </div>
    </div>
</div>


<div id="msj"></div>

<?php echo $this->endSection();?>

<?php echo $this->section('scripts');?>

<script>
let tablaLineas, tablaFamilias, tablaMarcas;

$(function(){
    tablaLineas = $('#tablaLineas').DataTable({
        "processing": true, 
        "pageLength": 50, 
        "ajax": {
            "url": "listar-lineas",
            "type": "GET"
        },
        "columns": [
            { "data": null, "render": (data, type, row, meta) => meta.row + 1 }, // Auto-incremento visual
            { "data": "linea" },
            { "data": "descripcion" },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <button class="btn btn-sm text-success" onclick="editarLinea(${row.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm text-danger" onclick="eliminarLinea(${row.id})"><i class="fas fa-trash"></i></button>
                    `;
                }
            }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
    });

    tablaFamilias = $('#tablaFamilias').DataTable({
        "processing": true, 
        "pageLength": 50, 
        "ajax": {
            "url": "listar-familias",
            "type": "GET"
        },
        "columns": [
            { "data": null, "render": (data, type, row, meta) => meta.row + 1 }, // Auto-incremento visual
            { "data": "familia" },
            { "data": "descripcion" },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <button class="btn btn-sm text-success" onclick="editarFamilia(${row.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm text-danger" onclick="eliminarFamilia(${row.id})"><i class="fas fa-trash"></i></button>
                    `;
                }
            }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
    });

    tablaMarcas = $('#tablaMarcas').DataTable({
        "processing": true, 
        "pageLength": 50, 
        "ajax": {
            "url": "listar-marcas",
            "type": "GET"
        },
        "columns": [
            { "data": null, "render": (data, type, row, meta) => meta.row + 1 }, // Auto-incremento visual
            { "data": "marca" },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <button class="btn btn-sm text-success" onclick="editarMarca(${row.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm text-danger" onclick="eliminarMarca(${row.id})"><i class="fas fa-trash"></i></button>
                    `;
                }
            }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
    });

    $("#frmLinea").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardarLinea'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        $.post('registrar-linea', $(this).serialize(), function(data){
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
                limpiarCamposLinea();
                tablaLineas.ajax.reload(null, false);
                $("#modalLinea").modal("hide");
            }
            $("#msj").html(data);
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        });
    });

    const myModalEl_L = document.getElementById('modalLinea')
    myModalEl_L.addEventListener('hidden.bs.modal', event => {
        $('#tituloModalLinea').text('Formulario Linea');
        $("#btnGuardarLinea").text("REGISTRAR LINEA");
        limpiarCamposLinea();
        $("#msj").html("");
    });

    $("#frmFamilia").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardarFamilia'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        $.post('registrar-familia', $(this).serialize(), function(data){
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
                limpiarCamposFamilia();
                tablaFamilias.ajax.reload(null, false);
                $("#modalFamilia").modal("hide");
            }
            $("#msj").html(data);
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        });
    });

    const myModalEl_F = document.getElementById('modalFamilia')
    myModalEl_F.addEventListener('hidden.bs.modal', event => {
        $('#tituloModalFamilia').text('Formulario Familia');
        $("#btnGuardarFamilia").text("REGISTRAR FAMILIA");
        limpiarCamposFamilia();
        $("#msj").html("");
    });

    $("#frmMarca").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardarMarca'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        $.post('registrar-marca', $(this).serialize(), function(data){
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
                limpiarCamposMarca();
                tablaMarcas.ajax.reload(null, false);
                $("#modalMarca").modal("hide");
            }
            $("#msj").html(data);
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        });
    });

    const myModalEl_M = document.getElementById('modalMarca')
    myModalEl_M.addEventListener('hidden.bs.modal', event => {
        $('#tituloModalMarca').text('Formulario Marca');
        $("#btnGuardarMarca").text("REGISTRAR MARCA");
        limpiarCamposMarca();
        $("#msj").html("");
    });

});

function limpiarCamposLinea(){
    $("#frmLinea")[0].reset();
    $('[id^="msj-"').text("");
    $("#idlinea_e").val("");
}

function limpiarCamposFamilia(){
    $("#frmFamilia")[0].reset();
    $('[id^="msj-"').text("");
    $("#idfamilia_e").val("");
}

function limpiarCamposMarca(){
    $("#frmMarca")[0].reset();
    $('[id^="msj-"').text("");
    $("#idmarca_e").val("");
}

function editarLinea(id) {
    // 1. Buscamos la fila en el DataTable usando el ID
    // 'miTabla' es la variable donde inicializaste tu DataTable
    let data = tablaLineas.rows().data().toArray().find(x => x.id == id);

    if (data) {
        // 2. Limpiamos mensajes de error previos (si usas validaciones)
        $('#frmLinea')[0].reset();
        $('.form-text').text(''); 

        // 3. Llenamos los campos del modal con los datos del objeto 'data'
        $('#idlinea_e').val(data.id);
        $('#linea').val(data.linea);
        $('#desclinea').val(data.descripcion);

        // 4. Cambiamos el estilo del modal para "Modo Edición"
        $('#tituloModalLinea').text('Editar Linea');
        $('#btnGuardarLinea').text('ACTUALIZAR LINEA').removeClass('btn-danger').addClass('btn-success');

        // 5. Mostramos el modal
        $('#modalLinea').modal('show');
    } else {
        // Usando SweetAlert2 como me indicaste anteriormente
        Swal.fire('Error', 'No se pudieron recuperar los datos de la linea', 'error');
    }
}

function eliminarLinea(id) {
    Swal.fire({
        title: "¿Vas a eliminar la linea?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-linea', {
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
                    tablaLineas.ajax.reload(null, false);
                }
            });
        }
    });
}

function editarFamilia(id) {
    // 1. Buscamos la fila en el DataTable usando el ID
    // 'miTabla' es la variable donde inicializaste tu DataTable
    let data = tablaFamilias.rows().data().toArray().find(x => x.id == id);

    if (data) {
        // 2. Limpiamos mensajes de error previos (si usas validaciones)
        $('#frmFamilia')[0].reset();
        $('.form-text').text(''); 

        // 3. Llenamos los campos del modal con los datos del objeto 'data'
        $('#idfamilia_e').val(data.id);
        $('#familia').val(data.familia);
        $('#descfamilia').val(data.descripcion);

        // 4. Cambiamos el estilo del modal para "Modo Edición"
        $('#tituloModalFamilia').text('Editar Familia');
        $('#btnGuardarFamilia').text('ACTUALIZAR FAMILIA').removeClass('btn-danger').addClass('btn-success');

        // 5. Mostramos el modal
        $('#modalFamilia').modal('show');
    } else {
        // Usando SweetAlert2 como me indicaste anteriormente
        Swal.fire('Error', 'No se pudieron recuperar los datos de la familia', 'error');
    }
}

function eliminarFamilia(id) {
    Swal.fire({
        title: "¿Vas a eliminar la familia?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-familia', {
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
                    tablaFamilias.ajax.reload(null, false);
                }
            });
        }
    });
}

function editarMarca(id) {
    // 1. Buscamos la fila en el DataTable usando el ID
    // 'miTabla' es la variable donde inicializaste tu DataTable
    let data = tablaMarcas.rows().data().toArray().find(x => x.id == id);

    if (data) {
        // 2. Limpiamos mensajes de error previos (si usas validaciones)
        $('#frmMarca')[0].reset();
        $('.form-text').text(''); 

        // 3. Llenamos los campos del modal con los datos del objeto 'data'
        $('#idmarca_e').val(data.id);
        $('#marca').val(data.marca);

        // 4. Cambiamos el estilo del modal para "Modo Edición"
        $('#tituloModalMarca').text('Editar Marca');
        $('#btnGuardarMarca').text('ACTUALIZAR MARCA').removeClass('btn-danger').addClass('btn-success');

        // 5. Mostramos el modal
        $('#modalMarca').modal('show');
    } else {
        // Usando SweetAlert2 como me indicaste anteriormente
        Swal.fire('Error', 'No se pudieron recuperar los datos de la marca', 'error');
    }
}

function eliminarMarca(id) {
    Swal.fire({
        title: "¿Vas a eliminar la marca?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-marca', {
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
                    tablaMarcas.ajax.reload(null, false);
                }
            });
        }
    });
}

</script>


<?php echo $this->endSection();?>