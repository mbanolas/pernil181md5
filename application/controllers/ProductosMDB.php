<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class ProductosMDB extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('productosMDB_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // $this->load->library('excel');
    }

    // utilizada en MDB
    function getIva()
    {
        $grupo = $_POST['grupo'];
        $sql = "SELECT valor_iva FROM pe_grupos gr 
                        LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva
                        WHERE gr.id_grupo='$grupo'";
        if ($this->db->query($sql)->num_rows() == 1)
            $iva = $this->db->query($sql)->row()->valor_iva;
        else $iva = 0;
        $iva = number_format($iva, 2, ".", "");
        echo  json_encode($iva);
    }

    function codigo13Valido()
    {
        $codigo13 = $_POST['codigo13'];
        $id = $_POST['id'];
        if ($codigo13 == "") {
            echo  json_encode("es nulo");
            return;
        }
        $sql = "SELECT codigo_producto FROM pe_productos WHERE codigo_producto='$codigo13'";
        // mensaje($sql);
        $num_rows = $this->db->query($sql)->num_rows();
        // mensaje($num_rows);
        if ($num_rows == 0 && $id == 0) {
            $resultado = true;
            echo  json_encode("");
            return;
        }
        if ($num_rows == 0 && $id != 0) {
            $resultado = false;
            echo  json_encode("Código inexistente, pero debería existir");
            return;
        }
        if ($num_rows == 1 && $id != 0) {
            $resultado = true;
            echo  json_encode("");
            return;
        }
        if ($num_rows >= 1 && $id == 0) {
            $resultado = false;
            echo  json_encode("Código existente, no válido para nuevo producto ");
            return;
        }
        if ($num_rows > 1) {
            $resultado = false;
            echo  json_encode('Código repetido');
            return;
        }
        $resultado = false;
        echo  json_encode('caso no previsto ' . $id . ' ' . $codigo13);
    }


    function actualizarProductosIgualBoka($id){
        $this->db->query("SELECT id_producto,
                                    peso_real,
                                    precio_compra,
                                    precio_ultimo");
    }

    function checkStatusProducto(){
        $id = $_POST['id'];
        $row=$this->db->query("SELECT status_producto FROM pe_productos WHERE id='$id'")->row();
        echo  json_encode($row->status_producto);

    }
    function descatalogar(){
        $id = $_POST['id'];
        $result=$this->db->query("UPDATE pe_productos SET status_producto=0 WHERE id='$id'");
        echo  json_encode($result);
    }

    function grabarDatos()
    {
        $datos = $_POST;
        $id = $_POST['id'];
        $set = $this->convertirInverso($datos);

        mensaje('$set grabarDatos' . $set);
        if ($id == 0) {
            $sql = "INSERT INTO pe_productos SET $set";
        } else {
            $sql = "UPDATE pe_productos SET $set WHERE id='$id'";
            mensaje($sql);
        }
        if ($this->db->query($sql)) {
            // devuelve los datos formateados para ponerlos en la tabla sin volver a cargarla
            $actualizar = array(
                'id' => $id,
                'nombre' => $datos['nombre'],
                'peso_real' => number_format((float) $datos['peso_real'], 3, ",", ""),
                'precio_compra' => number_format((float) $datos['precio_compra'], 3, ",", ""),
                'tarifa_venta' => number_format((float) $datos['tarifa_venta'], 3, ",", ""),
                'margen_real_producto' => number_format((float) $datos['margen_real_producto'], 2, ",", ""),
                'stock_total' => number_format((float) $datos['stock_total'], 0, ",", ""),
                'valoracion' => number_format((float) $datos['valoracion'], 2, ",", ""),
                'id_grupo' => $datos['id_grupo'],
                'id_familia' => $datos['id_familia'],
                'id_proveedor_web' => $datos['id_proveedor_web'],
                'tipo_unidad' => $datos['tipo_unidad'],
            );
            $this->actualizarProductosIgualBoka($id);
            echo  json_encode($actualizar);
            return;
        }
        // devuelve datos que se tendrán qu e actualizar
        echo  json_encode(false);
    }
    // graba datos producto desde productosSpeedy via ajax 
    function grabarProducto()
    {
        $resultado = "";
        $estructura = $this->estructuraDatos();
        $sets = array();
        $id = $_POST['id'];
        foreach ($_POST as $k0 => $v0) {
            if ($k0 == 'id') continue;
            // mensaje('datos POST '.$k0.' '.$v0);
            foreach ($estructura as $k => $v) {
                // mensaje('pos 1 '.$v['campo'].' '.$k0. ' '.($v['campo']==$k0));

                if ($v['campo'] == $k0) {
                    if ($v['tipo'] == 'date') {
                        $v0 = fechaBD($v0);
                        // mensaje('fecha '.$v['campo']."='".$v0."'");
                        $sets[] = $v['campo'] . "='" . $v0 . "'";
                        continue;
                    }
                    $factor = 0;
                    if (array_key_exists('factor', $v) == 1) {
                        $factor = $v['factor'];
                    }
                    if ($factor != 0) {
                        // mensaje('$v0 antes '.$v0.' '.$factor);
                        $v0 = str_replace(",", "", $v0);
                        // mensaje('$v0 factor'.$v0.' '.$factor);
                        // mensaje('numero con factor'.' '.$v['campo']."='".$v0*$factor."'");
                        $sets[] = $v['campo'] . "='" . $v0 * $factor . "'";
                    } else {
                        // mensaje('otro sin factor '.$v['campo']."='".$v0."'");
                        if ($v['campo'] == 'nombre_producto') $v['campo'] = 'nombre';
                        $sets[] = $v['campo'] . "='" . $v0 . "'";
                    }
                }
            }
        }
        $set = implode(", ", $sets);

        if ($id == 0) {
            $sql = "INSERT INTO pe_productos SET " . $set;
            // mensaje($sql);
            $resultado = $this->db->query($sql);
            $row = $this->db->query("SELECT * FROM pe_productos ORDER BY id DESC LIMIT 1")->row();
            $id_nuevo = $row->id;
            $control_stock = $row->control_stock;
            $id_proveedor_web = $row->id_proveedor_web;
            $hoy = date('Y-m-d');
            $this->db->query("UPDATE pe_productos SET fecha_alta='$hoy', fecha_modificacion='$hoy' WHERE id='$id_nuevo'");
            // como es producto nuevo se incluye en pe_stocks totales
            $this->db->query("INSERT INTO pe_stocks_totales 
                                     SET cantidad='0',
                                         codigo_producto='$id_nuevo',
                                         codigo_bascula='$id_nuevo',
                                         proveedor='$id_proveedor_web',
                                         fecha_modificacion_stock='$hoy',
                                         id_pe_producto='$id_nuevo',
                                         nombre='$id_nuevo',    
                                         activo='0', 
                                         control_stock=$control_stock;
                                         valoracion='0'
                                         ");
        } else {
            $sql = "UPDATE pe_productos SET " . $set . " WHERE id='$id'";
            $resultado = $this->db->query($sql);
            $sql = "SELECT * FROM pe_stocks_totales WHERE codigo_producto='$id'";
            $num_rows = $this->db->query($sql)->num_rows();
            if ($num_rows != 1) {
                $resultado = 0;
            } else {
                $id_stocks_totales = $this->db->query($sql)->row()->id;
                $row = $this->db->query("SELECT * FROM pe_productos WHERE id='$id'")->row();
                $resultado = $this->db->query("UPDATE pe_stocks_totales SET proveedor='" . $row->id_proveedor_web . "', control_stock='" . $row->control_stock . "' WHERE id='" . $id_stocks_totales . "'");
            }
        }

        // mensaje($resultado);
        echo  json_encode(array('resultado' => $resultado, 'codigo_producto' => $_POST['codigo_producto'], 'nombre' => $_POST['nombre']));
    }

    // export utilizado en MDB
    function exportExcel($codigo_producto = "", $id_producto = "", $producto = "", $peso_real = "", $tipo_unidad = "", $vacio = "", $proveedor = "", $vacio1 = "", $vacio2 = "", $vacio3 = "", $vacio4 = "", $vacio5 = "")
    {
        $datos['codigo_producto'] = $codigo_producto;
        $datos['id_producto'] = $id_producto;
        $producto = str_replace("%20", " ", $producto);
        $datos['producto'] = $producto;
        $datos['peso_real'] = $peso_real;
        $datos['tipo_unidad'] = $tipo_unidad;
        $proveedor = str_replace("%20", " ", $proveedor);
        $datos['proveedor'] = $proveedor;
        // mensaje($producto);

        $sql = "SELECT 
                p.id as id,
                p.codigo_producto as codigo_producto,
                p.id_producto as id_producto,
                p.nombre as nombre,
                FORMAT(p.peso_real/1000,2) as peso_real,
                p.tipo_unidad, 
                g.nombre_grupo,
                f.nombre_familia,
                FORMAT(p.precio_compra/1000,2) as precio_compra,
                tipo_unidad as tipo_unidad,
                pr.nombre_proveedor as nombre_proveedor,
                FORMAT(p.tarifa_venta/1000,2) as tarifa_venta,
                p.control_stock,
                p.stock_total,
                FORMAT(p.valoracion/1000,2) as valoracion,
                FORMAT(p.margen_real_producto/1000,2) as margen,
                p.url_imagen_portada
              FROM pe_productos p 
              LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo
              LEFT JOIN pe_familias f ON p.id_familia=f.id_familia
              LEFT JOIN pe_proveedores pr ON p.id_proveedor_web=pr.id_proveedor  
              WHERE p.status_producto=1";
        $sql .= $codigo_producto != "_" ? " AND p.codigo_producto like '%$codigo_producto%'" : "";
        $sql .= $id_producto != "_" ? " AND p.id_producto like '%$id_producto%'" : "";
        $sql .= $producto != "_" ? " AND p.nombre like '%$producto%'" : "";
        $sql .= $peso_real != "_" ? " AND p.peso_real like '%$peso_real%'" : "";
        $sql .= $tipo_unidad != "_" ? " AND p.tipo_unidad like '%$tipo_unidad%'" : "";
        $sql .= $proveedor != "_" ? " AND pr.nombre_proveedor like '%$proveedor%'" : "";

        $sql .= " ORDER BY p.codigo_producto";

        mensaje($sql);
        $datos['result'] = $this->db->query($sql)->result();

        $this->load->library('email');
        $ahora = date('d/m/Y H:i:s');
        if ($this->session->categoria != 1) {
            enviarEmail($this->email, 'Exportación datos productos ', host() . ' - Pernil181', 'Bajado por: <br>Usuario: ' . $this->session->nombre . '<br>Fecha: ' . $ahora, 3);
        }
        $this->load->view('prepararExcelProductos', $datos);
    }
    
    function eliminarProducto($id)
    {
        $resultado = "hola";
        $this->load->model('productos_');
        $resultado = $this->productos_->eliminarProducto($id);
        $this->load->helper('url');

        //redirect('gestionTablasProductos/productos');

        echo  json_encode($resultado);
    }

    function checkPosibilityToEliminate($id_pe_producto)
    {
        $resultado = $this->productos_->checkPosibilityToEliminate($id_pe_producto);
        echo  json_encode($resultado);
    }

    function embalajes($producto = "")
    {
        $this->load->model('stocks_model');
        $dato = array();
        $dato['producto'] = $producto;
        // $dato['optionsProductos'] = $this->stocks_model->getProductos()['optionsProductos'];
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('embalajes.php', $dato);
        $this->load->view('templates/footer.html');

        $this->load->view('myModal.php');
    }

    function packs($producto = "")
    {
        $this->load->model('stocks_model');
        $dato = array();
        $dato['producto'] = $producto;
        // $dato['optionsProductos'] = $this->stocks_model->getProductos()['optionsProductos'];
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('packs.php', $dato);
        $this->load->view('templates/footer.html');

        $this->load->view('myModal.php');
    }

    function getDatosEmbalajes()
    {
        $id = $_POST['id'];
        $id_pe_producto = $this->productos_->getIdPeProductoEmbalaje($id);
        $datos = $this->productos_->getProducto($id_pe_producto);
        $datosPeEmbalajes = $this->productos_->getDatosPeEmbalajes($id);
        $embalajes = $this->productos_->getEmbalajes($id_pe_producto);
        //echo  json_encode($id_pe_producto);
        echo  json_encode(array('datos' => $datos, 'embalajes' => $embalajes, 'datosPeEmbalajes' => $datosPeEmbalajes));
    }

    function getDatosPacks()
    {
        $id = $_POST['id'];
        $id_pe_producto = $this->productos_->getIdPePack($id);
        $datos = $this->productos_->getProducto($id_pe_producto); //datos codigo pack
        $datosPePacks = $this->productos_->getDatosPePacks($id);
        $packs = $this->productos_->getProductosPack($id_pe_producto);
        //echo  json_encode($id_pe_producto);
        echo  json_encode(array('datos' => $datos, 'packs' => $packs, 'datosPePacks' => $datosPePacks));
    }


    // obtener datos de un producto para editarlos
    function getDatosProductoNuevo()
    {
        $estructura = $this->estructuraDatos();
        $campos = [];
        $row = [];
        foreach ($estructura as $v) {
            if (isset($v['nuevo'])) {
                $row[$v['campo']] = $v['nuevo'];
            } else {
                $row[$v['campo']] = "";
            }
            if($v['tipo']=='seleccion'){
                $c='valor_'.$v['campo'];
                $row[$c]="Seleccionar ".$v['texto'];
            }
        }
        $convertido = $this->convertirDirecto($row);
        echo  json_encode($convertido);
    }

    // obtener datos de un producto para editarlos
    function getDatosProducto($id_pe_producto = "")
    {
        $id_pe_producto = $_POST['id_pe_producto'];
        if ($id_pe_producto == "") {
            echo  json_encode(0);
            return;
        }
        $estructura = $this->estructuraDatos();
        $campos = [];
        $camposX=[];
        $leftjoin= [];
        foreach ($estructura as $k=> $v) {
            $campos[] = $v['campo'];
            if(isset($v['tipo']) && $v['tipo']=='seleccion'){
                $camposX[] = "lf$k.".$v['seleccion']['valor']." as valor_".$v['campo'];
                $leftjoin[] ="LEFT JOIN ".$v['seleccion']['tabla']." lf".$k." ON lf$k.". $v['seleccion']['indice']."=p.".$v['campo']." ";
            }
        }
    
        $c  = "p.".implode(", p.", $campos);
        $x=implode(", ", $camposX);
        $c.=", ".$x;
        $lj = implode(" ", $leftjoin);
        $sql = "SELECT $c FROM pe_productos p ".
                    $lj
                    ."WHERE p.id='$id_pe_producto'";
        mensaje('getDatosProducto '.$sql);
        $row = $this->db->query($sql)->row_array();
        $convertido = $this->convertirDirecto($row);
        echo  json_encode($convertido);
    }

    function getProducto()
    {
        $id_pe_producto = $_POST['id_pe_producto'];
        $datos = $this->productos_->getProducto($id_pe_producto);
        $embalajes = $this->productos_->getEmbalajes($id_pe_producto);
        echo  json_encode(array('datos' => $datos, 'embalajes' => $embalajes));
    }

    function getProductosPack()
    {
        $id_pe_producto_pack = $_POST['id_pe_producto_pack'];
        $datos = $this->productos_->getProducto($id_pe_producto_pack);
        $productosPack = $this->productos_->getProductosPack($id_pe_producto_pack);
        echo  json_encode(array('datos' => $datos, 'productosPack' => $productosPack));
    }

    function getIdPeProductoEmbalaje()
    {
        $id = $_POST['id'];
        $id_pe_producto = $this->productos_->getIdPeProductoEmbalaje($id);
        echo  json_encode($id_pe_producto);
    }

    function getIdPePack()
    {
        $id = $_POST['id'];
        $id_pe_producto = $this->productos_->getIdPePack($id);
        echo  json_encode($id_pe_producto);
    }

    function getCodigoProducto($id_pe_producto)
    {
        $codigo_producto = $this->db->query("SELECT codigo_producto FROM pe_productos WHERE id='$id_pe_producto'")->row()->codigo_producto;
        return $codigo_producto;
    }

    function registrarEmbalaje()
    {
        $id_pe_producto = $_POST['id_pe_producto'];
        if (!array_key_exists('codigos', $_POST)) {
            $resultado = $this->productos_->eliminarEmbalaje($id_pe_producto);
        } else {
            $codigos = $_POST['codigos'];
            $cantidades = $_POST['cantidades'];
            $tiendas = $_POST['tiendas'];
            $onlines = $_POST['onlines'];
            $resultado = $this->productos_->registrarEmbalaje($id_pe_producto, $codigos, $cantidades, $tiendas, $onlines);
        }
        echo  json_encode($resultado);
    }

    function registrarPack()
    {
        $id_pe_producto_pack = $_POST['id_pe_producto_pack'];
        $totalPrecio_compra = $_POST['totalPrecio_compra'];
        $totalTarifa_ventaPack = $_POST['totalTarifa_ventaPack'];
        $totalTarifa_venta = intval(round($_POST['totalTarifa_venta']));



        if (!array_key_exists('codigos', $_POST)) {
            $resultado = $this->productos_->eliminarPack($id_pe_producto_pack);
        } else {
            $codigos = $_POST['codigos'];
            $cantidades = $_POST['cantidades'];
            $descuentos = $_POST['descuentos'];
            $margenPack = $_POST['margenPack'];
            $margen = $_POST['margen'];
            log_message('info', '$id_pe_producto_pack ' . $id_pe_producto_pack);
            log_message('info', '$totalPrecio_compra ' . $totalPrecio_compra);
            log_message('info', '$totalTarifa_ventaPack ' . $totalTarifa_ventaPack);
            log_message('info', '$totalTarifa_venta ' . $totalTarifa_venta);

            $resultado = $this->productos_->registrarPack($id_pe_producto_pack, $codigos, $cantidades, $descuentos, $totalPrecio_compra, $totalTarifa_ventaPack, $totalTarifa_venta, $margen, $margenPack);
        }
        echo  json_encode($resultado);
    }

    function bajarExcelProductos()
    {
        $this->load->model('productos_');
        $tabla = $this->productos_->bajarExcelProductos();

        echo  json_encode($tabla);
    }

    function getProductoPesos($id_pe_producto)
    {
        $this->load->model('productos_');
        $codigos = $this->productos_->getProductoPesos($id_pe_producto);

        echo  json_encode($codigos);
    }



    function getFamilias($id_grupo = 0)
    {
        if (isset($_POST['grupo'])) $id_grupo = $_POST['grupo'];
        else $id_grupo = 0;
        $this->load->model('productos_');
        $familias = $this->productos_->getFamiliasMDB($id_grupo);

        echo  json_encode($familias);
    }

    function activarProducto($id_pe_producto)
    {
        $this->load->model('productos_');
        $this->productos_->activarProducto($id_pe_producto);
        $this->load->helper('url');
        redirect('gestionTablasProductos/productosDescatalogados');
    }

    function desactivarProducto($id_pe_producto)
    {
        $this->load->model('productos_');
        $this->productos_->desactivarProducto($id_pe_producto);
        $this->load->helper('url');
        redirect('gestionTablasProductos/productos');
    }

    function getUnidad($id_pe_producto)
    {
        $this->load->model('productos_');
        $tipoUnidad = $this->productos_->getUnidad($id_pe_producto);
        echo  json_encode($tipoUnidad);
    }

    function getUnidadCodigoProducto($codigo_producto)
    {
        $this->load->model('productos_');
        $tipoUnidad = $this->productos_->getUnidadCodigoProducto($codigo_producto);
        echo  json_encode($tipoUnidad);
    }

    function getInfoCodigoBascula($id_producto)
    {
        $this->load->model('productos_');
        $infoCodigoBascula = $this->productos_->getInfoCodigoBascula($id_producto);
        echo  json_encode($infoCodigoBascula);
    }

    function getPrecio($id_pe_producto, $proveedor, $tipoUnidad)
    {
        $this->load->model('productos_');
        $datosPrecio = $this->productos_->getPrecio($id_pe_producto, $proveedor, $tipoUnidad);
        echo  json_encode($datosPrecio);
    }

    function getCostePVP($id_pe_producto)
    {
        $this->load->model('productos_');
        $CostePVP = $this->productos_->getCostePVP($id_pe_producto);
        echo  json_encode($CostePVP);
    }

    function getDatosCompraProducto($id_pe_producto)
    {
        $this->load->model('productos_');
        $tipoUnidad = $this->productos_->getDatosCompraProducto($id_pe_producto);
        echo  json_encode($tipoUnidad);
    }


    function getCodigoEan()
    {
        $codigoBascula = $_POST['codigoBascula'];
        $this->load->model('productos_');
        $codigoEan = $this->productos_->getCodigoEan();
        echo  json_encode($codigoEan);
    }

    public function _outputProductos($output = null, $table)
    {
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = 'Pernil 181';

        // $this->load->view('templates/header.html');
        $this->load->view('templates/headerGrocery', $output);

        $this->load->view('templates/top.php', $datos);
        $this->load->view('outputBD.php', $output);
        $this->load->view('myModal.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer.html');
    }

    function checkGrupoFmiliaCodigo13()
    {
        $id=$_POST['id'];
        $codigo_producto=$_POST['codigo_producto'];
        $errores=[];
        $campos=[];
        $numCodigo13=$this->db->query("SELECT id FROM pe_productos WHERE codigo_producto='$codigo_producto'")->num_rows();
        if($id==0 && $numCodigo13>0) {$campos[]='codigo_producto'; $errores[]="Código producto: El código de producto YA existe. Cambiarlo por otro o editar el actual";}
        if($id>0 && $numCodigo13!=1) {$campos[]='codigo_producto'; $errores[]="Código producto: El código de producto ESTA repetido. Contactar con administrador";}
        $id_grupo = $_POST['id_grupo'];
        $id_familia = $_POST['id_familia'];
        $num = $this->db->query("SELECT * FROM pe_grupos_familias WHERE id_grupo='$id_grupo' AND id_familia='$id_familia'")->num_rows();
        if ($num == 0) {
            $campos[]='id_familia';
            $errores[]="Familia: La familia seleccionada NO corresponde al grupo. Cambiarlos adecuadamente ";
        } 
        echo  json_encode(array('errores'=>$errores,'campos'=>$campos));
    }

    // convierte datos leidos en pe_productos a su representación
    function convertirDirecto($row)
    {
        $estructura = $this->estructuraDatos();
        foreach ($estructura as $k => $v) {
            switch ($v['tipo']) {

                case 'number':
                    $decimales = isset($v['decimales']) ? $v['decimales'] : 0;
                    $factor = isset($v['factor']) ? $v['factor'] : 1;
                    $row[$v['campo']] = number_format($row[$v['campo']] / $factor, $decimales, ".", "");
                    break;
                case 'date':
                    $row[$v['campo']] = fechaEuropea($row[$v['campo']]);
                    break;
                case 'seleccion':
                    
                    break;
                default:
            }
        }
        foreach ($row as $k => $v) {
            mensaje($k . ' ' . $v);
        }
        return $row;
    }


    function convertirInverso($datos)
    {
        $estructura = $this->estructuraDatos();
        $sets = [];
        foreach ($estructura as $k => $v) {
            switch ($v['tipo']) {
                case 'text':
                    $datos[$v['campo']] = trim($datos[$v['campo']]);
                    $sets[] = $v['campo'] . "='" . $datos[$v['campo']] . "'";
                    break;
                case 'number':
                    $decimales = isset($v['decimales']) ? $v['decimales'] : 0;
                    $factor = isset($v['factor']) ? $v['factor'] : 1;
                    $datos[$v['campo']] = $datos[$v['campo']] * $factor;
                    $sets[] = $v['campo'] . "='" . $datos[$v['campo']] . "'";
                    break;
                case 'date':
                    $datos[$v['campo']] = fechaBD($datos[$v['campo']]);
                    $sets[] = $v['campo'] . "='" . $datos[$v['campo']] . "'";
                    break;
                case 'seleccion':
                    // en $datos[$v['campo']] esta el valor del campo, pero se debe buscar para guardar el id del archivp
                    $valor = $datos[$v['campo']];
                    mensaje('$v[campo] ' . $valor);
                    switch ($v['campo']) {
                        case 'id_proveedor_web':
                            $indice = $this->db->query("SELECT id_proveedor FROM pe_proveedores WHERE nombre_proveedor like '%$valor%' LIMIT 1")->row()->id_proveedor;
                            $sets[] = $v['campo'] . "='" . $indice . "'";
                            break;
                        case 'modificado_por':
                            $indice = $this->db->query("SELECT id FROM pe_users WHERE nombre like '%$valor%' LIMIT 1")->row()->id;
                            $sets[] = $v['campo'] . "='" . $indice . "'";
                            break;
                        case 'id_grupo':
                            $indice = $this->db->query("SELECT id_grupo FROM pe_grupos WHERE nombre_grupo like '%$valor%' LIMIT 1")->row()->id_grupo;
                            $sets[] = $v['campo'] . "='" . $indice . "'";
                            break;
                        case 'id_familia':
                            $indice = $this->db->query("SELECT id_familia FROM pe_familias WHERE nombre_familia like '%$valor%' LIMIT 1")->row()->id_familia;
                            $sets[] = $v['campo'] . "='" . $indice . "'";
                            break;
                        case 'tipo_unidad':
                            $sets[] = $v['campo'] . "='" . $datos[$v['campo']] . "'";
                            break;
                        case 'control_stock':
                            $sets[] = $v['campo'] . "='" . $datos[$v['campo']] . "'";
                            break;
                        default:
                    }
                    break;
                default:
            }
        }
        foreach ($sets as $k => $v) {
            mensaje($k . ' ' . $v);
        }
        return implode(",", $sets);
    }

    function estructuraDatos()
    {
        $dato['estructura'] = array();
        //0
        $dato['estructura'][] = array(
            'campo' => 'id',
            'texto' => 'Identificador',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'editar' => 'disabled',
            'nuevo' => 0,
            // 'mostrar'=>false,
            'ancho' => '40'
        );
        //1
        $dato['estructura'][] = array(
            'campo' => 'codigo_producto',
            'texto' => 'Código 13',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'editar' => 'disabled',
            'nuevo' => "",
            // 'mostrar'=>false,
            'ancho' => '40'
        );
        //2
        $dato['estructura'][] = array(
            'campo' => 'id_producto',
            'texto' => 'Código Boka',
            'tipo' => 'text',
            'color' => 'black',
            'editar' => "",
            'nuevo' => "",
            'requerido' => true,
            'ancho' => '30'
        );
        //3
        $dato['estructura'][] = array(
            'campo' => 'nombre',
            'texto' => 'Nombre producto',
            'tipo' => 'text',
            'color' => 'black',
            'editar' => "",
            'nuevo' => "",
            'requerido' => true,
            'ancho' => '30'
        );
        //4
        $dato['estructura'][] = array(
            'campo' => 'nombre_generico',
            'texto' => 'Nombre genérico',
            'tipo' => 'text',
            'color' => 'black',
            'editar' => "",
            'nuevo' => "",
            'requerido' => true,
            'ancho' => '30'
        );
        //5
        $dato['estructura'][] = array(
            'campo' => 'codigo_ean',
            'texto' => 'Código EAN',
            'tipo' => 'text',
            'editar' => "",
            'nuevo' => "",
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );
        //6
        $dato['estructura'][] = array(
            'campo' => 'id_grupo',
            'texto' => 'Grupo',
            'tipo' => 'seleccion',
            'color' => 'black',
            'editar' => "",
            'ver' => "disabled",
            'nuevo' => 0,
            'seleccion' => array('tabla' => 'pe_grupos', 'indice' => 'id_grupo', 'valor' => 'nombre_grupo'),
            'requerido' => true,
            'ancho' => '30'
        );
        //7
        $dato['estructura'][] = array(
            'campo' => 'id_familia',
            'texto' => 'Familia',
            'tipo' => 'seleccion',
            'color' => 'black',
            'editar' => "",
            'ver' => "disabled",
            'nuevo' => 0,
            'seleccion' => array('tabla' => 'pe_familias', 'indice' => 'id_familia', 'valor' => 'nombre_familia'),
            'requerido' => true,
            'ancho' => '30'
        );
        //8
        $dato['estructura'][] = array(
            'campo' => 'peso_real',
            'texto' => 'Peso real (Kg)',
            'tipo' => 'number',
            'factor' => 1000,
            'editar' => "",
            'nuevo' => 0,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );
        //9
        $dato['estructura'][] = array(
            'campo' => 'anada',
            'texto' => 'Añada',
            'tipo' => 'text',
            'color' => 'black',
            'editar' => "",
            'nuevo' => "",
            'requerido' => false,
            'ancho' => '30'
        );
        //10
        $dato['estructura'][] = array(
            'campo' => 'stock_minimo',
            'texto' => 'Stock mínimo',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 0,
            'editar' => "",
            'nuevo' => 0,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        //11
        $dato['estructura'][] = array(
            'campo' => 'control_stock',
            'texto' => 'Control stock',
            'tipo' => 'seleccion',
            'color' => 'black',
            'editar' => "",
            'ver' => "disabled",
            'nuevo' => "0",
            'requerido' => true,
            'seleccion' => array('tabla' => 'pe_si_no', 'indice' => 'indice', 'valor' => 'valor'),
            'ancho' => '30'
        );
        //12
        $dato['estructura'][] = array(
            'campo' => 'fecha_alta',
            'texto' => 'Fecha alta',
            'tipo' => 'date',
            'color' => 'black',
            'editar' => "disabled",
            'nuevo' => date("Y-m-d"),
            'requerido' => true,
            'ancho' => '30'
        );
        //13
        $dato['estructura'][] = array(
            'campo' => 'fecha_modificacion',
            'texto' => 'Fecha modificacion',
            'tipo' => 'date',
            'editar' => "disabled",
            'nuevo' => date("Y-m-d"),
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        //14
        $dato['estructura'][] = array(
            'campo' => 'modificado_por',
            'texto' => 'Modificado por',
            'tipo' => 'seleccion',
            'color' => 'black',
            'requerido' => true,
            'editar' => "disabled",
            'ver' => "disabled",
            'nuevo' => $this->session->id,
            'seleccion' => array('tabla' => 'pe_users', 'indice' => 'id', 'valor' => 'nombre'),
            'ancho' => '30'
        );
        //15
        $dato['estructura'][] = array(
            'campo' => 'unidades_caja',
            'texto' => 'Unidades caja/compra',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 0,
            'editar' => "",
            'nuevo' => 1000,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        //16
        $dato['estructura'][] = array(
            'campo' => 'id_proveedor_web',
            'texto' => 'Proveedor',
            'tipo' => 'seleccion',
            'columnas' => '4',
            'editar' => "",
            'ver' => "disabled",
            'nuevo' => 0,
            'color' => 'black',
            'seleccion' => array('tabla' => 'pe_proveedores', 'indice' => 'id_proveedor', 'valor' => 'nombre_proveedor'),
            'requerido' => true,
            'ancho' => '30'
        );
        //17
        $dato['estructura'][] = array(
            'campo' => 'tipo_unidad',
            'texto' => 'Tipo unidad',
            'tipo' => 'seleccion',
            'editar' => "",
            'ver' => "disabled",
            'color' => 'black',
            'nuevo' => 0,
            'seleccion' => array('tabla' => 'pe_tipo_unidades', 'indice' => 'indice', 'valor' => 'valor'),
            'requerido' => true,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        //18
        $dato['estructura'][] = array(
            'campo' => 'precio_ultimo_unidad',
            'texto' => 'Precio Compra (€/unidad compra)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'nuevo' => 0,
            'requerido' => true,
            'editar' => "",
            'ancho' => '30'
        );
        //19
        $dato['estructura'][] = array(
            'campo' => 'precio_ultimo_peso',
            'texto' => 'Precio Compra (€/Kg compra)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            'editar' => "",
            'nuevo' => 0,
            'ancho' => '30'
        );
        //20
        $dato['estructura'][] = array(
            'campo' => 'descuento_1_compra',
            'texto' => 'Descuento compra (%)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            'editar' => "",
            'nuevo' => 0,
            'ancho' => '30'
        );
        //21
        $dato['estructura'][] = array(
            'campo' => 'precio_transformacion_unidad',
            'texto' => 'Precio transformación (€/unidad)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            'editar' => "",
            'nuevo' => 0,

            // 'editar'=>false,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'precio_transformacion_peso',
            'texto' => 'Precio transformación (€/Kg)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            'editar' => "",
            'nuevo' => 0,

            // 'editar'=>false,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'precio_compra',
            'texto' => 'Precio Compra Final en Tienda',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            'editar' => "disabled",
            'nuevo' => 0,

            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'unidades_precio',
            'texto' => 'Unidades Precio',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 0,
            'color' => 'black',
            'requerido' => true,
            'editar' => "",
            'nuevo' => 1000,

            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'tarifa_venta',
            'texto' => 'Tarifa PVP',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => true,
            'editar' => "",
            'nuevo' => 0,

            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'beneficio_recomendado',
            'texto' => 'Beneficio recomendado (%)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => true,
            'editar' => "",
            'nuevo' => 35000,

            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'margen_real_producto',
            'texto' => 'Margen (%)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => true,
            'editar' => "disabled",
            'nuevo' => 0,

            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'stock_total',
            'texto' => 'Total unidades stock',
            'tipo' => 'number',
            'factor' => 1,
            'decimales' => 0,
            'color' => 'black',
            'requerido' => true,
            'editar' => "disabled",
            'nuevo' => 0,

            // 'mostrar'=>false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'valoracion',
            'texto' => 'Valor stock precio compra actual',
            'tipo' => 'number',
            'factor' => 1,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => true,
            'editar' => "disabled",
            'nuevo' => 0,

            // 'mostrar'=>false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'iva',
            'texto' => 'IVA',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => false,
            'editar' => 'disabled',
            'nuevo' => 0,

            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'url_producto',
            'texto' => 'Url producto',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'editar' => "",
            'nuevo' => "",
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'url_imagen_portada',
            'texto' => 'Imagen Producto',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'editar' => "",
            'nuevo' => "",
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'notas',
            'texto' => 'Notas',
            'tipo' => 'text',
            'color' => 'black',
            'editar' => "",
            'nuevo' => "",
            'requerido' => false,
            'ancho' => '30'
        );


        return $dato['estructura'];
    }

    function getVerProducto()
    {
        $id_pe_producto = $_POST['id_pe_producto'];
        $datos = $this->productos_->getFullProducto($id_pe_producto);


        $dato['estructura'] = $this->estructuraDatos();

        $dato['renderVer'] = '';
        $dato['renderVer'] .= '<div class="container-fluid">';
        foreach ($dato['estructura'] as $k => $v) {
            if ($v['tipo'] == 'date') $datos[$v['campo']] = fechaEuropea($datos[$v['campo']]);
            if ($v['tipo'] == 'number') $datos[$v['campo']] = number_format($datos[$v['campo']] / $v['factor'], $v['decimales']);
            if ($v['requerido'])  $v['texto'] .= '<span class="requerido">*</span>';

            $dato['renderVer'] .= '<div class="row">';
            $dato['renderVer'] .= '<label class="col-sm-5 text-right"><span class="' . $v['campo'] . '" >' . $v['texto'] . ':</span></label>';

            switch ($v['tipo']) {
                case 'date':
                case 'number':
                case 'text':
                    $dato['renderVer'] .= '<label class="col-sm-7 font-weight-bold"><span class="' . $v['campo'] . '" >' . $datos[$v['campo']] . '</span></label>';
                    // $dato['renderVer'] .= '<input type="text" readonly class=" "  value="' . $datos[$v['campo']] . '">';
                    if ($v['campo'] == "url_imagen_portada") {
                        $dato['renderVer'] .= '<label  class="col-sm-5 "></label> ';
                        $dato['renderVer'] .= '<img src="' . $datos['url_imagen_portada'] . '" class="imagen_producto" height="250" width="250" ></img>';
                    }

                    break;
                case 'seleccion':
                    $sql = "SELECT " . $v['seleccion']['valor'] . " FROM " . $v['seleccion']['tabla'] . " WHERE " . $v['seleccion']['indice'] . "='" . $datos[$v['campo']] . "'";
                    $row = $this->db->query($sql)->row_array();
                    $dato['renderVer'] .= '<label class="col-sm-7 font-weight-bold">' . $row[$v['seleccion']['valor']] . '</label>';
                    // $dato['renderVer'] .= '<input type="text" readonly class="form-control-plaintext"  value="' . $row[$v['seleccion']['valor']] . '">';
                    break;
                default:
            }


            // $dato['renderVer'] .= '<img src="'.$datos['url_imagen_portada'].'"  ></img>';
            $dato['renderVer'] .= '</div>';
        }


        $dato['renderVer'] .= '</div>';
        echo  json_encode($dato['renderVer']);
    }


    function getEditarProducto()
    {
        // $this->load->database();
        $id_pe_producto = $_POST['id_pe_producto'];
        $producto = $this->productos_->getFullProducto($id_pe_producto);


        $estructura = $this->estructuraDatos();
        $this->load->model('Productos_');
        $producto = $this->productos_->getFullProducto($id_pe_producto);
        $id_familia = $this->productos_->getFamiliasOpciones();
        $id_grupo = $this->productos_->getGruposOpciones();
        $control_stock = $this->productos_->getSiNoOpciones();
        $tipo_unidad = $this->productos_->getTipoUnidadesOpciones();
        $id_proveedor_web = $this->productos_->getProveedoresOpciones(1);

        $grid[0] = array(0, 1, 2, 5);
        $grid[1] = array(3, 4);
        $grid[2] = array(6, 7, 8, 9, 17);
        $grid[3] = array(10, 11, 12, 13);
        $grid[4] = array(16, 15, 18, 19, 20);
        $grid[5] = array(21, 22, 23, 26);
        $grid[6] = array(24, 25, 30, 27);
        $grid[7] = array(28, 29);
        $grid[8] = array(31, 32);

        $modal = '<div class="container-full">';
        foreach ($grid as $fila) {
            $modal .= '<div class="row">';
            foreach ($fila as $columna) {
                switch ($estructura[$columna]['tipo']) {
                    case 'text':
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="' . $producto[$estructura[$columna]['campo']] . '">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'date':
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="' . fechaEuropea($producto[$estructura[$columna]['campo']]) . '">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'number':
                        $factor = isset($estructura[$columna]['factor']) ? $estructura[$columna]['factor'] : 1;
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="' . $producto[$estructura[$columna]['campo']] / $factor . '">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'seleccion':
                        $sm = "";
                        if (isset($estructura[$columna]['columnas'])) $sm = "-" . $estructura[$columna]['columnas'];
                        $modal .= ' <div class="col-sm' . $sm . '">
                        <select id="' . $estructura[$columna]['campo'] . '" class="select-wrapper mdb-select md-form colorful-select dropdown-dark" ' . $estructura[$columna]['editar'] . ' searchable="Buscar aquí...">
                            <option value="0" disabled selected>Seleccionar ' . $estructura[$columna]['campo'] . '</option>';
                        $seleccion = $estructura[$columna]['campo'];
                        foreach ($$seleccion as $k => $v) {
                            $selected = $v['id'] == $producto[$estructura[$columna]['campo']] ? "selected" : "";
                            $modal .= "<option $selected value='" . $v['id'] . "'>" . $v['valor'] . "</option>";
                        }
                        $modal .= '</select>
                        <label class="mdb-main-label active">' . $estructura[$columna]['texto'] . '</label>
                    </div>';
                        break;
                }
            }
            $modal .= '</div>';
        }
        $modal .= '</div>';

        $dato['renderVer'] = $modal;

        echo  json_encode($dato['renderVer']);
    }

    function getEditarProductoModalVer(){
        // $this->load->database();
        $estructura = $this->estructuraDatos();
        $id_familia = $this->productos_->getFamiliasOpciones();
        $id_grupo = $this->productos_->getGruposOpciones();
        $control_stock = $this->productos_->getSiNoOpciones();
        $tipo_unidad = $this->productos_->getTipoUnidadesOpciones();
        $id_proveedor_web = $this->productos_->getProveedoresOpciones();
        $modificado_por = $this->productos_->getUsuariosOpciones();

        $grid[0] = array(0, 1, 2, 5);
        $grid[1] = array(3, 4);
        $grid[2] = array(6, 7, 8, 9, 17);
        $grid[3] = array(10, 11, 12, 13);
        $grid[4] = array(16, 15, 18, 19, 20);
        $grid[5] = array(21, 22, 23, 26);
        $grid[6] = array(24, 25, 30, 27);
        $grid[7] = array(31, 32);
        $grid[8] = array(28, 29, 33, 14);

        $modal = '<div class="container-full">';
        foreach ($grid as $fila) {
            $modal .= '<div class="row">';
            foreach ($fila as $columna) {
                switch ($estructura[$columna]['tipo']) {
                    case 'text':
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text"  id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'date':
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'number':
                        $factor = isset($estructura[$columna]['factor']) ? $estructura[$columna]['factor'] : 1;
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'seleccion':
                        $sm = "";
                        if (isset($estructura[$columna]['columnas'])) $sm = "-" . $estructura[$columna]['columnas'];
                        $modal .= ' <div class="col-sm' . $sm . '">
                        <select id="' . $estructura[$columna]['campo'] . '" class="select-wrapper mdb-select md-form colorful-select dropdown-dark" ' . $estructura[$columna]['ver'] . ' searchable="Buscar aquí...">
                            <option value="0" disabled selected>Seleccionar ' . $estructura[$columna]['texto'] . '</option>';
                        $seleccion = $estructura[$columna]['campo'];
                        foreach ($$seleccion as $k => $v) {
                            $modal .= "<option  value='" . $v['id'] . "'>" . $v['valor'] . "</option>";
                        }
                        $modal .= '</select>
                        <label class="mdb-main-label active">' . $estructura[$columna]['texto'] . '</label>
                    </div>';
                        break;
                }
            }
            $modal .= '</div>';
        }
        $modal .= '</div>';

        return $modal;
    }
    function getEditarProductoModal(){
        // $this->load->database();
        $estructura = $this->estructuraDatos();
        $id_familia = $this->productos_->getFamiliasOpciones();
        $id_grupo = $this->productos_->getGruposOpciones();
        $control_stock = $this->productos_->getSiNoOpciones();
        $tipo_unidad = $this->productos_->getTipoUnidadesOpciones();
        $id_proveedor_web = $this->productos_->getProveedoresOpciones();
        $modificado_por = $this->productos_->getUsuariosOpciones();

        $grid[0] = array(0, 1, 2, 5);
        $grid[1] = array(3, 4);
        $grid[2] = array(6, 7, 8, 9, 17);
        $grid[3] = array(10, 11, 12, 13);
        $grid[4] = array(16, 15, 18, 19, 20);
        $grid[5] = array(21, 22, 23, 26);
        $grid[6] = array(24, 25, 30, 27);
        $grid[7] = array(31, 32);
        $grid[8] = array(28, 29, 33, 14);

        $modal = '<div class="container-full">';
        foreach ($grid as $fila) {
            $modal .= '<div class="row">';
            foreach ($fila as $columna) {
                switch ($estructura[$columna]['tipo']) {
                    case 'text':
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text"  id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'date':
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'number':
                        $factor = isset($estructura[$columna]['factor']) ? $estructura[$columna]['factor'] : 1;
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'seleccion':
                        $sm = "";
                        if (isset($estructura[$columna]['columnas'])) $sm = "-" . $estructura[$columna]['columnas'];
                        $modal .= ' <div class="col-sm' . $sm . '">
                                          <select id="' . $estructura[$columna]['campo'] . '" class="select-wrapper mdb-select md-form colorful-select dropdown-dark" ' . $estructura[$columna]['editar'] . ' searchable="Buscar aquí...">
                                              <option value="0" disabled selected>Seleccionar ' . $estructura[$columna]['texto'] . '</option>';
                        $seleccion = $estructura[$columna]['campo'];
                        foreach ($$seleccion as $k => $v) {
                            $modal .= "              <option  value='" . $v['id'] . "'>" . $v['valor'] . "</option>";
                        }
                        $modal .= '      </select>
                                        <label class="mdb-main-label active">' . $estructura[$columna]['texto'] . '</label>
                                    </div>';
                        break;
                }
            }
            $modal .= '</div>';
        }
        // $modal .= '<br>';
        mensaje($modal);

        return $modal;
    }

    function cambiar_status_producto()
    {
        $id = $_POST['id'];
        $status_producto = $this->db->query("SELECT status_producto FROM pe_productos WHERE id='$id'")->row()->status_producto;
        $status_producto = 1 - $status_producto;
        $resultado = $this->db->query("UPDATE pe_productos SET status_producto='$status_producto' WHERE id='$id'");
        echo  json_encode($resultado);
    }



    public function tablaBasica()
    {
        $estructura = $this->estructuraDatos();
        $this->load->model('Productos_');
        $producto = $this->productos_->getFullProducto(300);
        $id_familia = $this->productos_->getFamiliasOpciones();
        $id_grupo = $this->productos_->getGruposOpciones();
        $control_stock = $this->productos_->getSiNoOpciones();
        $tipo_unidad = $this->productos_->getTipoUnidadesOpciones();
        $id_proveedor_web = $this->productos_->getProveedoresOpciones(1);

        $grid[0] = array(0, 1, 2, 5);
        $grid[1] = array(3, 4);
        $grid[2] = array(6, 7, 8, 9, 17);
        $grid[3] = array(10, 11, 12, 13);
        $grid[4] = array(16, 15, 18, 19, 20);
        $grid[5] = array(21, 22, 23);
        $grid[6] = array(24, 25, 26);
        $grid[7] = array(27, 28, 29);
        $grid[8] = array(30, 31, 32);

        $modal = '<div class="container">';
        foreach ($grid as $fila) {
            $modal .= '<div class="row">';
            foreach ($fila as $columna) {
                switch ($estructura[$columna]['tipo']) {
                    case 'text':
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="' . $producto[$estructura[$columna]['campo']] . '">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'date':
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="' . fechaEuropea($producto[$estructura[$columna]['campo']]) . '">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'number':
                        $factor = isset($estructura[$columna]['factor']) ? $estructura[$columna]['factor'] : 1;
                        $modal .= '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="text" id="' . $estructura[$columna]['campo'] . '" class="form-control" ' . $estructura[$columna]['editar'] . ' value="' . $producto[$estructura[$columna]['campo']] / $factor . '">
                                        <label class="active" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                        break;
                    case 'seleccion':
                        $sm = "";
                        if (isset($estructura[$columna]['columnas'])) $sm = "-" . $estructura[$columna]['columnas'];
                        $modal .= ' <div class="col-sm' . $sm . '">
                        <select id="' . $estructura[$columna]['campo'] . '" class="mdb-select md-form colorful-select dropdown-dark" ' . $estructura[$columna]['editar'] . ' searchable="Buscar aquí...">
                            <option value="0" disabled selected>Seleccionar ' . $estructura[$columna]['campo'] . '</option>';
                        $seleccion = $estructura[$columna]['campo'];
                        foreach ($$seleccion as $k => $v) {
                            $selected = $v['id'] == $producto[$estructura[$columna]['campo']] ? "selected" : "";
                            $modal .= "<option $selected value='" . $v['id'] . "'>" . $v['valor'] . "</option>";
                        }
                        $modal .= '</select>
                        <label class="mdb-main-label active">' . $estructura[$columna]['texto'] . '</label>
                    </div>';
                        break;
                }
            }
            $modal .= '</div>';
        }
        $modal .= '</div>';

        $datos['modal'] = $modal;
        $this->load->view('mdb/templates/header');
        $this->load->view('mdb/templates/nav_bar');

        $this->load->view('mdb/tablaBasica', $datos);
        $this->load->view('mdb/templates/footer');
    }

    // patbal productos 
    public function productosSpeedy($status_producto = 1)
    {
        $dato = array();
        $dato['estructura'] = $this->estructuraDatos();
        $dato['status_producto'] = $status_producto;
        $dato['tipoProducto'] = $status_producto ? " Catalogados" : " Descatalogados";
        $dato['otroTipoProducto'] = !$status_producto ? " Catalogados" : " Descatalogados";
        
        $sql = "SELECT p.*, pr.nombre_proveedor as proveedor FROM pe_productos p
                    LEFT JOIN pe_proveedores pr ON p.id_proveedor_web=pr.id_proveedor
                    WHERE status_producto='$status_producto'
                    ORDER BY p.codigo_producto LIMIT 20";

        $sql = "SELECT p.*, pr.nombre_proveedor as proveedor FROM pe_productos p
                    LEFT JOIN pe_proveedores pr ON p.id_proveedor_web=pr.id_proveedor
                    WHERE status_producto='$status_producto'
                    ORDER BY p.codigo_producto ";

        $dato['productos'] = $this->db->query($sql)->result();
        
        $thead='<tr>
                    <th></th>
                     <th>Código Producto</th>
                     <th>Cód. Bascula</th>
                     <th class="izquierda">Nombre</th>
                     <th>Peso Real (Kg)</th>
                     <th>Tipo Unidad</th>
                     <th>Precio Compra Final en Tienda</th>
                     <th class="izquierda">Proveedor</th>
                     <th>Tarifa PVP</th>
                     <th>Margen (%)</th>
                     <th>Undidades Stock</th>
                     <th>Valor Stock precio compra actual</th>
                     <th>Imagen Producto</th>
                </tr>';
        $dato['tabla'] = 
            "<thead>
                $thead
            </thead>
            <tbody>";

        foreach ($dato['productos'] as $k => $v) {
            $dato['tabla'] .= '<tr producto="' . $v->id . '">';

            $dato['tabla'] .= '<td>';

            $dato['tabla'] .= '        <!--Dropdown primary-->';
            $dato['tabla'] .= '        <div class="dropdown">';
            $dato['tabla'] .= '     ';
            $dato['tabla'] .= '          <!--Trigger-->';
            $dato['tabla'] .= '          <a class="btn btn-light dropdown-toggle acciones" type="button"  data-toggle="dropdown"';
            $dato['tabla'] .= '            aria-haspopup="true" aria-expanded="false">Acciones</a>';
            $dato['tabla'] .= '  ';
            $dato['tabla'] .= '        ';
            $dato['tabla'] .= '          <!--Menu-->';
            $dato['tabla'] .= '          <div class="dropdown-menu dropdown-light">';
            $dato['tabla'] .= '            <a class="dropdown-item editar" href="#" >Editar</a>';
            $dato['tabla'] .= '            <a class="dropdown-item ver" href="#" >Ver producto</a>';
            $dato['tabla'] .= '            <a class="dropdown-item descatalogar" href="#" >Descatalogar</a>';
            $dato['tabla'] .= '            <a class="dropdown-item eliminar" href="#" >Eliminar</a>';
            $dato['tabla'] .= '          </div>';
            $dato['tabla'] .= '        </div>';
            $dato['tabla'] .= '        <!--/Dropdown primary-->';

            $dato['tabla'] .= '</td>';
            $dato['tabla'] .= '<td class="text-center">' . $v->codigo_producto . '</td>';
            $dato['tabla'] .= '<td class="text-right">' . $v->id_producto . '</td>';
            $dato['tabla'] .= '<td class="text-left">' . $v->nombre . '</td>';
            $dato['tabla'] .= '<td class="text-right">' . number_format($v->peso_real / 1000, 3, ",", ".") . '</td>';
            $dato['tabla'] .= '<td class="text-right">' . $v->tipo_unidad . '</td>';
            $dato['tabla'] .= '<td class="text-right">' . number_format($v->precio_compra / 1000, 3, ",", ".") . '</td>';
            $dato['tabla'] .= '<td class="text-left">' . $v->proveedor . '</td>';
            $dato['tabla'] .= '<td class="text-right">' . number_format($v->tarifa_venta / 1000, 3, ",", ".") . '</td>';
            $dato['tabla'] .= '<td class="text-right">' . number_format($v->margen_real_producto / 1000, 3, ",", ".") . '</td>';
            $dato['tabla'] .= '<td class="text-right">' . $v->stock_total . '</td>';
            $dato['tabla'] .= '<td class="text-right">' . number_format($v->valoracion / 1000, 3, ",", ".") . '</td>';
            // $dato['tabla'].='<td>'.$v->url_imagen_portada.'</td>';
            $dato['tabla'] .= '<td><button class="img text-center" img="' . $v->url_imagen_portada . '">Img</button></td>';
            $dato['tabla'] .= '</tr>';
        }

        $dato['tabla'] .= "</tbody>
                                <tfoot>
                                    $thead
                            </tfoot>";


        // se crea la configuracion de myModalProducto para editar y Nuevo producto
        $datoModal['modal'] = $this->getEditarProductoModal();
        // $datoModalVer['modalVer'] = $this->getEditarProductoModalVer();

        $this->load->view('mdb/templates/header');      // encabezamiento MDB
        // $this->load->view('mdb/templates/nav_bar');     // menu MDB
        $this->load->view('mdb/productos', $dato);      // tabla productos
        $this->load->view('mdb/templates/footer');      // pie MDB
        $this->load->view('mdb/myModalProducto', $datoModal); //modal edit y nuevo
        
        $this->load->view('mdb/mostrarImagenModal');
    }

    // obsoleta
    public function productos()
    {

        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');

        $crud->set_table('pe_productos');
        $crud->set_lang_string('delete_error_message', 'My Own Error Message Here!');
        $crud->callback_delete(array($this, '_delete'));
        //$crud->callback_before_delete(array($this,'_producto_before_delete'));


        $output = $crud->render();
        $this->_outputProductos($output, 'Productos');
    }

    public function _delete($primary_key)
    {
        $this->db->update('pe_productos', array('notas' => 'borrado'), array('id' => $primary_key));
        return false;
    }

    function insertProductosPeso()
    {
        $result = $this->db->query("SHOW FIELDS FROM pe_productos")->result();

        extract($_POST);
        $textoError = "";
        $error = false;
        $titulo = "Información";
        if ($this->existeCodigoProducto($codigoProducto)) {
            $error = true;
            $textoError = "NO SE PUEDE CREAR el producto " . $codigoProducto . " porque YA existe";
            echo  json_encode(array('titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
            return;
        }
        $row = $this->db->query("SELECT * FROM pe_productos WHERE codigo_producto='$codigoProductoOriginal'")->row_array();
        $set = "";
        unset($row['id']);
        $row['codigo_producto'] = $codigoProducto;
        // $row['cat_referencia']=$codigoProducto;
        // $row['cat_referencia_en']=$codigoProducto;
        // $row['cat_referencia_fr']=$codigoProducto;

        // $row['cat_nombre']=$nombre;
        // $row['cat_nombre_en']=$nombre;
        // $row['cat_nombre_fr']=$nombre;
        // $row['cat_url_producto']=$row['url_imagen_portada'];
        // $row['cat_url_producto_en']=$row['url_imagen_portada'];
        // $row['cat_url_producto_fr']=$row['url_imagen_portada'];
        $row['url_imagen_portada_excel'] == $row['url_imagen_portada'];
        $row['id_producto'] = $idProducto;
        $row['nombre'] = $nombre;
        $row['nombre_generico'] = $nombreGenerico;
        $row['precio_compra'] = $precioCompra;
        $row['precio_compra_excel'] = $precioCompra;
        $row['precio_ultimo_unidad'] = $precioCompra;
        $row['precio_ultimo_peso'] = 0;
        $row['unidades_precio'] = 1000;
        $row['tarifa_venta'] = $tarifaVenta;
        $row['tarifa_venta_excel'] = $tarifaVenta;
        $row['tarifa_venta_unidad'] = $tarifaVenta;
        $row['tarifa_venta_peso'] = 0;
        $row['margen_real_producto'] = $beneficioProducto;
        $row['margen_real_producto_excel'] = $beneficioProducto;
        $row['iva'] = $iva;

        $row['anada'] = isset($anada) ? $anada : $row['anada'];
        $row['peso_real'] = isset($pesoReal) ? $pesoReal : $row['peso_real'];

        $row['fecha_caducidad'] = '1970-01-010';
        $hoy = date("Y-m-d");
        $row['fecha_modificacion'] = $hoy;
        $row['fecha_proveedor_2'] = $hoy;
        $row['fecha_proveedor_3'] = $hoy;
        $row['fecha_alta'] = $hoy;
        $row['modificado_por'] = $_SESSION['id'];
        $row['stock_minimo'] = 1000;
        $row['stock_total'] = 0;
        $row['unidades_stock'] = 0;
        $row['valoracion'] = 0;
        $row['control_stock'] = 'Sí';
        $row['tipo_unidad'] = 'Und';
        $row['tipo_unidad_mostrar'] = 0;
        $row['descuento_profesionales'] = 0;
        $row['tarifa_profesionales'] = $tarifaVenta;
        $row['margen_venta_profesionales'] = $beneficioProducto;
        $row['descuento_profesionales_vip'] = 0;
        $row['tarifa_profesionales_vip'] = $tarifaVenta;
        $row['margen_venta_profesionales_vip'] = $beneficioProducto;
        // Los campos int que tengan '' se pone 0
        $result = $this->db->query("SHOW FIELDS FROM pe_productos")->result();
        foreach ($result as $k => $v) {
            if ($k && strpos($v->Type, 'int') && trim($row[$v->Field]) == '')
                $row[$v->Field] = 0;
        }


        foreach ($row as $k => $v) {
            $set .= "$k = '$v', ";
        }
        $set = substr(trim($set), 0, -1);

        $sql = "INSERT INTO pe_productos SET " . $set;
        // mensaje('insertar producto '.$sql);
        if (!$this->db->query($sql)) {
            $textoError = "NO SE HA PODIDO CREAR el producto " . $codigoProducto . " ERROR AL INSERTAR. INFORMAR Administrador";
            $error = true;
        };
        $this->load->library('email');
        $ahora = date('d/m/Y H:i:s');
        enviarEmail($this->email, 'Insertado productos peso', host() . ' - Pernil181', 'Realizada por: ' . $this->session->nombre . '<br>Fecha: ' . $ahora, 3);
        echo  json_encode(array('sql' => $sql, 'titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
    }

    function existeCodigoProducto($codigoProducto)
    {
        return $this->db->query("SELECT codigo_producto FROM pe_productos WHERE codigo_producto='$codigoProducto'")->num_rows();
    }
}
