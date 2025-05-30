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
        pre.idcliente,pre.pre_piezas,pre.pre_verpiezas,pre.pre_status,pre.pre_numero,
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

        return $st;
    }

    public function modificarGuia($idguia,$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$transportista,$opt,$estado,$nroGuia,$clienterecoge){
        $query = "update guia set gui_fechatraslado=?,gui_motivo=?,gui_motivodesc=?,gui_ptopartida=?,gui_direccionp=?,gui_ptollegada=?,gui_direccionll=?,gui_placa=?,idtransportista=?,gui_completa=?,gui_status=?,gui_nro=?,gui_clienterecoge=? where idguia = ?";

        $st = $this->db->query($query, [$fechatrasl,$motivo,$desc_trasl,$ubigeop,$direccionp,$ubigeoll,$direccionll,$placa,$transportista,$opt,$estado,$nroGuia,$clienterecoge,$idguia]);

        return $st;
    }

    public function eliminarGuia($idguia){
        $query = "delete from guia where idguia = ?";

        $st = $this->db->query($query, [$idguia]);

        return $st;
    }

    public function modificarFechaDevolucionGuia($idguia, $fecha, $completo, $track, $estado){
        $query = "update guia set gui_fechadev=?,gui_devcompleta=?,guia_track=?,gui_status=? where idguia = ?";

        $st = $this->db->query($query, [$fecha, $completo, $track, $estado, $idguia]);

        return $st;
    }

    /* public function registrarFechaEntregado($fechaent, $estado,$idguia){
        $query = "update guia set gui_fechaent=?,gui_status=? where idguia=?";

        $st = $this->db->query($query, [$fechaent, $estado,$idguia]);

        return $st;
    } */

    /* select dp.idpresupuesto,dp.dp_cant,dp.idtorre,tor.tor_desc,pie.idpieza,pie.pie_codigo,pie.pie_desc,pie.pie_cant stock_ini,
dt.dt_cantidad,(dp.dp_cant * dt.dt_cantidad) cant_req
from detalle_presupuesto dp
inner join torre tor on dp.idtorre=tor.idtorre
inner join detalle_torre dt on tor.idtorre=dt.idtorre
inner join pieza pie on dt.idpieza=pie.idpieza 
where dp.idpresupuesto=4; 

select ifnull(sum(dtor.dt_cantidad * dpre.dp_cant), 0)
from detalle_presupuesto dpre
inner join presupuesto pres on dpre.idpresupuesto=pres.idpresupuesto
inner join torre torr on dpre.idtorre=torr.idtorre
inner join detalle_torre dtor on torr.idtorre=dtor.idtorre
inner join pieza piez on dtor.idpieza=piez.idpieza 
where piez.idpieza=6 and pres.idpresupuesto=2  and pres.pre_status in(1)

select dp.idpresupuesto,dp.dp_cant,dp.idtorre,tor.tor_desc,pie.idpieza,pie.pie_codigo,pie.pie_desc,pie.pie_cant stock_ini,
dt.dt_cantidad,(dp.dp_cant * dt.dt_cantidad) cant_req,
(
    select ifnull(sum(dtor.dt_cantidad * dpre.dp_cant), 0)
    from detalle_presupuesto dpre
    inner join presupuesto pres on dpre.idpresupuesto=pres.idpresupuesto
    inner join torre torr on dpre.idtorre=torr.idtorre
    inner join detalle_torre dtor on torr.idtorre=dtor.idtorre
    inner join pieza piez on dtor.idpieza=piez.idpieza 
    where piez.idpieza=pie.idpieza and pres.pre_status in(2,3)
) nro_salidas
from detalle_presupuesto dp
inner join torre tor on dp.idtorre=tor.idtorre
inner join detalle_torre dt on tor.idtorre=dt.idtorre
inner join pieza pie on dt.idpieza=pie.idpieza 
where dp.idpresupuesto=4;
*/
}