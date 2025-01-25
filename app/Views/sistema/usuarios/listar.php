<?php
if($usuarios){
    //print_r($usuarios);
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 15px">#</th>
            <th>Usuario</th>
            <th>Dni</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Perfil</th>
            <th style="width: 100px">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $RegistrosAMostrar = 10;//paginacion
        
        $cont = ( $page - 1 ) * $RegistrosAMostrar;
        foreach($usuarios as $u){
            $cont++;
            $id            = $u['idusuario'];
            $dni           = $u['usu_dni'];
            $nombres       = $u['usu_nombres'];
            $apellidos     = $u['usu_apellidos'];
            $usuario       = $u['usu_usuario'];
            $tipo          = $u['tu_tipo'];
            $idtipousuario = $u['idtipousuario'];

            $arr = json_encode(
                [
                    $dni,$nombres,$apellidos,$usuario,$idtipousuario
                ]
            );

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$usuario</td>";
            echo "<td>$dni</td>";
            echo "<td>$nombres</td>";
            echo "<td>$apellidos</td>";
            echo "<td>$tipo</td>";
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

    <div class="pt-3 <?=$totalRegistros <= 3 ? 'd-none' : ''?>">
        <ul class="pagination pagination-sm m-0 float-end">
            <li class="page-item <?=$PagAct > ($PaginasIntervalo + 1) ? '' : 'd-none'?>">
                <a class="page-link" href="javascript:;" onclick="listarUsuarios(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarUsuarios($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='javascript:;' onclick='listarUsuarios($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item <?=$PagAct < ($PagUlt - $PaginasIntervalo) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarUsuarios(<?=$PagUlt?>,'<?=$cri?>')">»</a>
            </li>
        </ul>
    </div>

<?php
}else{
    echo '
        <div class="alert alert-warning" role="alert">
            No se encontraron usuarios
        </div>
    ';
}
?>


<script>
$(".editar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id'),
        arr = $(this).data('arr');
    //console.log(arr);

    $("#usuario").val(arr[3]);
    $("#dni").val(arr[0]);
    $("#nombres").val(arr[1]);
    $("#apellidos").val(arr[2]);
    $("#perfil").val(arr[4]);

    $("#id_usuarioe").val(id);
    $("#btnGuardar").text("MODIFICAR USUARIO");
    $("#modalUsuario").modal('show');
});
$(".eliminar").on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id');
    console.log(id);
});
</script>