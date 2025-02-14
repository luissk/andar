<?php 
namespace App\Models;

use CodeIgniter\Model;

class GuiaModel extends Model{

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

    public function getGuias($desde, $hasta, $cri = ''){
        $sql = $cri != '' ? " and (gui.gui_nro LIKE '%" . $this->db->escapeLikeString($cri) . "%' or gui.gui_vehiculo LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select * from guia gui
        where gui.idguia is not null $sql order by gui.gui_fecha desc
        limit ?,?";

        $st = $this->db->query($query, [$desde, $hasta]);

        return $st->getResultArray();
    }

    public function getGuiasCount($cri = ''){
        $sql = $cri != '' ? " and (gui.gui_nro LIKE '%" . $this->db->escapeLikeString($cri) . "%' or gui.gui_vehiculo LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select count(gui.idguia) as total from guia gui
        where gui.idguia is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

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