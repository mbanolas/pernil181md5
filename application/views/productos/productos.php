<!-- Start your project here-->
<style>
    /* margen superior para ver después de la barra menú*/
    body>div.card.card-cascade {
        margin-top: 70px;
    }

    #productos_filter {
        margin: 0px 0px;
    }

    #productos>tbody>tr>td:nth-child(1)>div {
        margin: 0px;
    }

    #productos>tbody>tr>td:nth-child(1)>div>input {
        margin: 0px;
    }

    td {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
        vertical-align: middle !important;
    }

    .text-right {
        padding-right: 8px !important;
    }

    #productos>tbody>tr>td:nth-child(1) {
        background-color: white !important;
    }

    .acciones {
        padding: 13px;
    }

    #productos>tbody>tr>td:nth-child(1)>div>a:hover {
        color: black;
    }

    #productos>tbody>tr>td>div>div>a:hover {
        background-color: lightgray !important;
        color: black !important;
    }

    .card {
        padding: 0px;
        margin: 15px;
    }

    body>div.card.card-cascade>div.view.view-cascade.gradient-card-header.blue-gradient>h2 {
        padding: 0px 0px 0px;
    }

    body>div.card.card-cascade>div.view.view-cascade.gradient-card-header.blue-gradient {
        padding: 10px 10px;
    }

    .card-header-title {
        text-align: left;
    }

    body>div.card.card-cascade>div.card-body.card-body-cascade.text-center {
        padding: 5px;
    }

    .spinner-grow {
        margin-left: 50px;
    }



    .imagen_producto {
        margin-left: 20px;
    }

    .precio_compra {
        font-size: 20px;
        color: red;
    }

    .tarifa_venta {
        font-size: 20px;
        color: blue;
    }

    .margen_real_producto {
        font-size: 20px;
        color: green;
    }

    input[placeholder] {
        border-top: 0px;
        border-bottom: 1px solid #ced4da;
        border-left: 0px;
        border-right: 0px;
    }

    input[placeholder]:focus {
        color: #495057;
        background-color: #fff;
        border-bottom: 0.15rem solid #6ba0f3;
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0rem rgba(0, 123, 255, .25);
    }

    .card-header-title {
        margin: 0 0 0;
    }

    .card-header-title>button,
    .card-header-title>a {
        margin-top: 4px;
        margin-bottom: 0px;
        padding: 4px 15px;
    }

    /* dimensiones Modal producto */
    .modal-dialog-producto {
        max-width: 80% !important;
        text-align: left;
    }

    .dropdown-menu.dropdown-light {
        margin-left: 10px;
    }

    /* columna acciones */
    #productos > tbody > tr:nth-child(1) > td:nth-child(1){
        min-width:120px;
    }
    /* oculta fila 1 y 2 , columna 1 */
    #productos > thead > tr:nth-child(2) > th:nth-child(1){
        visibility: hidden;
    }
    #productos > thead > tr:nth-child(1) > th:nth-child(1){
        visibility: hidden;
    }

    

    .img{
        width: 25px;
        border: 1px solid lightgray;
    }
</style>
<!-- Project -->
<!-- Card -->
<div class="card card-cascade">
    <!-- Card image -->
    <div class="view view-cascade gradient-card-header blue-gradient">
        <!-- Title -->
        <h2 class="card-header-title ">Productos <?php echo strtolower($tipoProducto) ?>
            <div class="spinner-grow text-danger loading " role="status">
                <span class="sr-only ">Cargando...</span>
            </div>
            <button class="btn btn-primary float-right" id="no_filtros">No filtros</button>
            <a class="btn btn-primary float-right" id="exportar" href="<?= base_url() ?>index.php/productos/exportExcel">
                Exportar
                <!--Small yellow-->
                <div class="ml-1 d-none spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
            </a>
            <!-- <a class="btn btn-secondary float-right" id="exportar">Exportar</a> -->
            <button class="btn btn-primary float-right" id="nuevos_rangos" data-toggle="modal" data-target="#myModal1">Nuevos rangos/añadas</button>
            <a class="btn btn-primary float-right" id="cambioTipoProducto" href="<?php echo base_url() ?>index.php/productos/productos/<?php echo (1 - intval($status_producto)) ?>">Prod. <?php echo $otroTipoProducto ?></a>
            <button class="btn btn-primary float-right" id="nuevo">Nuevo</button>
        </h2>
        <!-- Subtitle -->
        <!-- <p class="card-header-subtitle mb-0">Deserve for her own card</p> -->
    </div>

    <!-- Card content -->
    <div class="card-body card-body-cascade text-center">
        <!-- cargango sniiper -->
        <h1 class="loading">Cargando...</h1>
        <div class="preloader-wrapper big active loading">
            <div class="spinner-layer spinner-blue-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <!-- final snnipers -->
        <!-- tabla productos -->
        <table id="productos" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" style="display:none">
            <thead>
                <tr>
                    <th></th>
                    <th>Código Producto</th>
                    <th>Cód. Bascula</th>
                    <th class="izquierda" style="min-width:40% !important">Nombre</th>
                    <th>Peso Real (Kg)</th>
                    <th>Tipo Unidad</th>
                    <th>Precio Compra Final en Tienda</th>
                    <th class="izquierda" style="min-width:20% !important">Proveedor</th>
                    <th>Tarifa PVP</th>
                    <th>Margen (%)</th>
                    <th>Undidades Stock</th>
                    <th>Valor Stock precio compra actual</th>
                    <th>Imagen Producto</th>
                </tr>
            </thead>
            <tbody>
               
                <?php foreach ($productos as $k => $v) { ?>
                    <tr producto='<?php echo $v->id ?>'>
                        <td >
                            <!--Dropdown -->
                            <div class="dropdown">
                                <!--Trigger-->
                                <a class="btn btn-sm btn-blue-grey dropdown-toggle acciones" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Acciones </a>
                                <!--Menu-->
                                <div class="dropdown-menu dropdown-light">
                                    <a class="dropdown-item btn-sm editar" href="#">Editar</a>
                                    <a class="dropdown-item ver" href="#">Ver producto</a>
                                    <?php $desCat = $status_producto ? " Descatalogar" : " Catalogar" ?>
                                    <a class="dropdown-item <?php echo strtolower($desCat) ?>" href="#"><?php echo $desCat ?></a>
                                    <a class="dropdown-item eliminar" href="#">Eliminar</a>
                                </div>
                            </div>
                            <!--/Dropdown -->
                        </td>
                        <td class="text-center"><?php echo $v->codigo_producto ?></td>
                        <td class="text-right"><?php echo $v->id_producto ?></td>
                        <td class="text-left"><?php echo $v->nombre ?> </td>
                        <td class="text-right"><?php echo number_format($v->peso_real / 1000, 3, ",", ".") ?></td>
                        <td class="text-right"><?php echo $v->tipo_unidad ?> </td>
                        <td class="text-right"><?php echo number_format($v->precio_compra / 1000, 3, ",", ".") ?></td>
                        <td class="text-left"><?php echo $v->proveedor ?> </td>
                        <td class="text-right"><?php echo number_format($v->tarifa_venta / 1000, 3, ",", ".") ?></td>
                        <td class="text-right"><?php echo number_format($v->margen_real_producto / 1000, 3, ",", ".") ?></td>
                        <td class="text-right"><?php echo $v->stock_total ?></td>
                        <td class="text-right"><?php echo number_format($v->valoracion, 3, ",", ".") ?></td>
                        <td><img class=" img rounded-circle" src="<?php echo $v->url_imagen_portada ?>"  /></td>
                        <!-- <td><img class=" img rounded-circle" src="<?php //echo $v->url_imagen_portada ?>" onerror="this.onerror=null;this.src='<?php echo base_url() ?>images/no-imagen.jpg';"  /></td> -->
                        <!-- <td><img class=" img rounded-circle"  src="<?php //echo $v->url_imagen_portada ?>"  onerror="this.onerror=null;this.src='http://www.jamonarium.com/2352-large_default/paleta-iberica-bellota-gran-reserva-2014.jpg';/></td> -->

                     </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
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
                </tr>
            </tfoot>

            <!-- <?php //echo $tabla; 
                    ?> -->
        </table>
        <!-- fin tabla productos -->
    </div>
    <!-- EndCard content -->
</div>
<!-- End Card -->
<!-- End project -->


<script data-require="bootstrap@*" data-semver="3.3.6" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script>
    // Material Select Initialization
    $(document).ready(function() {
        
        // modelo ajax
        // $.ajax({
        //         type: "POST",
        //         url: "<?= base_url() ?>index.php/<controller>/<fuction>",
        //         data: {},
        //         success: function(datos) {
        //             //    alert (datos)
        //             var datos = $.parseJSON(datos)
        //             // alert(datos['id_proveedor'])
        //         },
        //         error: function() {
        //             alert("Información importante. Error en el proceso <controller>/<fuction>. Informar");
        //         }
        //     })

        // No se muestra la tabla hasta que está cargada
        $('.loading').addClass('d-none')
        $('#productos').show()

        // variable busquedas utilizada en EXPORT 
        var jsonBusquedas = "";

        // acciones botones en barra menu productos    
        // NUEVO 
        // Abre modal para poner NUEVO producto
        $('#nuevo').click(function() {
            $('#actividad').val('nuevo')

            // En datosSuccess se guardan los datos generados por ajax para emplearlos fuera de ajax
            var datosSuccess = ""
            // ajax syncrono: no se ejecuta lo que sigue a ajax hasta que este finalic
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getDatosProductoNuevo",
                data: {

                },
                success: function(datos) {
                    // alert(datos)
                    datosSuccess = $.parseJSON(datos);
                },
                error: function() {
                    alert("Error en el proceso. getVerProducto");
                }
            })
            $('input.form-control').removeAttr('disabled')

            // botones
            $('.cerrar').addClass('d-none')
            $('.grabar_editar').addClass('d-none')
            $('.grabar_nuevo').removeClass('d-none')
            $('.descatalogar_producto').addClass('d-none')
            $('.catalogar_producto').addClass('d-none')
            $('.cancelar_editar').removeClass('d-none')

            $('#id').attr('disabled', 'disabled')
            $('#myModalProducto').css('color', 'black')
            $('.modal-title').html('Introducir datos nuevo producto')

            $.each(datosSuccess, function(index, value) {
                if (true) {
                    $('#' + index).val(value)
                    $('#' + index).parent().children('label').addClass('active')
                    if ($('input[data-activates="select-options-' + index + '"]').length)
                        $('input[data-activates="select-options-' + index + '"]').val(datosSuccess['valor_' + index])
                }
            })

            $('#codigo_producto').removeAttr('disabled')

            $('#fecha_modificacion').val('<?php echo date('d/m/Y') ?>')
            $('#modificado_por').val(<?php echo $this->session->id ?>)

            $('#id_grupo').prop('disabled', false)
            $('#id_grupo').materialSelect('refresh')

            $('#id_familia').prop('disabled', false)
            $('#id_familia').materialSelect('refresh')

            $('#id_proveedor_web').prop('disabled', false)
            $('#id_proveedor_web').materialSelect('refresh')

            $('#control_stock').prop('disabled', false)
            $('#control_stock').materialSelect('refresh')

            $('#tipo_unidad').prop('disabled', false)
            $('#tipo_unidad').materialSelect('refresh')

            $("#myModalProducto").modal({
                backdrop: 'static',
                keyboard: false
            })
        })

        // CATALOGAR DESCATALOGAR
        // Abre modal para VER producto y descatalogar
        $('table#productos').delegate('.descatalogar', 'click', function() {
            $('#actividad').val('descatalogar')
            id = $(this).parent().parent().parent().parent().attr('producto')

            // En datosSuccess se guardan los datos generados por ajax para emplearlos fuera de ajax
            var datosSuccess = ""
            // ajax syncrono: no se ejecuta lo que sigue a ajax hasta que este finalic
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getDatosProducto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    datosSuccess = $.parseJSON(datos);
                },
                error: function() {
                    alert("Error en el proceso. getDatosProducto - descatalogar");
                }
            })
            // disable inputs y selects
            $('input.form-control').attr('disabled', 'disabled')
            $('input[type="search"]').removeAttr('disabled')



            $('#id_grupo').prop('disabled', true)
            $('#id_grupo').materialSelect('refresh')

            $('#id_familia').prop('disabled', true)
            $('#id_familia').materialSelect('refresh')

            $('#id_proveedor_web').prop('disabled', true)
            $('#id_proveedor_web').materialSelect('refresh')

            $('#control_stock').prop('disabled', true)
            $('#control_stock').materialSelect('refresh')

            $('#tipo_unidad').prop('disabled', true)
            $('#tipo_unidad').materialSelect('refresh')

            // botones
            $('.cerrar').addClass('d-none')
            $('.grabar_editar').addClass('d-none')
            $('.grabar_nuevo').addClass('d-none')
            $('.descatalogar_producto').removeClass('d-none')
            $('.catalogar_producto').addClass('d-none')
            $('.cancelar_editar').removeClass('d-none')

            $('.modal-title').html('Datos producto - clasificado')
            $('#myModalProducto').css('color', 'black')
            $.each(datosSuccess, function(index, value) {
                $('#' + index).val(value)
                $('#' + index).parent().children('label').addClass('active')
                if ($('input[data-activates="select-options-' + index + '"]').length)
                    $('input[data-activates="select-options-' + index + '"]').val(datosSuccess['valor_' + index])
            })
            $("#myModalProducto").modal({
                backdrop: 'static',
                keyboard: false
            })
        })

        // Abre modal para VER producto y catalogar
        $('table#productos').delegate('.catalogar', 'click', function() {
            $('#actividad').val('catalogar')
            id = $(this).parent().parent().parent().parent().attr('producto')

            // En datosSuccess se guardan los datos generados por ajax para emplearlos fuera de ajax
            var datosSuccess = ""
            // ajax syncrono: no se ejecuta lo que sigue a ajax hasta que este finalic
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getDatosProducto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    datosSuccess = $.parseJSON(datos);
                },
                error: function() {
                    alert("Error en el proceso. getDatosProducto-catalogar");
                }
            })
            // disable inputs y selects
            $('input.form-control').attr('disabled', 'disabled')
            $('input[type="search"]').removeAttr('disabled')



            $('#id_grupo').prop('disabled', true)
            $('#id_grupo').materialSelect('refresh')

            $('#id_familia').prop('disabled', true)
            $('#id_familia').materialSelect('refresh')

            $('#id_proveedor_web').prop('disabled', true)
            $('#id_proveedor_web').materialSelect('refresh')

            $('#control_stock').prop('disabled', true)
            $('#control_stock').materialSelect('refresh')

            $('#tipo_unidad').prop('disabled', true)
            $('#tipo_unidad').materialSelect('refresh')

            // botones
            $('.cerrar').addClass('d-none')
            $('.grabar_editar').addClass('d-none')
            $('.grabar_nuevo').addClass('d-none')
            $('.descatalogar_producto').addClass('d-none')
            $('.catalogar_producto').removeClass('d-none')
            $('.cancelar_editar').removeClass('d-none')

            $('.modal-title').html('Datos producto - clasificado')
            $('#myModalProducto').css('color', 'black')
            $.each(datosSuccess, function(index, value) {
                $('#' + index).val(value)
                $('#' + index).parent().children('label').addClass('active')
                if ($('input[data-activates="select-options-' + index + '"]').length)
                    $('input[data-activates="select-options-' + index + '"]').val(datosSuccess['valor_' + index])
            })
            $("#myModalProducto").modal({
                backdrop: 'static',
                keyboard: false
            })
        })

        //set button id on click to hide first modal
        $("#signin").on("click", function() {
            $('#myModal1').modal('hide');
        });

        //trigger next modal
        $("#signin").on("click", function() {
            if ($('#productos_compra').prop('checked')) {
                $('#myModal2').modal('show');
            } else {
                $('#myModal3').modal('show');
            }
        });

        // NI FILTROS
        $('#no_filtros').click(function() {
            // Reset Column filtering
            $('#productos thead input').val('').change();
            // Redraw table (and reset main search filter)
            $("#productos").DataTable().search("").draw();
        })

        // EXPORTAR
        $('#exportar').click(function(e) {
            $('#exportar > div.spinner-border').removeClass('d-none')
            //recoje los datos de buscar 
            jsonBusquedas = "/"
            var v = "_"
            if ($('#productos_filter > input').val() != "") v = encodeURIComponent($('#productos_filter > input').val())
            jsonBusquedas += v
            var i;
            for (i = 2; i < 13; i++) {
                jsonBusquedas += "/"
                var v = "_"
                if ($('#productos > thead > tr:nth-child(1) > th:nth-child(' + i + ') > input').val() != "") {
                    v = encodeURIComponent($('#productos > thead > tr:nth-child(1) > th:nth-child(' + i + ') > input').val())
                }
                jsonBusquedas += v
            }
            console.log(this.href + jsonBusquedas);
            setTimeout(function() {
                $('#exportar > div.spinner-border').addClass('d-none')
            }, 2000);
            window.location.href = this.href + jsonBusquedas
            return false;
        })

        // acciones en cada producto
        // Abre modal para EDITAR producto
        // Editar
        $('table#productos').delegate('.editar', 'click', function() {
            $('#actividad').val('editar')
            id = $(this).parent().parent().parent().parent().attr('producto')
            $('#cerrar').addClass('hide')
            $('#grabar').removeClass('hide')
            $('#cancelar').removeClass('hide')

            // En datosSuccess se guardan los datos generados por ajax para emplearlos fuera de ajax
            var datosSuccess = ""
            // ajax syncrono: no se ejecuta lo que sigue a ajax hasta que este finalic
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getDatosProducto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    datosSuccess = $.parseJSON(datos);
                },
                error: function() {
                    alert("Error en el proceso. getVerProducto");
                }
            })
            $('input.form-control').removeAttr('disabled')

            $('#fecha_modificacion').val('<?php echo date('d/m/Y') ?>')
            $('#modificado_por').val(<?php echo $this->session->id ?>)

            $('#id_grupo').prop('disabled', false)
            $('#id_grupo').materialSelect('refresh')

            $('#id_familia').prop('disabled', false)
            $('#id_familia').materialSelect('refresh')

            $('#id_proveedor_web').prop('disabled', false)
            $('#id_proveedor_web').materialSelect('refresh')

            $('#control_stock').prop('disabled', false)
            $('#control_stock').materialSelect('refresh')

            $('#tipo_unidad').prop('disabled', true)
            $('#tipo_unidad').materialSelect('refresh')

            $('input#id').attr('disabled', 'disabled')
            $('input#codigo_producto').attr('disabled', 'disabled')

            $('#myModalProducto').css('color', 'black')
            $('.modal-title').html('Modificar datos producto')

            // botones
            $('.cerrar').addClass('d-none')
            $('.grabar_editar').removeClass('d-none')
            $('.grabar_nuevo').addClass('d-none')
            $('.descatalogar_producto').addClass('d-none')
            $('.catalogar_producto').addClass('d-none')
            $('.cancelar_editar').removeClass('d-none')
            $.each(datosSuccess, function(index, value) {
                $('#' + index).val(value)
                $('#' + index).parent().children('label').addClass('active')
                if ($('input[data-activates="select-options-' + index + '"]').length)
                    $('input[data-activates="select-options-' + index + '"]').val(datosSuccess['valor_' + index])
            })

            $("#myModalProducto").modal({
                backdrop: 'static',
                keyboard: false
            })

        })

        // Abre modal para VER producto
        // Ver
        $('table#productos').delegate('.ver', 'click', function() {
            $('#actividad').val('ver')
            id = $(this).parent().parent().parent().parent().attr('producto')

            // En datosSuccess se guardan los datos generados por ajax para emplearlos fuera de ajax
            var datosSuccess = ""
            // ajax syncrono: no se ejecuta lo que sigue a ajax hasta que este finalic
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getDatosProducto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    datosSuccess = $.parseJSON(datos);
                },
                error: function() {
                    alert("Error en el proceso. getVerProducto");
                }
            })
            // disable inputs y selects
            $('input.form-control').attr('disabled', 'disabled')
            $('input[type="search"]').removeAttr('disabled')


            // $('.md-form input:not([type]), .md-form input[type="text"]:not(.browser-default), .md-form input[type="password"]:not(.browser-default), .md-form input[type="email"]:not(.browser-default), .md-form input[type="url"]:not(.browser-default), .md-form input[type="time"]:not(.browser-default), .md-form input[type="date"]:not(.browser-default), .md-form input[type="datetime"]:not(.browser-default), .md-form input[type="datetime-local"]:not(.browser-default), .md-form input[type="tel"]:not(.browser-default), .md-form input[type="number"]:not(.browser-default), .md-form input[type="search"]:not(.browser-default), .md-form input[type="phone"]:not(.browser-default), .md-form input[type="search-md"], .md-form textarea.md-textarea').css('border-bottom','0px solid red !important')
            // $('.md-form .form-control:disabled, .md-form .form-control[readonly]').css('border','4px solid red !important')

            $('#id_grupo').prop('disabled', true)
            $('#id_grupo').materialSelect('refresh')

            $('#id_familia').prop('disabled', true)
            $('#id_familia').materialSelect('refresh')

            $('#id_proveedor_web').prop('disabled', true)
            $('#id_proveedor_web').materialSelect('refresh')

            $('#control_stock').prop('disabled', true)
            $('#control_stock').materialSelect('refresh')

            $('#tipo_unidad').prop('disabled', true)
            $('#tipo_unidad').materialSelect('refresh')

            // botones
            $('.cerrar').removeClass('d-none')
            $('.grabar_editar').addClass('d-none')
            $('.grabar_nuevo').addClass('d-none')
            $('.descatalogar_producto').addClass('d-none')
            $('.catalogar_producto').addClass('d-none')
            $('.cancelar_editar').addClass('d-none')

            $('.modal-title').html('Datos producto')
            $('#myModalProducto').css('color', 'black')

            $.each(datosSuccess, function(index, value) {
                $('#' + index).val(value)
                $('#' + index).parent().children('label').addClass('active')
                if ($('input[data-activates="select-options-' + index + '"]').length)
                    $('input[data-activates="select-options-' + index + '"]').val(datosSuccess['valor_' + index])
            })
            $("#myModalProducto").modal({
                backdrop: 'static',
                keyboard: false
            })
        })

        // motral modal imagen del producto
        // imagen producto
        $('table#productos').delegate('.img', 'click', function() {
            $('#actividad').val('imagen')
            id = $(this).parent().parent().attr('producto')
            var img = $(this).attr('src');
            // En datosSuccess se guardan los datos generados por ajax para emplearlos fuera de ajax
            var datosSuccess = ""
            // ajax syncrono: no se ejecuta lo que sigue a ajax hasta que este finalic
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/getDatosProducto",
                data: {
                    'id_pe_producto': id
                },
                success: function(datos) {
                    // alert(datos)
                    datosSuccess = $.parseJSON(datos);
                },
                error: function() {
                    alert("Error en el proceso. getVerProducto");
                }
            })

            // disable inputs y selects
            $('input.form-control').attr('disabled', 'disabled')

            $('#id_grupo').prop('disabled', true)
            $('#id_grupo').materialSelect('refresh')

            $('#id_familia').prop('disabled', true)
            $('#id_familia').materialSelect('refresh')

            $('#id_proveedor_web').prop('disabled', true)
            $('#id_proveedor_web').materialSelect('refresh')

            $('#control_stock').prop('disabled', true)
            $('#control_stock').materialSelect('refresh')

            $('#tipo_unidad').prop('disabled', true)
            $('#tipo_unidad').materialSelect('refresh')

            // botones
            $('.cerrar').removeClass('d-none')
            $('.grabar_editar').addClass('d-none')
            $('.grabar_nuevo').addClass('d-none')
            $('.descatalogar_producto').addClass('d-none')
            $('.catalogar_producto').addClass('d-none')
            $('.cancelar_editar').addClass('d-none')

            $('.modal-title').html('Datos producto')
            $('#myModalProducto').css('color', 'black')

            $.each(datosSuccess, function(index, value) {
                $('#' + index).val(value)
                $('#' + index).parent().children('label').addClass('active')
                if ($('input[data-activates="select-options-' + index + '"]').length)
                    $('input[data-activates="select-options-' + index + '"]').val(datosSuccess['valor_' + index])
            })
            // $("#myModalProducto").modal({
            //     backdrop: 'static',
            //     keyboard: false
            // })
            console.log(img)
            var producto = $(this).parent().parent().children('td:eq(3)').html()
            console.log(producto)
            var codigo13 = $(this).parent().parent().children('td:eq(1)').html()
            console.log(codigo13)
            var codigoBoka = $(this).parent().parent().children('td:eq(2)').html()
            console.log(codigoBoka)
            // $('#myModalProducto').css('color', 'black')
            $('.modal-title-imagen').html('<h5>Imagen del producto</h5><h5><b>' + producto + '</b></h5><h5>Código: ' + codigo13 + '</h5><h5>Boka: ' + codigoBoka + '</h5>')
            if (img == "") $('#imagen').html("Este producto NO tiene imagen")
            else $('#imagen').html('<img src="' + img + '" alt="NO se ha encontrado la imagen<br>"' + img + ' >')
            $("#mostrarImagenModal").modal(
            )
        })

        $('.dataTables_length').addClass('bs-select');

        //configuración tabla productos
        $('#productos').DataTable({
     
            "dom": "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-4' l><'col-sm-12 col-md-4' i><'col-sm-12 col-md-4'p>>",
            orderCellsTop: true,
            fixedHeader: true,
            "pagingType": "first_last_numbers",
            "order": [
                [1, "asc"]
            ],
            colReorder: true,
            initComplete: function() {
                // añade search al pie
                this.api().columns().every(function(e) {
                    if (e != 0) {
                        var column = this;
                        $(`<input class="form-control form-control-sm" type="search" placeholder="Buscar">`)
                            .appendTo($(column.footer()).empty())
                            // .appendTo($('#productos > thead > tr:nth-child(2) > th').empty())
                            .on('change input', function() {
                                var val = $(this).val()
                                column
                                    .search(val ? val : '', true, false)
                                    .draw();
                            });
                    }
                });
            },
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla =(",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
            },
        })
        // ver https://datatables.net/examples/api/multi_filter.html Comments
        // coloca la barra columns search en la parte superior
        $('#productos tfoot tr').appendTo('#productos thead');

    });
</script>