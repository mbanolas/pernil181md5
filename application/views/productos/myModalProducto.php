<style>
    .modal-body {
        margin-left: 4rem;
        margin-right: 4rem;
    }

    /* elimina linea en inputs disabled */
    input[disabled] {
        border-bottom: 0px !important;
        color: #495057 !important;
    }



    .select-wrapper span.caret.disabled {
        color: rgba(0, 0, 0, 0) !important;
    }

    .warning {
        background-color: yellow !important;
    }

    /* eliminar linea de los select disabled */
    .select-wrapper.mdb-select.disabled>div>input {
        border: 0px solid white !important;
    }

    /* pintar el caret white no visible */
    .select-wrapper.mdb-select.disabled>div>span {
        color: white;
    }
</style>


<!-- Modal -->
<div class="modal fade right" id="myModalProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-producto modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalPreviewLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="actividad" value="">
                <?php echo $modal ?>
            </div>
            <div class="alert alert-danger d-none alert-cambios" role="alert">
                No se han guardo los cambios
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cerrar" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary grabar_editar">Guardar cambios producto</button>
                <button type="button" class="btn btn-primary grabar_nuevo">Guardar nuevo producto</button>
                <button type="button" class="btn btn-primary descatalogar_producto">Descatalogar producto</button>
                <button type="button" class="btn btn-primary catalogar_producto">Catalogar producto</button>
                <button type="button" class="btn btn-warning cancelar_editar" data-dismiss="modal">Cancelar cambios</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<script>
    $(document).ready(function() {

        var cambiado = false

        $('.form-control, .mdb-select').change(function() {
            cambiado = true
            console.log('cambiado ' + cambiado)
        })

        // Material Select Initialization
        $('.mdb-select').materialSelect();
        // Data Picker Initialization
        $('.datepicker').datepicker({});

        // si se cambia el grupo se pone a 0 las famila y se pone el nuevo iva y recalcula el margen
        $('#id_grupo').change(function() {
            // poner familias a 0
            $('#id_familia').val(0)
            // cambiar iva
            var id_grupo = $(this).val()
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/getIva",
                data: {
                    grupo: id_grupo
                },
                success: function(datos) {
                    // alert(datos);
                    var datos = $.parseJSON(datos);
                    $('#iva').val(datos)
                    $('#margen_real_producto').val(calcularMargen())
                },
                error: function() {
                    alert("Error en el proceso getIVA. Informar");
                }
            })

        })

        // cuando se cambia el precio de compra (unidad o peso se cambia el precio de compra y recalcula el margen)
        $('#precio_ultimo_unidad, #precio_ultimo_peso').change(function() {
            var precio_compra = parseFloat($(this).val())
            var tipo_unidad = $('#tipo_unidad').val()
            var transformacion = parseFloat($('#precio_transformacion_unidad').val())
            if (tipo_unidad == "Kg") {
                transformacion = parseFloat($('#precio_transformacion_peso').val())
            }
            if (transformacion != 0) return //no se hace nada porque prevalece el precio de transformacion
            var descuento = parseFloat($('#descuento_1_compra').val())
            var unidades_precio = parseFloat($('#unidades_precio').val())
            unidades_precio = unidades_precio == 0 ? 1 : unidades_precio
            precio_compra = precio_compra * (1 - descuento / 100) / unidades_precio
            $('#precio_compra').val(precio_compra.toFixed(3))
            $('#margen_real_producto').val(calcularMargen())
            $('#valoracion').val(calcularValoracion())
        })

        // si cambia el precio transformacion se cambia el precio de compra tal cual y se calcula el margen
        $('#precio_transformacion_unidad, #precio_transformacion_unidad').change(function() {
            var precio_compra = parseFloat($(this).val())
            $('#precio_compra').val(precio_compra.toFixed(3))
            $('#margen_real_producto').val(calcularMargen())
            $('#valoracion').val(calcularValoracion())
        })

        // si se cambia el descuento se cambia el precio final si es que no existe precio de transformacin 
        // y se recalcula el margen
        $('#descuento_1_compra').change(function() {
            var descuento = parseFloat($(this).val())
            var tipo_unidad = $('#tipo_unidad').val()
            var transformacion = parseFloat($('#precio_transformacion_unidad').val())
            var precio_compra = parseFloat($('#precio_ultimo_unidad').val())
            if (tipo_unidad == "Kg") {
                transformacion = parseFloat($('#precio_transformacion_peso').val())
                precio_compra = parseFloat($('#precio_ultimo_peso').val())
            }
            if (transformacion != 0) return //no se hace nada porque prevalece el precio de transformacion
            var unidades_precio = parseFloat($('#unidades_precio').val())
            unidades_precio = unidades_precio == 0 ? 1 : unidades_precio
            precio_compra = precio_compra * (1 - descuento / 100) / unidades_precio
            $('#precio_compra').val(precio_compra.toFixed(3))
            $('#margen_real_producto').val(calcularMargen())
            $('#valoracion').val(calcularValoracion())
        })

        // si se cambia el pvp se recalcula el margen
        $('#tarifa_venta').change(function() {
            $('#margen_real_producto').val(calcularMargen())
        })

        // si se cambia el tipo de unidad define los campos editables /no editables
        $('#tipo_unidad').change(function() {
            tipoUnidad()
        })

        // cuando en editar se cancela oculta las alertas
        $('.cancelar_editar').click(function() {
            if (cambiado) $('.alert-cambios').removeClass('d-none')
        })

        // acciones botones pie ventana
        // accion descatalogar producto
        $('.descatalogar_producto').click(function() {
            var id = $('#id').val()
            var veri = estaCatalogado(id)
            // alert('veri '+veri)
            if (veri == true) {
                var data = '{'
                $.each($('input[id]'), function(index, value) {
                    data += '"' + $(this).attr('id') + '":"' + $(this).val() + '",'
                })
                $.each($('input.select-dropdown.form-control[data-activates]'), function(index, value) {
                    data += '"' + $(this).attr('data-activates').substring(15) + '":"' + $(this).val() + '",'
                })
                var txt = data.slice(0, -1) + '}';
                var obj = JSON.parse(txt)
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>" + "index.php/productos/descatalogar",
                    data: {
                        id: id
                    },
                    success: function(datos) {
                        //    alert (datos)
                        var datos = $.parseJSON(datos)
                        var id = $('input[id="id"]').val()
                        $('#tituloMyModal').html('Información')
                        $('#cuerpoMyModal').html('Producto descatalogado correctamente')
                        $('#myModal').modal()
                        // actualiza cambios en la tabla productos cargándala de nuevo
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        // alert('id '+id)
                        // $('html').find($('tr[producto="' + id + '"')).remove()
                        // $("#myModalProducto").modal('toggle')

                    },
                    error: function() {
                        alert("Error en el proceso grabarDatos. Informar");
                    }
                })
            }
        })

        $('.catalogar_producto').click(function() {
            var id = $('#id').val()
            var veri = estaCatalogado(id)
            // alert('veri '+veri)
            if (veri == true) {
                var data = '{'
                $.each($('input[id]'), function(index, value) {
                    data += '"' + $(this).attr('id') + '":"' + $(this).val() + '",'
                })
                $.each($('input.select-dropdown.form-control[data-activates]'), function(index, value) {
                    data += '"' + $(this).attr('data-activates').substring(15) + '":"' + $(this).val() + '",'
                })
                var txt = data.slice(0, -1) + '}';
                var obj = JSON.parse(txt)
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>" + "index.php/productos/catalogar",
                    data: {
                        id: id
                    },
                    success: function(datos) {
                        //    alert (datos)
                        var datos = $.parseJSON(datos)
                        var id = $('input[id="id"]').val()
                        $('#tituloMyModal').html('Información')
                        $('#cuerpoMyModal').html('Producto catalogado correctamente')
                        $('#myModal').modal()
                        // actualiza cambios en la tabla productos cargándala de nuevo
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        // alert('id '+id)
                        // $('html').find($('tr[producto="' + id + '"')).remove()
                        // $("#myModalProducto").modal('toggle')

                    },
                    error: function() {
                        alert("Error en el proceso grabarDatos. Informar");
                    }
                })
            }
        })

        // // acción grabar nuevo producto
        // $('.grabar_nuevo').click(function() {
        //     console.log('acción grabar nuevo')
        //     var veri = verificaciones()
        //     if (veri == true) {

        //     }

        // })

        // acción grabar datos producto
        $('.grabar_editar, .grabar_nuevo').click(function() {
            var veri = verificaciones()
            // alert('veri '+veri)
            if (veri == true) {
                var data = '{'
                $.each($('input[id]'), function(index, value) {
                    data += '"' + $(this).attr('id') + '":"' + $(this).val() + '",'
                })
                $.each($('input.select-dropdown.form-control[data-activates]'), function(index, value) {
                    data += '"' + $(this).attr('data-activates').substring(15) + '":"' + $(this).val() + '",'
                })
                var txt = data.slice(0, -1) + '}';
                var obj = JSON.parse(txt)
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>" + "index.php/productos/grabarDatos",
                    data: obj,
                    success: function(datos) {
                        //    alert (datos)
                        var datos = $.parseJSON(datos)
                        var id = $('input[id="id"]').val()
                        $('#tituloMyModal').html('Información')
                        if (id == 0) {
                            $('#cuerpoMyModal').html('Producto dado de alta correctamente')
                            $('#myModal').modal()
                            // actualiza cambios en la tabla productos cargándala de nuevo
                            setTimeout(function() {
                                // location.reload();
                            }, 3000);
                        } else {
                            console.log($('#id').val())
                            console.log($('#id_producto').val())
                            console.log($('#nombre').val())
                            console.log($('#peso_real').val())
                            console.log($('#tipo_unidad > option[value="' + $('#tipo_unidad').val() + '"]').html())
                            console.log($('#precio_compra').val())
                            console.log($('#id_proveedor_web > option[value="' + $('#id_proveedor_web').val() + '"]').html())
                            console.log($('#tarifa_venta').val())
                            console.log($('#margen_real_producto').val())
                            console.log($('#url_imagen_portada').val())
                            // se modifica la linea de la tabla de productos SIN volver a recargar la tabla
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(2)').html($('#id_producto').val())
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(3)').html($('#nombre').val())
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(4)').html($('#peso_real').val())
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(5)').html($('#tipo_unidad').val())
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(6)').html($('#precio_compra').val())
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(7)').html($('#id_proveedor_web').val())
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(8)').html($('#tarifa_venta').val())
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(9)').html($('#margen_real_producto').val())
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(10)').html($('#valoracion').val())
                            // $('tbody > tr[producto="' + $('#id').val() + '"] > td:eq(11) > button').attr('img', $('#url_imagen_portada').val())

                            // console.log($('tbody > tr[producto="'+$('#id_producto').val()+'"]').html())
                            var informacion=""
                            if(datos['informacion']!="") informacion=datos['informacion']
                            $('#cuerpoMyModal').html('Producto <b>' + $('input[id="codigo_producto"]').val() + ' </b>modificado correctamente<br><br>' + informacion)
                            $('#myModal').modal()
                            if (datos['informacion'] == "") {
                                setTimeout(function() {
                                    $('#myModal').modal('toggle');
                                    $('#myModalProducto').modal('toggle');
                                    
                                }, 3000);
                            }
                            
                        }


                    },
                    error: function() {
                        alert("Error en el proceso grabarDatos. Informar");
                    }
                })
            }
        })

        // utilidades de input y select MDB
        $('#productos_wrapper').find('label').each(function() {
            $(this).parent().append($(this).children());
        });
        $('#productos_wrapper .dataTables_filter').find('input').each(function() {
            const $this = $(this);
            $this.attr("placeholder", "Buscar");
            $this.removeClass('form-control-sm');
        });
        $('#productos_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#productos_wrapper .dataTables_filter').addClass('md-form');
        $('#productos_wrapper select').removeClass('custom-select custom-select-sm form-control form-control-sm');
        $('#productos_wrapper select').addClass('mdb-select');
        $('#productos_wrapper .mdb-select').materialSelect();
        $('#productos_wrapper .dataTables_filter').find('label').remove();




        // calcula el margen (beneficio)
        function calcularMargen() {
            var iva = parseFloat($('#iva').val())
            var precio = parseFloat($('#precio_compra').val())
            var pvp = parseFloat($('#tarifa_venta').val())
            var margen = 0
            if (pvp != 0)
                margen = (100 * pvp - precio * (100 + iva)) / pvp
            return margen.toFixed(2)
        }

        // calcula la valoracion del stock
        function calcularValoracion() {
            var stock = parseFloat($('#stock_total').val())
            var precio = parseFloat($('#precio_compra').val())
            var valoracion = stock * precio
            return valoracion.toFixed(2)
        }

        // si se cambia el tipo unidad se disable o no ciertos campos
        function tipoUnidad() {
            if ($('#actividad').val() == "ver") return;
            if ($('#tipo_unidad').val() == "Und") {
                $('#precio_ultimo_peso').attr('disabled', 'disabled')
                $('#precio_ultimo_unidad').removeAttr('disabled')
                $('#precio_transformacion_peso').attr('disabled', 'disabled')
                $('#precio_transformacion_unidad').removeAttr('disabled')
            }
            if ($('#tipo_unidad').val() == "Kg") {
                $('#precio_ultimo_unidad').attr('disabled', 'disabled')
                $('#precio_ultimo_peso').removeAttr('disabled')
                $('#precio_transformacion_unidad').attr('disabled', 'disabled')
                $('#precio_transformacion_peso').removeAttr('disabled')
            }
            if ($('#tipo_unidad').val() == "---") {
                $('#precio_ultimo_peso').removeAttr('disabled')
                $('#precio_ultimo_unidad').removeAttr('disabled')
                $('#precio_transformacion_peso').removeAttr('disabled')
                $('#precio_transformacion_unidad').removeAttr('disabled')
            }
        }

        function estaCatalogado(id) {
            $('.alert-danger').addClass('d-none')
            $('.alert-danger').html("")
            $('.form-control').removeClass('warning')
            var avisos = ""
            $.ajax({
                async: false,
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/checkStatusProducto",
                data: {
                    id: id,
                },
                success: function(datos) {
                    var datos = $.parseJSON(datos)
                    if (datos == 0)
                        $.each(datos['errores'], function(index, value) {
                            avisos = "Este producto YA está desclasificado" + "<br>"
                        })
                },
                error: function() {
                    alert("Información importante:Error en el proceso checkStatusProducto. Informar");
                }
            })
            if (avisos != "") {
                $('.alert-danger').html(avisos)
                $('.alert-danger').removeClass('d-none')
                return false;
            } else {
                return true
            }
        }

        // verifica que los datos de editar y nuevo son correctoa
        // utiliza ajax sincrono
        function verificaciones() {
            $('.alert-danger').addClass('d-none') // oculta alertas anteriores
            $('.alert-danger').html("") // y las borra
            $('.form-control').removeClass('warning') // elimina marcados de errores 
            var avisos = "";

            // comprobación campos requeridos
            <?php foreach ($estructura as $k => $v) {
                if (isset($v['editar']) && $v['editar'] == "") {
                    if (isset($v['requerido']) && $v['requerido'] == true) { ?>
                        if ($('#<?php echo $v['campo'] ?>').val() == "") {
                            avisos += 'El <?php echo $v['texto'] ?> es obligatorio<br>'
                            $('#<?php echo $v['campo'] ?>').addClass('warning')
                        }
            <?php }
                }
            } ?>

            // comprobación que los campos numericos sean números
            <?php foreach ($estructura as $k => $v) {
                if (isset($v['editar']) && $v['editar'] == "") {
                    if (isset($v['tipo']) && $v['tipo'] == 'number') { ?>
                        if (isNaN($('#<?php echo $v['campo'] ?>').val())) {
                            avisos += 'El <?php echo $v['texto'] ?> debe ser numérico<br>'
                            $('#<?php echo $v['campo'] ?>').addClass('warning')
                        }
            <?php }
                }
            } ?>

            // comprobación que los campos selecciones tengan un valor para editar
            <?php foreach ($estructura as $k => $v) {
                if (isset($v['editar']) && $v['editar'] == "") {
                    if (isset($v['tipo']) && $v['tipo'] == 'seleccion') { ?>
                        console.log(<?php echo $v['campo'] ?> + ' ' + $('#<?php echo $v['campo'] ?>').val())
                        if ($('#<?php echo $v['campo'] ?>').val() == null) {
                            avisos += 'En <?php echo $v['texto'] ?> debe seleccionar un valor<br>'
                            $('input[data-activates="select-options-<?php echo $v['campo'] ?>"]').addClass('warning')
                        }
            <?php }
                }
            } ?>

            // comprobacion codigo 13
            console.log($('#codigo_producto').val().search('^[0-9]{13}$'))
            if ($('#codigo_producto').val().search('^[0-9]{13}$') < 0) {
                avisos += "El código de producto debe tener 13 digitos numericos<br>"
                $('#codigo_producto').addClass('warning')
            }

            // comprobacion codigo Boka ( de 1 a 5 digitos)
            console.log($('#id_producto').val().search('^[0-9]{1,5}$'))
            if ($('#id_producto').val().search('^[0-9]{1,5}$') < 0) {
                avisos += "El código Boka debe tener de 1 a 5 dígitos numéricos<br>"
                $('#id_producto').addClass('warning')
            }

            // productos con tipo_unidad=Kg , debe tener Boka=0
            if ($('#id_producto').val()>0 && $('#tipo_unidad').val()=="Kg") {
                avisos += "Los productos con tipo unidad = Kg, deben tener el código Boka = 0. Son productos de COMPRA y no se venden en tienda<br>"
                $('#id_producto').addClass('warning')
            }

            // comprobación añada
            var today = new Date()
            if ($('#anada').val() != "" && !($('#anada').val() > 1950 && $('#anada').val() <= today.getFullYear())) {
                avisos += "La añada debe ser entre 1950 y año actual<br>"
                $('#anada').addClass('warning')
            }

            // comprobacion pareamiento grupo - familia
            var id_grupo = $('#id_grupo').val()
            var id_familia = $('#id_familia').val()
            $.ajax({
                async: false,
                type: "POST",
                url: "<?php echo base_url() ?>" + "index.php/productos/checkGrupoFamilia",
                data: {
                    id_grupo: id_grupo,
                    id_familia: id_familia
                },
                success: function(datos) {
                    var datos = $.parseJSON(datos)
                    if (datos == false) {
                        avisos += "Familia: la familia seleccionada no corresponde al grupo<br>"
                        $('input[data-activates="select-options-id_familia"]').addClass('warning')
                    }

                },
                error: function() {
                    alert("Información importante:Error en el proceso checkGrupoFmiliaCodigo13. Informar");
                }
            })
            if (avisos != "") {
                $('.alert-danger').html(avisos)
                $('.alert-danger').removeClass('d-none')
                return false;
            } else {
                return true
            }
        }

        // evento justo DESPUES de mostrar la tabla - prepara inicialización de la vista
        $("#myModalProducto").on('shown.bs.modal', function() {
            $('#id_producto').focus()
            if ($('#id').val() == 0) $('#codigo_producto').focus()
            // adapta los campos correspondientes al tipo de unidad
            tipoUnidad()
            // inicializa campos de verificacion 
            $('.alert-danger').addClass('d-none')
            $('.alert-danger').html("")
            $('.form-control').removeClass('warning')
            $('.mdb-select').removeClass('warning')
        })

        // evento justo ANTES de mostrar la tabla - no usado
        $("#myModalProducto").on('show.bs.modal', function() {})

        // evento justo ANTES de ocultar la tabla - no usado
        $("#myModalProducto").on('hidde.bs.modal', function() {});

        // evento justo DESPUES de ocultar la tabla - no usado
        $("#myModalProducto").on('hidden.bs.modal', function() {});

        $("#myModal").on('hidden.bs.modal', function() {
            location.reload();
        });

    })
        

    


</script>