<?php
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
$piezas      = $presu['pre_piezas'];
$verPiezas   = $presu['pre_verpiezas'];
$pentrega    = $presu['pre_pentrega'];
$fpago       = $presu['pre_fpago'];
$voferta     = $presu['pre_voferta'];
$lentrega    = $presu['pre_lentrega'];
$preciotrans = $presu['pre_preciotrans'];

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

?>

<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Presupuesto</title>

<style type="text/css">
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
   
    .gray {
        background-color: lightgray
    }

    #table_items tr th{
        padding: 10px 2px;
    }
    #table_items tr td{
        text-align: center;
        padding: 5px 2px;
        border-bottom: 1px solid #666;
    }
    #table_items tr td.price{
        text-align: right;
    }
    #table_items tr.piezas td{
        color: #666;
    }
    #table_items tfoot.pie_price tr th{
        padding: 3px 2px;
    }
</style>

</head>
<body>
    <header>
        <table style="width: 100%; height: 119px;" cellpadding="5" cellspacing="0">
            <tr>
                <td valign="top" width="180px">
                    <img src="<?=base_url('public/images/logo/'.$logo.'')?>" alt="logo" width="100%"/>
                </td>
                <td align="center" valign="top">
                    <h3 style="font-size: 20px;margin:0" align="center">ANDAMIOS ANDAR</h3>
                    <p style="font-size: 14px;">
                        <?=$direc?><br>
                        <?=$correo?><br>
                        <?=$telef?> / 957323010
                    </p>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table width="100%">
            <tr>
                <td align="left"><code>Copyright &copy; <?php echo date("d/m/Y");?></code></td>
                <td align="right">página <span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>

    <div>
        <table cellspacing="0" style=" font-size: 12px" width="100%">
            <tr>
                <td width="50%">
                    <table>
                        <tr align="left">
                            <th>Cliente:</th>
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
                    <table>
                        <tr align="left">
                            <th>Vendedor:</th>
                            <td><?=$nomusuario?></td>
                        </tr>
                        <tr align="left">
                            <th>Dni:</th>
                            <td><?=$dniusu?></td>
                        </tr>
                        <tr align="left">
                            <th>Fecha:</th>
                            <td><?=Date('d/m/Y', strtotime($prefecha))?></td>
                        </tr>
                        <tr align="left">
                            <th>Presupuesto:</th>
                            <td><?=$prenum?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <br/>

    <div style="font-size: 12px;">
        Estimado Cliente, le hacemos llegar el siguiente PRESUPUESTO.<br><br>

        <table width="100%" style="font-size: 12px;" id="table_items" cellspacing="0">
            <thead style="background-color: lightgray;">
                <tr>
                    <th width="40px">Item</th>
                    <th width="300px" align="left">Description</th>
                    <th><?=$peri?></th>
                    <th>Cant.</th>
                    <th>Precio Unit.</th>
                    <th>Precio Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $piezaModel = model('PiezaModel');

                $piezas     = json_decode($piezas, true);

                $c = 0;
                $sum = 0;
                $pesoTotalPiezas = 0;
                foreach($detalle as $d){
                    $c++;
                    $sum += $d['dp_precio'];

                    //para sacar los pesos totales
                    $pesoUnTotal = 0;
                    $pesoTT = 0;
                    $piezasFilter = array_filter($piezas, fn($p) => $p['idtor'] == $d['idtorre']);
                    foreach( $piezasFilter as $pi ){
                        $pieza_bd = $piezaModel->getPieza($pi['idpie']);
                        $pie_peso = $pieza_bd['pie_peso'];
                        $pie_peso_t = $pie_peso * $pi['dtcan'] * $d['dp_cant'];
                        $pesoUnTotal += $pie_peso;
                        $pesoTT += $pie_peso_t;
                    }
                    $pesoTotalPiezas += $pesoTT;
                    
                    echo "<tr>";

                    echo "<td>$c</td>";
                    echo "<td style='text-align:left'>".$d['tor_desc']."</td>";
                    echo "<td>$nperiodo</td>";
                    echo "<td>".$d['dp_cant']."</td>";
                    echo "<td class='price'>S/. ".number_format($d['dp_precio'] / $d['dp_cant'],2,".",",")."</td>";
                    echo "<td class='price'>S/. ".number_format($d['dp_precio'],2,".",",")."</td>";

                    echo "</tr>";

                    if( $verPiezas == 1 ){
                        $piezasFilter = array_filter($piezas, fn($p) => $p['idtor'] == $d['idtorre']);
                        $c2 = 0;
                        foreach( $piezasFilter as $pi ){
                            $c2++;
                            $pieza_bd = $piezaModel->getPieza($pi['idpie']);//solo para sacar los nombres de las piezas, porque los precios de las piezas se obtienen del presupuesto guardado, ya que con esos precios se grabaron
                            $preciop = $pi['piepre'] * $pi['dtcan'];

                            $tott = help_calcularPresu($preciop,$periodo,$nperiodo,$porcprecio,$porcsem) * $d['dp_cant'] * $presu['pre_tcambio'];

                            echo "<tr class='piezas'>";
                            echo "<td>$c.$c2</td>";
                            echo "<td style='text-align:left'>".$pieza_bd['pie_desc']."</td>";
                            echo "<td></td>";
                            echo "<td>".$pi['dtcan'] * $d['dp_cant']."</td>";
                            echo "<td class='price'>S/. ".number_format($tott/($pi['dtcan'] * $d['dp_cant']),2,".","")."</td>";
                            echo "<td class='price'>S/. ".number_format($tott,2,".","")."</td>";
                            echo "</tr>";
                        }                                    
                    }
                }
                ?>
            </tbody>
            <tfoot class="pie_price">
                <?php
                if( $preciotrans != ''){
                    $sum = $sum + $preciotrans;
                ?>
                <tr>
                    <th colspan="6" align="left">
                        * Precio transporte: S/. <?=$preciotrans?>
                    </th>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <th colspan="6" align="left">
                        * Peso total: <?=number_format($pesoTotalPiezas / 1000,2,".","")?> Tn
                    </th>
                </tr>
                <!-- <tr>
                    <th colspan="5" align="right"><br></th>
                    <th align="right"><br></th>
                </tr> -->
                <tr>
                    <th colspan="5" align="right">SubTotal:</th>
                    <th align="right">S/. <?=number_format($sum,2,".",",")?></th>
                </tr>
                <tr>
                    <th colspan="5" align="right">IGV(18%):</th>
                    <th align="right">S/. <?=number_format($sum * 0.18,2,".",",")?></th>
                </tr>
                <tr>
                    <th colspan="5" align="right">Total:</th>
                    <th align="right">S/. <?=number_format($sum + $sum * 0.18,2,".",",")?></th>
                </tr>
            </tfoot>
        </table>
        <br>
        <div style="font-size:11px;">
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td>PLAZO DE ENTREGA&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;<?=$pentrega?></td>
                </tr>
                <tr>
                    <td>FORMA DE PAGO&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;<?=$fpago?></td>
                </tr>
                <tr>
                    <td>VALIDEZ DE LA OFERTA&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;<?=$voferta?></td>
                </tr>
                <tr>
                    <td>LUGAR DE ENTREGA&nbsp;</td>
                    <td>:</td>
                    <td>&nbsp;<?=$lentrega?></td>
                </tr>
            </table>
        </div>
        <br>
        <div style="text-align:center;background-color:#ddd;padding:3px 0">
            Cuenta Soles:
        </div>
        <div style="text-align:center; font-weight:bold">
            BBVA 00110872020009900392<br>
            CCI 01187200020009900392<br>
            BCP 19116373803094
        </div>
        <br>
        <div style="text-align:center;background-color:#ddd;padding:7px 0"></div>
        <br>
        <div style="font-size: 10px;">
            * COTIZACIÓN SUJETA A VARIACIONES
            <br><br>
            <u>Condiciones de alquiler:</u><br>
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td valign='top'>*</td>
                    <td>El cliente acepta que recibió los andamios en buen estado y limpios.</td>
                </tr>
                <tr>
                    <td valign='top'>*</td>
                    <td>El cliente se responsabiliza por daños que puedan ocasionar al equipo en alquiler, comprometiéndose a pagar por a valorizaciones correspondientes.</td>
                </tr>
                <tr>
                    <td valign='top'>*</td>
                    <td>En caso de prolongar el alquiler el cliente se apersonará o llamará para renovar el contrato, caso contrario cumplida la fecha de vencimiento se pasará a recoger el equipo.</td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>