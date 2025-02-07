<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>



<?php echo $this->endSection();?>


<?php echo $this->section('contenido');?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Módulo Presupuesto</h4>
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
                                    <input type="search" class="form-control search-input" placeholder="Buscar por nro ..." id="txtBuscar">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                            <div class="col-sm-7 text-end">
                                <a class="btn btn-warning" href="nuevo-presupuesto">Nuevo Presupuesto</a>
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

<?php echo $this->endSection();?>


<?php echo $this->section('scripts');?>

<script>
function listarPresupuestos(page, cri = ''){
    $("#divListar").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO DATOS');
    $.post('listar-presupuestos', {
        page,cri
    }, function(data){
        $("#divListar").html(data);
    })
}

listarPresupuestos(1);

$(function(){
    let timeout;
    $("#txtBuscar").on('input', function(e){
        let cri = $(this).val();
        //console.log(cri);
        clearTimeout(timeout);
        timeout = setTimeout(() => {
        	if( cri.length > 2 ){            
                listarPresupuestos(1,cri);
            }else if( cri.length == 0 ){
                listarPresupuestos(1);
            }
      	}, 600);
    });

});
</script>

<?php echo $this->endSection();?>