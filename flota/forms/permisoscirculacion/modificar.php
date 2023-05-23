<?php


?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Emisiones</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">
                    <div class="col-6">
                        <label for="realizado" class="control-label">Realizado</label>
                        <div class="form-group">
                            <input class="form-control" type="date" value="2018-11-23T10:30:00" id="realizado" name="realizado" required>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="vencimiento" class="control-label">Vencimiento</label>
                        <div class="form-group">
                            <input class="form-control" type="date" value="2018-11-23T10:30:00" id="vencimiento" name="vencimiento" required>
                        </div>
                    </div>

                    

                
                    <input type="hidden" name="refcamiones" id="refcamiones" value="<?php echo $id; ?>"/>
                    <input type="hidden" name="idmodificar" id="idmodificar" value=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn bg-gradient-warning modificar">Modificar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
