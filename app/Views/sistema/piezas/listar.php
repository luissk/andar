<?php
if($piezas){
    //print_r($piezas);
    //echo "$campo - $order";
    $class_order_desc = $campo == 'pie_desc' && $order    == 'ASC' ? 'up': 'down';
    $class_order_prec = $campo == 'pie_precio' && $order  == 'DESC' ? 'down': 'up';
    $class_order_cant = $campo == 'pie_cant' && $order    == 'DESC' ? 'down': 'up';
    $class_order_stoc = $campo == 'stockActual' && $order == 'DESC' ? 'down': 'up';
?>
<div class="row">
    <div class="col-sm-12">
        <a href="piezas-a-excel" title="Reporte a Excel" class="text-success fs-4" target="_blank"><i class="fa-solid fa-file-excel"></i></a>
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 15px">#</th>
            <th>Código</th>
            <th>Descripción de la pieza <a href="javascript:void(0);" class="btn-link link-dark ordenar" data-opt="pie_desc"><i class="fa-solid fa-caret-<?=$class_order_desc?>"></a></i></th>
            <th>Peso</th>
            <th>Precio <a href="javascript:void(0);" class="btn-link link-dark ordenar" data-opt="pie_precio"><i class="fa-solid fa-caret-<?=$class_order_prec?>"></a></th>
            <th>Cantidad <a href="javascript:void(0);" class="btn-link link-dark ordenar" data-opt="pie_cant"><i class="fa-solid fa-caret-<?=$class_order_cant?>"></a></th>
            <th>Stock Act. <a href="javascript:void(0);" class="btn-link link-dark ordenar" data-opt="stockActual"><i class="fa-solid fa-caret-<?=$class_order_stoc?>"></a></th>
            <th style="width: 100px">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //$presuModel = model('PresupuestoModel');

        $RegistrosAMostrar = 50;//paginacion
        
        $cont = ( $page - 1 ) * $RegistrosAMostrar;
        foreach($piezas as $p){
            $cont++;
            $id       = $p['idpieza'];
            $codigo   = $p['pie_codigo'];
            $desc     = $p['pie_desc'];
            $peso     = $p['pie_peso'];
            $precio   = $p['pie_precio'];
            $cantidad = $p['pie_cant'];

            /* $nroEntregados = $presuModel->getStockPieza($id, $estadoPresu = [3], 'e');
            $nroSalidas    = $presuModel->getStockPieza($id, $estadoPresu = [2,3], 's');
            $stockAct      = ($cantidad + $nroEntregados - $nroSalidas) <= 0 ? 0 : ($cantidad + $nroEntregados - $nroSalidas); */
            $stockAct = $p['stockActual'];

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

    <div class="d-flex align-items-center justify-content-between mt-2">
        <div class="">
            <p class="fw-bolder">Total de Piezas: <?=$totalRegistros;?></p>
        </div>
        <div class="<?=$totalRegistros <= 50 ? 'd-none' : ''?>">
            <ul class="pagination pagination-sm m-0 float-end">
                <li class="page-item <?=$PagAct > ($PaginasIntervalo + 1) ? '' : 'd-none'?>">
                    <a class="page-link" href="javascript:;" onclick="listarPiezas(1,'<?=$cri?>','<?=$campo?>','<?=$order?>')">«</a>
                </li>
                <?php
                for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                    if($i >= 1) {
                        echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarPiezas($i,\"$cri\",\"$campo\",\"$order\")'>$i</a></li>";
                    }
                }
                ?>
                <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
                <?php
                for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                    if( $i <= $PagUlt) {
                        echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarPiezas($i,\"$cri\",\"$campo\",\"$order\")'>$i</a></li>";
                    }
                }
                ?>
                <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                    <a class="page-link" href="#" onclick="listarPiezas(<?=$PagUlt?>,'<?=$cri?>','<?=$campo?>','<?=$order?>')">»</a>
                </li>
            </ul>
        </div>
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

$(".ordenar").on('click', function(e){
    let opt = $(this).data('opt');
    let order = '';
    if( $(this).children().hasClass('fa-caret-down') ){
        $(this).children().removeClass('fa-caret-down')
        $(this).children().addClass('fa-caret-up');
        order = 'ASC';
    }else{
        $(this).children().removeClass('fa-caret-up')
        $(this).children().addClass('fa-caret-down');
        order = 'DESC';
    }
    listarPiezas(1, '<?=$cri?>', opt, order);

    console.log(opt, order)
});
</script>