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
                                    <input type="search" class="form-control search-input" placeholder="Buscar por ..." id="txtBuscar">
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>


<?php echo $this->endSection();?>