<style>
    /* dimensiones Modal producto */
    .modal-dialog-informacion {
        max-width: 40% !important;
        text-align: left;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-informacion">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel">Crear nuevo producto</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="actividad" value="">
                <h5>Seleccionar nuevos tipos</h5>

                <div class="form-check">
                    <input type="radio" class="form-check-input" id="productos_compra" name="materialExampleRadios" checked>
                    <label class="form-check-label" for="productos_compra">Crear nuevos <strong>productos con un peso </strong>determinado, copiandolo de otro</label>
                </div>

                <div class="form-check">
                    <input type="radio" class="form-check-input" id="productos_bodega" name="materialExampleRadios">
                    <label class="form-check-label" for="productos_bodega">Crear nuevos <strong>productos bodega </strong>con otra añada, copiándolo de otra</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="signin">Continuar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
   
});
</script>