<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bienvenida extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data['style']='bienvenida.css';
		$data['js']='bienvenida.js';
		$data['base_url_anterior']=base_url_anterior();
		$this->load->view('templates/header',$data);
		$this->load->view('templates/menus',$data);
		$this->load->view('bienvenida',$data);
		$this->load->view('templates/footer',$data);
	}

	
}
