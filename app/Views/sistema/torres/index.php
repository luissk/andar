<?php echo $this->extend('plantilla/layout')?>

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
            <div class="card card-warning card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="torres-tab" data-bs-toggle="tab" data-bs-target="#torres-tab-pane" type="button" role="tab" aria-controls="torres-tab-pane" aria-selected="true">Torres</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="piezas-tab" data-bs-toggle="tab" data-bs-target="#piezas-tab-pane" type="button" role="tab" aria-controls="piezas-tab-pane" aria-selected="false">Piezas</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="torres-tab-pane" role="tabpanel" aria-labelledby="torres-tab" tabindex="0">
                                TORRES
                            </div>
                            <div class="tab-pane fade" id="piezas-tab-pane" role="tabpanel" aria-labelledby="piezas-tab" tabindex="">
                                PIEZAS
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                    </div>
            </div>
        </div>
    </div>
</div>




<?php echo $this->endSection();?>