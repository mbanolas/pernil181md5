<style>
    /* dimensiones Modal producto */
    .modal-dialog-informacion {
        max-width: 40% !important;
        text-align: left;
    }
</style>
<!-- Modal -->
<div class="modal fade right" id="myModalInformacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-informacion modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulomyModalInformacion">Información</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body modal-body-myModalInformacion">
                
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary" id="informacionContinuuar">Continuar</a>
                <button type="button" class="btn btn-secondary cerrar" data-dismiss="modal" id="informacionCerrar">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<script>
    $(document).ready(function() {

        var tipoRango = "producto"

        $('.form-check-input#rango_compra').click(function() {
            tipoRango = "producto"
        })
        $('.form-check-input#rango_bodega').click(function() {
            tipoRango = "bodega"
        })

        
        



    })
</script>