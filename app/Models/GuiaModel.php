<?php 
namespace App\Models;

use CodeIgniter\Model;

class GuiaModel extends Model{

    public function nroGuia(){
        $query = "select 
        concat(
            'EG01-',
            LPAD(
                case when convert(substring(max(gui_nro),6,8),DECIMAL) is NULL then 0 else convert(substring(max(gui_nro),6,8),DECIMAL) end + 1
                , 8, '0'
            )
        ) as nro
        from guia";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function getGuia_x_nroGuia($nroGuia){
        $query = "select idguia from guia where gui_nro=?";

        $st = $this->db->query($query, [$nroGuia]);

        return $st->getRowArray();
    }

    public function getGuias($desde, $hasta, $cri = ''){
        $sql = $cri != '' ? " and (gui.gui_nro LIKE '%" . $this->db->escapeLikeString($cri) . "%' or cli.cli_nombrerazon LIKE '%" . $this->db->escapeLikeString($cri) . "%' or pre.pre_numero LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select gui.idguia,gui.gui_nro,gui.gui_fecha,gui.gui_fechatraslado,gui.gui_motivo,gui.gui_motivodesc,gui.gui_ptopartida,gui.gui_direccionp,
        gui.gui_ptollegada,gui.gui_direccionll,gui.gui_placa,gui.idpresupuesto,gui.idtransportista,gui.gui_completa,gui.gui_status,gui.gui_fechaent,gui.gui_fechadev,
        gui.gui_devcompleta,gui.guia_track,gui.gui_clienterecoge,
        pre.idcliente,pre.pre_piezas,pre.pre_verpiezas,pre.pre_status,pre.pre_numero,
        tran.tra_nombres,tran.tra_apellidos,tran.tra_dni,tran.tra_telef,
        cli.cli_dniruc,cli.cli_nombrerazon,cli.cli_nombrecontact,cli.cli_correocontact,cli.cli_telefcontact
        from guia gui
        inner join presupuesto pre on gui.idpresupuesto=pre.idpresupuesto
        inner join transportista tran on gui.idtransportista=tran.idtransportista
        inner join cliente cli on pre.idcliente=cli.idcliente
        where gui.idguia is not null $sql order by gui.gui_fecha desc
        limit ?,?";

        $st = $this->db->query($query, [$desde, $hasta]);

        return $st->getResultArray();
    }

    public function getGuiasCount($cri = ''){
        $sql = $cri != '' ? " and (gui.gui_nro LIKE '%" . $this->db->escapeLikeString($cri) . "%' or cli.cli_nombrerazon LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select count(gui.idguia) as total 
        from guia gui
        inner join presupuesto pre on gui.idpresupuesto=pre.idpresupuesto
        inner join transportista tran on gui.idtransportista=tran.idtransportista
        inner join cliente cli on pre.idcliente=cli.idcliente
        where gui.idguia is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function getGuia($idguia, $estado = [2,3]){
        $query = "select gui.idguia,gui.gui_nro,gui.gui_fecha,gui.gui_fechatraslado,gui.gui_motivo,gui.gui_motivodesc,gui.gui_ptopartida,gui.gui_direccionp,
        gui.gui_ptollegada,gui.gui_direccionll,gui.gui_placa,gui.idpresupuesto,gui.idtransportista,gui.gui_completa,gui.gui_status,gui.gui_fechaent,gui.gui_fechadev,
        gui.gui_devcompleta,gui.guia_track,gui.gui_clienterecoge,
        pre.idcliente,pre.pre_piezas,pre.pre_verpiezas,pre.pre_status,pre.pre_numero,pre.pre_ruc,
        tran.tra_nombres,tran.tra_apellidos,tran.tra_dni,tran.tra_telef,
        cli.cli_dniruc,cli.cli_nombrerazon,cli.cli_nombrecontact,cli.cli_correocontact,cli.cli_telefcontact,
        ubip.iddepa iddepap,ubip.idprov idprovp,ubip.iddist iddistp,ubip.depa depap,ubip.prov provp, ubip.dist distp,
        ubill.iddepa iddepall,ubill.idprov idprovll,ubill.iddist iddistll,ubill.depa depall,ubill.prov provll, ubill.dist distll
        from guia gui
        inner join presupuesto pre on gui.idpresupuesto=pre.idpresupuesto
        inner join transportista tran on gui.idtransportista=tran.idtransportista
        inner join cliente cli on pre.idcliente=cli.idcliente
        inner join ubigeo ubip on gui.gui_ptopartida=ubip.idubigeo
        inner join ubigeo ubill on gui.gui_ptollegada=ubill.idubigeo
        where gui.idguia=? and gui.gui_status in ?";

        $st = $this->db->query($query, [$idguia, $estado]);

        return $st->getRowArray();
    }

    public function generarGuia($nroGuia,$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$idpre,$transportista,$idusuario2,$opt,$estado,$clienterecoge){
        $query = "insert into guia(gui_nro,gui_fecha,gui_fechatraslado,gui_motivo,gui_motivodesc,gui_ptopartida,gui_direccionp,gui_ptollegada,gui_direccionll,gui_placa,idpresupuesto,idtransportista,idusuario2,gui_completa,gui_status,gui_clienterecoge) values(?,now(),?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $st = $this->db->query($query, [$nroGuia,$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$idpre,$transportista,$idusuario2,$opt,$estado,$clienterecoge]);

        //return $st;
        return $this->db->insertID();
    }

    public function modificarGuia($idguia,$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$transportista,$opt,$estado,$nroGuia,$clienterecoge){
        $query = "update guia set gui_fechatraslado=?,gui_motivo=?,gui_motivodesc=?,gui_ptopartida=?,gui_direccionp=?,gui_ptollegada=?,gui_direccionll=?,gui_placa=?,idtransportista=?,gui_completa=?,gui_status=?,gui_nro=?,gui_clienterecoge=? where idguia = ?";

        $st = $this->db->query($query, [$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$transportista,$opt,$estado,$nroGuia,$clienterecoge,$idguia]);

        return $st;
    }

    public function guiaGuardadaDetalle($idguia){
        $sql_detalles = "SELECT idpieza, cantidad_enviada, dp_origen, idproveedor 
                     FROM guia_salida_detalle 
                     WHERE idguia = ?";
        $detalles_reg = $this->db->query($sql_detalles, [$idguia])->getResultArray();

        // 3. CONSTRUIR LA MATRIZ DE PRECARGA PARA JAVASCRIPT
        $guia_guardada = [];

        foreach ($detalles_reg as $det) {
            $idpieza = intval($det['idpieza']);

            // Si es la primera vez que mapeamos esta pieza en el bucle, inicializamos su estructura
            if (!isset($guia_guardada[$idpieza])) {
                $guia_guardada[$idpieza] = [
                    'propio'  => 0,
                    'externo' => []
                ];
            }

            if ($det['dp_origen'] === 'propio') {
                // Asignamos la cantidad que salió del stock de la empresa
                $guia_guardada[$idpieza]['propio'] = intval($det['cantidad_enviada']);
            } else if ($det['dp_origen'] === 'externo') {
                // Insertamos en el sub-array cada uno de los proveedores de alquiler asociados
                $guia_guardada[$idpieza]['externo'][] = [
                    'id_proveedor' => intval($det['idproveedor']),
                    'cantidad'     => intval($det['cantidad_enviada'])
                ];
            }
        }

        // 4. Enviamos la matriz estructurada al controlador
        return $guia_guardada;
    }

    public function eliminarGuia($idguia){
        $query = "delete from guia where idguia = ?";

        $st = $this->db->query($query, [$idguia]);

        return $st;
    }

    public function modificarFechaDevolucionGuia($idguia, $fecha, $completo, $estado){
        $query = "update guia set gui_fechadev=?,gui_devcompleta=?,gui_status=? where idguia = ?";

        $st = $this->db->query($query, [$fecha, $completo, $estado, $idguia]);

        return $st;
    }

    public function eliminarDevolucionCompleta($idguia){
        $query = "delete from guia_devolucion_detalle where idguia = ?";

        $st = $this->db->query($query, [$idguia]);

        return $st;
    }

    public function paraDevolver($idpresupuesto, $idguia){
        $sql_piezas = "SELECT 
                gsd.idpieza,
                
                -- CAMBIO 1: Traemos el nombre de forma aislada para que las torres no dupliquen las filas
                (SELECT dp.dp_desc_hist 
                 FROM detalle_presupuesto_piezas dp 
                 WHERE dp.idpresupuesto = ? AND dp.idpieza = gsd.idpieza LIMIT 1) AS pieza_nombre,
                
                -- 1. TOTAL ENVIADO: Sumamos todo lo que salió en esta guía específica
                SUM(gsd.cantidad_enviada) AS cantidad_total_enviada,

                -- 2. ENVIADO PROPIO: Cantidad neta de la empresa que salió
                SUM(CASE WHEN gsd.dp_origen = 'propio' THEN gsd.cantidad_enviada ELSE 0 END) AS cant_enviada_propio,

                -- 3. DEVUELTO PROPIO: Lo que ya regresó al almacén de nuestro stock para esta guía
                (SELECT IFNULL(SUM(gdd.cantidad_devuelta), 0) 
                 FROM guia_devolucion_detalle gdd 
                 WHERE gdd.idguia = gsd.idguia 
                   AND gdd.idpieza = gsd.idpieza 
                   AND gdd.dp_origen = 'propio') AS cant_devuelta_propio

               FROM guia_salida_detalle gsd
               -- CAMBIO 2: Eliminamos el INNER JOIN con detalle_presupuesto_piezas de aquí
               WHERE gsd.idguia = ?
               GROUP BY gsd.idpieza";

        $piezas_obra = $this->db->query($sql_piezas, [$idpresupuesto, $idguia])->getResultArray();

        foreach ($piezas_obra as $key => $pieza) {
            $idpieza = $pieza['idpieza'];

            // Consulta para descubrir qué proveedores externos enviaron esta pieza en esta guía
            $sql_externos = "SELECT 
                            gsd.idproveedor,
                            p.pro_razon, -- Asumiendo que tu tabla de proveedores se llama 'proveedor'
                            SUM(gsd.cantidad_enviada) AS cant_enviada_ext,
                            
                            -- Restamos lo que ya se le devolvió a este proveedor específico
                            (SELECT IFNULL(SUM(gdd.cantidad_devuelta), 0) 
                            FROM guia_devolucion_detalle gdd 
                            WHERE gdd.idguia = gsd.idguia 
                            AND gdd.idpieza = gsd.idpieza 
                            AND gdd.idproveedor = gsd.idproveedor 
                            AND gdd.dp_origen = 'externo') AS cant_devuelta_ext
                        FROM guia_salida_detalle gsd
                        INNER JOIN proveedor p ON gsd.idproveedor = p.idproveedor
                        WHERE gsd.idguia = ? 
                        AND gsd.idpieza = ? 
                        AND gsd.dp_origen = 'externo'
                        GROUP BY gsd.idproveedor";

            $piezas_obra[$key]['externos'] = $this->db->query($sql_externos, [$idguia, $idpieza])->getResultArray();

            $sql_historial = "SELECT 
                    gdd.idguia_dev_det,
                    gdd.cantidad_devuelta, 
                    gdd.dp_origen, 
                    gdd.gdd_fecha,
                    p.pro_razon
                    FROM guia_devolucion_detalle gdd
                    LEFT JOIN proveedor p ON gdd.idproveedor = p.idproveedor
                    WHERE gdd.idguia = ? 
                    AND gdd.idpieza = ?
                    ORDER BY gdd.gdd_fecha DESC";

            $piezas_obra[$key]['historial_devoluciones'] = $this->db->query($sql_historial, [$idguia, $idpieza])->getResultArray();
        }

        return $piezas_obra;
    }

}