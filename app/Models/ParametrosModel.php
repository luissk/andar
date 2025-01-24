<?php 
namespace App\Models;

use CodeIgniter\Model;

class ParametrosModel extends Model{
    
    public function getParametros(){
        $query = "select * from parametros";

        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function guardarParametro($opcion, $data, $idparametros = null){//opcion 1->insertar, opcion 2->modificar
        if( $opcion == 1 ){

            $query = "insert into parametros(par_porcensem,par_logo,par_firma,par_direcc,par_telef,par_correo) values(?,?,?,?,?,?)";
            $st = $this->db->query($query, [
                $data['porcentaje'],$data['logo'],$data['firma'],$data['direccion'],$data['telefono'],$data['correo']
            ]);

            return $st;

        }else if( $opcion == 2 ){
            
            $query = "update parametros set par_porcensem = ?,par_logo = ?,par_firma = ?,par_direcc = ?,par_telef = ?,par_correo = ? where idparametros = ?";
            $st = $this->db->query($query, [
                $data['porcentaje'],$data['logo'],$data['firma'],$data['direccion'],$data['telefono'],$data['correo'],$idparametros
            ]);

            return $st;

        }
    }

    public function eliminarImagen($opt){
        if( $opt == 'logo' ){
            $query = "update parametros set par_logo = '' ";
        }else if( $opt == 'firma' ){
            $query = "update parametros set par_firma= '' ";
        }
        $st = $this->db->query($query);
        return $st;
    }
}