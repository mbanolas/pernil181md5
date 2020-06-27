<style>
    /* dimensiones Modal producto */
    .modal-dialog-bodega {
        max-width: 70% !important;
        text-align: left;
        min-height: 400px;
    }
    .modal-content-myModal3{
        height: 100%;
        min-height: 500px;
    }
    .descatalogado{
        color:lightgray;
    }
</style>
<!-- Modal -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-bodega modal-dialog-scrollable">
        <div class="modal-content modal-content-myModal3">
            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel">Productos bodega</h4>
            </div>

            <div class="modal-body">

                <select class=" seleccion_producto_bodega mdb-select md-form" searchable="Buscar aquí..">
                    <option value="" disabled selected>Seleccionar producto</option>
                    <?php foreach ($modal as $k => $v) { ?>

                        <option value="<?php echo $k ?>"><?php echo $v ?></option>
                    <?php } ?>
                </select>
                <label class="mdb-main-label">Productos bodega</label>
                <div id="productos_actuales_bodega">

                </div>
                <div id="producto_nuevo_bodega" class="d-none">
                    <hr style="background-color: red;">
                    
                    <div class="container-fluid">
                    <h5>Producto nuevo</h5>
                        <div class="row">
                            <div class="col-2 ">
                                <div class="md-form">
                                    <input type="text" id="boka3" class="form-control">
                                    <label for="boka3">Código boka</label>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="md-form">
                                    <input type="text" id="nombre_generico3" class="form-control">
                                    <label for="nombre_generico3">Nombre genérico</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="md-form">
                                    <input type="text" id="anada3" class="form-control">
                                    <label for="anada3">Añada</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="md-form">
                                    <input type="text" id="precio_compra3" class="form-control">
                                    <label for="precio_compra3">Precio compra</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="md-form">
                                    <input type="text" id="tarifa_venta3" class="form-control">
                                    <label for="tarifa_venta3">Tarifa venta</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="md-form">
                                    <input type="text" id="iva3" class="form-control" disabled>
                                    <label for="iva3">IVA</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="md-form">
                                    <input type="text" id="beneficio3" class="form-control" disabled>
                                    <label for="beneficio3">Beneficio (%)</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="md-form">
                                    <input type="text" id="codigo_producto3" class="form-control">
                                    <label for="codigo_producto3">Código producto</label>
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="md-form">
                                    <input type="text" id="nombre3" class="form-control">
                                    <label for="nombre3">Nombre</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="codigoProductoOriginal3" value="">
                <div class="notificaciones d-none">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary " id="insertarVino">Añadir producto bodega</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        var insertados=false
        
        function beneficioProducto(tarifaVenta,precioCompra,iva){
            var tarifaVenta=Number(tarifaVenta)
            var precioCompra=Number(precioCompra)
            var iva=Number(iva)
            if (!precioCompra) return 0;
            var beneficio=(100*tarifaVenta-precioCompra*(100+iva))/tarifaVenta;
            return beneficio.toFixed(2);
        }

        function getTablaDatos(productos) {
            var tabla = '<div class="container-fluid">'
            tabla += "<h5>Productos actuales</h5>"
            tabla += '<table class="table table-sm"><thead><tr><th scope="col">Código</th><th scope="col">Boka</th><th scope="col">Nombre</th><th scope="col">Añada</th><th scope="col">Unidad</th><th scope="col">Precio compra</th><th scope="col">Tarifa venta</th></tr></thead>'
            tabla += '<tbody>'
            $.each(productos, function(index, value) {
                var descatalogado = ""
                if (value['statusProducto'] == 0) descatalogado = 'descatalogado'
                tabla += '<tr class="' + descatalogado + '"><th scope="row">' + value['codigoProducto'] + '</th><td>' + value['codigoBoka'] + '</td><td>' + value['nombre'] + '</td><td>' + value['anada'] + '</td><td>' + value['tipoUnidad'] + '</td><td>' + value['precioCompra'] + '</td><td>' + value['tarifaVenta'] + '</td></tr>'
            })
            tabla += '</tbody></table>'
            tabla += '</div>'
            return tabla
        }

        $('.seleccion_producto_bodega').change(function() {
            var id = $(this).val()
            console.log('.seleccion_producto_bodega ' + $(this).val())
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>index.php/productos/rangosAnada/" + $(this).val(),
                data: {
                    id: id
                },
                success: function(datos) {
                    alert(datos)
                    var datos = $.parseJSON(datos)
                    alert(datos[0]['codigoProducto'])
                    var tabla = getTablaDatos(datos)
                    $('#productos_actuales_bodega').html(tabla)

                    var nombreGenerico = datos[0]['nombre'].replace('COMPRA', '').replace('Compra', '')
                    var iva=(datos[0]['iva']/1000).toFixed(2)

                    $('#iva3').val(iva)
                    $('#iva3').parent().children('label').addClass('active')
                  
                    $('#nombre_generico3').val(nombreGenerico)
                    $('#nombre_generico3').parent().children('label').addClass('active')
                    
                    $boka=datos[0]['codigoProducto'].substr(4, 4)
                    while($boka.substr(0,1)=="0") $boka=$boka.substr(1)

                    $('#boka3').val($boka)
                    $('#boka3').parent().children('label').addClass('active')

                    $('input#peso_real3').val('')
                    $('#precio_compra3').val('')
                    $('#tarifa_venta3').val('')
                    $('#nombre3').val('')
                    $('#codigo_producto3').val('')

                    $('#producto_nuevo_bodega').removeClass('d-none')

                    $('input#anada3').parent().children('label').addClass('active')
                    $('input#anada3').focus()


                    $('input#anada3').keyup(function() {
                        $('.notificaciones').addClass('d-none')
                        $('.notificaciones').html('')
                        var anada = $(this).val()
                        var precioCompra = datos[0]['precioCompra']; //(datos[0]['precioCompra']*1).toFixed(3)
                        var tarifaVenta = datos[0]['tarifaVenta']; //(Number((datos[0]['tarifaVenta']*1).toFixed(2))).toFixed(3)
                        var codigoProductoOriginal = datos[0]['codigoProducto']
                        var dosUltimosDigitos=""
                        if(anada.length>2 && anada.length<5) dosUltimosDigitos=anada.substr(2,2)
                        var codigoProducto = datos[0]['codigoProducto'].substr(0, 11) + dosUltimosDigitos

                        $('#precio_compra3').val(precioCompra)
                        $('#precio_compra3').parent().children('label').addClass('active')

                        $('#tarifa_venta3').val(tarifaVenta)
                        $('#tarifa_venta3').parent().children('label').addClass('active')

                        $('#beneficio3').val(beneficioProducto(tarifaVenta,precioCompra,iva))
                        $('#beneficio3').parent().children('label').addClass('active')

                        $('#codigo_producto3').val(codigoProducto)
                        $('#codigo_producto3').parent().children('label').addClass('active')

                        $('#codigoProductoOriginal3').val(codigoProductoOriginal)
                        $('#nombre3').val(nombreGenerico.trim() + ' (' + anada + ')')
                        $('#nombre3').parent().children('label').addClass('active')
                    })
                },
                error: function() {
                    alert("Información importante. Error en el proceso rangosAnada. Informar");
                }
            })


        })

        $('#insertarVino').click(function() {
            var codigoProductoOriginal = $('#codigoProductoOriginal3').val()
            var codigoProducto = $('#codigo_producto3').val()
            var idProducto = $('#boka3').val()
            var nombre = $('#nombre3').val()
            var nombreGenerico = $('#nombre_generico3').val()
            var anada=$('#anada3').val()
            var precioCompra = (Number($('#precio_compra3').val()) * 1000).toFixed(0)
            var tarifaVenta = (Number($('#tarifa_venta3').val()) * 1000).toFixed(0)
            var beneficioProducto = (Number($('#beneficio3').val()) * 1000).toFixed(0)
            var iva = (Number($('#iva3').val()) * 1000).toFixed(0)

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>" + "index.php/productos/insertProductosCopia/2",
                data: {
                    codigoProductoOriginal: codigoProductoOriginal,
                    codigoProducto: codigoProducto,
                    idProducto: idProducto,
                    nombre: nombre,
                    nombreGenerico: nombreGenerico,
                    anada: anada,
                    precioCompra: precioCompra,
                    tarifaVenta: tarifaVenta,
                    beneficioProducto:beneficioProducto,
                    iva:iva
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
                        $.each(datos, function(index, value) {
                            console.log(datos)
                        })
                        var tabla = getTablaDatos(datos)
                        console.log(tabla)
                        $('#productos_actuales_bodega').html(tabla)
                        $('#productos_actuales_bodega').removeClass('d-none')

                        // pone en blanco los campos nuevo producto para imtroducir otro
                        $('#peso_real3').val('')

                        $('#precio_compra3').val('')
                        $('#precio_compra3').parent().children('label').removeClass('active')

                        $('#tarifa_venta3').val('')
                        $('#tarifa_venta3').parent().children('label').removeClass('active')

                        $('#beneficio3').val('')
                        $('#beneficio3').parent().children('label').removeClass('active')

                        $('#codigo_producto2').val('')
                        $('#codigo_producto2').parent().children('label').removeClass('active')

                        $('#nombre3').val('')
                        $('#nombre3').parent().children('label').removeClass('active')
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
                            $('#anada3').focus()
                            $('#anada3').parent().children('label').addClass('active')
                        }, 3000);

                    } else {
                        // $('#myModal2').modal('hide');
                        $('#myModalInformacion').css('color', 'red')
                        $('#informacionCerrar').removeClass('d-none')
                        $('#titulomyModalInformacion').html('Información')
                        $('.modal-body-myModalInformacion').html(datos['textoError'])
                        $('#informacionContinuuar').addClass('d-none')
                        $('#myModalInformacion').modal();
                    }
                },
                error: function() {
                    alert("Error en el proceso de insertar insertProductosCopia/2. Informar");
                }
            })

        })

        $("#myModal3").on('hidden.bs.modal', function() {
            if(insertados){
                window.location.href = "<?php echo base_url() ?>index.php/productos/productos";
            }
        });
    })
</script>