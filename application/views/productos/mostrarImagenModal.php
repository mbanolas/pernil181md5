<style>

    </style>

<!-- Modal -->
<div class="modal fade" id="mostrarImagenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-imagen" id="tituloImagen">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="imagen"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // evento justo DESPUES de ocultar la ventana modal
        $("#mostrarImagenModal").on('hidden.bs.modal', function() {
            // $("#myModalProducto").modal('toggle')
        });
});


</script>