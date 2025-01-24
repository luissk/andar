<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>

<?php //print_r($parametro) 
$porc_bd = ($parametro) ? $parametro['par_porcensem'] : '';
$dire_bd = ($parametro) ? $parametro['par_direcc'] : '';
$tele_bd = ($parametro) ? $parametro['par_telef'] : '';
$corr_bd = ($parametro) ? $parametro['par_correo'] : '';
?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Parámetros del Sistema</h4>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Modificar Parámetros</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <form id="frmParametros">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3 mb-3">
                                    <label for="porcentaje" class="form-label">Porcentaje Semanal (%)</label>
                                    <input type="text" class="form-control" id="porcentaje" name="porcentaje" value="<?=$porc_bd?>">
                                    <div id="msj-porcentaje" class="form-text text-danger"></div>
                                </div>
                                <div class="col-sm-9 mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?=$dire_bd?>">
                                    <div id="msj-direccion" class="form-text text-danger"></div>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?=$tele_bd?>">
                                    <div id="msj-telefono" class="form-text text-danger"></div>
                                </div>
                                <div class="col-sm-9 mb-3">
                                    <label for="correo" class="form-label">Correo</label>
                                    <input type="email" class="form-control" id="correo" name="correo" value="<?=$corr_bd?>">
                                    <div id="msj-correo" class="form-text text-danger"></div>
                                </div>                       
                                <div class="col-sm-9 mb-3">
                                    <label for="logo" class="form-label">logo</label>
                                    <input type="file" class="form-control" id="logo" name="logo">
                                    <div id="msj-logo" class="form-text text-danger"></div>
                                </div>
                                <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                    <?php
                                    if( $parametro && $parametro['par_logo'] != '' ){
                                        $ruta_logo = 'public/images/logo/'.$parametro['par_logo'];
                                        echo "<img src='$ruta_logo?v=".time()."' class='rounded img-fluid' alt='logo' />";
                                        echo "<a data-id=".$parametro['idparametros']." data-opt='logo' href='javascript:;' class='text-danger eliminaImagen' title='Eliminar Logo'><i class='fa-solid fa-trash'></i></a>";
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-9 mb-3">
                                    <label for="firma" class="form-label">Firma</label>
                                    <input type="file" class="form-control" id="firma" name="firma">
                                    <div id="msj-firma" class="form-text text-danger"></div>
                                </div>
                                <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                <?php
                                    if( $parametro && $parametro['par_firma'] != '' ){
                                        $ruta_firma = 'public/images/firma/'.$parametro['par_firma'];
                                        echo "<img src='$ruta_firma?v=".time()."' class='rounded img-fluid' alt='firma' />";
                                        echo "<a data-id=".$parametro['idparametros']." data-opt='firma' href='javascript:;' class='text-danger eliminaImagen' title='Eliminar Firma'><i class='fa-solid fa-trash'></i></a>";
                                    }
                                    ?>
                                </div>
                            </div>                      
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning" id="btnGuardar">GUARDAR</button>
                        </div>
                        <div class="progress mt-2 d-none">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                        </div>                       
                    </form>
                </div>
                <div id="msj"></div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection();?>


<?php echo $this->section('scripts');?>

<script>
$(function(){
    $('#frmParametros').on('submit', function(e){
        e.preventDefault();
        let btn = document.querySelector('#btnGuardar'),
            txtbtn = btn.textContent,
            btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = `${btnHTML} PROCESANDO...`;

        let formData = new FormData(this);
        
        let progress = $('.progress'), 
            progress_bar = $('.progress-bar');

        progress.removeClass('d-none');

        $.ajax({
            method: 'POST',
            url: 'modificar-parametros',
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
            },
            xhr: function() {
                var xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        //console.log(percentComplete);
                        progress_bar.width(percentComplete + "%").text(percentComplete + "%");

                        if (percentComplete === 100) {
                            progress.addClass('d-none');
                        }
                    }
                }, false);

                return xhr;
            }

        });
    });


    $('#logo, #firma').on('change', function(){
        let tipos = ['image/jpeg','image/jpg'];
        let file = this.files[0];
        let tipofile = file.type;
        let sizefile = file.size;

        if(!tipos.includes(tipofile)){
            Swal.fire({
                text: "LA IMAGEN DEBE ESTAR EN FORMATO JPG",
                icon: "info"
            });
            $(this).val('');
            return false;
        }
        if(sizefile >= 2097152){
            Swal.fire({
                text: "LA IMAGEN NO DEBE SER MAYOR A 2MB",
                icon: "info"
            });
            $(this).val('');
            return false;
        }
    });

    $(".eliminaImagen").on('click', function(e){
        let id = $(this).data('id'),
            opt = $(this).data('opt');

        $.post('eliminar-imagen', {
            id, opt
        }, function(data){
            $('#msj').html(data);
        });
    });
});   
</script>

<?php echo $this->endSection();?>