<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>

<?php
//print_r($misdatos);
$usuario   = $misdatos['usu_usuario'];
$dni       = $misdatos['usu_dni'];
$nombres   = $misdatos['usu_nombres']." ".$misdatos['usu_apellidos'];
$tipo      = $misdatos['tu_tipo'];
$idusuario = $misdatos['idusuario'];
?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Mis Datos</h4>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <ul class="list-group border-start border-2 border-warning">
                    <li class='list-group-item'>
                        <small class="fw-semibold">Usuario</small>
                        <div><?=$usuario?></div>
                    </li>
                    <li class='list-group-item'>
                        <small class="fw-semibold">Nombres</small>
                        <div><?=$nombres?></div>
                    </li>
                    <li class='list-group-item'>
                        <small class="fw-semibold">DNI</small>
                        <div><?=$dni?></div>
                    </li>
                    <li class='list-group-item'>
                        <small class="fw-semibold">Perfil</small>
                        <div><?=$tipo?></div>
                    </li>
                    <li class='list-group-item'>
                        <small class="fw-semibold">Cambiar Password</small>
                        <div>
                            <form id="frmPass">                            
                                <input type="password" class="form-control" id="password" name="password" value="" maxlength="15">
                                <div id="msj-password" class="form-text text-danger"></div>
                                <button type="submit" class="btn btn-danger" id="btnGuardar">Cambiar Password</button>
                            </form>
                        </div>
                    </li>
                </ul>
                <div id="msj"></div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection();?>


<?php echo $this->section('scripts');?>

<script>$
$(function(){
    $("#frmPass").on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardar'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        $.post('cambiar-password', {password:$("#password").val()}, function(data){
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
})
</script>

<?php echo $this->endSection();?>