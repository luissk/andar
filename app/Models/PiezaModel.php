<?php 
namespace App\Models;

use CodeIgniter\Model;

class PiezaModel extends Model{
    public function getPiezaPorCodigo($codigo){
        $query = "select * from pieza where pie_codigo=?";
        $st = $this->db->query($query, [$codigo]);

        return $st->getRowArray();
    }

    public function getPieza($idpieza){
        $query = "select idpieza, pie_codigo, pie_desc,pie_fechareg,pie_peso,pie_precio,pie_cant,
        ifnull(( select calcularStock(pre_piezas, idpieza, 'st_sale') from presupuesto where pre_status in(2,3) ), 0) as salidas,
        ifnull(( select calcularStock(pre_piezas, idpieza, 'ingresa') from presupuesto where pre_status in(3) ), 0) as entradas,
        (
            pie_cant + 
            ifnull(( select calcularStock(pre_piezas, idpieza, 'ingresa') from presupuesto where pre_status in(3) ), 0) - 
            ifnull(( select calcularStock(pre_piezas, idpieza, 'st_sale') from presupuesto where pre_status in(2,3) ), 0)
        ) as stockActual
        from pieza where idpieza = ?";

        $st = $this->db->query($query, [$idpieza]);

        return $st->getRowArray();
    }

    public function getPiezasAjax($cri = ''){
        $sql = $cri != '' ? " and (pie_codigo LIKE '%" . $this->db->escapeLikeString($cri) . "%' or pie_desc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select idpieza, pie_codigo, pie_desc,pie_fechareg,pie_peso,pie_precio,pie_cant,
        ifnull(( select calcularStock(pre_piezas, idpieza, 'st_sale') from presupuesto where pre_status in(2,3) ), 0) as salidas,
        ifnull(( select calcularStock(pre_piezas, idpieza, 'ingresa') from presupuesto where pre_status in(3) ), 0) as entradas,
        (
            pie_cant + 
            ifnull(( select calcularStock(pre_piezas, idpieza, 'ingresa') from presupuesto where pre_status in(3) ), 0) - 
            ifnull(( select calcularStock(pre_piezas, idpieza, 'st_sale') from presupuesto where pre_status in(2,3) ), 0)
        ) as stockActual
        from pieza 
        where idpieza is not null $sql order by pie_desc asc";

        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getPiezas($desde, $hasta, $cri = '', $campo = 'pie_desc', $order = 'ASC'){
        $sql = $cri != '' ? " and (pie_codigo LIKE '%" . $this->db->escapeLikeString($cri) . "%' or pie_desc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select idpieza, pie_codigo, pie_desc,pie_fechareg,pie_peso,pie_precio,pie_cant,
        ifnull(( select calcularStock(pre_piezas, idpieza, 'st_sale') from presupuesto where pre_status in(2,3) ), 0) as salidas,
        ifnull(( select calcularStock(pre_piezas, idpieza, 'ingresa') from presupuesto where pre_status in(3) ), 0) as entradas,
        (
            pie_cant + 
            ifnull(( select calcularStock(pre_piezas, idpieza, 'ingresa') from presupuesto where pre_status in(3) ), 0) - 
            ifnull(( select calcularStock(pre_piezas, idpieza, 'st_sale') from presupuesto where pre_status in(2,3) ), 0)
        ) as stockActual
        from pieza 
        where idpieza is not null $sql order by $campo $order
        limit ?,?";

        $st = $this->db->query($query, [$desde, $hasta]);

        return $st->getResultArray();
    }

    public function getPiezasCount($cri = ''){
        $sql = $cri != '' ? " and (pie_codigo LIKE '%" . $this->db->escapeLikeString($cri) . "%' or pie_desc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select count(idpieza) as total 
        from pieza where idpieza is not null $sql";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function insertarPieza($codigo,$desc,$peso,$precio,$cantidad,$idusuario2){
        $query = "insert into pieza(pie_codigo,pie_desc,pie_peso,pie_precio,pie_cant,idusuario2,pie_fechareg) values(?,?,?,?,?,?,now())";
        $st = $this->db->query($query, [
            $codigo,$desc,$peso,$precio,$cantidad,$idusuario2
        ]);

        return $st;
    }

    public function modificarPieza($codigo,$desc,$peso,$precio,$cantidad,$idpieza){
        $query = "update pieza set pie_codigo=?,pie_desc=?,pie_peso=?,pie_precio=?,pie_cant=? where idpieza=?";
        $st = $this->db->query($query, [
            $codigo,$desc,$peso,$precio,$cantidad,$idpieza
        ]);

        return $st;
    }

    //VERIFICAR SI TIENE REGISTRO EN TABLAS(detalle_torre) EL USUARIO A ELIMINAR
    public function verificarPieTieneRegEnTablas($idpieza, $tabla){
        $query = "select count(idpieza) as total from $tabla where idpieza=?";
        $st = $this->db->query($query, [$idpieza]);

        return $st->getRowArray();
    }

    public function eliminarPieza($idpieza){
        $query = "delete from pieza where idpieza = ?";
        $st = $this->db->query($query, [$idpieza]);

        return $st;
    }

}