<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validacion extends CI_Controller {

	function __construct()
	{
        parent::__construct();
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function index()
 {
   $data['post']=$_POST;
   
   $data['resultado']=$this->user->login($_POST['username'],$_POST['password']);
   //session_start();
   if ($data['resultado']==false){
       
                $dato['autor']='Miguel Angel Bañolas';
                $dato['tituloAplicacion']=tituloAplicacion();
                $dato['host']=host();
                $dato['error']="'El nombre de Usuario NO es correcto o la Contraseña no le coresponde.<BR />";
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/cabecera',$dato);
		            $this->load->view('pernil181', $dato);  
                $this->load->view('templates/footer.html',$dato);
   }
   else{
       foreach ($data['resultado'] as $row) { 
                switch($row->tipoUsuario){
                    case 0:
                        $tipoUsuario='Administrador Tienda';
                        break;
                    case 1:
                        $tipoUsuario='Administrador Sistema Informático';
                        break;
                    case 2:
                        $tipoUsuario='Dependiente';
                        break;
                    case 3:
                        $tipoUsuario='Administrativa';
                        break;
                    case 4:
                        $tipoUsuario='Administración';
                        break;
                    case 5:
                        $tipoUsuario='Administración';
                        break;
                    default:
                        $tipoUsuario='Sin Catalogar';
                }
                $newdata = array(
                    'id'=>$row->id,
                    'username'  => $row->username,
                    'nombre'     => $row->nombre,
                    'logged_in' => true,
                    'tipoUsuario' => $tipoUsuario,
                    'categoria' => $row->tipoUsuario,
                );
                
                //$this->session->set_userdata($newdata);
                foreach($newdata as $k=>$v){
                    $_SESSION[$k]=$v;
                }
                
                
                
            }
            redirect('bienvenida');
       //redirect('inicio/nueva');
   }
   
   
  

 }
}
