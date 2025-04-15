<?php
if($transportistas){
    //print_r($transportistas);
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 15px">#</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Dni</th>
            <th>Teléfono</th>
            <th style="width: 100px">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $RegistrosAMostrar = 40;//paginacion
        
        $cont = ( $page - 1 ) * $RegistrosAMostrar;
        foreach($transportistas as $t){
            $cont++;
            $id        = $t['idtransportista'];
            $dni       = $t['tra_dni'];
            $nombres   = $t['tra_nombres'];
            $apellidos = $t['tra_apellidos'];
            $telefono  = $t['tra_telef'];

            $arr = json_encode(
                [
                    $dni,$nombres,$apellidos,$telefono
                ]
            );

            echo "<tr>";

            echo "<td>$cont</td>";           
            echo "<td>$nombres</td>";
            echo "<td>$apellidos</td>";
            echo "<td>$dni</td>";
            echo "<td>$telefono</td>";
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

    <div class="pt-3 <?=$totalRegistros <= 40 ? 'd-none' : ''?>">
        <ul class="pagination pagination-sm m-0 float-end">
            <li class="page-item <?=$PagAct > ($PaginasIntervalo + 1) ? '' : 'd-none'?>">
                <a class="page-link" href="javascript:;" onclick="listarTransportistas(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarTransportistas($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarTransportistas($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarTransportistas(<?=$PagUlt?>,'<?=$cri?>')">»</a>
            </li>
        </ul>
    </div>

<?php
}else{
    echo '
        <div class="alert alert-warning" role="alert">
            No se encontraron transportistas
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

    $("#dni").val(arr[0]);
    $("#nombres").val(arr[1]);
    $("#apellidos").val(arr[2]);
    $("#telefono").val(arr[3]);

    $("#id_transe").val(id);
    $("#btnGuardar").text("MODIFICAR TRANSPORTISTA");
    $("#modalTransportista").modal('show');
});
$(".eliminar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id');
    
    Swal.fire({
        title: "¿Vas a eliminar al transportista?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-transportista', {
                id
            }, function(data){
                //console.log(data);
                $('#msjLista').html(data);
            });
        }
    });
});
</script>