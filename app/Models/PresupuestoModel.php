<?php 
namespace App\Models;

use CodeIgniter\Model;

class PresupuestoModel extends Model{

    public function nroPresupuesto(){
        $query = " select concat( LPAD(count(idpresupuesto) + 1, 4, '0'),'-',YEAR(now()) ) as nro from presupuesto where YEAR(pre_fechareg) = YEAR(now()) ";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    

}