<form class="form-horizontal" method="post" name="add_stock" onsubmit="return validarCantidad()">
    <!-- Modal -->
    <div id="add-stock" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Agregar Stock</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="quantity" class="col-sm-2 control-label">Cantidad</label>
                        <div class="col-sm-6">
                            <input type="number" min="1" max="99999" name="quantity" class="form-control" id="quantity" placeholder="Cantidad" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reference" class="col-sm-2 control-label">Referencia</label>
                        <div class="col-sm-6">
                            <input type="text" name="reference" class="form-control" id="reference" placeholder="Referencia">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
function validarCantidad() {
    var cantidad = document.getElementById('quantity').value;
    if (cantidad > 99999) {
        alert('Error: La cantidad no puede exceder las 5 cifras.');
        return false;
    }
    return true;
}
</script>
