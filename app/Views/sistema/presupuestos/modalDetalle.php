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
                    <li class='list-group-item'><b>Plazo de entrega:</b> <?=$presupuesto['pre_pentrega']?></li>
                    <li class='list-group-item'><b>Forma de pago:</b> <?=$presupuesto['pre_fpago']?></li>
                    <li class='list-group-item'><b>Validez de la oferta:</b> <?=$presupuesto['pre_voferta']?></li>
                    <li class='list-group-item'><b>Lugar de entrega:</b> <?=$presupuesto['pre_lentrega']?></li>
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
                                <th>Peso U.</th>
                                <th>Peso T.</th>
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

                            $c = 0;
                            $sum = 0;
                            $pesoTotalPiezas = 0;
                            foreach($detalle as $d){
                                $c++;
                                $sum += $d['dp_precio'];

                                //para sacar los pesos totales
                                $pesoUnTotal = 0;
                                $pesoTT = 0;
                                $piezasFilter = array_filter($piezas, fn($p) => $p['idtor'] == $d['idtorre']);
                                foreach( $piezasFilter as $pi ){
                                    $pieza_bd = $piezaModel->getPieza($pi['idpie']);
                                    $pie_peso = $pieza_bd['pie_peso'];
                                    $pie_peso_t = $pie_peso * $pi['dtcan'] * $d['dp_cant'];
                                    $pesoUnTotal += $pie_peso;
                                    $pesoTT += $pie_peso_t;
                                }
                                $pesoTotalPiezas += $pesoTT;

                                echo '<tr>';
                                echo '<td>'.$c.'</td>';
                                echo '<td>'.$d['tor_desc'].'</td>';
                                echo '<td>'.$d['dp_cant'].'</td>';
                                echo '<td>'.number_format($pesoUnTotal,2,".","").'</td>';
                                echo '<td>'.number_format($pesoTT,2,".","").'</td>';
                                echo '<td class="text-end">S/. '.number_format(($d['dp_precio'] / $d['dp_cant']),2,".","").'</td>';
                                echo '<td class="text-end">S/. '.number_format($d['dp_precio'],2,".","").'</td>';
                                echo '</tr>';

                                if( $verPiezas == 1 ){//para ver el detalle de piezas
                                    $piezasFilter = array_filter($piezas, fn($p) => $p['idtor'] == $d['idtorre']);
                                    $c2 = 0;
                                    foreach( $piezasFilter as $pi ){
                                        $c2++;
                                        $pieza_bd = $piezaModel->getPieza($pi['idpie']);//solo para sacar los nombres de las piezas, porque los precios de las piezas se obtienen del presupuesto guardado, ya que con esos precios se grabaron
                                        $preciop = $pi['piepre'] * $pi['dtcan'];

                                        $pie_peso = $pieza_bd['pie_peso'];
                                        $pie_peso_t = $pie_peso * $pi['dtcan'] * $d['dp_cant'];

                                        //$subt = number_format(help_calcularPresu($preciop,$periodo,$nroperiodo,$porcpre,$porcsem) * $presupuesto['pre_tcambio'],2,".","");
                                        $tott = help_calcularPresu($preciop,$periodo,$nroperiodo,$porcpre,$porcsem) * $d['dp_cant'] * $presupuesto['pre_tcambio'];

                                        echo "<tr style='color:#666'>";
                                        echo "<td>$c.$c2.</td>";
                                        echo "<td>".$pieza_bd['pie_desc']."</td>";
                                        echo "<td>".$pi['dtcan'] * $d['dp_cant']."</td>";
                                        echo "<td>".$pie_peso."</td>";
                                        echo "<td>".number_format($pie_peso_t,2,".","")."</td>";
                                        echo "<td class='text-end'>".number_format($tott/($pi['dtcan'] * $d['dp_cant']),2,".","")."</td>";                                        
                                        echo "<td class='text-end'>".number_format($tott,2,".","")."</td>";
                                        echo "</tr>";
                                    }                                    
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7">
                                    * Peso total: <?=number_format($pesoTotalPiezas / 1000,2,".","")?> Tn
                                </th>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-end">Total</th>
                                <th class="text-end">S/. <?=number_format($sum,2,".","")?></th>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-end">IGV (18%)</th>
                                <th class="text-end">S/. <?=number_format($sum*0.18,2,".","")?></th>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-end">Total</th>
                                <th class="text-end">S/. <?=number_format($sum+$sum*0.18,2,".","")?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>