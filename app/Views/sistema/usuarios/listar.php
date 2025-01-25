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
        $cont = 0;
        foreach($usuarios as $u){
            $cont++;
            $id        = $u['idusuario'];
            $dni       = $u['usu_dni'];
            $nombres   = $u['usu_nombres'];
            $apellidos = $u['usu_apellidos'];
            $usuario   = $u['usu_usuario'];
            $tipo      = $u['tu_tipo'];

            echo "<tr>";

            echo "<td>$cont</td>";
            echo "<td>$usuario</td>";
            echo "<td>$dni</td>";
            echo "<td>$nombres</td>";
            echo "<td>$apellidos</td>";
            echo "<td>$tipo</td>";
            echo '<td class="d-flex justify-content-center">';
            echo '<a href="#" class="link-success" title="Modificar"><i class="fa-solid fa-pen-to-square"></i></a>';
            echo '<a href="#" class="link-danger ms-2" title="Eliminar"><i class="fa-solid fa-trash"></i></a>';
            echo '</td>';

            echo "</tr>";
        }
        ?>
    </tbody>
</table>

    <?php

    $RegistrosAMostrar = 10;
    $PaginasIntervalo  = 2;
    $PagAct            = $page;

    $PagUlt = $totalRegistros / $RegistrosAMostrar;
    $res    = $totalRegistros % $RegistrosAMostrar;
    if( $res > 0 ) $PagUlt = floor($PagUlt) + 1;
    ?>

    <div class="pt-3 <?=$totalRegistros <= 10 ? 'd-none' : ''?>">
        <ul class="pagination pagination-sm m-0 float-end">
            <li class="page-item <?=$PagAct > ($PaginasIntervalo + 1) ? '' : 'd-none'?>">
                <a class="page-link" href="#" onclick="listarUsuarios(1,'<?=$cri?>')">«</a>
            </li>
            <?php
            for ( $i = ($PagAct - $PaginasIntervalo) ; $i <= ($PagAct - 1) ; $i ++) {
                if($i >= 1) {
                    echo "<li class='page-item'><a class='page-link' href='#' onclick='listarUsuarios($i,\"$cri\")'>$i</a></li>";
                }
            }
            ?>
            <li class="page-item active"><a class="page-link pe-none"><?=$PagAct?></a></li>
            <?php
            for ( $i = ($PagAct + 1) ; $i <= ($PagAct + $PaginasIntervalo) ; $i ++) {
                if( $i <= $PagUlt) {
                    echo "<li class='page-item'><a class='page-link' href='#' onclick='listarUsuarios($i,\"$cri\")'>$i</a></li>";
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