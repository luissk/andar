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
}