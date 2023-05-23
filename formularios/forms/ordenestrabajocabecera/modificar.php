<?php

?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar OT</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">


                    <div class="col-6">
                        <label for="fecha" class="control-label">Fecha</label>
                        <div class="form-group">
                            <input class="form-control" id="fecha" name="fecha" type="datetime-local" value="2018-11-23T10:30:00" id="fecha">
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="fechafin" class="control-label">Fecha Fin</label>
                        <div class="form-group">
                            <input class="form-control" id="fechafin" name="fechafin" type="datetime-local" value="2018-11-23T10:30:00" id="fechafin">
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="refsemaforo" class="control-label">Prioridad</label>
                        <div class="form-group">
                            <select class="form-control" name="refsemaforo" id="refsemaforo">
                            <?php foreach ($Ordenestrabajocabecera->getSemaforo()->traerTodos() as $row) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nivel']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="refestados" class="control-label">Estado</label>
                        <div class="form-group">
                            <select class="form-control" name="refestados" id="refestados">
                            <?php foreach ($Ordenestrabajocabecera->getEstados()->traerTodos() as $row) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['estado']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>

                
                    <input type="hidden" name="refsolicitudesvisitas" id="refsolicitudesvisitas" value=""/>
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
