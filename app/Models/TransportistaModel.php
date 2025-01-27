<?php 
namespace App\Models;

use CodeIgniter\Model;

class TransportistaModel extends Model{

    public function getTransportista($idtrans){
        $query = "select * from transportista where idtransportista = ?";

        $st = $this->db->query($query, [$idtrans]);

        return $st->getRowArray();
    }
    
    public function getTransportistas($desde, $hasta, $cri = ''){
        $sql = $cri != '' ? " and (tra_nombres LIKE '%" . $this->db->escapeLikeString($cri) . "%' or tra_apellidos LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select * from transportista 
            where idtransportista is not null $sql
            limit ?,?";

        $st = $this->db->query($query, [$desde, $hasta]);

        return $st->getResultArray();
    }

    public function getTransportistasCount($cri = ''){
        $sql = $cri != '' ? " and (tra_nombres LIKE '%" . $this->db->escapeLikeString($cri) . "%' or tra_apellidos LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select count(idtransportista) as total 
        from transportista
        where idtransportista is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function insertarTransportista($nombres,$apellidos,$dni,$telefono,$idusuario2){
        $query = "insert INTO transportista (tra_nombres, tra_apellidos, tra_dni, tra_telef, idusuario2,tra_fechareg) VALUES (?,?,?,?,?,now())";
        $st = $this->db->query($query, [
            $nombres,$apellidos,$dni,$telefono,$idusuario2
        ]);

        return $st;
    }

    public function modificarTransportista($nombres,$apellidos,$dni,$telefono,$idtrans){
        $query = "update transportista set tra_nombres=?, tra_apellidos=?, tra_dni=?, tra_telef=? where idtransportista=?";
        $st = $this->db->query($query, [
            $nombres,$apellidos,$dni,$telefono,$idtrans
        ]);

        return $st;
    }

    //VERIFICAR SI TIENE REGISTRO EN TABLAS(usuario,transportista,cliente,pieza,torre,presupuesto,guia,factura) EL USUARIO A ELIMINAR
    public function verificarTransTieneRegEnTablas($idtrans, $tabla){
        $query = "select count(idtransportista) as total from $tabla where idtransportista=?";
        $st = $this->db->query($query, [$idtrans]);

        return $st->getRowArray();
    }

    public function eliminarTransportista($idtrans){
        $query = "delete from transportista where idtransportista = ?";
        $st = $this->db->query($query, [$idtrans]);

        return $st;
    }


}