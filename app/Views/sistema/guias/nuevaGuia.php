<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<?php echo $this->endSection();?>

<?php echo $this->section('contenido');?>
<?php
if( isset($presu_bd) && $presu_bd ){
    $titulo   = "Modificar";
    $btnTexto = "MODIFICAR GUIA";
}else{
    $titulo   = "Realizar";
    $btnTexto = "MODIFICAR GUIA";
}
?>

<div class="app-content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><?=$titulo?> Guía</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="cbody">
                        <?php
                        echo "<pre>";
                        print_r($presupuesto);
                        print_r($detalle_guia);
                        print_r($transportitas);
                        echo "</pre>";
                        ?>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php echo $this->endSection();?>