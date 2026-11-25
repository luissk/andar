<?php 
namespace App\Models;

use CodeIgniter\Model;

class PiezaModel extends Model{
    public function getPiezaPorCodigo($codigo){
        $query = "select * from pieza where pie_codigo=?";
        $st = $this->db->query($query, [$codigo]);

        return $st->getRowArray();
    }

    public function getPieza($idpieza){//MODIFICAR MAS ADELANTE
        $query = "select idpieza, pie_codigo, pie_desc,pie_fechareg,pie_peso,pie_precio,pie_cant 
        from pieza where idpieza = ?";

        $st = $this->db->query($query, [$idpieza]);

        return $st->getRowArray();
    }

    public function listarStockDePiezas($idsPiezas = []) {
        // Si por alguna razón el array llega vacío, evitamos lanzar un error de sintaxis en el IN de SQL
        if (empty($idsPiezas)) {
            return [];
        }

        $query = "SELECT 
                        p.idpieza,
                        p.pie_cant AS stock_inicial,
                        
                        -- 1. STOCK ALQUILADO: Lo que realmente está afuera en obra hoy (Salidas - Devoluciones)
                        (
                            IFNULL((SELECT SUM(gsd.cantidad_enviada) 
                                    FROM guia_salida_detalle gsd 
                                    WHERE gsd.idpieza = p.idpieza AND gsd.dp_origen = 'propio'), 0) 
                            - 
                            IFNULL((SELECT SUM(gdd.cantidad_devuelta) 
                                    FROM guia_devolucion_detalle gdd 
                                    WHERE gdd.idpieza = p.idpieza AND gdd.dp_origen = 'propio'), 0)
                        ) AS stock_alquilado,

                        -- 2. STOCK ACTUAL REAL: El físico disponible en tu almacén en este microsegundo
                        (p.pie_cant - (
                            IFNULL((SELECT SUM(gsd.cantidad_enviada) 
                                    FROM guia_salida_detalle gsd 
                                    WHERE gsd.idpieza = p.idpieza AND gsd.dp_origen = 'propio'), 0) 
                            - 
                            IFNULL((SELECT SUM(gdd.cantidad_devuelta) 
                                    FROM guia_devolucion_detalle gdd 
                                    WHERE gdd.idpieza = p.idpieza AND gdd.dp_origen = 'propio'), 0)
                        )) AS stock_actual_real

                    FROM pieza p 
                    WHERE p.idpieza IN ?";

        // Se ejecuta pasando el array limpio con tu estructura original
        $st = $this->db->query($query, [$idsPiezas]);
        return $st->getResultArray();
    }

    public function getPiezasAjax($cri = ''){
        $sql = $cri != '' ? " and p.pie_desc LIKE '%" . $this->db->escapeLikeString($cri) . "%' " : '';
        $query = "SELECT 
            p.idpieza,
            p.pie_codigo,
            p.pie_desc,
            p.pie_peso,
            p.pie_precio,
            p.pie_fechareg,
            p.pie_cant AS cantidad_inicial,
            p.pie_cant AS stock_inicial,
            
            -- 1. EN OBRA (PROPIO): Tu material físico que está fuera del almacén actualmente
            (
                IFNULL((SELECT SUM(gsd.cantidad_enviada) 
                        FROM guia_salida_detalle gsd 
                        WHERE gsd.idpieza = p.idpieza AND gsd.dp_origen = 'propio'), 0) 
                - 
                IFNULL((SELECT SUM(gdd.cantidad_devuelta) 
                        FROM guia_devolucion_detalle gdd 
                        WHERE gdd.idpieza = p.idpieza AND gdd.dp_origen = 'propio'), 0)
            ) AS stock_en_obra,

            -- 2. ALQUILADO A TERCEROS (EXTERNO): Lo que traes de proveedores externos para cubrir faltantes
            (
                IFNULL((SELECT SUM(gsd.cantidad_enviada) 
                        FROM guia_salida_detalle gsd 
                        WHERE gsd.idpieza = p.idpieza AND gsd.dp_origen = 'externo'), 0) 
                - 
                IFNULL((SELECT SUM(gdd.cantidad_devuelta) 
                        FROM guia_devolucion_detalle gdd 
                        WHERE gdd.idpieza = p.idpieza AND gdd.dp_origen = 'externo'), 0)
            ) AS stock_alquilado_externo,

            -- 3. STOCK ACTUAL REAL: Tu inventario físico disponible en patio (Stock Inicial - Lo que está en obra)
            (p.pie_cant - (
                IFNULL((SELECT SUM(gsd.cantidad_enviada) 
                        FROM guia_salida_detalle gsd 
                        WHERE gsd.idpieza = p.idpieza AND gsd.dp_origen = 'propio'), 0) 
                - 
                IFNULL((SELECT SUM(gdd.cantidad_devuelta) 
                        FROM guia_devolucion_detalle gdd 
                        WHERE gdd.idpieza = p.idpieza AND gdd.dp_origen = 'propio'), 0)
            )) AS stock_actual_real

        FROM pieza p 
        WHERE p.idpieza IS NOT NULL $sql
        ORDER BY p.idpieza DESC";

        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getPiezas($desde, $hasta, $cri = '', $campo = 'pie_desc', $order = 'ASC'){//MODIFICAR MAS ADELANTE
        $sql = $cri != '' ? " and (pie_codigo LIKE '%" . $this->db->escapeLikeString($cri) . "%' or pie_desc LIKE '%" . $this->db->escapeLikeString($cri) . "%') " : '';

        $query = "select idpieza, pie_codigo, pie_desc,pie_fechareg,pie_peso,pie_precio,pie_cant,
        ifnull(( select sum(calcularStock(pre_piezas, idpieza, 'st_sale')) from presupuesto where pre_status in(2,3) ), 0) as salidas,
        ifnull(( select sum(calcularStock(pre_piezas, idpieza, 'ingresa')) from presupuesto where pre_status in(3) ), 0) as entradas,
        (
            pie_cant + 
            ifnull(( select sum(calcularStock(pre_piezas, idpieza, 'ingresa')) from presupuesto where pre_status in(3) ), 0) - 
            ifnull(( select sum(calcularStock(pre_piezas, idpieza, 'st_sale')) from presupuesto where pre_status in(2,3) ), 0)
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


    //LINEAS FAMILIAS MARCAS
    //LINEAS
    public function getLineas(){
        $query = "select * from piezalinea";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }
    public function getLinea($idlinea){
        $query = "select * from piezalinea where idlinea=?";
        $st = $this->db->query($query, [$idlinea]);

        return $st->getRowArray();
    }
    public function insertarLinea($data){
        $query = "insert into piezalinea(linea,lineadesc,idusuario) values(?,upper(?),?)";
        $st = $this->db->query($query, [
            $data['linea'], $data['desclinea'], $data['idusuario']
        ]);

        return $st;
    }
    public function modificarLinea($data, $idlinea){
        $query = "update piezalinea set linea=?,lineadesc=? where idlinea=?";
        $st = $this->db->query($query, [
            $data['linea'], $data['desclinea'], $idlinea
        ]);

        return $st;
    }
    public function verificarLineaTieneRegEnTablas($id, $tabla){
        $query = "select count(idlinea) as total from $tabla where idlinea=?";
        $st = $this->db->query($query, [$id]);

        return $st->getRowArray();
    }
    public function eliminarLinea($id){
        $query = "delete from piezalinea where idlinea = ?";
        $st = $this->db->query($query, [
            $id
        ]);

        return $st;
    }

    //FAMILIAS
    public function getFamilias(){
        $query = "select * from piezafamilia";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }
    public function getFamilia($idfamilia){
        $query = "select * from piezafamilia where idfamilia=?";
        $st = $this->db->query($query, [$idfamilia]);

        return $st->getRowArray();
    }
    public function insertarFamilia($data){
        $query = "insert into piezafamilia(familia,familiadesc,idusuario) values(?,upper(?),?)";
        $st = $this->db->query($query, [
            $data['familia'], $data['descfamilia'], $data['idusuario']
        ]);

        return $st;
    }
    public function modificarFamilia($data, $idfamilia){
        $query = "update piezafamilia set familia=?,familiadesc=? where idfamilia=?";
        $st = $this->db->query($query, [
            $data['familia'], $data['descfamilia'], $idfamilia
        ]);

        return $st;
    }
    public function verificarFamiliaTieneRegEnTablas($id, $tabla){
        $query = "select count(idfamilia) as total from $tabla where idfamilia=?";
        $st = $this->db->query($query, [$id]);

        return $st->getRowArray();
    }
    public function eliminarFamilia($id){
        $query = "delete from piezafamilia where idfamilia = ?";
        $st = $this->db->query($query, [
            $id
        ]);

        return $st;
    }

    //MARCAS
    public function getMarcas(){
        $query = "select * from piezamarca";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }
    public function getMarca($idmarca){
        $query = "select * from piezamarca where idmarca=?";
        $st = $this->db->query($query, [$idmarca]);

        return $st->getRowArray();
    }
    public function insertarMarca($data){
        $query = "insert into piezamarca(marca,idusuario) values(upper(?),?)";
        $st = $this->db->query($query, [
            $data['marca'], $data['idusuario']
        ]);

        return $st;
    }
    public function modificarMarca($data, $idmarca){
        $query = "update piezamarca set marca=? where idmarca=?";
        $st = $this->db->query($query, [
            $data['marca'], $idmarca
        ]);

        return $st;
    }
    public function verificarMarcaTieneRegEnTablas($id, $tabla){
        $query = "select count(idmarca) as total from $tabla where idmarca=?";
        $st = $this->db->query($query, [$id]);

        return $st->getRowArray();
    }
    public function eliminarMarca($id){
        $query = "delete from piezamarca where idmarca = ?";
        $st = $this->db->query($query, [
            $id
        ]);

        return $st;
    }


}