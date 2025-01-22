<?php 
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model{
    public function validarLogin($usuario){
        $query = "select idusuario, usu_dni, usu_nombres, usu_apellidos, usu_usuario, usu_password, idtipousuario from usuario where LOWER(usu_usuario) = LOWER(?)";
        $st = $this->db->query($query, [$usuario]);

        return $st->getRowArray();
    }
}