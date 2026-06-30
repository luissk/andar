<?php
/** @var array $presu */ 
/** @var array $params */ 
/** @var array $detalle */ 
/** @var array $deta_pre_pie_bd */ 

$logo   = $params['par_logo'];
$direc  = $params['par_direcc'];
$telef  = $params['par_telef'];
$correo = $params['par_correo'];

$prenum      = $presu['pre_numero'];
$prefecha    = $presu['pre_fechareg'];
$periodo     = $presu['pre_periodo'];
$nperiodo    = $presu['pre_periodonro'];
$porcprecio  = $presu['pre_porcenprecio'];
$porcsem     = $presu['pre_porcsem'];
$verPiezas   = $presu['pre_verpiezas'];
$pentrega    = $presu['pre_pentrega'];
$fpago       = $presu['pre_fpago'];
$voferta     = $presu['pre_voferta'];
$lentrega    = $presu['pre_lentrega'];
$preciotrans = $presu['pre_preciotrans'];
$nrodias     = $presu['pre_nrodiasm'];
$preciomyd   = $presu['pre_preciomyd'];
$pre_ruc     = $presu['pre_ruc'];
$pre_glosa     = $presu['pre_glosa'];

$cliente    = $presu['cli_nombrerazon'];
$dniruc     = $presu['cli_dniruc'];
$nomcontact = $presu['cli_nombrecontact'];
$corcontact = $presu['cli_correocontact'];
$telcontact = $presu['cli_telefcontact'];

$nomusuario = $presu['usu_nombres']." ".$presu['usu_apellidos'];
$dniusu     = $presu['usu_dni'];

$peri = '';
if( $periodo == 'd' ) $peri = 'Día';
if( $periodo == 's' ) $peri = 'Semana';
if( $periodo == 'm' ) $peri = 'Mes';

if( $nrodias != '' && $nrodias != 0 ) $peri = 'Días';

// NOTA: Para forzar la descarga directa como Word desde el navegador, 
// puedes descomentar las siguientes líneas en tu controlador o al inicio de este archivo:
// header("Content-type: application/vnd.ms-word");
// header("Content-Disposition: attachment; Filename=Presupuesto_".$prenum.".doc");
// header("Pragma: no-cache");
// header("Expires: 0");
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" 
      xmlns:w="urn:schemas-microsoft-com:office:word" 
      xmlns="http://www.w3.org/TR/REC-html40"
      lang="es">
<head>
<meta charset="UTF-8">
<title>Presupuesto</title>
<style type="text/css">
    body {
        font-family: Verdana, Arial, sans-serif;
        color: #333;
    }
    /* Estilos de página nativos para Word */
    @page Section1 {
        size: 595.3pt 841.9pt; /* Tamaño A4 */
        margin: 72.0pt 54.0pt 72.0pt 54.0pt; /* Márgenes estándar en puntos */
        mso-header-margin: 35.4pt;
        mso-footer-margin: 35.4pt;
        mso-paper-source: 0;
    }
    div.Section1 {
        page: Section1;
    }
    table {
        border-collapse: collapse;
    }
    .gray {
        background-color: #E6E6E6;
    }
    .item-th {
        padding: 10px 2px;
        background-color: #E6E6E6;
        font-size: 12px;
        font-weight: bold;
    }
    .item-td {
        text-align: center;
        padding: 6px 2px;
        font-size: 11px;
        border-bottom: 1px solid #666666;
    }
    .price {
        text-align: right;
    }
    .piezas-td {
        text-align: center;
        padding: 4px 2px;
        font-size: 10px;
        color: #666666;
        border-bottom: 1px solid #999999;
    }
    .pie_price th {
        padding: 4px 2px;
        font-size: 11px;
    }
</style>
</head>
<body>

<div class="Section1">

    <table style="width: 100%; border-bottom: 2px solid #444444;" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top" width="180" style="padding-bottom: 15px;">
                <img src="<?=base_url('public/images/logo/'.$logo.'')?>" alt="logo" width="160"/>
            </td>
            <td align="center" valign="top" style="padding-bottom: 15px;">
                <h3 style="font-size: 18px; margin:0; font-family: Verdana, sans-serif;"><b>ANDAMIOS ANDAR</b></h3>
                <p style="font-size: 12px; margin: 5px 0 0 0; color: #555;">
                    <?=$direc?><br>
                    <?=$correo?><br>
                    <?=$telef?> / 957323010
                </p>
            </td>
        </tr>
    </table>

    <br/>

    <table cellspacing="0" cellpadding="0" style="width: 100%;">
        <tr>
            <td width="50%" valign="top">
                <table cellspacing="0" cellpadding="3" style="width: 100%; font-size: 11px;">
                    <tr align="left">
                        <th width="80" valign="top">Cliente:</th>
                        <td><?=$cliente?></td>
                    </tr>
                    <tr align="left">
                        <th>Dni/Ruc:</th>
                        <td><?=$dniruc?></td>
                    </tr>
                    <tr align="left">
                        <th>Contacto:</th>
                        <td><?=$nomcontact?></td>
                    </tr>
                    <tr align="left">
                        <th>Correo:</th>
                        <td><?=$corcontact?></td>
                    </tr>
                    <tr align="left">
                        <th>Teléfono:</th>
                        <td><?=$telcontact?></td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <table cellspacing="0" cellpadding="3" style="width: 100%; font-size: 11px;">
                    <tr align="left">
                        <th width="90" valign="top">Vendedor:</th>
                        <td><?=$nomusuario?></td>
                    </tr>
                    <tr align="left">
                        <th>Fecha:</th>
                        <td><?=Date('d/m/Y', strtotime($prefecha))?></td>
                    </tr>
                    <tr align="left">
                        <th>Presupuesto:</th>
                        <td><b><?=$prenum?></b></td>
                    </tr>
                    <tr align="left">
                        <th>RUC:</th>
                        <td><?=$pre_ruc?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br/>

    <p style="font-size: 11px; margin: 0 0 10px 0;">Estimado Cliente, le hacemos llegar el siguiente PRESUPUESTO.</p>

    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #666666;">
        <thead>
            <tr>
                <th width="40" class="item-th">Item</th>
                <th width="280" align="left" class="item-th" style="text-align: left;">Descripción</th>
                <th width="50" class="item-th"><?=$peri?></th>
                <th width="50" class="item-th">Cant.</th>
                <th width="90" class="item-th" style="text-align: right;">Precio Unit.</th>
                <th width="90" class="item-th" style="text-align: right;">Precio Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $c = 0;
            $sum = 0;
            $pesoTotalPiezas = 0;
            foreach($detalle as $d){
                $c++;
                $sum += $d['dp_precio'];

                $pesoUnTotal = 0;
                $pesoTT = 0;
                foreach( $deta_pre_pie_bd as $pi ){
                    if( $d['idtorre'] == $pi['idtorre'] ){
                        $pie_peso    =  $pi['dp_peso_hist'];
                        $pie_peso_t  =  $pie_peso * $pi['dp_cant_x_torre'] * $pi['dp_cant_x_presu'];
                        $pesoUnTotal += $pie_peso;
                        $pesoTT      += $pie_peso_t;
                    }                                    
                }
                $pesoTotalPiezas += $pesoTT;
                
                echo "<tr>";
                echo "<td class='item-td'>$c</td>";
                echo "<td class='item-td' style='text-align:left;'>".$d['dp_torredesc']."</td>";
                echo "<td class='item-td'>".($nrodias != '' && $nrodias != 0 ? $nrodias : $nperiodo)."</td>";
                echo "<td class='item-td'>".$d['dp_cant']."</td>";
                echo "<td class='item-td price'>S/. ".number_format($d['dp_precio'] / $d['dp_cant'],2,".",",")."</td>";
                echo "<td class='item-td price'>S/. ".number_format($d['dp_precio'],2,".",",")."</td>";
                echo "</tr>";

                if( $verPiezas == 1 ){
                    $c2 = 0;
                    foreach( $deta_pre_pie_bd as $pi ){
                        $c2++;
                        if( $d['idtorre'] == $pi['idtorre'] ){
                            $preciop = $pi['dp_precio_hist'] * $pi['dp_cant_x_torre'];
                            $tott = help_calcularPresu($preciop,$periodo,$nperiodo,$porcprecio,$porcsem) * $d['dp_cant'] * $presu['pre_tcambio'];

                            echo "<tr>";
                            echo "<td class='piezas-td'>$c.$c2</td>";
                            echo "<td class='piezas-td' style='text-align:left;'>".$pi['dp_desc_hist']."</td>";
                            echo "<td class='piezas-td'></td>";
                            echo "<td class='piezas-td'>".$pi['dp_cant_x_torre'] * $d['dp_cant']."</td>";
                            echo "<td class='piezas-td price'>S/. ".number_format($tott/($pi['dp_cant_x_torre'] * $d['dp_cant']),2,".","")."</td>";
                            echo "<td class='piezas-td price'>S/. ".number_format($tott,2,".","")."</td>";
                            echo "</tr>";
                        }
                    }                                    
                }
            }

            if( $preciotrans != '' && $preciotrans != 0 ){
                $sum = $sum + $preciotrans;
                $c++;
                echo "<tr>";
                echo "<td class='item-td'>".$c."</td>";
                echo "<td class='item-td' style='text-align:left;'>COSTO DE FLETE</td>";
                echo "<td class='item-td'></td>";
                echo "<td class='item-td'>1</td>";
                echo "<td class='item-td price'>S/. ".number_format($preciotrans,2)."</td>";
                echo "<td class='item-td price'>S/. ".number_format($preciotrans,2)."</td>";
                echo "</tr>";
            }

            if( $preciomyd != '' && $preciomyd != 0 ){
                $sum = $sum + $preciomyd;
                $c++;
                echo "<tr>";
                echo "<td class='item-td'>".$c."</td>";
                echo "<td class='item-td' style='text-align:left;'>COSTO DE MONTAJE Y DESMONTAJE</td>";
                echo "<td class='item-td'></td>";
                echo "<td class='item-td'>1</td>";
                echo "<td class='item-td price'>S/. ".number_format($preciomyd,2)."</td>";
                echo "<td class='item-td price'>S/. ".number_format($preciomyd,2)."</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
        <tfoot class="pie_price">
            <tr>
                <th colspan="5" align="left" style="text-align: left; font-weight: normal; padding-top: 8px;">
                    * Peso total: <?=number_format($pesoTotalPiezas / 1000,2,".","")?> Tn
                </th>
                <th></th>
            </tr>
            <tr>
                <th colspan="5" align="right" style="text-align: right;">SubTotal:</th>
                <th align="right" style="text-align: right;">S/. <?=number_format($sum,2,".",",")?></th>
            </tr>
            <tr>
                <th colspan="5" align="right" style="text-align: right;">IGV(18%):</th>
                <th align="right" style="text-align: right;">S/. <?=number_format($sum * 0.18,2,".",",")?></th>
            </tr>
            <tr>
                <th colspan="5" align="right" style="text-align: right;">Total:</th>
                <th align="right" style="text-align: right; border-top: 1px solid #333;">S/. <?=number_format($sum + $sum * 0.18,2,".",",")?></th>
            </tr>
        </tfoot>
    </table>

    <br/>

    <table cellspacing="0" cellpadding="2" style="width: 100%; font-size: 11px;">
        <tr>
            <td colspan="3" style="font-weight: 600; padding: 5px 0">
                <?=$pre_glosa != '' ? "- $pre_glosa" : ''?>
            </td>
        </tr>
        <tr>
            <td width="150"><b>PLAZO DE ENTREGA</b></td>
            <td width="10">:</td>
            <td><?=$pentrega?></td>
        </tr>
        <tr>
            <td><b>FORMA DE PAGO</b></td>
            <td>:</td>
            <td><?=$fpago?></td>
        </tr>
        <tr>
            <td><b>VALIDEZ DE LA OFERTA</b></td>
            <td>:</td>
            <td><?=$voferta?></td>
        </tr>
        <tr>
            <td><b>LUGAR DE ENTREGA</b></td>
            <td>:</td>
            <td><?=$lentrega?></td>
        </tr>
    </table>

    <br/>

    <table width="100%" cellspacing="0" cellpadding="8" style="border: 1px solid #cccccc;">
        <tr bgcolor="#F2F2F2">
            <td width="50%" align="center" style="border-right: 1px solid #cccccc;"><b>Cuenta Soles:</b></td>
            <td width="50%" align="center"><b>Cuenta Dólares:</b></td>
        </tr>
        <tr>
            <td valign="top" style="font-size: 11px; border-right: 1px solid #cccccc;">
                BCP: <b>1947314765013</b><br>
                BCP CCI: <b>00219400731476501396</b><br>
                Cta Detracción: <b>00058566721</b>
            </td>
            <td valign="top" style="font-size: 11px;">
                BCP: <b>19416839495188</b><br>
                BCP CCI: <b>00219411683949518897</b>
            </td>
        </tr>
    </table>

    <br/>

    <table width="100%" cellspacing="0" cellpadding="2" style="font-size: 10px; color: #555555;">
        <tr>
            <td colspan="2"><b>* COTIZACIÓN SUJETA A VARIACIONES</b></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 5px;"><u>Condiciones de alquiler:</u></td>
        </tr>
        <tr>
            <td valign="top" width="10">*</td>
            <td>El cliente acepta que recibió los andamios en buen estado y limpios.</td>
        </tr>
        <tr>
            <td valign="top">*</td>
            <td>El cliente se responsabiliza por daños que puedan ocasionar al equipo en alquiler, comprometiéndose a pagar por las valorizaciones correspondientes.</td>
        </tr>
        <tr>
            <td valign="top">*</td>
            <td>En caso de prolongar el alquiler el cliente se apersonará o llamará para renovar el contrato, caso contrario cumplida la fecha de vencimiento se pasará a recoger el equipo.</td>
        </tr>
    </table>

    <br/><br/>

    <table width="100%" style="border-top: 1px solid #666666; font-size: 10px;">
        <tr>
            <td align="left" style="padding-top: 5px;">Copyright &copy; <?php echo date("d/m/Y");?> - Andamios Andar</td>
            <td align="right" style="padding-top: 5px;">Presupuesto N° <?=$prenum?></td>
        </tr>
    </table>

</div>

</body>
</html>