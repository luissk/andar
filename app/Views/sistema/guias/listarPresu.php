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
        $cont = 0;
        foreach($presupuestos as $p){
            $cont++;
            $id              = $p['idpresupuesto'];
            $pre_fechareg    = date("d/m/Y h:i a", strtotime($p['pre_fechareg']));
            $pre_numero      = $p['pre_numero'];
            $cli_nombrerazon = $p['cli_nombrerazon'];

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$pre_fechareg</td>";
            echo "<td>$pre_numero</td>";
            echo "<td>$cli_nombrerazon</td>";
            echo '<td class="d-flex justify-content-center">';
            echo '<a href="nueva-guia-p-'.$id.'" class="link-success ms-2 seleccionar" title="Seleccionar" data-id='.$id.'><i class="fa-solid fa-arrow-pointer"></i></a>';
            echo '<a href="javascript:;" class="link-danger ms-2 detalleP" title="Detalle" data-id='.$id.'><i class="fa-solid fa-search"></i></a>';
            //echo '<a href="javascript:;" class="link-dark ms-2 pdfP" title="Pdf" data-id='.$id.'><i class="fa-regular fa-file-pdf"></i></a>';
            echo '</td>';

            echo "</tr>";
        }
        ?>
    </tbody>
</table>

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

<div class="modal fade" id="modalDetalleP" tabindex="-1" aria-labelledby="modalDetallePLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Detalle de Presupuesto xD</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalle_p">

            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="modalPdfP" tabindex="-1" aria-labelledby="modalPdfPLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Presupuesto PDF</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="pdfP_div">

            </div>
        </div>
    </div>
</div> -->

<script>
$(".detalleP").on('click', function(e){
    let id = $(this).data('id');
    $("#modalDetalleP").modal('show');
    $("#detalle_p").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO...')
    $.post('detalle-presu-modal', {
        id
    }, function(data){
        $('#detalle_p').html(data);
    });
});

/* $('.pdfP').click(function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    $("#modalPdfP").modal('show');
    
    var iframe = $('<iframe width="100%" height="100%">');
    iframe.attr('src','pdf-presupuesto-'+id);
    $('#pdfP_div').html(iframe);
}); */
</script>