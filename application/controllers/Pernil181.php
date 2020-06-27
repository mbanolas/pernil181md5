<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pernil181 extends CI_Controller {

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
	public function index()
	{
		mensaje('base_url '.base_url());

		$data['base_url']=base_url();
		$data['style']='pernil181.css';
		$data['js']='pernil181.js';
		$this->load->view('templates/header',$data);
		// $this->load->view('templates/menu',$data);
		$this->load->view('pernil181',$data);
		$this->load->view('templates/footer',$data);
	}

	public function login(){
		extract($_POST);
		$sql="SELECT * FROM pe_users WHERE username='$username' AND password='".MD5($password)."' LIMIT 1";
		$result=$this->db->query($sql)->num_rows();
		if($result){
			$row=$this->db->query($sql)->row();
			switch($row->tipoUsuario){
				case 0:
					$cargo="Administrador";
				break;
				case 1:
					$cargo="Administrador sistema";
				break;
				case 2:
					$cargo="Dependiente tienda";
				break;
				case 6:
					$cargo="Administracion tienda online";
				break;
				default:
				$cargo="Gestor no definido";
			}
			$this->load->library('session');
			$datos=array(
				'id'=> $row->id,
				'username'=>$row->username,
				'nombre'=>$row->nombre,
				'tipo_usuario'=>$row->tipoUsuario,
				'categoria'=>$row->tipoUsuario,
				'cargo'=>$cargo,
				'logged_in'=>true
			);
			$this->session->set_userdata($datos);
		}
		
		echo json_encode($result); 
	}
}
