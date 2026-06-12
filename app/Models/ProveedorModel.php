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

    public function provTieneSalidas($id){
        $query = "select count(idguia_salida_det) as total from guia_salida_detalle where idproveedor = ?";
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