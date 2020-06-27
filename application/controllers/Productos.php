<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Productos extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Productos_Model');
    }

    public function index()
    {
        mensaje('base_url ' . base_url());
        $dato['base_url_anterior'] = base_url_anterior();
        $dato['status_producto'] = 1;
        $dato['tipoProducto'] = 1 ? " Catalogados" : " Descatalogados";
        $dato['otroTipoProducto'] = !1 ? " Catalogados" : " Descatalogados";

        $dato['style'] = 'productos.css';
        $dato['js'] = 'productos.js';
        $this->load->view('templates/header', $dato);      // encabezamiento MDB
        $this->load->view('templates/menus');      // encabezamiento MDB
        $this->load->view('productos/productos');      // tabla productos
        $this->load->view('templates/footer');      // pie MDB



    }

    public function productos($status_producto = 1)
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

        // se crea la configuracion de myModalProducto para editar y Nuevo producto
        $datoModal['modal'] = $this->getEditarProductoModal();
        $datoModalCompra['modal'] = $this->getProductoCompra();
        $datoModalBodega['modal'] = $this->getProductoBodega();

        $dato['base_url_anterior'] = base_url_anterior();
        $dato['status_producto'] = $status_producto;
        $dato['tipoProducto'] = $status_producto ? " Catalogados" : " Descatalogados";
        $dato['otroTipoProducto'] = !$status_producto ? " Catalogados" : " Descatalogados";

        $dato['style'] = 'productos.css';
        $dato['js'] = 'productos.js';
        $this->load->view('templates/header', $dato);      // encabezamiento MDB
        $this->load->view('templates/menus');      // encabezamiento MDB
        $this->load->view('productos/productos');      // tabla productos
        $this->load->view('templates/footer');      // pie MDB
        $this->load->view('productos/myModal1'); //modal edit y nuevo
        $this->load->view('productos/myModal2', $datoModalCompra); //modal edit y nuevo
        $this->load->view('productos/myModal3', $datoModalBodega); //modal edit y nuevo
        $this->load->view('productos/myModalInformacion'); //modal edit y nuevo
        $this->load->view('productos/mostrarImagenModal');
        $this->load->view('productos/myModalProducto', $datoModal); //modal edit y nuevo
        $this->load->view('productos/myModal'); //modal edit y nuevo
    }

    // define la estructura datos de la tabla
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

        // $dato['estructura'][] = array(
        //     'campo' => 'espack',
        //     'texto' => 'Es Pack?',
        //     'tipo' => 'checkbox',
        //     'color' => 'black',
        //     'editar' => "",
        //     'nuevo' => "",
        //     'requerido' => false,
        //     'ancho' => '30'
        // );


        return $dato['estructura'];
    }

    // obtiene los datos para editar
    function getEditarProductoModal()
    {
        // $this->load->database();
        $estructura = $this->estructuraDatos();
        $id_familia = $this->Productos_Model->getFamiliasOpciones();
        $id_grupo = $this->Productos_Model->getGruposOpciones();
        $control_stock = $this->Productos_Model->getSiNoOpciones();
        $tipo_unidad = $this->Productos_Model->getTipoUnidadesOpciones();
        $id_proveedor_web = $this->Productos_Model->getProveedoresOpciones();
        $modificado_por = $this->Productos_Model->getUsuariosOpciones();

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
                    case 'checkbox':
                        $modal .=  '<div class="col-sm">
                                    <div class="md-form">
                                        <input type="checkbox" id="' . $estructura[$columna]['campo'] . '" class="form-check-input" ' . $estructura[$columna]['editar'] . ' checked >
                                        <label class="form-check-label" for="' . $estructura[$columna]['campo'] . '">' . $estructura[$columna]['texto'] . '</label>
                                    </div>
                                </div>';
                    break;
                }
            }
            $modal .= '</div>';
        }
        return $modal;
    }

    // obtiene los datos de productos bodega para crear nuevas añadas
    function getProductoBodega()
    {
        $datos['productos'] = array();
        $result = $this->Productos_Model->getResult("SELECT id_producto FROM pe_productos WHERE id_grupo=8 AND  tipo_unidad='Und' GROUP BY id_producto ");
        foreach ($result as $k => $v) {
            $row = $this->Productos_Model->getRow("SELECT id,codigo_producto,nombre,status_producto FROM pe_productos WHERE id_producto='" . $v->id_producto . "' ORDER BY nombre ASC, anada  DESC LIMIT 1");
            $datos['productosBodega'][$row->id] = $row->nombre . ' (' . $row->codigo_producto . ')';
        }
        asort($datos['productosBodega']);
        // array_unshift($datos['productosBodega'], "Seleccionar producto bodega");
        return $datos['productosBodega'];
    }

    // obtiene los datos de productos compra (boka=0) para crear nuevas añadas
    function getProductoCompra()
    {
        $datos['productos'] = array();
        $result = $this->Productos_Model->getResult("SELECT * FROM pe_productos WHERE id_producto=0 AND  tipo_unidad='Kg' ORDER BY nombre");
        foreach ($result as $k => $v) {
            $datos['productosCompra'][$v->id] = $v->nombre . ' (' . $v->codigo_producto . ')';
        }
        return $datos['productosCompra'];
    }

    function rangosPeso($id = 0)
    {
        // $id=$_POST['id'];
        mensaje('id ' . $id);
        $sql = "SELECT codigo_producto,status_producto FROM pe_productos WHERE id='$id'";
        mensaje('sql ' . $sql);

        $codigo_producto = $this->Productos_Model->getRow($sql)->codigo_producto;
        mensaje('codigo_producto ' . $codigo_producto);
        $codigoBoka = substr($codigo_producto, 5, 3);
        mensaje('codigoBoka ' . $codigoBoka);
        $preCodigo = substr($codigo_producto, 0, 8);

        $productos = $this->getProductosCodigoPre($preCodigo);
        echo json_encode($productos);
    }

    // comprueba existencia producto
    function existeCodigoProducto($codigoProducto)
    {
        return $this->Productos_Model->getNumRows("SELECT codigo_producto FROM pe_productos WHERE codigo_producto='$codigoProducto'");
    }

    // insertar nuevo productos rango Peso
    function insertProductosCopia($tipo)
    {
        // $tipo=1, si se trata de copia productos peso
        // $tipo=2, si se trata de copia productos Bodega
        $result = $this->Productos_Model->getResult("SHOW FIELDS FROM pe_productos");
        extract($_POST);
        $textoError = "";
        $error = false;
        $titulo = "Información";
        mensaje($codigoProducto);
        mensaje($this->existeCodigoProducto($codigoProducto));

        if ($tipo == 1 && (!is_numeric($pesoReal) || $pesoReal <= 0)) {
            $error = true;
            $textoError = "El peso debe ser un número mayor que 0";
            echo  json_encode(array('titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
            return;
        }
        if ($tipo == 2 && (!is_numeric($anada) || $anada <= 1950 || $anada > date("Y"))) {
            $error = true;
            $textoError = "La añada debe ser un año de 4 cifras entre 1950 y año actual";
            echo  json_encode(array('titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
            return;
        }
        if (!is_numeric($precioCompra)) {
            $error = true;
            $textoError = "El precio de compra debe ser un número";
            echo  json_encode(array('titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
            return;
        }
        if (!is_numeric($tarifaVenta)) {
            $error = true;
            $textoError = "La tarifa venta debe ser un número";
            echo  json_encode(array('titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
            return;
        }

        // $codigoProducto, debe ser único
        if ($this->existeCodigoProducto($codigoProducto)) {
            $error = true;
            $textoError = "NO SE PUEDE CREAR el producto " . $codigoProducto . " porque YA existe";
            echo  json_encode(array('titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
            return;
        }
        mensaje("SELECT * FROM pe_productos WHERE codigo_producto='$codigoProductoOriginal'");
        $row = $this->Productos_Model->getRowArray("SELECT * FROM pe_productos WHERE codigo_producto='$codigoProductoOriginal'");
        $set = "";
        unset($row['id']);
        $row['codigo_producto'] = $codigoProducto;
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
        $row['modificado_por'] = $this->session->id;
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
        $row['status_producto'] = 1;
        // Los campos int que tengan '' se pone 0
        $result = $this->Productos_Model->getResult("SHOW FIELDS FROM pe_productos");
        foreach ($result as $k => $v) {
            if ($k && strpos($v->Type, 'int') && trim($row[$v->Field]) == '')
                $row[$v->Field] = 0;
        }


        foreach ($row as $k => $v) {
            $set .= "$k = '$v', ";
        }
        $set = substr(trim($set), 0, -1);

        $sql = "INSERT INTO pe_productos SET " . $set;
        mensaje('insertar producto ' . $sql);
        $error = false;
        $textoError = "";
        if (!$this->Productos_Model->query($sql)) {
            $textoError = "NO SE HA PODIDO CREAR el producto <strong>" . $codigoProducto . " </strong>ERROR AL INSERTAR. INFORMAR Administrador";
            $error = true;
            echo  json_encode(array('titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
            return;
        };
        // se vuelven a obterner los datos para actailizar la informcion en la ventana de rangos
        mensaje('$codigo producto insertado ' . $codigoProducto);
        $prefijo = substr($codigoProducto, 0, 8);
        mensaje('$prefijo ' . $prefijo);
        $productos = $this->getProductosCodigoPre($prefijo);

        echo json_encode(['error' => $error, 'textoError' => $textoError, 'productos' => $productos]);
    }

    function rangosAnada($id = 0)
    {
        // $id=$_POST['id'];
        mensaje('id ' . $id);
        $sql = "SELECT codigo_producto,status_producto FROM pe_productos WHERE id='$id'";
        mensaje('sql ' . $sql);

        $codigo_producto = $this->Productos_Model->getRow($sql)->codigo_producto;
        mensaje('codigo_producto ' . $codigo_producto);
        $codigoBoka = substr($codigo_producto, 5, 3);
        mensaje('codigoBoka ' . $codigoBoka);
        $preCodigo = substr($codigo_producto, 0, 8);

        $productos = $this->getProductosCodigoPreAnada($preCodigo);
        echo json_encode($productos);
    }

    function getProductosCodigoPre($prefijo)
    {
        $sql = "SELECT * FROM pe_productos WHERE SUBSTR(codigo_producto,1,8)='$prefijo'  ORDER BY peso_real ";
        mensaje('prefijo ' . $prefijo);
        $result = $this->Productos_Model->getResult($sql);
        $productos = array();
        foreach ($result as $k => $v) {
            // if($v->id_producto){
            $productos[] = array(
                'codigoProducto' => $v->codigo_producto,
                'codigoBoka' => $v->id_producto,
                'iva' => $v->iva,
                'tipoUnidad' => $v->tipo_unidad,
                'pesoReal' => number_format($v->peso_real / 1000, 3),
                'anada' => $v->anada,
                'nombre' => $v->nombre,
                'nombreGenerico' => $v->nombre_generico,
                'precioCompra' => number_format($v->precio_compra / 1000, 3),
                'tarifaVenta' => number_format($v->tarifa_venta / 1000, 3),
                'statusProducto' => $v->status_producto
            );
            // }
        }
        return $productos;
    }
    function getProductosCodigoPreAnada($prefijo)
    {
        $sql = "SELECT * FROM pe_productos WHERE SUBSTR(codigo_producto,1,8)='$prefijo'  ORDER BY anada DESC ";
        // mensaje('getProductosCodigoPre '.$sql);
        $result = $this->Productos_Model->getResult($sql);
        $productos = array();
        foreach ($result as $k => $v) {
            // if($v->id_producto){
            $productos[] = array(
                'codigoProducto' => $v->codigo_producto,
                'codigoBoka' => $v->id_producto,
                'iva' => $v->iva,
                'tipoUnidad' => $v->tipo_unidad,
                'pesoReal' => number_format($v->peso_real / 1000, 3),
                'anada' => $v->anada,
                'nombre' => $v->nombre,
                'nombreGenerico' => $v->nombre_generico,
                'precioCompra' => number_format($v->precio_compra / 1000, 3),
                'tarifaVenta' => number_format($v->tarifa_venta / 1000, 3),
                'statusProducto' => $v->status_producto
            );
            // }
        }
        return $productos;
    }

    // descatalogar producto
    function descatalogar()
    {
        $id = $_POST['id'];
        $result = $this->db->query("UPDATE pe_productos SET status_producto=0 WHERE id='$id'");
        echo  json_encode($result);
    }

    // catalogar producto
    function catalogar()
    {
        $id = $_POST['id'];
        $result = $this->db->query("UPDATE pe_productos SET status_producto=1 WHERE id='$id'");
        echo  json_encode($result);
    }

    // exportar productos seleccionados Excel
    function exportExcel($buscar = "", $codigo_producto = "", $id_producto = "", $producto = "", $peso_real = "", $tipo_unidad = "", $precio_compra = "", $proveedor = "", $tarifa_venta = "", $margen_real_producto = "", $stock_total = "", $valoracion = "", $vacio5 = "")
    {
        $this->load->library('excel');
        // renueva datos pasados por url para pasarlos al Excel indicando la selección 

        $datos['buscar'] = urldecode($buscar);
        $datos['codigo_producto'] = $codigo_producto != "_" ? urldecode($codigo_producto) : "";
        $datos['id_producto'] = $id_producto != "_" ? urldecode($id_producto) : "";
        $datos['producto'] = $producto != "_" ? urldecode($producto) : "";
        $datos['peso_real'] = $peso_real != "_" ? urldecode($peso_real) : "";
        $datos['tipo_unidad'] = $tipo_unidad != "_" ? urldecode($tipo_unidad) : "";
        $datos['precio_compra'] = $precio_compra != "_" ? urldecode($precio_compra) : "";
        $datos['tarifa_venta'] = $tarifa_venta != "_" ? urldecode($tarifa_venta) : "";
        $datos['margen'] = $margen_real_producto != "_" ? urldecode($margen_real_producto) : "";
        $datos['stock_total'] = $stock_total != "_" ? urldecode($stock_total) : "";
        $datos['valoracion'] = $valoracion != "_" ? urldecode($valoracion) : "";
        $datos['vacio5'] = $vacio5 != "_" ? urldecode($vacio5) : "";
        $datos['proveedor'] = $proveedor != "_" ? urldecode($proveedor) : "";

        $sql = "SELECT 
                p.id as id,
                p.codigo_producto as codigo_producto,
                p.id_producto as id_producto,
                p.nombre as nombre,
                FORMAT(p.peso_real/1000,2) as peso_real,
                p.tipo_unidad, 
                g.nombre_grupo,
                f.nombre_familia,
                FORMAT(p.precio_compra/1000,3) as precio_compra,
                tipo_unidad as tipo_unidad,
                pr.nombre_proveedor as nombre_proveedor,
                FORMAT(p.tarifa_venta/1000,3) as tarifa_venta,
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

        // renueva datos pasados por url para utilizarlos en mysql
        $buscar = urldecode($buscar);
        $codigo_producto = urldecode($codigo_producto);
        $id_producto = urldecode($id_producto);
        $producto = urldecode($producto);
        $peso_real = urldecode($peso_real);
        $tipo_unidad = $tipo_unidad;
        $precio_compra = urldecode($precio_compra);
        $proveedor = urldecode($proveedor);
        $tarifa_venta = urldecode($tarifa_venta);
        $margen_real_producto = urldecode($margen_real_producto);
        $stock_total = urldecode($stock_total);
        $valoracion = urldecode($valoracion);
        $vacio5 = urldecode($vacio5);
        // elimina la ',', ya que en la base de datos NO utiliza números decimales
        if ($peso_real != "_") {
            $peso_real = str_replace(".", "", $peso_real);  // se elimina '.' de miles
            $peso_real = str_replace(",", ".", $peso_real);
            $peso_real = (float) $peso_real * 1000;
            $peso_real = str_replace(".", "", $peso_real);
        }

        $precio_compra = $this->numeroRegistradoBDProductos($precio_compra);


        if ($tarifa_venta != "_") {
            $tarifa_venta = str_replace(".", "", $tarifa_venta);  // se elimina '.' de miles
            $tarifa_venta = str_replace(",", ".", $tarifa_venta);
            $tarifa_venta = (float) $tarifa_venta * 1000;
            $tarifa_venta = str_replace(".", "", $tarifa_venta);
        }
        if ($margen_real_producto != "_") {
            $margen_real_producto = str_replace(".", "", $margen_real_producto);  // se elimina '.' de miles
            $margen_real_producto = str_replace(",", ".", $margen_real_producto);
            $margen_real_producto = (float) $margen_real_producto * 10;   // margen real producto en %
            $margen_real_producto = str_replace(".", "", $margen_real_producto);
        }
        if ($stock_total != "_") {
            $stock_total = str_replace(".", "", $stock_total);  // se elimina '.' de miles
            $stock_total = str_replace(",", ".", $stock_total);
            $stock_total = (float) $stock_total * 1000;
            $stock_total = str_replace(".", "", $stock_total);
        }
        // valoración SI utiliza decimales con '.', 
        if ($valoracion != "_") {
            $valoracion = str_replace(".", "", $valoracion);  // se elimina '.' de miles
            $valoracion = str_replace(",", ".", $valoracion);
        }

        // se añaden condiciones de búsqueda en       
        $sql .= $codigo_producto != "_" ? " AND p.codigo_producto like '%$codigo_producto%'" : "";
        $sql .= $id_producto != "_" ? " AND p.id_producto like '%$id_producto%'" : "";
        $sql .= $producto != "_" ? " AND p.nombre like '%$producto%'" : "";
        $sql .= $peso_real != "_" ? " AND p.peso_real like '%$peso_real%'" : "";
        $sql .= $precio_compra != "_" ? " AND p.precio_compra like '%$precio_compra%'" : "";
        $sql .= $tarifa_venta != "_" ? " AND p.tarifa_venta like '%$tarifa_venta%'" : "";
        $sql .= $margen_real_producto != "_" ? " AND p.margen_real_producto like '%$margen_real_producto%'" : "";
        $sql .= $stock_total != "_" ? " AND p.stock_total like '%$stock_total%'" : "";
        $sql .= $valoracion != "_" ? " AND p.valoracion like '%$valoracion%'" : "";
        $sql .= $tipo_unidad != "_" ? " AND p.tipo_unidad like '%$tipo_unidad%'" : "";
        $sql .= $proveedor != "_" ? " AND pr.nombre_proveedor like '%$proveedor%'" : "";
        if ($buscar != "_") {
            $sql .= " AND ( ";
            $sql .= " p.codigo_producto like '%$buscar%'";
            $sql .= " OR p.id_producto like '%$buscar%'";
            $sql .= " OR p.nombre like '%$buscar%'";
            $sql .=  " OR p.peso_real like '%" . str_replace(",", "", $buscar) . "%'";
            $sql .=  " OR p.precio_compra like '%" . str_replace(",", "", $buscar) . "%'";
            $sql .=  " OR p.tarifa_venta like '%" . str_replace(",", "", $buscar) . "%'";
            $sql .=  " OR p.margen_real_producto like '%" . str_replace(",", "", $buscar) . "%'";
            $sql .=  " OR p.stock_total like '%" . str_replace(",", "", $buscar) . "%'";
            $sql .=  " OR p.valoracion like '%" . str_replace(",", ".", $buscar) . "%'"; // valoracion esta en decimales con'.'
            $sql .= " OR p.tipo_unidad like '%$buscar%'";
            $sql .=  " OR pr.nombre_proveedor like '%$buscar%'";
            $sql .= " ) ";
        }
        $sql .= " ORDER BY p.codigo_producto";

        mensaje($buscar);
        mensaje($sql);
        $datos['result'] = $this->db->query($sql)->result();



        // $this->load->library('email');
        // $ahora = date('d/m/Y H:i:s');
        // if ($this->session->categoria != 1) {
        //     enviarEmail($this->email, 'Exportación datos productos ', host() . ' - Pernil181', 'Bajado por: <br>Usuario: ' . $this->session->nombre . '<br>Fecha: ' . $ahora, 3);
        // }
        $this->load->view('productos/prepararExcelProductos', $datos);
    }

    function numeroRegistradoBDProductos($numero)
    {
        if ($numero == "_") return $numero;
        $numero = urldecode($numero);
        $numero = str_replace(".", "", $numero);  // se elimina '.' de miles
        $numero = str_replace(",", "", $numero); // se elimina el punto decimal
        return $numero;
    }
    // getIVA
    function getIva()
    {
        $iva = $this->Productos_Model->getIva($_POST['grupo']);
        $iva = number_format($iva, 2, ".", "");
        echo  json_encode($iva);
    }

    // verifica corresponencia grupo-fanilia - solicitado via ajax
    function checkGrupoFamilia()
    {
        $id_grupo = $_POST['id_grupo'];
        $id_familia = $_POST['id_familia'];
        $num = $this->db->query("SELECT * FROM pe_grupos_familias WHERE id_grupo='$id_grupo' AND id_familia='$id_familia'")->num_rows();
        if ($num == 0) {
            echo  json_encode(false);
        } else
            echo  json_encode(array(true));
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
            $actualizado = array(
                'id' => $id,
                'nombre' => $datos['nombre'],
                'codigo_producto' => $datos['codigo_producto'],
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
            // poner datos relativos a quien los modifica
            if ($id == 0) {
                // obtengo el nuevo id
                $id = $this->db->query("SELECT id FROM pe_productos ORDER BY id DESC LIMIT 1")->row()->id;
            }
            mensaje('modig¡ficado por ' . $this->session->id);
            $hoy = date("Y-m-d");
            $usuario = $this->session->id;
            $sql = "UPDATE pe_productos SET  fecha_modificacion='$hoy', modificado_por='$usuario' where id='$id'";
            mensaje('sql ' . $sql);
            $this->db->query("UPDATE pe_productos SET fecha_modificacion='" . date('Y-m-d') . "', modificado_por='" . $this->session->id . "' WHERE id='$id'");

            $informacion = $this->actualizarProductosIgualBoka($id);
            echo  json_encode(['actualizar' => $actualizado, 'informacion' => $informacion]);
            return;
        }
        // devuelve datos que se tendrán qu e actualizar
        echo  json_encode(false);
    }

    // actualizar datos en productos con igual Boka 
    // sólo se considera actualizar de forma proporcional al peso el pvp (tarifa venta)
    // el precio compra de cada productos, viene determinado por el precio de compra de la unidad o, si existe,
    // el precio de la transformación que se haya realizado en su momento
    // también se cambian el grupo y la familia si se hubieran cambiado, y el iva
    // siempre se recalcula el margen y la valoración del producto (?)
    function actualizarProductosIgualBoka($id)
    {
        $row = $this->Productos_Model->getRow("SELECT * FROM pe_productos WHERE id='$id'");
        if ($row->id_grupo == 8) return false; // se trata de productos bodega
        if ($row->peso_real == 0) return false; // no tiene peso

        $id_producto = $row->id_producto;
        $sql = "SELECT * FROM pe_productos WHERE id_producto='$id_producto'";
        if ($this->Productos_Model->getNumRows($sql) <= 1) return false;
        // datos producto editado / nuevo
        $tarifa_venta = $row->tarifa_venta;
        $tarifa_venta_unidad = $row->tarifa_venta_unidad;
        $tarifa_venta_peso = $row->tarifa_venta_peso;
        $peso_real = $row->peso_real;
        $id_grupo = $row->id_grupo;
        $id_familia = $row->id_familia;
        $iva = $row->iva;
        $tipo_unidad = $row->tipo_unidad;

        $result = $this->Productos_Model->getResult("SELECT * FROM pe_productos WHERE id_producto='$id_producto'");
        $informacion = "";
        foreach ($result as $k => $v) {

            if ($v->tipo_unidad != $tipo_unidad || $v->id_grupo != $id_grupo || $v->id_familia != $id_familia || $v->iva != $iva) {
                $informacion .= "No hay coherencia con las características del producto <b>" . $v->codigo_producto . " </b>. No se actualizan datos<br>";
                continue;
            }

            $id = $v->id;
            $tarifa_venta_nueva = $tarifa_venta / $peso_real * $v->peso_real;
            $tarifa_venta_unidad_nueva = $tarifa_venta_unidad / $peso_real * $v->peso_real;
            $tarifa_venta_peso_nueva = $tarifa_venta_peso / $peso_real * $v->peso_real;
            $sql = "UPDATE pe_productos SET
                    tarifa_venta='$tarifa_venta_nueva',
                    tarifa_venta_unidad='$tarifa_venta_unidad_nueva',
                    tarifa_venta_peso='$tarifa_venta_peso_nueva'
                    WHERE id='$id'
                ";
            mensaje($sql);
            $this->db->query($sql);
        }
        return $informacion;

        // criterios 



    }

    // comprueba status_producto (Catalogado / Descatalogado)
    function checkStatusProducto()
    {
        $id = $_POST['id'];
        $row = $this->db->query("SELECT status_producto FROM pe_productos WHERE id='$id'")->row();
        echo  json_encode($row->status_producto);
    }

    // obtener datos de un producto para editarlos -via ajax
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
            if ($v['tipo'] == 'seleccion') {
                $c = 'valor_' . $v['campo'];
                $row[$c] = "Seleccionar " . $v['texto'];
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
        $camposX = [];
        $leftjoin = [];
        foreach ($estructura as $k => $v) {
            $campos[] = $v['campo'];
            if (isset($v['tipo']) && $v['tipo'] == 'seleccion') {
                $camposX[] = "lf$k." . $v['seleccion']['valor'] . " as valor_" . $v['campo'];
                $leftjoin[] = "LEFT JOIN " . $v['seleccion']['tabla'] . " lf" . $k . " ON lf$k." . $v['seleccion']['indice'] . "=p." . $v['campo'] . " ";
            }
        }

        $c  = "p." . implode(", p.", $campos);
        $x = implode(", ", $camposX);
        $c .= ", " . $x;
        $lj = implode(" ", $leftjoin);
        $sql = "SELECT $c FROM pe_productos p " .
            $lj
            . "WHERE p.id='$id_pe_producto'";
        mensaje('getDatosProducto ' . $sql);
        $row = $this->db->query($sql)->row_array();
        $convertido = $this->convertirDirecto($row);
        echo  json_encode($convertido);
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
                case 'checkbox':

                    break;
                default:
            }
        }
        // foreach ($row as $k => $v) {
        //     mensaje($k . ' ' . $v);
        // }
        return $row;
    }

    // convierte datos productos para ser grabados 
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
        // foreach ($sets as $k => $v) {
        //     mensaje($k . ' ' . $v);
        // }
        return implode(",", $sets);
    }
}
