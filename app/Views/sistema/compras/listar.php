<?php
if($compras){
    //print_r($torres);
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 15px">#</th>
            <th>Fecha</th>
            <th>Nro Doc</th>
            <th>Proveedor</th>
            <th>Ruc</th>
            <th style="width: 100px">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $RegistrosAMostrar = 40;//paginacion
        
        $cont = ( $page - 1 ) * $RegistrosAMostrar;
        foreach($compras as $c){
            $cont++;
            $id        = $c['idcompra'];
            $fecha     = $c['com_fecha'];
            $nrodoc    = $c['com_nrodoc'];
            $proveedor = $c['com_proveedor'];
            $ruc       = $c['com_ruc'];

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$fecha</td>";
            echo "<td>$nrodoc</td>";
            echo "<td>$proveedor</td>";
            echo "<td>$ruc</td>";
            echo '<td class="d-flex justify-content-center">';
            echo '<a href="editar-compra-'.$id.'" class="link-success editar"><i class="fa-solid fa-pen-to-square"></i></a>';
            echo '<a href="javascript:;" class="link-danger ms-2 eliminar" title="Eliminar" data-id='.$id.'><i class="fa-solid fa-trash"></i></a>';
            echo '<a href="javascript:;" class="link-danger ms-2 detalle" title="Detalle" data-id='.$id.'><i class="fa-solid fa-search"></i></a>';
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

    <div class="pt-3 <?=$totalRegistros <= 40 ? 'd-none' : ''?>">
        <ul class="pagination pagination-sm m-0 float-end">
            <li class="page-item <?=$PagAct > ($PaginasIntervalo + 1) ? '' : 'd-none'?>">
                <a class="page-link" href="javascript:;" onclick="listarCompras(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarCompras($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarCompras($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarCompras(<?=$PagUlt?>,'<?=$cri?>')">»</a>
            </li>
        </ul>
    </div>

<?php
}else{
    echo '
        <div class="alert alert-warning" role="alert">
            No se encontraron compras
        </div>
    ';
}
?>
<div id="msjLista"></div>

<div class="modal fade" id="modalDetalleCompra" tabindex="-1" aria-labelledby="modalDetalleCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Detalle de Compra</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalle_compra">

            </div>
        </div>
    </div>
</div>

<script>
$(".eliminar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id');
    
    Swal.fire({
        title: "¿Vas a eliminar la compra?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-compra', {
                id
            }, function(data){
                //console.log(data);
                $('#msjLista').html(data);
            });
        }
    });
});


$(".detalle").on('click', function(e){
    let id = $(this).data('id');
    $("#modalDetalleCompra").modal('show');
    $("#detalle_compra").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO...')
    $.post('detalle-compra-modal', {
        id
    }, function(data){
        $('#detalle_compra').html(data);
    });
});
</script>