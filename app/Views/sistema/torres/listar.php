<?php
if($torres){
    //print_r($torres);
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 15px">#</th>
            <th>Descripción de torre</th>
            <th style="width: 100px">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $RegistrosAMostrar = 10;//paginacion
        
        $cont = ( $page - 1 ) * $RegistrosAMostrar;
        foreach($torres as $t){
            $cont++;
            $id            = $t['idtorre'];
            $desc           = $t['tor_desc'];
            $plano       = $t['tor_plano'];

            $modeloTorre = model('TorreModel');
            $detalle = $modeloTorre->getDetalleTorre($id);
            $arr_dt = json_encode($detalle);

            $arr = json_encode(
                [
                    $desc,$plano
                ]
            );

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$desc</td>";
            echo '<td class="d-flex justify-content-center">';
            echo '<a href="javascript:;" class="link-success editar" title="Modificar" data-id='.$id.' data-arr=\''.$arr.'\' data-arrd=\''.$arr_dt.'\'><i class="fa-solid fa-pen-to-square"></i></a>';
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

    <div class="pt-3 <?=$totalRegistros <= 10 ? 'd-none' : ''?>">
        <ul class="pagination pagination-sm m-0 float-end">
            <li class="page-item <?=$PagAct > ($PaginasIntervalo + 1) ? '' : 'd-none'?>">
                <a class="page-link" href="javascript:;" onclick="listarTorres(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarTorres($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarTorres($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarTorres(<?=$PagUlt?>,'<?=$cri?>')">»</a>
            </li>
        </ul>
    </div>

<?php
}else{
    echo '
        <div class="alert alert-warning" role="alert">
            No se encontraron torres
        </div>
    ';
}
?>
<div id="msjLista"></div>

<div class="modal fade" id="modalDetalleTorre" tabindex="-1" aria-labelledby="modalDetalleTorreLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="tituloModal">Detalle de Torre</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalle_torre">

            </div>
        </div>
    </div>
</div>

<script>
$(".editar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id'),
        arr = $(this).data('arr'),
        arrd = $(this).data('arrd');
    //console.log(arr);

    for( let i of arrd ){
        let item = {
            id:i.idpieza,
            text:i.pie_desc,
            cant:i.dt_cantidad
        }
        items.push(item);//items fue declarado en el index.php
    }
    dibujaFilas();

    //plano
    if( arr[1] != '' ){
        let html = `<a href="javascript:;" class="eliminarPlano" data-id=${id}>(eliminar plano)</a>`;
        $("#divplano").html(html);
    }
    //fin plano

    $("#desc").val(arr[0]);

    $("#id_torree").val(id);
    $("#btnGuardar").text("MODIFICAR TORRE");
    $("#modalTorre").modal('show');
});

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

$("#modalTorre").on("click",".eliminarPlano", function(e){
    let id = $(this).data('id');
    let cri = $("#txtBuscar").val();
    Swal.fire({
        title: "¿Vas a eliminar el plano?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-plano', {
                id,cri
            }, function(data){
                //console.log(data);
                $('#msjLista').html(data);
            });
        }
    });
})

$(".detalle").on('click', function(e){
    let id = $(this).data('id');
    $("#modalDetalleTorre").modal('show');
    $("#detalle_torre").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> CARGANDO...')
    $.post('detalle-torre-modal', {
        id
    }, function(data){
        $('#detalle_torre').html(data);
    });
});
</script>