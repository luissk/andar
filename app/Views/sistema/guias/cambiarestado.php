<?php
$idguia = $guia['idguia'];

$gui_fechaent = $guia['gui_fechaent'];

$fecha = date('Y-m-d');
$opt = "registrar";
$btn = "REGISTRAR FECHA";
if( $gui_fechaent != '' ){
    $fecha = $gui_fechaent;
    $btn = "MODIFICAR FECHA";
}
?>

<div class="row text-start">
    <div class="col-sm-6 pb-2">
        <label for="fechaent">Fecha de Entregado</label>
        <input type="date" class="form-control" name="fechaent" id="fechaent" value="<?=$fecha?>">
    </div>
    <div class="col-sm-6 pb-2 d-flex align-items-end">
        <button class="btn btn-danger" id="btnRegFecha" data-id=<?=$idguia?> ><?=$btn?></button>
    </div>
</div>
<div id="msjcambiar"></div>

<script>
$("#btnRegFecha").on('click', function(e){
    e.preventDefault();
    let btn = document.querySelector('#btnRegFecha'),
        txtbtn = btn.textContent,
        btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    btn.setAttribute('disabled', 'disabled');
    btn.innerHTML = `${btnHTML} PROCESANDO...`;

    let id = $(this).data('id'),
        fechaent = $("#fechaent").val();
    
    if( fechaent == '' ){
        Swal.fire({title: "Seleccione un fecha", icon: "error"});
        btn.removeAttribute('disabled');
        btn.innerHTML = txtbtn;
    }

    Swal.fire({
        title: "¿Vas a definir la fecha de entrega?",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('cambiar-estado', {
                id, opt:'registrar',fechaent
            }, function(data){
                $("#msjcambiar").html(data);
                btn.removeAttribute('disabled');
                btn.innerHTML = txtbtn; 
            });
        }else{
            btn.removeAttribute('disabled');
            btn.innerHTML = txtbtn;
        }
    });
    
})
</script>