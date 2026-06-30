<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProveedorModel extends Model{

    public function getProveedores(){
        $query = "select * from proveedor";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getProveedor($id){
        $query = "select * from proveedor where idproveedor = ?";
        $st = $this->db->query($query, [$id]);

        return $st->getRowArray();
    }

    public function insertarProveedor($data){
        $query = "insert into proveedor(pro_ruc,pro_razon) values(?,upper(?))";
        $st = $this->db->query($query, [
            $data['ruc'], $data['razon']
        ]);

        return $st;
    }

    public function modificarProveedor($data, $id){
        $query = "update proveedor set pro_ruc = ?, pro_razon = upper(?) where idproveedor = ?";
        $st = $this->db->query($query, [
            $data['ruc'], $data['razon'], $id
        ]);

        return $st;
    }

    public function verificarProTieneRegEnTablas($id, $tabla){
        $query = "select count(idproveedor) as total from $tabla where idproveedor=?";
        $st = $this->db->query($query, [$id]);

        return $st->getRowArray();
    }

    public function eliminarProveedor($id){
        $query = "delete from proveedor  where idproveedor = ?";
        $st = $this->db->query($query, [
            $id
        ]);

        return $st;
    }

}