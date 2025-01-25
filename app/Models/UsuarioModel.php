<?php 
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model{
    public function validarLogin($usuario){
        $query = "select usu.idusuario, usu.usu_dni, usu.usu_nombres, usu.usu_apellidos, usu.usu_usuario, usu.usu_password, usu.idtipousuario,
        tu.tu_tipo 
        from usuario usu
        inner join tipousuario tu on usu.idtipousuario=tu.idtipousuario
        where LOWER(usu_usuario) = LOWER(?)";
        $st = $this->db->query($query, [$usuario]);

        return $st->getRowArray();
    }

    public function getUsuario($idusuario){
        $query = "select usu.idusuario, usu.usu_dni, usu.usu_nombres, usu.usu_apellidos, usu.usu_usuario, usu.usu_password, usu.idtipousuario,
        tu.tu_tipo 
        from usuario usu
        inner join tipousuario tu on usu.idtipousuario=tu.idtipousuario
        where idusuario = ?";

        $st = $this->db->query($query, [$idusuario]);

        return $st->getRowArray();
    }

    public function getPerfiles(){
        $query = "select * from tipousuario";

        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getUsuarios($desde, $hasta, $cri = ''){
        $sql = $cri != '' ? " and usu.usu_usuario LIKE '%" . $this->db->escapeLikeString($cri) . "%' " : '';

        $query = "select usu.idusuario, usu.usu_dni, usu.usu_nombres, usu.usu_apellidos, usu.usu_usuario, usu.usu_password, usu.idtipousuario,
        tu.tu_tipo 
        from usuario usu
        inner join tipousuario tu on usu.idtipousuario=tu.idtipousuario 
        where usu.idusuario is not null $sql
        limit ?,?";

        $st = $this->db->query($query, [$desde, $hasta]);

        return $st->getResultArray();
    }

    public function getUsuariosCount($cri = ''){
        $sql = $cri != '' ? " and usu.usu_usuario LIKE '%" . $this->db->escapeLikeString($cri) . "%' " : '';

        $query = "select count(usu.idusuario) as total 
        from usuario usu
        where usu.idusuario is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

}