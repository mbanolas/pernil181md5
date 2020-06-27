<style>
    /* dimensiones Modal producto */
    .modal-dialog-compra {
        max-width: 70% !important;
        text-align: left;
        min-height: 400px;
    }

    .modal-content-myModal2 {
        height: 100%;
        min-height: 500px;
    }
</style>
<!-- Modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-compra modal-dialog-scrollable">
        <div class="modal-content modal-content-myModal2">
            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel">Productos compra a peso</h4>
            </div>

            <div class="modal-body">

                <select class=" seleccion_producto_peso mdb-select md-form" searchable="Buscar aquí..">
                    <option value="" disabled selected>Seleccionar producto</option>
                    <?php foreach ($modal as $k => $v) { ?>
                        <option value="<?php echo $k ?>"><?php echo $v ?></option>
                    <?php } ?>
                </select>
                <label class="mdb-main-label">Producto compra peso</label>
                <div id="productos_actuales_peso">

                </div>
                <div id="producto_nuevo" class="d-none">
                    <hr style="background-color: red;">

                    <div class="container-fluid">
                        <h5>Producto nuevo</h5>
                        <div class="row">
                            <div class="col-2 ">
                                <div class="md-form">
                                    <input type="text" id="boka2" class="form-control" disabled>
                                    <label for="boka2">Código boka</label>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="md-form">
                                    <input type="text" id="nombre_generico2" class="form-control" disabled>
                                    <label for="nombre_generico2">Nombre genérico</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="md-form">
                                    <input type="text" id="peso_real2" class="form-control">
                                    <label for="peso_real2">Peso</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="md-form">
                                    <input type="text" id="precio_compra2" class="form-control" disabled>
                                    <label for="precio_compra2">Precio compra</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="md-form">
                                    <input type="text" id="tarifa_venta2" class="form-control" disabled>
                                    <label for="tarifa_venta2">Tarifa venta</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="md-form">
                                    <input type="text" id="iva2" class="form-control" disabled>
                                    <label for="iva2">IVA</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="md-form">
                                    <input type="text" id="beneficio2" class="form-control" disabled>
                                    <label for="beneficio2">Beneficio (%)</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="md-form">
                                    <input type="text" id="codigo_producto2" class="form-control">
                                    <label for="codigo_producto2">Código producto</label>
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="md-form">
                                    <input type="text" id="nombre2" class="form-control">
                                    <label for="nombre2">Nombre</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="codigoProductoOriginal2" value="">
                <div class="notificaciones d-none">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary d-none" id="insertar">Añadir producto peso</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        var insertados=false

        function beneficioProducto(tarifaVenta, precioCompra, iva) {
            var tarifaVenta = Number(tarifaVenta)
            var precioCompra = Number(precioCompra)
            var iva = Number(iva)
            if (!precioCompra) return 0;
            var beneficio = (100 * tarifaVenta - precioCompra * (100 + iva)) / tarifaVenta;
            return beneficio.toFixed(2);
        }

        var datos = {}

        $('.seleccion_producto_peso').change(function() {
            var id = $(this).val()
            console.log('.seleccion_producto_peso ' + $(this).val())
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>index.php/productos/rangosPeso/" + $(this).val(),
                data: {
                    id: id
                },
                success: function(datos) {
                    // alert(datos)
                    datos = $.parseJSON(datos)
                    // alert(datos[0]['iva'])
                    // genera tabla productos existentes y la muestra
                    var tabla = getTablaDatos(datos)
                    $('#productos_actuales_peso').html(tabla)

                    // obtiene nombre genérico, iva y boka del primer producto y los muestra
                    var nombreGenerico = datos[0]['nombreGenerico'] ? datos[0]['nombre'] : datos[0]['nombre'].replace('COMPRA', '').replace('Compra', '')
                    var iva = (datos[0]['iva'] / 1000).toFixed(2)
                    $boka = datos[0]['codigoProducto'].substr(4, 4)
                    while ($boka.substr(0, 1) == "0") $boka = $boka.substr(1)
                    $('#iva2').val(iva)
                    $('#iva2').parent().children('label').addClass('active')
                    $('#nombre_generico2').val(nombreGenerico)
                    $('#nombre_generico2').parent().children('label').addClass('active')
                    $('#boka2').val($boka)
                    $('#boka2').parent().children('label').addClass('active')

                    // campos nuevo producto en blanco 
                    $('input#peso_real2').val('')
                    $('#precio_compra2').val('')
                    $('#tarifa_venta2').val('')
                    $('#nombre2').val('')
                    $('#codigo_producto2').val('')

                    // muestra formulario para nuevo producto peso
                    $('#producto_nuevo').removeClass('d-none')
                    $('#insertar').removeClass('d-none')

                    // focus en peso nuevo producto
                    $('input#peso_real2').parent().children('label').addClass('active')
                    $('input#peso_real2').focus()

                    // evento techa peso nuevo producto
                    $('input#peso_real2').keyup(function() {
                        // elimina notificaciones
                        $('.notificaciones').addClass('d-none')
                        $('.notificaciones').html('')
                        // cambia ',' por '.'
                        $(this).val($(this).val().replace(",", "."))
                        var peso = parseFloat($(this).val())
                        var precioCompra = (datos[0]['precioCompra'] * peso).toFixed(3)
                        var tarifaVenta = (Number((datos[0]['tarifaVenta'] * peso).toFixed(2))).toFixed(3)
                        var pesoEnCodigo = (peso * 1000).toFixed(0)
                        while (pesoEnCodigo.length < 5) pesoEnCodigo = '0' + pesoEnCodigo
                        var codigoProductoOriginal = datos[0]['codigoProducto']
                        var codigoProducto = datos[0]['codigoProducto'].substr(0, 8) + pesoEnCodigo

                        $('#precio_compra2').val(precioCompra)
                        $('#precio_compra2').parent().children('label').addClass('active')

                        $('#tarifa_venta2').val(tarifaVenta)
                        $('#tarifa_venta2').parent().children('label').addClass('active')

                        $('#beneficio2').val(beneficioProducto(tarifaVenta, precioCompra, iva))
                        $('#beneficio2').parent().children('label').addClass('active')

                        $('#codigo_producto2').val(codigoProducto)
                        $('#codigo_producto2').parent().children('label').addClass('active')

                        $('#codigoProductoOriginal2').val(codigoProductoOriginal)
                        $('#nombre2').val(nombreGenerico.trim() + ' (' + Number(peso).toFixed(3) + ' Kg)')
                        $('#nombre2').parent().children('label').addClass('active')

                        var nuevoPeso = parseFloat($(this).val())
                    })
                },
                error: function() {
                    alert("Información importante. Error en el proceso rangos_peso. Informar");
                }
            })
        })

        function getTablaDatos(productos) {
            var tabla = '<div class="container-fluid">'
            tabla += "<h5>Productos actuales</h5>"
            tabla += '<table class="table table-sm"><thead><tr><th scope="col">Código</th><th scope="col">Boka</th><th scope="col">Nombre</th><th scope="col">Peso</th><th scope="col">Unidad</th><th scope="col">Precio compra</th><th scope="col">Tarifa venta</th></tr></thead>'
            tabla += '<tbody>'
            $.each(productos, function(index, value) {
                var descatalogado = ""
                if (value['statusProducto'] == 0) descatalogado = 'descatalogado'
                tabla += '<tr class="' + descatalogado + '"><th scope="row">' + value['codigoProducto'] + '</th><td>' + value['codigoBoka'] + '</td><td>' + value['nombre'] + '</td><td>' + value['pesoReal'] + '</td><td>' + value['tipoUnidad'] + '</td><td>' + value['precioCompra'] + '</td><td>' + value['tarifaVenta'] + '</td></tr>'
            })
            tabla += '</tbody></table>'
            tabla += '</div>'
            return tabla
        }


        $('#insertar').click(function() {
            // obtiene los datps nuevo producto
            var codigoProductoOriginal = $('#codigoProductoOriginal2').val()
            var codigoProducto = $('#codigo_producto2').val()
            var idProducto = $('#boka2').val()
            var nombre = $('#nombre2').val()
            var nombreGenerico = $('#nombre_generico2').val()
            var pesoReal = (Number($('#peso_real2').val()) * 1000).toFixed(0)
            var precioCompra = (Number($('#precio_compra2').val()) * 1000).toFixed(0)
            var tarifaVenta = (Number($('#tarifa_venta2').val()) * 1000).toFixed(0)
            var beneficioProducto = (Number($('#beneficio2').val()) * 1000).toFixed(0)
            var iva = (Number($('#iva2').val()) * 1000).toFixed(0)
            // los inserta en tabla productos
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/insertProductosCopia/1",
                data: {
                    codigoProductoOriginal: codigoProductoOriginal,
                    codigoProducto: codigoProducto,
                    idProducto: idProducto,
                    nombre: nombre,

                    nombreGenerico: nombreGenerico,
                    pesoReal: pesoReal,
                    precioCompra: precioCompra,
                    tarifaVenta: tarifaVenta,
                    beneficioProducto: beneficioProducto,
                    iva: iva
                },
                success: function(datos) {
                    var datos = $.parseJSON(datos)
                    if (!datos['error']) {
                        // variable para marcar que se ha insertado algún producto
                        // para controlar la actualizacion de la tabla productos
                        insertados=true
                        // si todo va bien, devuelve datos de la familia producto en datos['productos']
                        // genera la tabla y la pone en pantalla (ya con el nuevo producto)
                        datos = datos['productos']
                        var tabla = getTablaDatos(datos)
                        $('#productos_actuales_peso').html(tabla)
                        $('#productos_actuales_peso').removeClass('d-none')

                        // pone en blanco los campos nuevo producto para imtroducir otro
                        $('#peso_real2').val('')

                        $('#precio_compra2').val('')
                        $('#precio_compra2').parent().children('label').removeClass('active')

                        $('#tarifa_venta2').val('')
                        $('#tarifa_venta2').parent().children('label').removeClass('active')

                        $('#beneficio2').val('')
                        $('#beneficio2').parent().children('label').removeClass('active')

                        $('#codigo_producto2').val('')
                        $('#codigo_producto2').parent().children('label').removeClass('active')

                        $('#nombre2').val('')
                        $('#nombre2').parent().children('label').removeClass('active')
                        // se muestra campos para nuevo prodycto
                        $('#producto_nuevo').removeClass('d-none')

                        // SE MUESTRA VENTANA CON AVISO todo ok
                        $('#myModalInformacion').css('color', 'black')
                        $('#informacionCerrar').addClass('d-none')
                        $('#titulomyModalInformacion').html('Información')
                        $('.modal-body-myModalInformacion').html('Producto <strong>' + codigoProducto + ' </strong>creado correctamente.')
                        $('#informacionContinuuar').addClass('d-none')
                        $('#myModalInformacion').modal({
                            backdrop: 'static',
                            keyboard: false
                        })

                        setTimeout(function() {
                            $('#myModalInformacion').modal('toggle');
                            $('#peso_real2').focus()
                            $('#peso_real2').parent().children('label').addClass('active')
                        }, 3000);
                    } else {
                        $('#myModalInformacion').css('color', 'red')
                        $('#informacionCerrar').removeClass('d-none')
                        $('#titulomyModalInformacion').html('Información')
                        $('.modal-body-myModalInformacion').html(datos['textoError'])
                        $('#informacionContinuuar').addClass('d-none')
                        $('#myModalInformacion').modal();
                    }
                },
                error: function() {
                    alert("Error en el proceso de insertar insertProductosCopia/1. Informar");
                }
            })
        })

        $("#myModal2").on('hidden.bs.modal', function() {
            if(insertados){
            window.location.href = "<?php echo base_url() ?>index.php/productos/productos";
            }
        });
        
    })
</script>