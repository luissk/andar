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
$track_bd      = json_decode($guia['guia_track'], true);

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
<title>Devolución Ingreso</title>

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
                        GUIA DE RECEPCION<br>
                        <span style="font-size:13px">N° <?=$guiNro?></span>
                    </div>                    
                </td>
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

    <section style='font-size:12px'>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td width="250px">
                    <b>Fecha de Traslado:</b> <?=$fecha?><br><br>
                </td>
            </tr>
        </table>
        <div style="padding-top:10px">
            <b>Ingresados: </b> 
        </div>
        <div style="padding-top:8px"></div>
        <?php
       /*  $presuModel = model('PresupuestoModel');
        $torreModel = model('TorreModel'); */
        $piezaModel = model('PiezaModel');

        $arr_track = array_filter($track_bd, fn($v) => $v['fecha'] == str_replace("-","/",$fecha));
        $arr_track = array_values($arr_track);
        /* echo "<pre>";
        print_r($arr_track);
        echo "</pre>";
        exit(); */      
        ?>
        <div>
            <table cellspacing="0" cellpadding="0" width="100%" class="tablapdf">
                <thead>
                    <tr>
                        <th>Nro</th>
                        <th>Cod. Bien</th>
                        <th>Descripción</th>
                        <th>Salió</th>
                        <th>Ingresó</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $cont = 0;
                foreach( $arr_track[0]['items'] as $pi ){                                                     
                    $cont++;
                    $idpieza  = $pi['idpieza'];
                    $pieza_bd = $piezaModel->getPieza($idpieza);

                    $piecodigo    = $pieza_bd['pie_codigo'];
                    $piedesc      = $pieza_bd['pie_desc'];
                    $salio        = $pi['salio'];
                    $nuevoingreso = $pi['nuevoingreso'];
                    $resetear     = $pi['resetear'];

                    //$reset = $resetear == 1 ? '<b>(reset)</b>' : '';

                    echo "<tr>";
                    echo "<td>$cont</td>";
                    echo "<td>$piecodigo</td>";
                    echo "<td class='left'>$piedesc</td>";
                    echo "<td>$salio</td>";
                    echo "<td>$nuevoingreso</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>