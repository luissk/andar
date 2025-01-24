<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="mb-0">Perfiles del Sistema</h4>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <ul class="list-group border-start border-2 border-warning">
                    <?php
                    if( $perfiles ){
                        foreach( $perfiles as $perfil ){
                            echo "<li class='list-group-item'> <i class='fa-regular fa-user'></i> ".$perfil['tu_tipo']."</li>";
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection();?>