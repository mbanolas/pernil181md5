<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 *  ======================================= 
 *  Author     : Muhammad Surya Ikhsanudin 
 *  License    : Protected 
 *  Email      : mutofiyah@gmail.com 
 *   
 *  Dilarang merubah, mengganti dan mendistribusikan 
 *  ulang tanpa sepengetahuan Author 
 *  ======================================= 
 */ 
require_once APPPATH."third_party/PHPExcel.php";

class Exceldrawing extends PHPExcel_Worksheet_Drawing {
    public function __construct() {
        parent::__construct();
        
    }
}
