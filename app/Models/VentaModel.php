<?php 
namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model{

    public function insertarVenta($nrodoc,$fecha,$cliente,$ruc,$idusuario){
        $query = "insert into venta(ven_nrodoc,ven_fecha,ven_cliente,ven_ruc,idusuario) values(?,?,?,?,?)";

        $st = $this->db->query($query, [$nrodoc,$fecha,$cliente,$ruc, $idusuario]);

        return $this->db->insertID();
    }

    public function insertarDetalleVenta($idventa,$idpieza,$cant,$preciov){
        $query = "insert into detalle_venta(idventa,idpieza,cantidad,precioven) values(?,?,?,?)";

        $st = $this->db->query($query, [$idventa,$idpieza,$cant,$preciov]);

        return $st;
    }

    public function modificarVenta($idventa,$nrodoc,$fecha,$cliente,$ruc){
        $query = "update venta set ven_nrodoc = ?,ven_fecha = ?,ven_cliente = ?,ven_ruc = ? where idventa = ?";

        $st = $this->db->query($query, [$nrodoc,$fecha,$cliente,$ruc, $idventa]);

        return $st;
    }

    public function getVentas($desde = '', $hasta = '', $cri = ''){
        $sql = $cri != '' ? " and (ven_cliente LIKE '%" . $this->db->escapeLikeString($cri) . "%' || ven_ruc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        if( $desde != '' && $hasta != '' ){
            $sql .= " limit $desde,$hasta";
        }

        $query = "select * from venta where idventa is not null $sql";

        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getVentasCount($cri = ''){
        $sql = $cri != '' ? " and (ven_cliente LIKE '%" . $this->db->escapeLikeString($cri) . "%' || ven_ruc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select count(idventa) as total from venta where idventa is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function getVenta($idventa){
        $query = "select * from venta where idventa = ?";

        $st = $this->db->query($query, [$idventa]);

        return $st->getRowArray();
    }

    public function getDetalleVenta($idventa){
        $query = "select dv.idventa,dv.idpieza,dv.cantidad,dv.precioven,
        pie.pie_desc,pie.pie_codigo
        from detalle_venta dv
        inner join pieza pie on dv.idpieza=pie.idpieza
        where dv.idventa = ?";

        $st = $this->db->query($query, [$idventa]);

        return $st->getResultArray();
    }

    public function eliminarDetalle($idventa){
        $query = "delete from detalle_venta where idventa=?";

        $st = $this->db->query($query, [$idventa]);

        return $st;
    }

    public function eliminarVenta($idventa){
        $query = "delete from venta where idventa=?";

        $st = $this->db->query($query, [$idventa]);

        return $st;
    }

}