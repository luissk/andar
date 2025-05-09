<?php 
namespace App\Models;

use CodeIgniter\Model;

class CompraModel extends Model{

    public function insertarCompra($nrodoc,$fecha,$proveedor,$ruc,$idusuario){
        $query = "insert into compra(com_nrodoc,com_fecha,com_proveedor,com_ruc,idusuario) values(?,?,?,?,?)";

        $st = $this->db->query($query, [$nrodoc,$fecha,$proveedor,$ruc, $idusuario]);

        return $this->db->insertID();
    }

    public function insertarDetalleCompra($idcompra,$idpieza,$cant,$precioc){
        $query = "insert into detalle_compra(idcompra,idpieza,cantidad,preciocom) values(?,?,?,?)";

        $st = $this->db->query($query, [$idcompra,$idpieza,$cant,$precioc]);

        return $st;
    }

    public function modificarCompra($idcompra,$nrodoc,$fecha,$proveedor,$ruc){
        $query = "update compra set com_nrodoc = ?,com_fecha = ?,com_proveedor = ?,com_ruc = ? where idcompra = ?";

        $st = $this->db->query($query, [$nrodoc,$fecha,$proveedor,$ruc, $idcompra]);

        return $st;
    }

    public function getCompras($desde = '', $hasta = '', $cri = ''){
        $sql = $cri != '' ? " and (com_proveedor LIKE '%" . $this->db->escapeLikeString($cri) . "%' || com_ruc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        if( $desde != '' && $hasta != '' ){
            $sql .= " limit $desde,$hasta";
        }

        $query = "select * from compra where idcompra is not null $sql";

        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getComprasCount($cri = ''){
        $sql = $cri != '' ? " and (com_proveedor LIKE '%" . $this->db->escapeLikeString($cri) . "%' || com_ruc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select count(idcompra) as total from compra where idcompra is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function getCompra($idcompra){
        $query = "select * from compra where idcompra = ?";

        $st = $this->db->query($query, [$idcompra]);

        return $st->getRowArray();
    }

    public function getDetalleCompra($idcompra){
        $query = "select dc.idcompra,dc.idpieza,dc.cantidad,dc.preciocom,
        pie.pie_desc,pie.pie_codigo
        from detalle_compra dc
        inner join pieza pie on dc.idpieza=pie.idpieza
        where dc.idcompra = ?";

        $st = $this->db->query($query, [$idcompra]);

        return $st->getResultArray();
    }

    public function eliminarDetalle($idcompra){
        $query = "delete from detalle_compra where idcompra=?";

        $st = $this->db->query($query, [$idcompra]);

        return $st;
    }

    public function eliminarCompra($idcompra){
        $query = "delete from compra where idcompra=?";

        $st = $this->db->query($query, [$idcompra]);

        return $st;
    }

}