<?php
if($guias){
    /* echo "<pre>";
    print_r($guias);
    echo "</pre>"; */
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 15px">#</th>            
            <th>Nro Guía</th>
            <!-- <th>Completo</th> -->
            <th>Estado</th>
            <th>F. Traslado</th>
            <!-- <th>F. Entregado</th> -->
            <th>F. Devolución</th>
            <th>Devol. completo</th>
            <th>Cliente</th>
            <th>Dni o Ruc</th>
            <th style="width: 120px">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $RegistrosAMostrar = 10;//paginacion
        
        $cont = ( $page - 1 ) * $RegistrosAMostrar;
        foreach($guias as $g){
            $cont++;
            $id                = $g['idguia'];
            $gui_fecha         = date("d/m/Y h      :  i a", strtotime($g['gui_fecha']));
            $gui_fechatraslado = date("d/m/Y", strtotime($g['gui_fechatraslado']));
            //$gui_fechaent      = $g['gui_fechaent'] != '' ? date("d/m/Y", strtotime($g['gui_fechaent'])) : '';
            $gui_fechadev      = $g['gui_fechadev'] != '' ? date("d/m/Y", strtotime($g['gui_fechadev'])) : '';
            $gui_estdev        = $g['gui_devcompleta'] == 1 ? 'Si': 'No';
            $gui_nro           = $g['gui_nro'];
            $gui_completa      = $g['gui_completa'] == 1 ? 'Si': 'No';
            $cli_dniruc        = $g['cli_dniruc'];
            $cli_nombrerazon   = $g['cli_nombrerazon'];
            $estadoguia        = help_statusPresu($g['gui_status']);

            $stilodev = $g['gui_devcompleta'] == 1 ? '' : 'fw-bolder text-danger';

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$gui_nro</td>";
            //echo "<td>$gui_completa</td>";
            echo "<td>$estadoguia</td>";
            echo "<td>$gui_fechatraslado</td>";
            //echo "<td>$gui_fechaent</td>";
            echo "<td>$gui_fechadev</td>";
            echo "<td class='$stilodev'>$gui_estdev</td>";
            echo "<td>$cli_nombrerazon</td>";
            echo "<td>$cli_dniruc</td>";
            echo '<td class="d-flex justify-content-center">';
            if( $g['gui_status'] == 2 || $g['gui_status'] == 3 ){
                echo '<a href="devolver-'.$id.'" class="link-success" title="Devolver Piezas"><i class="fa-solid fa-right-left"></i></a>';
            }
            echo '</td>';

            echo "</tr>";
        }
        ?>
    </tbody>
</table>

    <?php    
    $PaginasIntervalo  = 2;
    $PagAct            = $page;

    $PagUlt = $totalRegistros / $RegistrosAMostrar;
    $res    = $totalRegistros % $RegistrosAMostrar;
    if( $res > 0 ) $PagUlt = floor($PagUlt) + 1;
    ?>

    <div class="pt-3 <?=$totalRegistros <= 10 ? 'd-none' : ''?>">
        <ul class="pagination pagination-sm m-0 float-end">
            <li class="page-item <?=$PagAct > ($PaginasIntervalo + 1) ? '' : 'd-none'?>">
                <a class="page-link" href="javascript:;" onclick="listarGuiasDevol(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarGuiasDevol($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarGuiasDevol($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarGuiasDevol(<?=$PagUlt?>,'<?=$cri?>')">»</a>
            </li>
        </ul>
    </div>

<?php
}else{
    echo '
        <div class="alert alert-warning" role="alert">
            No se encontraron guías
        </div>
    ';
}
?>
<div id="msjLista"></div>

<script>

</script>