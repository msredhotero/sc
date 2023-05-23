<?php


?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Mantenimiento</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="refcamiones">Activo</label>
                            <select class="form-control" id="refcamiones" name="refcamiones">
                            <?php foreach ($Mantenimientoflota->getCamiones()->traerTodos() as $row) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['patente']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label for="reftareas">Areas</label>
                            <select class="form-control" id="reftareas" name="reftareas">
                            <?php foreach ($Mantenimientoflota->getTareas()->traerTodosFilter(array('esmantenimiento'=>'1')) as $row) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['tarea']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-4">
                        <label for="kilometros" class="control-label">Km</label>
                        <div class="form-group">
                            <input type="number" class="form-control" id="kilometros" name="kilometros" placeholder="0" >
                        </div>
                    </div>
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
