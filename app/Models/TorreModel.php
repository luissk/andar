<?php 
namespace App\Models;

use CodeIgniter\Model;

class TorreModel extends Model{
    
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



}