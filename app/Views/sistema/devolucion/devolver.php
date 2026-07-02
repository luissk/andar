<?php echo $this->extend('plantilla/layout')?>

<?php echo $this->section('contenido');?>
<?php
/* echo "<pre>";
print_r($detalle_piezas_obra);
echo "</pre>"; */
?>
<div class="app-content pt-3">
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header bg-dark text-white font-weight-bold d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-invoice text-warning"></i> Operación: Retorno de Materiales desde Obra</span>
                <span class="badge bg-secondary fs-6">Guía de Salida: <?= $guia_cabecera['gui_nro']; ?></span>
            </div>
            <div class="card-body bg-light-subtle">
                <div class="row small font-weight-bold text-secondary">
                    <div class="col-md-4">
                        <i class="fas fa-calendar-alt"></i> Despachado el: <span class="text-dark"><?= date('d/m/Y', strtotime($guia_cabecera['gui_fecha'])); ?></span>
                    </div>
                    <div class="col-md-8 text-md-end">
                        <i class="fas fa-clipboard-list"></i> Código Interno Presupuesto Origen: <span class="text-dark"><?= $idpresupuesto_bd; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white font-weight-bold">
                <i class="fas fa-boxes-stacked"></i> Control y Balance de Piezas en Proyecto
            </div>
            <div class="card-body">
                <form id="formDevolucion" autocomplete="off">
                    <input type="hidden" name="idguia" value="<?= $idguia_bd; ?>">
                    <input type="hidden" name="idpresupuesto" value="<?= $idpresupuesto_bd; ?>">

                    <div class="row mb-3 justify-content-end">
                        <div class="col-md-4 col-lg-3">
                            <label for="fecha_devolucion" class="form-label small font-weight-bold text-dark">
                                <i class="fas fa-calendar-day text-primary"></i> Fecha Real de Retorno:
                            </label>
                            <input type="date" 
                                id="fecha_devolucion" 
                                name="fecha_devolucion" 
                                class="form-control form-control-sm font-weight-bold" 
                                value="<?= date('Y-m-d'); ?>" 
                                max="<?= date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" id="tablaDevoluciones">
                            <thead class="table-light text-center small text-uppercase font-weight-bold">
                                <tr>
                                    <th style="width: 8%;">ID Pieza</th>
                                    <th style="width: 25%;">Descripción del Material</th>
                                    <th style="width: 10%;">Despachado</th>
                                    <th style="width: 12%;">Saldo en Obra</th>
                                    <th style="width: 35%;">Registrar Reingreso (Hoy)</th>
                                    <th style="width: 10%;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($detalle_piezas_obra as $value) { 
                                    $idpieza = $value['idpieza'];
                                    
                                    // 1. Calculamos el límite del Stock Propio que queda libre en obra
                                    $saldo_propio_max = intval($value['cant_enviada_propio']) - intval($value['cant_devuelta_propio']);
                                    $saldo_total_obra = $saldo_propio_max; 
                                ?>
                                <tr class="fila-pieza-maestra" data-idpieza="<?= $idpieza; ?>">
                                    <td class="text-center font-weight-bold text-secondary bg-light-subtle"><?= $idpieza; ?></td>
                                    <td>
                                        <span class="d-block font-weight-bold text-dark fs-6"><?= $value['pieza_nombre']; ?></span>
                                    </td>
                                    <td class="text-center bg-light font-weight-bold text-muted">
                                        <?= $value['cantidad_total_enviada']; ?>
                                    </td>
                                    <td class="text-center table-primary font-weight-bold">
                                        <span class="badge bg-primary fs-6 dynamic-saldo-badge" id="saldo_total_<?= $idpieza; ?>"></span>
                                    </td>
                                    <td>
                                        <div class="p-2 border rounded bg-light-subtle shadow-sm">
                                            
                                            <?php if ($value['cant_enviada_propio'] > 0): ?>
                                            <div class="row align-items-center g-2 mb-2 border-bottom pb-2">
                                                <div class="col-6 small font-weight-bold text-success text-truncate">
                                                    <i class="fas fa-warehouse"></i> Stock Propio:
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" 
                                                            name="piezas[<?= $idpieza; ?>][propio]" 
                                                            class="form-control text-center input-devolucion input-propio font-weight-bold text-success" 
                                                            min="0" 
                                                            max="<?= $saldo_propio_max; ?>" 
                                                            placeholder="Máx: <?= $saldo_propio_max; ?>"
                                                            data-idpieza="<?= $idpieza; ?>">
                                                        <span class="input-group-text bg-white small text-muted">Und</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-1 dynamic-note-container d-none" id="note_container_propio_<?= $idpieza; ?>">
                                                    <input type="text" 
                                                           name="piezas[<?= $idpieza; ?>][propio_observacion]" 
                                                           class="form-control form-control-sm border-warning bg-warning-subtle text-dark" 
                                                           placeholder="✍️ Nota/Observación del estado de la pieza propia..." style="font-size: 0.8rem;">
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <?php if (!empty($value['externos'])): ?>
                                                <?php foreach ($value['externos'] as $index => $ext): 
                                                    $saldo_ext_max = intval($ext['cant_enviada_ext']) - intval($ext['cant_devuelta_ext']);
                                                    $saldo_total_obra += $saldo_ext_max; // Acumulación real del total flotante
                                                ?>
                                                <div class="row align-items-center g-2 mb-1 border-bottom pb-2">
                                                    <div class="col-6 small font-weight-bold text-warning-emphasis text-truncate" title="<?= $ext['pro_razon']; ?>">
                                                        <i class="fas fa-truck-moving text-warning"></i> <?= $ext['pro_razon']; ?>:
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="input-group input-group-sm">
                                                            <input type="hidden" name="piezas[<?= $idpieza; ?>][externo][<?= $index; ?>][id_proveedor]" value="<?= $ext['idproveedor']; ?>">
                                                            <input type="number" 
                                                                name="piezas[<?= $idpieza; ?>][externo][<?= $index; ?>][cantidad]" 
                                                                class="form-control text-center input-devolucion input-externo font-weight-bold text-warning-emphasis" 
                                                                min="0" 
                                                                max="<?= $saldo_ext_max; ?>" 
                                                                placeholder="Máx: <?= $saldo_ext_max; ?>"
                                                                data-idpieza="<?= $idpieza; ?>"
                                                                data-indexext="<?= $index; ?>">
                                                            <span class="input-group-text bg-white small text-muted">Und</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-1 dynamic-note-container d-none" id="note_container_ext_<?= $idpieza; ?>_<?= $index; ?>">
                                                        <input type="text" 
                                                               name="piezas[<?= $idpieza; ?>][externo][<?= $index; ?>][observacion]" 
                                                               class="form-control form-control-sm border-warning bg-warning-subtle text-dark" 
                                                               placeholder="✍️ Nota/Observación para este proveedor..." style="font-size: 0.8rem;">
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-secondary btn-sm font-weight-bold px-3 btn-historial" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse_hist_<?= $idpieza; ?>" 
                                                aria-expanded="false">
                                            <i class="fas fa-history"></i> Historial
                                        </button>
                                    </td>
                                </tr>

                                <tr class="p-0 border-0 bg-light-subtle">
                                    <td colspan="6" class="p-0 border-0">
                                        <div class="collapse" id="collapse_hist_<?= $idpieza; ?>">
                                            <div class="p-3 border-start border-end border-bottom bg-white mx-2 my-1 rounded shadow-sm">
                                                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                                                    <h6 class="font-weight-bold text-secondary mb-0">
                                                        <i class="fas fa-clock text-info"></i> Trazabilidad de Retornos de la Pieza
                                                    </h6>
                                                    <small class="text-muted">Tabla: <code>guia_devolucion_detalle</code></small>
                                                </div>
                                                
                                                <?php if (!empty($value['historial_devoluciones'])): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped table-bordered mb-0 text-center align-middle" style="font-size: 0.85rem;">
                                                        <thead class="table-secondary font-weight-bold small">
                                                            <tr>
                                                                <th>Fecha / Ticket de Reingreso</th>
                                                                <th>Destino Asignado / Estado</th>
                                                                <th>Cantidad Recibida</th>
                                                                <th style="width: 15%;">Descargas / Acción</th> 
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($value['historial_devoluciones'] as $hist): ?>
                                                            <tr>
                                                                <td class="font-weight-bold text-dark">
                                                                    <div><?= date('d/m/Y h:i A', strtotime($hist['gdd_fecha'])); ?></div>
                                                                    <?php if(!empty($hist['gdc_numero_ticket'])): ?>
                                                                        <span class="badge bg-dark text-warning font-weight-bold mt-1" style="font-size: 0.75rem;"><i class="fas fa-ticket-alt"></i> <?= $hist['gdc_numero_ticket']; ?></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-start ps-3">
                                                                    <?php if ($hist['dp_origen'] == 'propio'): ?>
                                                                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-warehouse"></i> Almacén de Empresa</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2 py-1"><i class="fas fa-truck"></i> Devuelto a: <?= $hist['pro_razon']; ?></span>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if(!empty($hist['gdd_observacion'])): ?>
                                                                        <div class="mt-1 small text-danger font-weight-bold bg-danger-subtle p-1 rounded border border-danger-subtle" style="font-size: 0.78rem;">
                                                                            <i class="fas fa-exclamation-triangle"></i> Obs: <?= $hist['gdd_observacion']; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="font-weight-bold text-primary bg-white fs-6">
                                                                    <?= $hist['cantidad_devuelta']; ?> Unidades
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group" role="group">
                                                                        <a href="<?= 'pdf-guia-ingreso-'.$hist['idguia_dev_cab']; ?>" 
                                                                           target="_blank" 
                                                                           class="btn btn-outline-primary btn-sm font-weight-bold" 
                                                                           title="Imprimir Guía de Devolución PDF">
                                                                            <i class="fas fa-file-pdf text-danger"></i> PDF
                                                                        </a>
                                                                        <button type="button" 
                                                                                class="btn btn-outline-danger btn-sm btn-eliminar-registro" 
                                                                                data-id="<?= $hist['idguia_dev_det']; ?>" 
                                                                                data-idguia = "<?= $idguia_bd; ?>"
                                                                                data-idpresupuesto="<?= $idpresupuesto_bd; ?>"
                                                                                title="Anular este reingreso">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else: ?>
                                                <div class="text-center text-muted small py-2">
                                                    <i class="fas fa-info-circle text-secondary"></i> No existen ingresos ni parciales registrados para esta pieza aún.
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <script>document.getElementById('saldo_total_<?= $idpieza; ?>').innerText = '<?= $saldo_total_obra; ?>';</script>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-3">
                        <!-- <a href="<?= base_url('almacen/guias_lista'); ?>" class="btn btn-outline-secondary font-weight-bold px-4">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a> -->
                        <button type="submit" class="btn btn-success btn-lg px-5 font-weight-bold shadow-sm" id="btnProcesarDev">
                            <i class="fas fa-save"></i> Guardar Reingreso de Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection();?>

<?php echo $this->section('scripts');?>

<script>
$(document).ready(function() {

    // 1. VALIDACIÓN REACTIVA DIRECTA EN LA CELDA DE LA TABLA
    $('.input-devolucion').on('input change', function() {
        let input  = $(this);
        let valor  = parseInt(input.val()) || 0;
        let maximo = parseInt(input.attr('max')) || 0;
        let idpieza = input.data('idpieza');

        // Limpieza de valores menores a cero
        if (valor < 0) {
            input.val(0);
            valor = 0;
        }

        // Mostrar u ocultar dinámicamente el campo de notas según la cantidad ingresada
        if (input.hasClass('input-propio')) {
            let containerNotePropio = $('#note_container_propio_' + idpieza);
            if (valor > 0) {
                containerNotePropio.removeClass('d-none');
            } else {
                containerNotePropio.addClass('d-none').find('input').val('');
            }
        } else if (input.hasClass('input-externo')) {
            let indexExt = input.data('indexext');
            let containerNoteExt = $('#note_container_ext_' + idpieza + '_' + indexExt);
            if (valor > 0) {
                containerNoteExt.removeClass('d-none');
            } else {
                containerNoteExt.addClass('d-none').find('input').val('');
            }
        }

        // Candado en Caliente: Si supera el saldo real disponible en obra
        if (valor > maximo) {
            Swal.fire({
                icon: 'warning',
                title: 'Cantidad excedida',
                text: 'Físicamente en la obra solo quedan ' + maximo + ' unidades disponibles de este grupo.',
                confirmButtonColor: '#0d6efd'
            });
            input.val(maximo);
            // Hacer re-evaluación del contenedor de notas tras setear el máximo
            if (input.hasClass('input-propio')) {
                $('#note_container_propio_' + idpieza).removeClass('d-none');
            } else if (input.hasClass('input-externo')) {
                let indexExt = input.data('indexext');
                $('#note_container_ext_' + idpieza + '_' + indexExt).removeClass('d-none');
            }
        }
    });

    // 2. ENVÍO DINÁMICO ASÍNCRONO DEL FORMULARIO (GUARDAR Y EDITAR HISTORIAL)
    $('#formDevolucion').on('submit', function(e) {
        e.preventDefault();

        // Verificamos si el almacenero ingresó datos válidos (> 0) antes de procesar
        let controlCantidades = 0;
        $('.input-devolucion').each(function() {
            controlCantidades += parseInt($(this).val()) || 0;
        });

        // Candado Definitivo: Si el usuario dio clic y todo está en blanco o en 0
        if (controlCantidades === 0) {
            Swal.fire({
                icon: 'info',
                title: 'No hay modificaciones',
                text: 'Por favor, ingrese al menos una cantidad válida en los casilleros de reingreso antes de guardar.',
                confirmButtonColor: '#6c757d'
            });
            return; // Detiene completamente el envío
        }

        // Cuadro de diálogo moderno de confirmación
        Swal.fire({
            title: '¿Confirmar Reingreso?',
            text: "Se generará un ticket y una guía de devolución para este viaje de materiales.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, Registrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                
                // Animación de bloqueo del botón para prevenir doble submit
                let btn = $('#btnProcesarDev');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');

                $.ajax({
                    url: 'guardar-devolucion',
                    type: 'POST',
                    data: $('#formDevolucion').serialize(),
                    success: function(response) {
                        // El controlador inyectará directo el script con el SweetAlert de éxito y recarga
                        $('body').append(response);
                    },
                    error: function() {
                        Swal.fire('Error del Sistema', 'No se pudo entablar comunicación asíncrona con el servidor.', 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Reingreso de Material');
                    }
                });
            }
        });
    });
});

// EVENTO PARA ELIMINAR UN REGISTRO ESPECÍFICO DEL HISTORIAL
$(document).on('click', '.btn-eliminar-registro', function() {
    let id_registro = $(this).data('id'); // Captura el idguia_dev_det
    let idguia = $(this).data('idguia');
    let idpresupuesto = $(this).data('idpresupuesto');

    Swal.fire({
        title: '¿Anular este reingreso?',
        text: "Se restará esta cantidad del historial y el saldo en obra volverá a aumentar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, Anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'eliminar_devolucion_item',
                type: 'POST',
                data: { idguia_dev_det: id_registro, idguia, idpresupuesto },
                success: function(response) {
                    $('body').append(response); // Ejecuta el SweetAlert que manda el controlador
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo procesar la solicitud de eliminación.', 'error');
                }
            });
        }
    });
});
</script>
<?php echo $this->endSection();?>