<?php
/* echo "<pre>";
print_r($torre);
print_r($detalle);
echo "</pre>"; */
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-outline card-warning">
            <div class="card-header">                        
                <h3 class="card-title">Torre</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class='list-group-item fw-bolder'><?=$torre['tor_desc']?></li>
                    <?php
                    if( $torre['tor_plano'] != ''){
                    ?>
                    <li class='list-group-item'>
                        <?php
                        echo '<a href="public/uploads/planos/'.$torre['tor_plano'].'" target="_blank">ver plano</a>';
                        ?>
                    </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>       
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-outline card-warning">
            <div class="card-header">                        
                <h3 class="card-title">Detalle de Torre</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>
                                <th>Pieza</th>
                                <th style="width: 90px;">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $c = 0;
                            foreach($detalle as $d){
                                $c++;
                                echo '<tr>';

                                echo '<td>'.$c.'</td>';
                                echo '<td>'.$d['pie_desc'].'</td>';
                                echo '<td>'.$d['dt_cantidad'].'</td>';

                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>