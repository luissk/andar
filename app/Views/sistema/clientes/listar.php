<?php
if($clientes){
    //print_r($clientes);
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 15px">#</th>
            <th>Dni o Ruc</th>
            <th>Nombre o Razón</th>
            <th>Nombre contacto</th>
            <th>Correo contacto</th>
            <th>Telefono</th>
            <th style="width: 100px">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $RegistrosAMostrar = 40;//paginacion
        
        $cont = ( $page - 1 ) * $RegistrosAMostrar;
        foreach($clientes as $c){
            $cont++;
            $id      = $c['idcliente'];
            $dni     = $c['cli_dniruc'];
            $nombrer = $c['cli_nombrerazon'];
            $nombrec = $c['cli_nombrecontact'];
            $correoc = $c['cli_correocontact'];
            $telefc  = $c['cli_telefcontact'];

            $arr = json_encode(
                [
                    $dni,$nombrer,$nombrec,$correoc,$telefc
                ]
            );

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$dni</td>";
            echo "<td>$nombrer</td>";
            echo "<td>$nombrec</td>";
            echo "<td>$correoc</td>";
            echo "<td>$telefc</td>";
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
                <a class="page-link" href="javascript:;" onclick="listarClientes(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarClientes($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarClientes($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarClientes(<?=$PagUlt?>,'<?=$cri?>')">»</a>
            </li>
        </ul>
    </div>

<?php
}else{
    echo '
        <div class="alert alert-warning" role="alert">
            No se encontraron clientes
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

    $("#dniruc").val(arr[0]);
    $("#nombrer").val(arr[1]);
    $("#nombrec").val(arr[2]);
    $("#correoc").val(arr[3]);
    $("#telefc").val(arr[4]);


    $("#id_clientee").val(id);
    $("#btnGuardar").text("MODIFICAR CLIENTE");
    $("#modalCliente").modal('show');
});
$(".eliminar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id');
    
    Swal.fire({
        title: "¿Vas a eliminar al cliente?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('eliminar-cliente', {
                id
            }, function(data){
                //console.log(data);
                $('#msjLista').html(data);
            });
        }
    });
});
</script>