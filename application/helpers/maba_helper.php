<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------

if ( ! function_exists('host'))
{
	function host()
	{
                $host=isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];

		//$host=  $_SERVER['HTTP_HOST'];
 		return $host;
	}
}
if ( ! function_exists('base_url_anterior'))
{
	function base_url_anterior()
	{
 		return 'localhost:8888//pernil181';
	}
}
if ( ! function_exists('copyright'))
{
	function copyright()
	{

 		return '© 2007-'.date('Y').' Carlos - Alex';
	}
}

if ( ! function_exists('tituloAplicacion'))
{
	function tituloAplicacion()
	{
 		return "Gestión Jamonarium";
	}
}

if ( ! function_exists('sortByOption_'))
{
	function sortByOption_($a, $b) {
                return strcmp($a['codigo'], $b['codigo']);
       }
}

if ( ! function_exists('sortByOption'))
{
	function sortByOption($a, $b) {
                
                if ($a['codigo']==$b['codigo']) return 0;
                return ($a['codigo']<$b['codigo'])?-1:1;
       }
}

if ( ! function_exists('mensaje'))
{
	function mensaje($mensaje,$identificacion='------------------------ ') {
            log_message('INFO', $identificacion.$mensaje);      
       }
}

if ( ! function_exists('hoy'))
{
	function hoy(){
        return date('Y-m-d');     
       }
}


if ( ! function_exists('clearSearchCookies'))
{
    function clearSearchCookies() {
        //Check if the referer is not same as this page then clear the cookies
        $thisurl = site_url() . "/gestionTablas/productos";
        if(array_key_exists('HTTP_REFERER', $_SERVER)) {
            $refering_url = $_SERVER['HTTP_REFERER'];
            echo $refering_url; echo '<br>';
            echo substr($refering_url, 0, strlen($thisurl));echo '<br>';
            echo $thisurl;echo '<br>';
            if($refering_url != '') {
                if(substr($refering_url, 0, strlen($thisurl)) == $thisurl) {
                    echo 'Fine to go with .. since it is the same one continued in here';echo '<br>';
                } else {
                    deleteSearchCookies();
                }
            } else {
                deleteSearchCookies();
            }    
        } else {
            deleteSearchCookies();
        }
    }
}

if ( ! function_exists('deleteSearchCookies'))
{
    function deleteSearchCookies() {
        echo 'deleteSearchCookies';echo '<br>';
        foreach ($_COOKIE as $key=>$val) {
            if(stripos($key, 'crud_page') !== FALSE) {
                delete_cookie($key);
            }
            if(stripos($key, 'per_page') !== FALSE) {
                delete_cookie($key);
            }
            if(stripos($key, 'hidden_ordering') !== FALSE) {
                delete_cookie($key);
            }
            if(stripos($key, 'search_text') !== FALSE) {
                delete_cookie($key);
            }
            if(stripos($key, 'search_field') !== FALSE) {
                delete_cookie($key);
            }
        }
    }
}

if ( ! function_exists('fechaEuropea'))
{
    function fechaEuropea($fecha="")
    {
        if(!$fecha) $fecha=date("Y-m-d");
        return date("d/m/Y", strtotime($fecha));
    }    
}

if ( ! function_exists('fechaBD'))
{
    function fechaBD($fecha="")
    {
        if(!$fecha) $fecha=date("Y-m-d H:i");
        return substr($fecha,6,4).'-'.substr($fecha,3,2).'-'.substr($fecha,0,2).' '.substr($fecha,10);
    }    
}


if ( ! function_exists('enviarEmail'))
{
    function enviarEmail($email,$subject,$from,$mensaje,$destinatarios=1) {
        //$email=$this->load->library('email');
        
        $email->clear();

        $host = host();
        // mensaje($host);
        if (strpos($host,'localhost:8888')===0) {
            // mensaje('es host local');
            $email->to('mbanolas@gmail.com');
        } else {
            switch($destinatarios){
                case 1:
                    //$this->email->bcc('mbanolas@gmail.com');
                    //$this->email->bcc('mbanolas@gmail.com');
                    //$this->email->to('carlos@jamonarium.com');
                    $email->to('carlos@jamonarium.com');
                    $email->bcc('mbanolas@gmail.com');
                    break;
                case 2:
                    //$this->email->bcc('mbanolas@gmail.com');
                    //$this->email->bcc('mbanolas@gmail.com');
                    //$this->email->to('carlos@jamonarium.com');
                    $email->to('carlos@jamonarium.com');
                    $email->bcc('mbanolas@gmail.com');
                    break;
                case 4:  
                    $email->to('alextorguet@gmail.com');
                    $email->cc('mbanolas@gmail.com');
                    break;
                case 5:  
                    $email->to('carlos@jamonarium.com');
                    $email->cc('sergi@jamonarium.com');
                    $email->bcc('mbanolas@gmail.com');
                    break;
                default:
                    $email->to('mbanolas@gmail.com');
            }
        }

        $email->subject($subject);
        $email->from('info@olivaret.com',$from);
        $email->message($mensaje);
        

        if ($email->send()) {
            return true;
        } else {
            return false;
        }
        

            
        
        
    }
}


if ( ! function_exists('valida_nif_cif_nie'))
{
    function valida_nif_cif_nie($cif) 
    {
        //Copyright ©2005-2011 David Vidal Serra. Bajo licencia GNU GPL.
        //Este software viene SIN NINGUN TIPO DE GARANTIA; para saber mas detalles
        //puede consultar la licencia en http://www.gnu.org/licenses/gpl.txt(1)
        //Esto es software libre, y puede ser usado y redistribuirdo de acuerdo
        //con la condicion de que el autor jamas sera responsable de su uso.
        //Returns: 1 = NIF ok, 2 = CIF ok, 3 = NIE ok, -1 = NIF bad, -2 = CIF bad, -3 = NIE bad, 0 = ??? bad
        $cif = strtoupper($cif);
        for ($i = 0; $i < 9; $i ++)
        {
        $num[$i] = intval(substr($cif, $i, 1));
        }
        //si no tiene un formato valido devuelve error
        if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif))
        {
        log_message('INFO','---------------------- si no tiene un formato valido devuelve error');
        return 0;
        }
        //comprobacion de NIFs estandar
        if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif))
        {
        if ($num[8] == intval(substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, 8) % 23, 1)))
        {
        return 1;
        }
        else
        {
        return -1;
        }
        }
        //algoritmo para comprobacion de codigos tipo CIF
        $suma = $num[2] + $num[4] + $num[6];
        for ($i = 1; $i < 8; $i += 2)
        {
        $suma += intval(substr((2 * $num[$i]),0,1)) + intval(substr((2 * $num[$i]), 1, 1));
         log_message('INFO','---------------------- '.$suma);
        }
        $n = 10 - intval(substr($suma, strlen($suma)-1, 1));
        //comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
        if (preg_match('/^[KLM]{1}/', $cif))
        {
        if ($num[8] == chr(64 + $n) || $num[8] == intval(substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 1, 8) % 23, 1)))
        {
        return 1;
        }
        else
        {
                    log_message('INFO','---------------------- salida A');

        return -1;
        }
        }
        //comprobacion de CIFs
        if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif))
        {
        if ($num[8] == chr(64 + $n) || $num[8] == intval(substr($n, strlen($n) - 1, 1)))
        {
        return 2;
        }
        else
        {
            log_message('INFO','---------------------- salida B');
        return -2;
        }
        }
        //comprobacion de NIEs
        if (preg_match('/^[XYZ]{1}/', $cif))
        {
        if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $cif), 0, 8) % 23, 1))
        {
        return 3;
        }
        else
        {
            log_message('INFO','---------------------- salida C');
        return -3;
        }
        }
        //si todavia no se ha verificado devuelve error
        return 0;
        }
}


