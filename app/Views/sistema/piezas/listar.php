<?php
if($piezas){
    //print_r($piezas);
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 15px">#</th>
            <th>Código</th>
            <th>Descripción de la pieza</th>
            <th>Peso</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Stock Act.</th>
            <th style="width: 100px">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $presuModel = model('PresupuestoModel');

        $RegistrosAMostrar = 10;//paginacion
        
        $cont = ( $page - 1 ) * $RegistrosAMostrar;
        foreach($piezas as $p){
            $cont++;
            $id       = $p['idpieza'];
            $codigo   = $p['pie_codigo'];
            $desc     = $p['pie_desc'];
            $peso     = $p['pie_peso'];
            $precio   = $p['pie_precio'];
            $cantidad = $p['pie_cant'];

            $nroEntregados = $presuModel->getStockPieza($id, $estadoPresu = [4], 1);
            $nroSalidas    = $presuModel->getStockPieza($id, $estadoPresu = [2,3,4]);
            $stockAct      = ($cantidad + $nroEntregados - $nroSalidas) <= 0 ? 0 : ($cantidad + $nroEntregados - $nroSalidas);

            $arr = json_encode(
                [
                    $codigo,$desc,$peso,$precio,$cantidad
                ]
            );

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$codigo</td>";
            echo "<td>$desc</td>";
            echo "<td>$peso</td>";
            echo "<td>$precio</td>";
            echo "<td>$cantidad</td>";
            echo "<td>$stockAct</td>";
            echo '<td class="d-flex justify-content-center">';
            echo '<a href="javascript:;" class="link-success editar" title="Modificar" data-id='.$id.' data-arr=\''.$arr.'\'><i class="fa-solid fa-pen-to-square"></i></a>';
            echo '<a href="javascript:;" class="link-danger ms-2 eliminar" title="Eliminar" data-id='.$id.'><i class="fa-solid fa-trash"></i></a>';
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
                <a class="page-link" href="javascript:;" onclick="listarPiezas(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarPiezas($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarPiezas($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarPiezas(<?=$PagUlt?>,'<?=$cri?>')">»</a>
            </li>
        </ul>
    </div>

<?php
}else{
    echo '
        <div class="alert alert-warning" role="alert">
            No se encontraron piezas
        </div>
    ';
}
?>
<div id="msjLista"></div>

<script>
$(".editar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id'),
        arr = $(this).data('arr');
    //console.log(arr);
    
    $("#codigo").val(arr[0]);
    $("#desc").val(arr[1]);
    $("#peso").val(arr[2]);
    $("#precio").val(arr[3]);
    $("#cantidad").val(arr[4]);

    $("#id_piezae").val(id);
    $("#btnGuardar").text("MODIFICAR PIEZA");
    $("#modalPieza").modal('show');
});
$(".eliminar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id');
    
    Swal.fire({
        title: "¿Vas a eliminar la pieza?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-pieza', {
                id
            }, function(data){
                //console.log(data);
                $('#msjLista').html(data);
            });
        }
    });
});
</script>