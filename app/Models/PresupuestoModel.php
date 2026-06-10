<?php 
namespace App\Models;

use CodeIgniter\Model;

class PresupuestoModel extends Model{

    public function nroPresupuesto(){
        $query = "select 
        concat( 
            LPAD(
                case when convert(substring(max(pre_numero),1,4),DECIMAL) is NULL then 0 else convert(substring(max(pre_numero),1,4),DECIMAL) end + 1
                , 4, '0'
            ), '-',YEAR(now())
        ) as nro
        from presupuesto 
        where substring(pre_numero,6,4) = YEAR(now())";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function getPresu_x_nroPresu($nroPre){
        $query = "select idpresupuesto from presupuesto where pre_numero = ? ";

        $st = $this->db->query($query, [$nroPre]);

        return $st->getRowArray();
    }

    public function insertarPresupuesto($nroPre,$idusuario2,$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$verP,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias,$preciomyd,$pre_ruc){
        $query = "insert into presupuesto(pre_numero,pre_fechareg,idusuario2,idcliente,pre_porcenprecio,pre_porcsem,pre_periodo,pre_periodonro,pre_verpiezas,pre_tcambio,pre_pentrega,pre_fpago,pre_voferta,pre_lentrega,pre_preciotrans,pre_nrodiasm,pre_preciomyd,pre_ruc,pre_status) 
        values(?,now(),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)";

        $st = $this->db->query($query, [$nroPre,$idusuario2,$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$verP,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias,$preciomyd,$pre_ruc]);

        return $this->db->insertID();
    }

    public function insertarDetallePresu($idpre,$idtorre,$cant,$tmonto,$desc_torre){
        $query = "insert into detalle_presupuesto(idpresupuesto,idtorre,dp_cant,dp_precio,dp_torredesc) values(?,?,?,?,?)";

        $st = $this->db->query($query, [$idpre,$idtorre,$cant,$tmonto,$desc_torre]);

        return $st;
    }

    public function insertarDetallePresuPiezas($idpre, $ap){
        $query = "insert into detalle_presupuesto_piezas(idpresupuesto,idtorre,idpieza,dp_cod_hist,dp_desc_hist,dp_peso_hist,dp_precio_hist,dp_cant_x_torre,dp_cant_x_presu,dp_cant_hist) values(?,?,?,?,?,?,?,?,?,?)";

        $dp_cant_total = $ap['dtcan'] * $ap['dpcant']; // el total que debe ser enviado, lo que esta en el detalle de torre por peiza, por la cnatidad de torres en el presupuesto

        $st = $this->db->query($query, [$idpre,$ap['idtor'],$ap['idpie'],$ap['codigo'],$ap['pie_desc'],$ap['pie_peso'],$ap['piepre'],$ap['dtcan'],$ap['dpcant'],$dp_cant_total]);

        return $st;
    }

    public function modificarPresupuesto($cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$idpresu,$verP,$nroPre,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias,$preciomyd,$pre_ruc){
        $query = "update presupuesto set idcliente=?,pre_porcenprecio=?,pre_porcsem=?,pre_periodo=?,pre_periodonro=?,pre_verpiezas=?,pre_numero=?,pre_tcambio=?,pre_pentrega=?,pre_fpago=?,pre_voferta=?,pre_lentrega=?,pre_preciotrans=?,pre_nrodiasm=?,pre_preciomyd=?,pre_ruc=? where idpresupuesto = ? and pre_status=1";

        $st = $this->db->query($query, [$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$verP,$nroPre,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias,$preciomyd,$pre_ruc,$idpresu]);

        return $st;
    }

    public function getPresupuestos($desde = '', $hasta = '', $cri = '', $status = [1,2,3]){//1->activo, 2->con guía, 3->devuelto
        $sql = $cri != '' ? " and (pre.pre_numero LIKE '%" . $this->db->escapeLikeString($cri) . "%' or cli.cli_nombrerazon LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select pre.idpresupuesto,pre.pre_numero,pre.pre_fechareg,pre.pre_periodo,pre.pre_periodonro,pre.pre_status,pre.pre_piezas,pre.pre_verpiezas,pre.pre_tcambio,
        pre.pre_pentrega,pre.pre_fpago,pre.pre_voferta,pre.pre_lentrega,pre.pre_preciotrans,pre.pre_nrodiasm,pre.pre_preciomyd,
        cli.cli_dniruc,cli.cli_nombrerazon,cli.cli_nombrecontact,cli.cli_correocontact,cli.cli_telefcontact,
        usu.usu_usuario,usu.usu_nombres,usu.usu_apellidos
        from presupuesto pre 
        inner join cliente cli on pre.idcliente=cli.idcliente
        inner join usuario usu on pre.idusuario2=usu.idusuario
        where pre.idpresupuesto is not null and pre.pre_status in ? $sql order by pre.pre_fechareg desc";

        if( $desde != '' && $hasta != ''){
            $query .= " limit ?,?";
            $st = $this->db->query($query, [$status, $desde, $hasta]);
        }else{
            $st = $this->db->query($query, [$status]);
        }

        return $st->getResultArray();
    }

    public function getPresupuestosCount($cri = '', $status = [1,2,3]){
        $sql = $cri != '' ? " and (pre.pre_numero LIKE '%" . $this->db->escapeLikeString($cri) . "%' or cli.cli_nombrerazon LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select count(pre.idpresupuesto) as total
        from presupuesto pre 
        inner join cliente cli on pre.idcliente=cli.idcliente
        inner join usuario usu on pre.idusuario2=usu.idusuario
        where pre.idpresupuesto is not null and pre.pre_status in ? $sql";

        $st = $this->db->query($query,[$status]);

        return $st->getRowArray();
    }

    public function getPresupuesto($idpresu, $status = [1,2,3]){
        $query = "select pre.idpresupuesto,pre.pre_numero,pre.pre_fechareg,pre.pre_periodo,pre.pre_periodonro,pre.pre_status,pre.pre_porcenprecio,pre.pre_porcsem,pre.pre_piezas,pre.pre_verpiezas,pre.pre_tcambio,pre.pre_pentrega,pre.pre_fpago,pre.pre_voferta,pre.pre_lentrega,pre.pre_preciotrans,pre.pre_nrodiasm,pre.pre_preciomyd,pre.pre_ruc,
        cli.idcliente,cli.cli_dniruc,cli.cli_nombrerazon,cli.cli_nombrecontact,cli.cli_correocontact,cli.cli_telefcontact,
        usu.usu_usuario,usu.usu_nombres,usu.usu_apellidos,usu.usu_dni
        from presupuesto pre 
        inner join cliente cli on pre.idcliente=cli.idcliente
        inner join usuario usu on pre.idusuario2=usu.idusuario
        where pre.idpresupuesto = ? and pre.pre_status in ?";

        $st = $this->db->query($query, [$idpresu, $status]);

        if( $st->getNumRows() > 0 )
            return $st->getRowArray();
        return FALSE;
    }

    public function getDetallePresupuesto($idpresu){
        $query = "select dp.idtorre,dp.dp_torredesc,dp.dp_cant,dp.dp_precio,
            tor.tor_desc,tor.tor_plano,tor.tor_fechareg,tor.idusuario2
        from detalle_presupuesto dp
        inner join torre tor on dp.idtorre=tor.idtorre
        where dp.idpresupuesto = ?";

        $st = $this->db->query($query, [$idpresu]);

        return $st->getResultArray();
    }

    public function getDetallePresupuestoPiezas($idpresu){
        /* $query = "SELECT 
                idtorre,
                idpieza,
                dp_cod_hist,
                dp_desc_hist,
                dp_peso_hist,
                dp_precio_hist,                
                -- 1. LO PLANIFICADO (Fijo e inamovible): 
                -- Como es único por torre/pieza, usamos MAX para jalar el valor original del contrato.
                MAX(dp_cant_x_torre) AS dp_cant_x_torre,
                MAX(dp_cant_x_presu) AS dp_cant_x_presu,
                MAX(dp_cant_hist)    AS dp_cant_hist, -- <--- ¡AQUÍ ESTÁ! Jala tus 14 fijos sin sumar nada.                
                -- 2. LO LOGÍSTICO (Lo que sí cambia y se acumula en las filas nuevas):
                -- Sumamos los despachos y devoluciones de todas las filas para saber el gran total real.
                SUM(dp_cant_enviada) AS dp_cant_enviada,
                SUM(dp_cant_devuelta) AS dp_cant_devuelta
            FROM detalle_presupuesto_piezas
            WHERE idpresupuesto = ?
            GROUP BY idtorre, idpieza, dp_cod_hist, dp_desc_hist, dp_peso_hist, dp_precio_hist
            ORDER BY idtorre ASC, idpieza DESC"; */
        $query = "SELECT 
                dp_pie.idtorre,
                dp_pie.idpieza,
                dp_pie.dp_cod_hist,
                dp_pie.dp_desc_hist,
                dp_pie.dp_peso_hist,
                dp_pie.dp_precio_hist,
                dp_pie.dp_cant_x_torre,
                dp_pie.dp_cant_x_presu,
                dp_pie.dp_cant_hist,

                -- 1. SALIDAS: Sumamos lo enviado de tu tabla actual (usando tu detalle de salidas existente)
                (SELECT IFNULL(SUM(gsd.cantidad_enviada), 0) 
                FROM guia_salida_detalle gsd 
                INNER JOIN guia g ON gsd.idguia = g.idguia 
                WHERE gsd.idpieza = dp_pie.idpieza 
                AND gsd.idtorre = dp_pie.idtorre 
                AND g.idpresupuesto = dp_pie.idpresupuesto) AS dp_cant_enviada,

                -- 2. RETORNOS: Sumamos todas las devoluciones parciales que se hicieron en diferentes fechas
                (SELECT IFNULL(SUM(gdd.cantidad_devuelta), 0) 
                FROM guia_devolucion_detalle gdd
                INNER JOIN guia_devolucion gd ON gdd.idguia_devolucion = gd.idguia_devolucion
                INNER JOIN guia g ON gd.idguia = g.idguia
                WHERE gdd.idpieza = dp_pie.idpieza 
                AND gdd.idtorre = dp_pie.idtorre 
                AND g.idpresupuesto = dp_pie.idpresupuesto) AS dp_cant_devuelta

            FROM detalle_presupuesto_piezas dp_pie
            WHERE dp_pie.idpresupuesto = ?
            ORDER BY dp_pie.idtorre ASC, dp_pie.idpieza DESC";

        $st = $this->db->query($query, [$idpresu]);

        return $st->getResultArray();
    }

    public function verificarCambiosMaestro($idpresupuesto) {
        $sql = "SELECT dp.iddetalle_pre_pie 
                FROM detalle_presupuesto_piezas dp

                -- Amarrar la tabla intermedia para obtener el nombre histórico de la torre (dp_torredesc)
                INNER JOIN detalle_presupuesto dpre 
                        ON dp.idpresupuesto = dpre.idpresupuesto 
                    AND dp.idtorre = dpre.idtorre

                -- 2. Traemos el nombre actual vigente del catálogo maestro de torres
                LEFT JOIN torre t ON dp.idtorre = t.idtorre
                
                -- Traemos los datos actuales de la pieza (para el precio y descripción)
                LEFT JOIN pieza p ON dp.idpieza = p.idpieza
                
                -- Traemos la estructura actual de la torre en el maestro (pivote torre_piezas)
                LEFT JOIN detalle_torre tp ON dp.idtorre = tp.idtorre AND dp.idpieza = tp.idpieza
                
                WHERE dp.idpresupuesto = ? 
                AND (
                    -- ¡VALIDACIÓN DEL NOMBRE DE LA TORRE!:
                    dpre.dp_torredesc != t.tor_desc
                    -- 1. CAMBIÓ ALGO EN LO QUE YA EXISTÍA:
                    OR dp.dp_precio_hist != p.pie_precio 
                    OR dp.dp_cant_x_torre != tp.dt_cantidad
                    OR dp.dp_desc_hist != p.pie_desc
                    OR dp.dp_peso_hist != p.pie_peso
                    
                    -- 2. SE QUITÓ UNA PIEZA DEL MAESTRO:
                    -- Si existía en tu presupuesto (dp) pero ya no existe en el maestro de la torre (tp)
                    OR tp.idpieza IS NULL
                    )
                
                UNION -- Con esto unimos la otra mitad del problema
                
                SELECT dp_aux.iddetalle_pre_pie
                FROM detalle_torre tp_aux
                -- Buscamos si el maestro de las torres que tiene este presupuesto
                -- tiene piezas que NO se encuentran registradas en tu detalle de piezas actual
                LEFT JOIN detalle_presupuesto_piezas dp_aux 
                    ON tp_aux.idtorre = dp_aux.idtorre 
                    AND tp_aux.idpieza = dp_aux.idpieza 
                    AND dp_aux.idpresupuesto = ?
                WHERE tp_aux.idtorre IN (SELECT DISTINCT idtorre FROM detalle_presupuesto_piezas WHERE idpresupuesto = ?)
                -- 3. SE AGREGÓ UNA PIEZA NUEVA AL MAESTRO:
                -- Si existe en la estructura de la torre (tp_aux) pero no en tu presupuesto (dp_aux)
                AND dp_aux.idpieza IS NULL
                
                LIMIT 1";

        // Pasamos los parámetros correspondientes a cada '?' en orden
        $query = $this->db->query($sql, [$idpresupuesto, $idpresupuesto, $idpresupuesto]);
        
        // Si cualquiera de las dos partes del UNION encuentra una fila, el modal se dispara
        return ($query->getNumRows() > 0) ? true : false;
    }

    public function borrarDetallePresupuesto($idpresu){
        $query = "delete from detalle_presupuesto where idpresupuesto=?";

        $st = $this->db->query($query, [$idpresu]);

        return $st;
    }

    public function borrarDetallePresuPiezas($idpresu){
        $query = "delete from detalle_presupuesto_piezas where idpresupuesto=?";

        $st = $this->db->query($query, [$idpresu]);

        return $st;
    }
    
    //VERIFICAR SI TIENE REGISTRO EN TABLAS(guia,detalle_factura) el presupuesto A ELIMINAR
    public function verificarPresuTieneRegEnTablas($idpresu, $tabla){
        $query = "select count(idpresupuesto) as total from $tabla where idpresupuesto=?";
        $st = $this->db->query($query, [$idpresu]);

        return $st->getRowArray();
    }

    public function eliminarPresupuesto($idpresu){
        $query = "delete from presupuesto where idpresupuesto=?";

        $st = $this->db->query($query, [$idpresu]);

        return $st;
    }

    /* public function getDetaPresuParaGuia($idpresu){
        $query = "select dp.idpresupuesto,dp.dp_cant,dp.idtorre,tor.tor_desc,pie.idpieza,pie.pie_codigo,pie.pie_desc,pie.pie_cant stock_ini,
        dt.dt_cantidad,(dp.dp_cant * dt.dt_cantidad) cant_req,
        (
            select ifnull(sum(dtor.dt_cantidad * dpre.dp_cant), 0)
            from detalle_presupuesto dpre
            inner join presupuesto pres on dpre.idpresupuesto=pres.idpresupuesto
            inner join torre torr on dpre.idtorre=torr.idtorre
            inner join detalle_torre dtor on torr.idtorre=dtor.idtorre
            inner join pieza piez on dtor.idpieza=piez.idpieza 
            where piez.idpieza=pie.idpieza and pres.pre_status in(2,3)
        ) nro_salidas,
        (
            select ifnull(sum(dtor.dt_cantidad * dpre.dp_cant), 0)
            from detalle_presupuesto dpre
            inner join presupuesto pres on dpre.idpresupuesto=pres.idpresupuesto
            inner join torre torr on dpre.idtorre=torr.idtorre
            inner join detalle_torre dtor on torr.idtorre=dtor.idtorre
            inner join pieza piez on dtor.idpieza=piez.idpieza 
            where piez.idpieza=pie.idpieza and pres.pre_status in(4)
        ) nro_entregados
        from detalle_presupuesto dp
        inner join torre tor on dp.idtorre=tor.idtorre
        inner join detalle_torre dt on tor.idtorre=dt.idtorre
        inner join pieza pie on dt.idpieza=pie.idpieza 
        where dp.idpresupuesto=?";
        $st = $this->db->query($query, [$idpresu]);

        return $st->getResultArray();
    } */

    public function getDetaPresuParaGuia($idpresu){
        $query = "select dp.idpresupuesto,dp.dp_cant,dp.idtorre,tor.tor_desc,pie.idpieza,pie.pie_codigo,pie.pie_desc,pie.pie_cant stock_ini,
        dt.dt_cantidad,(dp.dp_cant * dt.dt_cantidad) cant_req
        from detalle_presupuesto dp
        inner join torre tor on dp.idtorre=tor.idtorre
        inner join detalle_torre dt on tor.idtorre=dt.idtorre
        inner join pieza pie on dt.idpieza=pie.idpieza 
        where dp.idpresupuesto=?";
        $st = $this->db->query($query, [$idpresu]);

        return $st->getResultArray();
    }

    public function getStockPieza($idpieza, $status = [1,2,3], $tipo = 'e'){//$tipo e:entrada, s: salida
        //extraer de los presupuesto las piezas
        $presus = $this->getPresupuestos('','','',$status);
        $suma = 0;
        foreach( $presus as $pre ){
            $piezas  = json_decode($pre['pre_piezas'], true);

            foreach( $piezas as $pi ){
                if( $pi['idpie'] == $idpieza ){
                    //$suma += $pi['dtcan'] * $pi['dpcant'];
                    if( $tipo == 'e' )
                        $suma += $pi['ingresa'];
                    else if( $tipo == 's' )
                        $suma += $pi['st_sale'];
                }
            }

        }

        return $suma;
    }

    public function modificaPresuPiezasEstatus($piezas, $estado, $idpresu){
        $query = "update presupuesto set pre_piezas=?,pre_status=? where idpresupuesto = ?";

        $st = $this->db->query($query, [$piezas, $estado, $idpresu]);

        return $st;
    }

    public function modificaStatusPre($idpresu, $estado){
        $query = "update presupuesto set pre_status=? where idpresupuesto = ?";

        $st = $this->db->query($query, [$estado, $idpresu]);

        return $st;
    }

}