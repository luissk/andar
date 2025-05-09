<?php
/* echo "<pre>";
print_r($venta_bd);
print_r($detalle_bd);
echo "</pre>"; */
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-outline card-warning">
            <div class="card-header">                        
                <h3 class="card-title">Datos</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class='list-group-item'><b>Fecha: </b><?=$venta_bd['ven_fecha']?></li>
                    <li class='list-group-item'><b>Nro Doc: </b><?=$venta_bd['ven_nrodoc']?></li>
                    <li class='list-group-item'><b>Cliente: </b><?=$venta_bd['ven_cliente']?></li>
                    <li class='list-group-item'><b>Ruc: </b><?=$venta_bd['ven_ruc']?></li>
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
            </div>
            <div class="card-body table-responsive">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 20px;">#</th>
                            <th>Código</th>
                            <th>Pieza</th>
                            <th>Cantidad</th>
                            <th>Precio Ven</th>
                            <th>Precio Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cont = 0;
                        foreach( $detalle_bd as $d ){
                            $cont++;
                            $codigo   = $d['pie_codigo'];
                            $pieza    = $d['pie_desc'];
                            $cantidad = $d['cantidad'];
                            $preciov  = $d['precioven'];
                            $preciot  = $cantidad * $preciov;

                            echo "<tr>";
                            echo "<td>$cont</td>";
                            echo "<td>$codigo</td>";
                            echo "<td>$pieza</td>";
                            echo "<td>$cantidad</td>";
                            echo "<td>$preciov</td>";
                            echo "<td>$preciot</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>