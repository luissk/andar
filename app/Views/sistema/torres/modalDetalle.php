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
                <h3 class="card-title">
                    Detalle de Torre 
                    <a href="torre-a-excel-<?=$torre['idtorre']?>" title="Reporte a Excel" class="text-success fs-4" target="_blank"><i class="fa-solid fa-file-excel"></i></a>
                </h3>
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
                                <th>Codigo</th>
                                <th>Pieza</th>
                                <th style="width: 90px;">Cantidad</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $c = 0;
                            $sum = 0;
                            foreach($detalle as $d){
                                $c++;
                                $sum += ($d['dt_cantidad'] * $d['pie_precio']);
                                echo '<tr>';
                                
                                echo '<td>'.$c.'</td>';
                                echo '<td>'.$d['pie_codigo'].'</td>';
                                echo '<td>'.$d['pie_desc'].'</td>';
                                echo '<td>'.$d['dt_cantidad'].'</td>';
                                echo '<td>S/. '.($d['dt_cantidad'] * $d['pie_precio']).'</td>';

                                echo '</tr>';
                            }
                            $igv   = $sum * 0.18;
                            $total = $sum + $igv;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Sub total</th>
                                <th>S/. <?=$sum?></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">IGV</th>
                                <th>S/. <?=number_format($igv,2,".","")?></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th>S/. <?=number_format($total,2,".","")?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>