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
                <h4 class="mb-0">Módulo Torres</h4>
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
                            <label for="plano" class="form-label">Plano de torre</label>
                            <input type="file" class="form-control" id="plano" name="plano">
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
                                    <!-- <tr>
                                        <td>1</td>
                                        <td>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quos nulla saepe sequi, cupiditate obcaecati voluptatem quas velit natus quo accusantium possimus eum sapiente itaque provident corrupti tenetur autem cumque aut.</td>
                                        <td>
                                            <input type="text" class="form-control">
                                        </td>
                                    </tr> -->
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
    let tr = document.createElement('tr'),
        filahtml = '';
    
    let cont = 0;
    for(let i of items){
        cont++;
        filahtml = `
            <td id="${i.id}">
                ${cont}
            </td>
            <td>${i.text}</td>
            <td><input type="text" value="${i.cant}" id="c${i.id}" class="form-control form-control-sm numerosindecimal"></td>
            <td class='text-center'><a onclick="eliminarItem(${i.id})"><i class='fas fa-trash-alt'></i></a></td>
        `;
    }
    tr.innerHTML = filahtml;
    fila.appendChild(tr);

    $(".numerosindecimal").on("keypress keyup blur",function (event) {    
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
}

function eliminarItem(id){
    let indice = items.findIndex(x => x.id == id);
    items.splice(indice, 1);
    $('table td[id='+id+']').parent().remove();
}

$(function(){
    $( '#piezas' ).select2( {
        theme: 'bootstrap-5',
        dropdownParent: $("#modalTorre"),
        //minimumInputLength: 2,
        //minimumResultsForSearch: 10,
        ajax: {
            //type: "POST",
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
                //console.log(data);
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

        item = {
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
        //console.log(items);
        
    });


    $("#btnSalida").on('click', function(e){
        e.preventDefault();
        
        let btn = document.querySelector('#btnSalida'),
                txtbtn = btn.textContent,
                btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btn.setAttribute('disabled', 'disabled');
            btn.innerHTML = `${btnHTML} Guardando`;
        
        let fechareg = $("#fechareg").val(),
            documento = $("#documento").val(),
            comentario = $("#comentario").val(),
            area = $("#area").val();
        
        if(fechareg == ''){
            swal_alert('Alerta', 'Seleccione una fecha', 'info', 'Aceptar');
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        }else if(documento.trim() == ''){
            swal_alert('Alerta', 'Ingrese un documento', 'info', 'Aceptar');
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        }else if(comentario.trim() == ''){
            swal_alert('Alerta', 'Ingrese un comentario', 'info', 'Aceptar');
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        }else if(area == ''){
            swal_alert('Alerta', 'Seleccione una área', 'info', 'Aceptar');
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        }else if(items.length == 0){
            swal_alert('Alerta', 'Productos sin agregar', 'info', 'Aceptar');
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        }else{
            //agregando cantidades
            for(let i of items){
                let cantidad = $("#c"+i.idproducto).val();
                if(cantidad <= 0){
                    swal_alert('Atención', `Cantidad inválida del producto ${i.codigo}`, 'info', 'Aceptar');
                    btn.removeAttribute('disabled');
                    btn.innerHTML = txtbtn;
                    return;
                }            
                if(cantidad > i.stock){
                    swal_alert('Atención', `Cantidad sobrepasa al stock del producto ${i.codigo}`, 'info', 'Aceptar');
                    btn.removeAttribute('disabled');
                    btn.innerHTML = txtbtn;
                    return;
                }
                i.cantidad = cantidad;
            }
            //console.log(items);

            let formData = new FormData;
            formData.append('fechareg', fechareg);
            formData.append('documento', documento);
            formData.append('comentario', comentario);
            formData.append('area', area);
            formData.append('items', JSON.stringify(items));

            let objConfirm = {
                title: 'REGISTRAR SALIDA',
                text: "¿Vas a registrar la salida?",
                icon: 'warning',
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                funcion: function(){
                    $.ajax({
                        beforeSend: function(){
                            //         
                        },
                        url: 'salida/saveSalida',
                        type:"POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(data){
                            if(data == 1){
                                btn.setAttribute('disabled', 'disabled');
                                alert('SALIDA EXITOSA..!')
                                location.reload();
                            }else{
                                swal_alert('Atención', data, 'warning', 'Aceptar');
                            }                 
                        }
                    });
                }
            }            
            swal_confirm(objConfirm);
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        }    
    });
});
</script>

<?php echo $this->endSection();?>