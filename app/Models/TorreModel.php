<?php 
namespace App\Models;

use CodeIgniter\Model;

class TorreModel extends Model{

    public function getTorre($idtorre){
        $query = "select * from torre where idtorre = ?";

        $st = $this->db->query($query, [$idtorre]);

        return $st->getRowArray();
    }

    public function getTorres($desde, $hasta, $cri = ''){
        $sql = $cri != '' ? " and tor_desc LIKE '%" . $this->db->escapeLikeString($cri) . "%' " : '';

        $query = "select * from torre where idtorre is not null $sql
        limit ?,?";

        $st = $this->db->query($query, [$desde, $hasta]);

        return $st->getResultArray();
    }

    public function getTorresCount($cri = ''){
        $sql = $cri != '' ? " and tor_desc LIKE '%" . $this->db->escapeLikeString($cri) . "%' " : '';

        $query = "select count(idtorre) as total from torre where idtorre is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }
    
    public function insertarTorre($desc, $nombre_plano, $idusuario2){
        $query = "insert into torre(tor_desc,tor_plano,idusuario2,tor_fechareg) values(?,?,?,now())";

        $st = $this->db->query($query, [$desc, $nombre_plano, $idusuario2]);

        return $this->db->insertID();
    }

    public function insertarDetalleTorre($idtorre,$idpieza,$cant){
        $query = "insert into detalle_torre(idtorre,idpieza,dt_cantidad) values(?,?,?)";

        $st = $this->db->query($query, [$idtorre,$idpieza,$cant]);

        return $st;
    }

    public function modificarTorre($desc, $nombre_plano, $idtorre){
        $query = "update torre set tor_desc=?,tor_plano=? where idtorre=?";

        $st = $this->db->query($query, [$desc, $nombre_plano, $idtorre]);

        return $st;
    }

    public function getDetalleTorre($idtorre){
        $query = "select dt.idtorre,dt.idpieza,dt.dt_cantidad,pi.pie_desc,pi.pie_precio
        from detalle_torre dt
        inner join pieza pi on dt.idpieza=pi.idpieza
        where dt.idtorre=?";

        $st = $this->db->query($query,[$idtorre]);

        return $st->getResultArray();
    }

    //VERIFICAR SI TIENE REGISTRO EN TABLAS(detalle_presupuesto) la torre A ELIMINAR
    public function verificarTorTieneRegEnTablas($idtorre, $tabla){
        $query = "select count(idtorre) as total from $tabla where idtorre=?";
        $st = $this->db->query($query, [$idtorre]);

        return $st->getRowArray();
    }

    public function eliminarTorre($idtorre){
        $query = "delete from torre where idtorre = ?";
        $st = $this->db->query($query, [$idtorre]);

        return $st;
    }

    public function eliminarDetalle($idtorre){
        $query = "delete from detalle_torre where idtorre = ?";
        $st = $this->db->query($query, [$idtorre]);

        return $st;
    }

    public function eliminarPlano($idtorre){
        $query = "update torre set tor_plano=''  where idtorre = ?";
        $st = $this->db->query($query, [$idtorre]);

        return $st;
    }

    public function getTorresCbo($cri = ''){
        $sql = $cri != '' ? " and tor.tor_desc LIKE '%" . $this->db->escapeLikeString($cri) . "%' " : '';

        $query = "select tor.idtorre,tor.tor_desc,sum(pie.pie_precio * dt.dt_cantidad) as total
        from torre tor
        inner join detalle_torre dt on tor.idtorre=dt.idtorre
        inner join pieza pie on dt.idpieza=pie.idpieza
        where tor.idtorre is not null $sql 
        GROUP by tor.idtorre, tor.tor_desc";

        $st = $this->db->query($query);

        return $st->getResultArray();
    }

}