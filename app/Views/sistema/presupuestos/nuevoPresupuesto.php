<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('css');?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<?php echo $this->endSection();?>


<?php echo $this->section('contenido');?>

<!-- <div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Realizar Presupuesto</h4>
            </div>
        </div>
    </div>
</div> -->

<div class="app-content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Realizar Presupuesto</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3 mb-3">
                                <label for="nropre" class="form-label">Nro Presupuesto</label>
                                <input type="nropre" class="form-control" id="nropre" name="nropre" value="" maxlength="10">
                                <div id="msj-nropre" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-5 mb-3">
                                <label for="cliente" class="form-label">Buscar Cliente</label>
                                <select class="form-select" name="cliente" id="cliente">
                                </select>
                                <div id="msj-cliente" class="form-text text-danger"></div>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="porcpre" class="form-label">% de Precio</label>
                                <input type="nropre" class="form-control" id="porcpre" name="porcpre" value="" maxlength="10">
                                <div id="msj-porcpre" class="form-text text-danger"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php echo $this->endSection();?>


<?php echo $this->section('scripts');?>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>


<?php echo $this->endSection();?>