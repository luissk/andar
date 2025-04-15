<?php

$logo   = $params['par_logo'];
$direc  = $params['par_direcc'];
$telef  = $params['par_telef'];
$correo = $params['par_correo'];

/* echo "<pre>";
print_r($guia);
echo "</pre>"; */

$guiNro        = $guia['gui_nro'];
$fechatrasl    = date("d/m/Y", strtotime($guia['gui_fechatraslado']));
$motivo        = $guia['gui_motivo'];
$motivodesc    = $guia['gui_motivodesc'];
$direcc_p      = $guia['gui_direccionp'];
$direcc_ll     = $guia['gui_direccionll'];
$depap         = $guia['depap'];
$provp         = $guia['provp'];
$distp         = $guia['distp'];
$depall        = $guia['depall'];
$provll        = $guia['provll'];
$distll        = $guia['distll'];
$trans_telef   = $guia['tra_telef'];
$trans_dni     = $guia['tra_dni'];
$transportista = $guia['tra_nombres']." ".$guia['tra_apellidos'];
$placa         = $guia['gui_placa'];
$cliente       = $guia['cli_nombrerazon'];
$dniruc        = $guia['cli_dniruc'];
$clicontact    = $guia['cli_nombrecontact'];
$clicorreocont = $guia['cli_correocontact'];
$clitelefcont  = $guia['cli_telefcontact'];

$idpresupuesto = $guia['idpresupuesto'];

$pre_piezas = json_decode($guia['pre_piezas'], true);

if( $motivo == 'v' ) $motivo = "Venta";
if( $motivo == 'e' ) $motivo = "Exportación";
if( $motivo == 'i' ) $motivo = "Importación";
if( $motivo == 'o' ) $motivo = "Otros";
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

    .tablapdf thead tr th{
        background-color: #ddd;
        padding: 5px 0;
    }
    .tablapdf tbody tr td{
        padding: 5px;
        text-align: center;
        border: 1px solid #ddd;
    }
    .tablapdf tbody tr td.left{
        text-align: left;
    }
</style>

</head>
<body>
    <header>
        <table style="width: 100%; height: 119px;" cellpadding="5" cellspacing="0">
            <tr>
                <td valign="top" width="150px">
                    <img src="<?=base_url('public/images/logo/'.$logo.'')?>" alt="logo" width="100%"/>
                </td>
                <td align="center" valign="top" width="280px">
                    <h3 style="font-size: 20px;margin:0" align="center">ANDAMIOS ANDAR</h3>
                    <p style="font-size: 13px;">
                        <?=$direc?><br>
                        <?=$correo?><br>
                        <?=$telef?>
                    </p>
                </td>
                <td align="center">
                    <div style="border:1px solid black; font-size: 12px;font-weight:600;border-radius:3px;padding-top:5px;padding-bottom:5px;font-family:Arial">
                        RUC: 20100150112<br>
                        GUIA DE REMISIÓN ELECTRÓNICA<br>
                        REMITENTE<br>
                        <span style="font-size:13px">N° EG01-00000001</span>
                    </div>                    
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table width="100%">
            <tr>
                <td align="left"><?=$opt == 1 ? 'Guía cliente' : 'Guía almacén'?></td>
                <td align="right">página <span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>

    <section style='font-size:12px'>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td width="250px">
                    <b>Fecha de Traslado:</b> <?=$fechatrasl?><br><br>
                    <b>Motivo de Traslado:</b> <?=$motivo?> <br><br>
                    <b>Motivo de Traslado:</b> <?=$motivodesc?>
                </td>
                <td width="20px"></td>
                <td valign="top">
                    <b>Punto de Partida:</b> <?=$direcc_p?> - <?=$distp?>, <?=$provp?>, <?=$depap?>
                    <br><br>
                    <b>Punto de Llegada:</b> <?=$direcc_ll?> - <?=$distll?>, <?=$provll?>, <?=$depall?>
                </td>
            </tr>
        </table>
        <div style="padding-top:10px">
            <b>Cliente: </b> <?=$cliente?> &nbsp;&nbsp;&nbsp; <b>DNI/RUC</b>: <?=$dniruc?> 
        </div>
        <div style="padding-top:10px">
            <b>Transportista: </b> <?=$transportista?> &nbsp;&nbsp;&nbsp; <b>DNI</b>: <?=$trans_dni?> &nbsp;&nbsp;&nbsp; <b>Placa del Vehículo</b>: <?=$placa?>
        </div>
        <div style="padding-top:10px">
            <b>Bienes a transportar: </b> 
        </div>
        <div style="padding-top:8px"></div>
        <?php
        $presuModel = model('PresupuestoModel');
        $torreModel = model('TorreModel');
        $piezaModel = model('PiezaModel');

        /* echo "<pre>";
        print_r($pre_piezas);
        echo "</pre>"; */
        $arr_aux = array_map(function($v){
            if( array_key_exists('falt', $v) && array_key_exists('st_sale', $v) ){//editar
                return ['idpie' => $v['idpie'], 'req' => $v['dtcan'] * $v['dpcant'],'falt' => $v['falt'], 'st_sale' => $v['st_sale']];
            }else{
                return ['idpie' => $v['idpie'], 'req' => $v['dtcan'] * $v['dpcant']];
            }
            
        }, $pre_piezas);

        $newarr = [];
        foreach( $arr_aux as $ax ){
            if( in_array($ax['idpie'], array_column($newarr, 'idpie')) ){
                $aa = array_filter($newarr, fn($v) => $v['idpie'] == $ax['idpie']);
                $aa = array_keys($aa)[0];
                $newarr[$aa]['req'] = $newarr[$aa]['req'] + $ax['req'];

                if( array_key_exists('falt', $ax) && array_key_exists('st_sale', $ax) ){//editar
                    $e_falt = $newarr[$aa]['falt'] == '' ? 0 : $newarr[$aa]['falt'];
                    $e_stsale = $newarr[$aa]['st_sale'] == '' ? 0 : $newarr[$aa]['st_sale'];
                    $newarr[$aa]['falt'] = $e_falt + ($ax['falt'] == '' ? 0 : $ax['falt']);
                    $newarr[$aa]['st_sale'] = $e_stsale + $ax['st_sale'];
                    //echo $aa;
                    //print_r($ax);
                }

                continue;
            }
            $newarr[] = $ax;
        }
        ?>
        <div>
            <table cellspacing="0" cellpadding="0" width="100%" class="tablapdf">
                <thead>
                    <tr>
                        <th>Nro</th>
                        <th>Cod. Bien</th>
                        <th>Descripción</th>
                        <th>U. medida</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $cont = 0;
                $suma_peso = 0;
                foreach( $newarr as $pi ){                                                        
                    $cont++;
                    $idpieza  = $pi['idpie'];
                    $pieza_bd = $piezaModel->getPieza($idpieza);

                    $piecodigo = $pieza_bd['pie_codigo'];
                    $piedesc   = $pieza_bd['pie_desc'];
                    $stockIni  = $pieza_bd['pie_cant'];
                    $piepeso   = $pieza_bd['pie_peso'];
                    $cantReq   = $opt == 1 ? $pi['req'] : $pi['st_sale'];

                    $suma_peso += $piepeso * $pi['req'];

                    echo "<tr>";
                    echo "<td>$cont</td>";
                    echo "<td>$piecodigo</td>";
                    echo "<td class='left'>$piedesc</td>";
                    echo "<td>UND</td>";
                    echo "<td>$cantReq</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        <div style="padding-top:10px">
            <b>Peso bruto total de la carga</b>: <?=$suma_peso?> Kg.
        </div>
        <div style="padding-top:10px">            
            <table>
                <tr>
                    <th valign="top">Observaciones:</th>
                    <td>
                    <?php
                    $detalle_presu = $presuModel->getDetallePresupuesto($idpresupuesto);
                    foreach($detalle_presu as $d){
                        $idtorre  = $d['idtorre'];
                        $dp_cant  = $d['dp_cant'];
                        $tor_desc = $d['tor_desc'];
                        echo "$dp_cant $tor_desc.<br>";
                    }
                    ?>
                    </td>
                </tr>
            </table>
        </div>
    </section>
</body>
</html>