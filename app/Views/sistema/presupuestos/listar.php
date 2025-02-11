<?php
if($presupuestos){
    //print_r($presupuestos);
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 15px">#</th>
            <th>Fecha</th>
            <th>Nro</th>
            <th>Cliente</th>
            <th style="width: 120px">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $RegistrosAMostrar = 10;//paginacion
        
        $cont = ( $page - 1 ) * $RegistrosAMostrar;
        foreach($presupuestos as $p){
            $cont++;
            $id              = $p['idpresupuesto'];
            $pre_fechareg    = date("d/m/Y h:i a", strtotime($p['pre_fechareg']));
            $pre_numero      = $p['pre_numero'];
            $cli_nombrerazon = $p['cli_nombrerazon'];

            /* $modeloTorre = model('TorreModel');
            $detalle = $modeloTorre->getDetalleTorre($id);
            $arr_dt = json_encode($detalle);

            $arr = json_encode(
                [
                    $desc,$plano
                ]
            ); */

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$pre_fechareg</td>";
            echo "<td>$pre_numero</td>";
            echo "<td>$cli_nombrerazon</td>";
            echo '<td class="d-flex justify-content-center">';
            echo '<a href="editar-presupuesto-'.$id.'" class="link-success" title="Modificar"><i class="fa-solid fa-pen-to-square"></i></a>';
            echo '<a href="javascript:;" class="link-danger ms-2 eliminar" title="Eliminar" data-id='.$id.'><i class="fa-solid fa-trash"></i></a>';
            echo '<a href="javascript:;" class="link-danger ms-2 detalle" title="Detalle" data-id='.$id.'><i class="fa-solid fa-search"></i></a>';
            echo '<a href="javascript:;" class="link-dark ms-2 pdf" title="Pdf" data-id='.$id.'><i class="fa-regular fa-file-pdf"></i></a>';
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
                <a class="page-link" href="javascript:;" onclick="listarPresupuestos(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarPresupuestos($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarPresupuestos($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarPresupuestos(<?=$PagUlt?>,'<?=$cri?>')">»</a>
            </li>
        </ul>
    </div>

<?php
}else{
    echo '
        <div class="alert alert-warning" role="alert">
            No se encontraron presupuestos
        </div>
    ';
}
?>
<div id="msjLista"></div>

<div class="modal fade" id="modalDetallePresu" tabindex="-1" aria-labelledby="modalDetallePresuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Detalle de Presupuesto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalle_presu">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPdfPresu" tabindex="-1" aria-labelledby="modalPdfPresuLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Presupuesto PDF</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="pdf_div">

            </div>
        </div>
    </div>
</div>

<script>
$(".eliminar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id');
    
    Swal.fire({
        title: "¿Vas a eliminar la torre?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-torre', {
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
    $("#modalDetallePresu").modal('show');
    $("#detalle_presu").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO...')
    $.post('detalle-presu-modal', {
        id
    }, function(data){
        $('#detalle_presu').html(data);
    });
});

$('.pdf').click(function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    $("#modalPdfPresu").modal('show');
    
    var iframe = $('<iframe width="100%" height="600px">');
    iframe.attr('src','pdf-presupuesto-'+id);
    $('#pdf_div').html(iframe);
});
</script>