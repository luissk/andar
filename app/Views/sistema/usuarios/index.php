<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Usuarios del Sistema</h4>
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
                                    <input type="text" class="form-control search-input" placeholder="Buscar por usuario" id="txtBuscar">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                            <div class="col-sm-7 text-end">
                                <a class="btn btn-warning" role="button" data-bs-toggle="modal" data-bs-target="#modalUsuario">Nuevo Usuario</a>
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

<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Formulario Usuario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="frmUsuario">
                <div class="modal-body">            
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" value="">
                            <div id="msj-usuario" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" value="">
                            <div id="msj-dni" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" value="">
                            <div id="msj-nombres" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" value="">
                            <div id="msj-apellidos" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="perfil" class="form-label">Perfil</label>
                            <select class="form-select" name="tipo" id="perfil">
                                <option value="">Seleccione</option>
                                <?php
                                foreach( $perfiles as $perfil ){
                                    echo "<option value=".$perfil['idtipousuario'].">".$perfil['tu_tipo']."</option>";
                                }
                                ?>
                            </select>
                            <div id="msj-perfil" class="form-text text-danger"></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="">
                            <div id="msj-password" class="form-text text-danger"></div>
                        </div>
                    </div>
                </div>
                <div id="msj"></div>
                <div class="modal-footer py-2">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-danger" id="btnGuardar">REGISTRAR USUARIO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php echo $this->endSection();?>


<?php echo $this->section('scripts');?>

<script>
function listarUsuarios(page, cri = ''){
    $("#divListar").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO DATOS');
    $.post('listar-usuarios', {
        page,cri
    }, function(data){
        $("#divListar").html(data);
    })
}

listarUsuarios(1);

$(function(){
    $("#txtBuscar").on('input', function(e){
        let cri = $(this).val();
        console.log(cri);
        if( cri.length > 2 ){            
            listarUsuarios(1,cri);
        }else if( cri.length == 0 ){
            listarUsuarios(1);
        }
    });

    $("#frmUsuario").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardar'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        $.post('registro-usuario', $(this).serialize(), function(data){
            $("#msj").html(data);
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        });
    });
})
</script>

<?php echo $this->endSection();?>