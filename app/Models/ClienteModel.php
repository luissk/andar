<?php 
namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model{
    public function getClientePorDniRuc($dniruc){
        $query = "select idcliente from cliente where cli_dniruc = ?";
        $st = $this->db->query($query, [$dniruc]);

        return $st->getRowArray();
    }


    public function getCliente($idcliente){
        $query = "select * from cliente where idcliente = ?";

        $st = $this->db->query($query, [$idcliente]);

        return $st->getRowArray();
    }

    public function getClientes($desde, $hasta, $cri = ''){
        $sql = $cri != '' ? " and (cli_nombrerazon LIKE '%" . $this->db->escapeLikeString($cri) . "%' or cli_dniruc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select * from cliente 
        where idcliente is not null $sql
        limit ?,?";

        $st = $this->db->query($query, [$desde, $hasta]);

        return $st->getResultArray();
    }

    public function getClientesCount($cri = ''){
        $sql = $cri != '' ? " and (cli_nombrerazon LIKE '%" . $this->db->escapeLikeString($cri) . "%' or cli_dniruc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select count(idcliente) as total 
        from cliente
        where idcliente is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function insertarCliente($dniruc,$nombrer,$nombrec,$correoc,$telefc,$idusuario2){
        $query = "insert into cliente(cli_dniruc,cli_nombrerazon,cli_nombrecontact,cli_correocontact,cli_telefcontact,idusuario2,cli_fechareg) values(?,?,?,?,?,?,now())";
        $st = $this->db->query($query, [
            $dniruc,$nombrer,$nombrec,$correoc,$telefc,$idusuario2
        ]);

        return $st;
    }

    public function modificarCliente($dniruc,$nombrer,$nombrec,$correoc,$telefc,$idcliente){
        $query = "update cliente set cli_dniruc=?,cli_nombrerazon=?,cli_nombrecontact=?,cli_correocontact=?,cli_telefcontact=? where idcliente=?";
        $st = $this->db->query($query, [
            $dniruc,$nombrer,$nombrec,$correoc,$telefc,$idcliente
        ]);

        return $st;
    }

    //VERIFICAR SI TIENE REGISTRO EN TABLAS(presupuesto) EL USUARIO A ELIMINAR
    public function verificarCliTieneRegEnTablas($idcliente, $tabla){
        $query = "select count(idcliente) as total from $tabla where idcliente=?";
        $st = $this->db->query($query, [$idcliente]);

        return $st->getRowArray();
    }

    public function eliminarCliente($idcliente){
        $query = "delete from cliente where idcliente = ?";
        $st = $this->db->query($query, [$idcliente]);

        return $st;
    }

}