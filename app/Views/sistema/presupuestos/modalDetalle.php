<?php
/* echo "<pre>";
print_r($presupuesto);
print_r($detalle);
echo "</pre>"; */
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-outline card-warning">
            <div class="card-header">                        
                <h3 class="card-title">Presupesto <?=$presupuesto['pre_numero']?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class='list-group-item'><b>Cliente: </b><?=$presupuesto['cli_nombrerazon']?> --- <b>Ruc o Dni</b>: <?=$presupuesto['cli_dniruc']?></li>
                    <li class='list-group-item'><b>Fecha: </b><?=date("d/m/Y h:i a", strtotime($presupuesto['pre_fechareg']))?></li>
                    <?php
                    $periodo = '';
                    if( $presupuesto['pre_periodo'] == 'd' ) $periodo = 'Día';
                    if( $presupuesto['pre_periodo'] == 's' ) $periodo = 'Semana(s)';
                    if( $presupuesto['pre_periodo'] == 'm' ) $periodo = 'Mes(es)';
                    ?>
                    <li class='list-group-item'><b>Periodo: </b><?=$presupuesto['pre_periodonro']." ".$periodo?></li>
                    <li class='list-group-item'><b>% Precio:</b> <?=$presupuesto['pre_porcenprecio']?></li>
                    <li class='list-group-item'><b>% Semanal:</b> <?=$presupuesto['pre_porcsem']?></li>
                </ul>
            </div>       
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-outline card-warning">
            <div class="card-header">                        
                <h3 class="card-title">Detalle</h3>
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
                                <th>Torre</th>
                                <th style="width: 90px;">Cantidad</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $c = 0;
                            $sum = 0;
                            foreach($detalle as $d){
                                $c++;
                                $sum += $d['dp_precio'];
                                echo '<tr>';

                                echo '<td>'.$c.'</td>';
                                echo '<td>'.$d['tor_desc'].'</td>';
                                echo '<td>'.$d['dp_cant'].'</td>';
                                echo '<td class="text-end">S/. '.number_format($d['dp_precio'],2,".",",").'</td>';

                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end">S/. <?=number_format($sum,2,".",",")?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>