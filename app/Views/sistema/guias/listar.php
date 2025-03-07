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

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$gui_nro</td>";
            //echo "<td>$gui_completa</td>";
            echo "<td>$estadoguia</td>";
            echo "<td>$gui_fechatraslado</td>";
            //echo "<td>$gui_fechaent</td>";
            echo "<td>$gui_fechadev</td>";
            echo "<td>$gui_estdev</td>";
            echo "<td>$cli_nombrerazon</td>";
            echo "<td>$cli_dniruc</td>";
            echo '<td class="d-flex justify-content-center">';
            if( $g['gui_status'] == 2 ){
                echo '<a href="editar-guia-g-'.$id.'" class="link-success" title="Modificar"><i class="fa-solid fa-pen-to-square"></i></a>';
                echo '<a href="javascript:;" class="link-danger ms-2 eliminar" title="Eliminar" data-id='.$id.'><i class="fa-solid fa-trash"></i></a>';
            }
            echo '<a href="javascript:;" class="link-dark ms-2 pdf" title="Guía Cliente" data-id='.$id.' data-opt=1><i class="fa-regular fa-file-pdf"></i></a>';
            if( $g['gui_completa'] != 1 ){
                echo '<a href="javascript:;" class="link-dark ms-2 pdf text-danger" title="Guía almacén" data-id='.$id.' data-opt=0><i class="fa-regular fa-file-pdf"></i></a>';    
            }
            //echo '<a href="javascript:;" class="link-dark ms-2 estado" title="Cambiar Estado" data-id='.$id.'><i class="fa-solid fa-list-check"></i></a>';
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
                <a class="page-link" href="javascript:;" onclick="listarGuias(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarGuias($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarGuias($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarGuias(<?=$PagUlt?>,'<?=$cri?>')">»</a>
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

<div class="modal fade" id="modalPdfGuia" tabindex="-1" aria-labelledby="modalPdfGuiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Guía PDF</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="pdf_div">

            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="modalEstado" tabindex="-1" aria-labelledby="modalEstadoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Cambiar a Entregado</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="estado_div">

            </div>
        </div>
    </div>
</div> -->

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

/* $(".detalle").on('click', function(e){
    let id = $(this).data('id');
    $("#modalDetallePresu").modal('show');
    $("#detalle_presu").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO...')
    $.post('detalle-presu-modal', {
        id
    }, function(data){
        $('#detalle_presu').html(data);
    });
}); */

$('.pdf').click(function(e) {
    e.preventDefault();
    let id = $(this).data('id'),
        opt = $(this).data('opt');
    $("#modalPdfGuia").modal('show');
    
    var iframe = $('<iframe width="100%" height="100%">');
    iframe.attr('src','pdf-guia-'+id+'-'+opt);
    $('#pdf_div').html(iframe);
});

$(".eliminar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id');
    
    Swal.fire({
        title: "¿Vas a eliminar la guía, el presupuesto cambiará de estado?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-guia', {
                id
            }, function(data){
                //console.log(data);
                $('#msjLista').html(data);
            });
        }
    });
});

/* $('.estado').click(function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    $("#modalEstado").modal('show');
    $("#estado_div").html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> PROCESANDO...`);
    $.post('cambiar-estado', {id}, function(data){
        $("#estado_div").html(data);
    });
}); */
</script>