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

    public function insertarPresupuesto($nroPre,$idusuario2,$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT,$verP,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias){
        $query = "insert into presupuesto(pre_numero,pre_fechareg,idusuario2,idcliente,pre_porcenprecio,pre_porcsem,pre_periodo,pre_periodonro,pre_piezas,pre_verpiezas,pre_tcambio,pre_pentrega,pre_fpago,pre_voferta,pre_lentrega,pre_preciotrans,pre_nrodiasm,pre_status) 
        values(?,now(),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)";

        $st = $this->db->query($query, [$nroPre,$idusuario2,$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT,$verP,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias]);

        return $this->db->insertID();
    }

    public function insertarDetallePresu($idpre,$idtorre,$cant,$tmonto){
        $query = "insert into detalle_presupuesto(idpresupuesto,idtorre,dp_cant,dp_precio) values(?,?,?,?)";

        $st = $this->db->query($query, [$idpre,$idtorre,$cant,$tmonto]);

        return $st;
    }

    public function modificarPresupuesto($cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT,$idpresu,$verP,$nroPre,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias){
        $query = "update presupuesto set idcliente=?,pre_porcenprecio=?,pre_porcsem=?,pre_periodo=?,pre_periodonro=?,pre_piezas=?,pre_verpiezas=?,pre_numero=?,pre_tcambio=?,pre_pentrega=?,pre_fpago=?,pre_voferta=?,pre_lentrega=?,pre_preciotrans=?,pre_nrodiasm=? where idpresupuesto = ? and pre_status=1";

        $st = $this->db->query($query, [$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT,$verP,$nroPre,$tcambio,$pentrega,$fpago,$voferta,$lentrega,$preciotrans,$nrodias,$idpresu]);

        return $st;
    }

    public function getPresupuestos($desde = '', $hasta = '', $cri = '', $status = [1,2,3]){//1->activo, 2->con guía, 3->devuelto
        $sql = $cri != '' ? " and (pre.pre_numero LIKE '%" . $this->db->escapeLikeString($cri) . "%' or cli.cli_nombrerazon LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select pre.idpresupuesto,pre.pre_numero,pre.pre_fechareg,pre.pre_periodo,pre.pre_periodonro,pre.pre_status,pre.pre_piezas,pre.pre_verpiezas,pre.pre_tcambio,
        pre.pre_pentrega,pre.pre_fpago,pre.pre_voferta,pre.pre_lentrega,pre.pre_preciotrans,pre.pre_nrodiasm,
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
        $query = "select pre.idpresupuesto,pre.pre_numero,pre.pre_fechareg,pre.pre_periodo,pre.pre_periodonro,pre.pre_status,pre.pre_porcenprecio,pre.pre_porcsem,pre.pre_piezas,pre.pre_verpiezas,pre.pre_tcambio,pre.pre_pentrega,pre.pre_fpago,pre.pre_voferta,pre.pre_lentrega,pre.pre_preciotrans,pre.pre_nrodiasm,
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
        $query = "select * 
        from detalle_presupuesto dp
        inner join torre tor on dp.idtorre=tor.idtorre
        where dp.idpresupuesto = ?";

        $st = $this->db->query($query, [$idpresu]);

        return $st->getResultArray();
    }

    public function borrarDetallePresupuesto($idpresu){
        $query = "delete from detalle_presupuesto where idpresupuesto=?";

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