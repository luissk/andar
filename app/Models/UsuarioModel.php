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

    public function cambiarPassword($idusuario, $password){
        $query = "update usuario set usu_password=? where idusuario=?";
        $st = $this->db->query($query, [$password,$idusuario]);

        return $st;
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

    /* public function existeUsuario_por_UsuDni($opt = 1, $criterio){//1->usuario,2->dni
        if( $opt == 1 ){
            $query = "select idusuario from usuario where LOWER(usu_usuario) = LOWER(?)";
        }else if( $opt == 2 ){
            $query = "select idusuario from usuario where usu_dni = ?";
        }

        $st = $this->db->query($query, [$criterio]);

        return $st->getRowArray();
    } */

    public function getPerfiles(){
        $query = "select * from tipousuario";

        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getUsuarios($desde, $hasta, $cri = ''){
        $sql = $cri != '' ? " and (usu.usu_usuario LIKE '%" . $this->db->escapeLikeString($cri) . "%' or usu.usu_nombres LIKE '%" . $this->db->escapeLikeString($cri) . "%' or usu.usu_apellidos LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

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

    public function insertarUsuario($usuario,$dni,$nombres,$apellidos,$perfil,$password,$idusuario2){
        $query = "insert into usuario(usu_usuario,usu_dni,usu_nombres,usu_apellidos,idtipousuario,usu_password,idusuario2,usu_fechareg) values(?,?,?,?,?,?,?,now())";
        $st = $this->db->query($query, [
            $usuario,$dni,$nombres,$apellidos,$perfil,$password,$idusuario2
        ]);

        return $st;
    }

    public function modificarUsuario($usuario,$dni,$nombres,$apellidos,$perfil,$password,$idusuario){
        $query = "update usuario set usu_usuario=?,usu_dni=?,usu_nombres=?,usu_apellidos=?,idtipousuario=?,usu_password=? where idusuario=?";
        $st = $this->db->query($query, [
            $usuario,$dni,$nombres,$apellidos,$perfil,$password,$idusuario
        ]);

        return $st;
    }

    //VERIFICAR SI TIENE REGISTRO EN TABLAS(usuario,transportista,cliente,pieza,torre,presupuesto,guia,factura) EL USUARIO A ELIMINAR
    public function verificarUsuTieneRegEnTablas($idusuario, $tabla){
        $query = "select count(idusuario2) as total from $tabla where idusuario2=?";
        $st = $this->db->query($query, [$idusuario]);

        return $st->getRowArray();
    }

    public function eliminarUsuario($idusuario){
        $query = "delete from usuario where idusuario = ?";
        $st = $this->db->query($query, [$idusuario]);

        return $st;
    }

}