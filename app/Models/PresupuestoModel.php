<?php 
namespace App\Models;

use CodeIgniter\Model;

class PresupuestoModel extends Model{

    public function nroPresupuesto(){
        $query = "select 
        concat( 
            LPAD(
                case when convert(substring(max(pre_numero),1,4),DECIMAL) is NULL then 0 else convert(substring(max(pre_numero),1,4),DECIMAL) end + 1
                , 4, '0'
            ), '-',YEAR(now())
        ) as nro
        from presupuesto 
        where substring(pre_numero,6,4) = YEAR(now())";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function insertarPresupuesto($nroPre,$idusuario2,$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT){
        $query = "insert into presupuesto(pre_numero,pre_fechareg,idusuario2,idcliente,pre_porcenprecio,pre_porcsem,pre_periodo,pre_periodonro,pre_piezas,pre_status) 
        values(?,now(),?,?,?,?,?,?,?,1)";

        $st = $this->db->query($query, [$nroPre,$idusuario2,$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT]);

        return $this->db->insertID();
    }

    public function insertarDetallePresu($idpre,$idtorre,$cant,$tmonto){
        $query = "insert into detalle_presupuesto(idpresupuesto,idtorre,dp_cant,dp_precio) values(?,?,?,?)";

        $st = $this->db->query($query, [$idpre,$idtorre,$cant,$tmonto]);

        return $st;
    }

    public function modificarPresupuesto($cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT,$idpresu){
        $query = "update presupuesto set idcliente=?,pre_porcenprecio=?,pre_porcsem=?,pre_periodo=?,pre_periodonro=?,pre_piezas=? where idpresupuesto = ?";

        $st = $this->db->query($query, [$cliente,$porcpre,$porcsem,$periodo,$nroperiodo,$arrDT,$idpresu]);

        return $st;
    }

    public function getPresupuestos($desde, $hasta, $cri = ''){
        $sql = $cri != '' ? " and (pre.pre_numero LIKE '%" . $this->db->escapeLikeString($cri) . "%' or cli.cli_nombrerazon LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select pre.idpresupuesto,pre.pre_numero,pre.pre_fechareg,pre.pre_periodo,pre.pre_periodonro,pre.pre_status,
        cli.cli_dniruc,cli.cli_nombrerazon,cli.cli_nombrecontact,cli.cli_correocontact,cli.cli_telefcontact,
        usu.usu_usuario,usu.usu_nombres,usu.usu_apellidos
        from presupuesto pre 
        inner join cliente cli on pre.idcliente=cli.idcliente
        inner join usuario usu on pre.idusuario2=usu.idusuario
        where pre.idpresupuesto is not null $sql order by pre.pre_fechareg desc
        limit ?,?";

        $st = $this->db->query($query, [$desde, $hasta]);

        return $st->getResultArray();
    }

    public function getPresupuestosCount($cri = ''){
        $sql = $cri != '' ? " and (pre.pre_numero LIKE '%" . $this->db->escapeLikeString($cri) . "%' or cli.cli_nombrerazon LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select count(pre.idpresupuesto) as total
        from presupuesto pre 
        inner join cliente cli on pre.idcliente=cli.idcliente
        inner join usuario usu on pre.idusuario2=usu.idusuario
        where pre.idpresupuesto is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function getPresupuesto($idpresu){
        $query = "select pre.idpresupuesto,pre.pre_numero,pre.pre_fechareg,pre.pre_periodo,pre.pre_periodonro,pre.pre_status,pre.pre_porcenprecio,pre.pre_porcsem,pre.pre_piezas,
        cli.idcliente,cli.cli_dniruc,cli.cli_nombrerazon,cli.cli_nombrecontact,cli.cli_correocontact,cli.cli_telefcontact,
        usu.usu_usuario,usu.usu_nombres,usu.usu_apellidos,usu.usu_dni
        from presupuesto pre 
        inner join cliente cli on pre.idcliente=cli.idcliente
        inner join usuario usu on pre.idusuario2=usu.idusuario
        where pre.idpresupuesto = ?";

        $st = $this->db->query($query, [$idpresu]);

        return $st->getRowArray();
    }

    public function getDetallePresupuesto($idpresu){
        $query = "select * 
        from detalle_presupuesto dp
        inner join torre tor on dp.idtorre=tor.idtorre
        where dp.idpresupuesto = ?";

        $st = $this->db->query($query, [$idpresu]);

        return $st->getResultArray();
    }

    public function borrarDetallePresupuesto($idpresu){
        $query = "delete from detalle_presupuesto where idpresupuesto=?";

        $st = $this->db->query($query, [$idpresu]);

        return $st;
    }
    
    //VERIFICAR SI TIENE REGISTRO EN TABLAS(guia,detalle_factura) el presupuesto A ELIMINAR
    public function verificarPresuTieneRegEnTablas($idpresu, $tabla){
        $query = "select count(idpresupuesto) as total from $tabla where idpresupuesto=?";
        $st = $this->db->query($query, [$idpresu]);

        return $st->getRowArray();
    }

    public function eliminarPresupuesto($idpresu){
        $query = "delete from presupuesto where idpresupuesto=?";

        $st = $this->db->query($query, [$idpresu]);

        return $st;
    }

}