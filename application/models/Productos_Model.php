<?php
class Productos_Model extends CI_Model {

    public function __construct()
    {
    }

    function getFamiliasOpciones(){
        $sql="SELECT * FROM pe_familias ORDER BY nombre_familia";
        $result=$this->db->query($sql)->result();
        $opciones=[];
        foreach($result as $k=>$v){
            $opciones[]=array('id'=>$v->id,'valor'=>$v->nombre_familia);
        }
        return $opciones;
    }

    function getGruposOpciones(){
        $sql="SELECT * FROM pe_grupos ORDER BY nombre_grupo";
        $result=$this->db->query($sql)->result();
        $opciones=[];
        foreach($result as $k=>$v){
            $opciones[]=array('id'=>$v->id,'valor'=>$v->nombre_grupo);
        }
        return $opciones;
    }

    function getSiNoOpciones(){
        $sql="SELECT * FROM pe_si_no";
        $result=$this->db->query($sql)->result();
        $opciones=[];
        foreach($result as $k=>$v){
            $opciones[]=array('id'=>$v->indice,'valor'=>$v->valor);
        }
        return $opciones;
    }

    function getTipoUnidadesOpciones(){
        $sql="SELECT * FROM pe_tipo_unidades";
        $result=$this->db->query($sql)->result();
        $opciones=[];
        foreach($result as $k=>$v){
            $opciones[]=array('id'=>$v->indice,'valor'=>$v->valor);
        }
        return $opciones;
    }

    function getProveedoresOpciones(){
        $sql="SELECT * FROM pe_proveedores ORDER BY nombre_proveedor";
        $result=$this->db->query($sql)->result();
        $opciones=[];
        foreach($result as $k=>$v){
            $opciones[]=array('id'=>$v->id_proveedor,'valor'=>$v->nombre_proveedor);
        }
        return $opciones;
    }

    function getUsuariosOpciones(){
        $sql="SELECT * FROM pe_users ORDER BY nombre";
        $result=$this->db->query($sql)->result();
        $opciones=[];
        foreach($result as $k=>$v){
            $opciones[]=array('id'=>$v->id,'valor'=>$v->nombre);
        }
        return $opciones;
    }

    function getIva($grupo){
        $grupo = $_POST['grupo'];
        $sql = "SELECT valor_iva FROM pe_grupos gr 
                        LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva
                        WHERE gr.id_grupo='$grupo'";
        if ($this->db->query($sql)->num_rows() == 1)
            $iva = $this->db->query($sql)->row()->valor_iva;
        else $iva = 0;
        return $iva;

    }
    
    function getResult($sql){
        return $this->db->query($sql)->result();
    }
    function getResultArray($sql){
        return $this->db->query($sql)->result_array();
    }

    function getRow($sql){
        return $this->db->query($sql)->row();
    }
    function getNumRows($sql){
        return $this->db->query($sql)->num_rows();
    }
    function getRowArray($sql){
        return $this->db->query($sql)->row_array();
    }
    function query($sql){
        return $this->db->query($sql);
    }

}
