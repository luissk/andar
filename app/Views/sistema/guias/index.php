<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>



<?php echo $this->endSection();?>


<?php echo $this->section('contenido');?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Módulo Guías de Remisión</h4>
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
                                    <input type="search" class="form-control search-input" placeholder="Buscar por nro o cliente ..." id="txtBuscar">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                            <div class="col-sm-7 text-end">
                                <a class="btn btn-warning" role="button" data-bs-toggle="modal" data-bs-target="#modalBuscaPresu">Nueva Guía</a>
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

<div class="modal fade" id="modalBuscaPresu" tabindex="-1" aria-labelledby="modalBuscaPresuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Busca Presupuesto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="search-container">
                            <input type="search" class="form-control search-input" placeholder="Buscar por nro o cliente ..." id="txtBuscarPresu">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3" id="div_listarPresu">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection();?>


<?php echo $this->section('scripts');?>

<script>
function listarGuias(page, cri = ''){
    $("#divListar").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO DATOS');
    $.post('listar-guias', {
        page,cri
    }, function(data){
        $("#divListar").html(data);
    })
}

listarGuias(1);

function listarPresu(cri = ''){
    $("#div_listarPresu").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO DATOS');
    $.post('listar-presu', {
        cri
    }, function(data){
        $("#div_listarPresu").html(data);
    })
}

$(function(){
    let timeout;
    $("#txtBuscar").on('input', function(e){
        let cri = $(this).val();
        //console.log(cri);
        clearTimeout(timeout);
        timeout = setTimeout(() => {
        	if( cri.length > 2 ){            
                listarGuias(1,cri);
            }else if( cri.length == 0 ){
                listarGuias(1);
            }
      	}, 600);
    });

    let timeoutPresu;
    $("#txtBuscarPresu").on('input', function(e){
        let cri = $(this).val();
        //console.log(cri);
        clearTimeout(timeoutPresu);
        timeoutPresu = setTimeout(() => {
        	if( cri.length > 2 ){            
                listarPresu(cri);
            }else{
                $("#div_listarPresu").html('');
            }
      	}, 600);
    });

});
</script>

<?php echo $this->endSection();?>