<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guía de Devolución General <?= $gdc_numero_ticket; ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 15px; }
        .info-header { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .tabla-pdf { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11px; }
        .tabla-pdf th { background-color: #212529; color: #ffffff; padding: 10px; font-weight: bold; text-transform: uppercase; font-size: 10px; border: 1px solid #212529; }
        .tabla-pdf td { padding: 10px; border: 1px solid #adadad; vertical-align: middle; }
        .text-center { text-align: center; }
        .text-danger { color: #dc3545; font-weight: bold; }
        .text-success { color: #198754; font-weight: bold; }

        * {
            font-family: Verdana, Arial, sans-serif;
            color: #333;
        }
        @page { 
            margin: 170px 50px 80px 60px;
        }
        header {
            position: fixed;
            top: -140px;
            left: 0px;
            right: 0px;
            height: 120px;

            /** Extra personal styles **/
            text-align: center;
            border-bottom: 2px solid #444;

        }

        footer {
            position: fixed; 
            bottom: -60px; 
            left: 0px; 
            right: 0px;
            height: 50px; 

            /** Extra personal styles **/
            border-top: 2px solid #444;
            text-align: center;
            line-height: 2;
            font-size: 13px;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>
<body>
    <?php
    $logo   = $params['par_logo'];
    $direc  = $params['par_direcc'];
    $telef  = $params['par_telef'];
    $correo = $params['par_correo'];
    ?>
    <header>
        <table style="width: 100%; height: 119px;" cellpadding="5" cellspacing="0">
            <tr>
                <td valign="top" width="150px">
                    <img src="<?=base_url('public/images/logo/'.$logo.'')?>" alt="logo" width="80%"/>
                </td>
                <td align="center" valign="top" width="280px">
                    <h3 style="font-size: 20px;margin:0" align="center">ANDAMIOS ANDAR</h3>
                    <p style="font-size: 13px;">
                        <?=$direc?><br>
                        <?=$correo?><br>
                        <?=$telef?> / 957323010
                    </p>
                </td>
                <!-- <td align="center">
                    <div style="border:1px solid black; font-size: 12px;font-weight:600;border-radius:3px;padding-top:5px;padding-bottom:5px;font-family:Arial">
                        RUC: <?= $guia['cli_dniruc'] ?><br>
                        GUIA DE RECEPCION<br>
                        <span style="font-size:13px">N° <?=$guia_salida_numero?></span>
                    </div>                    
                </td> -->
            </tr>
        </table>
    </header>
    <footer>
        <table width="100%">
            <tr>
                <td align="right">página <span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>

    <table class="info-header">
        <tr>
            <td>
                <h2 style="margin: 0; color: #212529; font-size: 20px;">GUÍA DE REINGRESO DE MATERIALES</h2>
                <p style="margin: 6px 0 0 0; font-size: 12px; color: #495057;">
                    <strong>Guía de Salida Referencial:</strong> <?= $guia_salida_numero; ?>
                </p>
            </td>
            <td align="right" valign="top">
                <div style="border: 2px solid #212529; padding: 10px; background-color: #f8f9fa; text-align: center; width: 190px;">
                    <span style="font-size: 9px; color: #6c757d; font-weight: bold;">TICKET DE DEVOLUCIÓN</span><br>
                    <strong style="font-size: 16px; color: #212529;"><?= $gdc_numero_ticket; ?></strong>
                </div>
                <p style="font-size: 11px; margin: 8px 0 0 0;"><strong>Fecha Operación:</strong> <?= date('d/m/Y h:i A', strtotime($gdc_fecha)); ?></p>
            </td>
        </tr>
    </table>

    <table class="tabla-pdf">
        <thead>
            <tr>
                <th width="15%">Código</th>
                <th width="49%">Descripción del Material</th>
                <th width="12%" class="text-center">Cant. Enviada</th>
                <th width="12%" class="text-center">Devuelto (Hoy)</th>
                <th width="12%" class="text-center">Saldo en Obra</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materiales as $item): ?>
                <tr>
                    <td class="text-center" style="font-weight: bold; color: #495057;">
                        <?= $item['dp_cod_hist']; ?>
                    </td>
                    <td>
                        <strong style="color: #212529; font-size: 11px;"><?= $item['dp_desc_hist']; ?></strong>
                    </td>
                    <td class="text-center" style="background-color: #f8f9fa;">
                        <?= $item['total_original_enviada']; ?> Und
                    </td>
                    <td class="text-center" style="font-weight: bold; background-color: #e8f4fd; color: #0d6efd;">
                        <?= $item['total_devuelta_ticket']; ?> Und
                    </td>
                    <td class="text-center" style="font-weight: bold; background-color: #fffbfb;">
                        <?php if ((int)$item['total_saldo_en_obra'] <= 0): ?>
                            <span class="text-success">0 (Completo)</span>
                        <?php else: ?>
                            <span class="text-danger"><?= $item['total_saldo_en_obra']; ?> Und</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>