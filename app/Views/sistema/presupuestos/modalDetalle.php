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
                <h3 class="card-title">Presupuesto <?=$presupuesto['pre_numero']?></h3>
                <!-- <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div> -->
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
                <!-- <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div> -->
            </div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>
                                <th>Torre</th>
                                <th style="width: 70px;">Cant.</th>
                                <th>Precio U.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $piezaModel = model('PiezaModel');

                            $periodo    = $presupuesto['pre_periodo'];
                            $nroperiodo = $presupuesto['pre_periodonro'];
                            $porcpre    = $presupuesto['pre_porcenprecio'];
                            $porcsem    = $presupuesto['pre_porcsem'];
                            $verPiezas  = $presupuesto['pre_verpiezas'];
                            $piezas     = json_decode($presupuesto['pre_piezas'], true);
                            /* echo "<pre>";
                            print_r($piezas);
                            echo "</pre>"; */

                            /* $fil = array_filter($piezas, fn($p) => $p['idtor'] == 6 );
                            $acu = array_reduce($fil, fn($acc, $p) => $acc + $p['piepre'] * $p['dtcan']); */

                            $c = 0;
                            $sum = 0;
                            foreach($detalle as $d){
                                /* $fil = array_filter($piezas, fn($p) => $p['idtor'] == $d['idtorre'] );
                                $acu = array_reduce($fil, fn($acc, $p) => $acc + $p['piepre'] * $p['dtcan']);
                                echo number_format(help_calcularPresu($acu,$periodo,$nroperiodo,$porcpre,$porcsem),2,".","")."<br>";
                                echo number_format(help_calcularPresu($acu,$periodo,$nroperiodo,$porcpre,$porcsem) * $d['dp_cant'] ,2,".","")."<br>"; */
                                $c++;
                                $sum += $d['dp_precio'];

                                echo '<tr>';
                                echo '<td>'.$c.'</td>';
                                echo '<td>'.$d['tor_desc'].'</td>';
                                echo '<td>'.$d['dp_cant'].'</td>';
                                echo '<td class="text-end">S/. '.number_format(($d['dp_precio'] / $d['dp_cant']),2,".","").'</td>';
                                echo '<td class="text-end">S/. '.number_format($d['dp_precio'],2,".","").'</td>';
                                echo '</tr>';

                                if( $verPiezas == 1 ){
                                    $piezasFilter = array_filter($piezas, fn($p) => $p['idtor'] == $d['idtorre']);
                                    $c2 = 0;
                                    foreach( $piezasFilter as $pi ){
                                        $c2++;
                                        $pieza_bd = $piezaModel->getPieza($pi['idpie']);//solo para sacar los nombres de las piezas, porque los precios de las piezas se obtienen del presupuesto guardado, ya que con esos precios se grabaron
                                        $preciop = $pi['piepre'] * $pi['dtcan'];

                                        echo "<tr style='color:#666'>";
                                        echo "<td>$c.$c2</td>";
                                        echo "<td>".$pieza_bd['pie_desc']."</td>";
                                        echo "<td>".$pi['dtcan']."</td>";
                                        echo "<td class='text-end'>".number_format(help_calcularPresu($preciop,$periodo,$nroperiodo,$porcpre,$porcsem),2,".","")."</td>";
                                        echo "<td class='text-end'>".number_format(help_calcularPresu($preciop,$periodo,$nroperiodo,$porcpre,$porcsem) * $d['dp_cant'],2,".","")."</td>";
                                        echo "</tr>";
                                    }                                    
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th class="text-end">S/. <?=number_format($sum,2,".","")?></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">IGV (18%)</th>
                                <th class="text-end">S/. <?=number_format($sum*0.18,2,".","")?></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th class="text-end">S/. <?=number_format($sum+$sum*0.18,2,".","")?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>