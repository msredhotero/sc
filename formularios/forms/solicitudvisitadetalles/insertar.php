<?php

  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade lgmNuevoModal" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Solicitud de Visita Tareas</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">
            <div class="col-6">
              <label for="reftareas" class="control-label">Tareas</label>
              <div class="form-group">
                <select class="form-control" name="reftareas" id="reftareas">
                  <?php foreach ($Solicitudvisitadetalles->getTareas()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['tarea']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>


            <div class="col-6">
              <label for="refestados" class="control-label">Estado</label>
              <div class="form-group">
                <select class="form-control" name="refestados" id="refestados">
                  <?php foreach ($Solicitudesvisitas->getEstados()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['estado']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-12">
              <label for="observaciones" class="control-label">Observaciones</label>
              <div class="form-group">
                <textarea rows="3" class="form-control" id="observaciones" name="observaciones" placeholder="observaciones" ></textarea>
              </div>
            </div>


            <input type="hidden" name="refsolicitudesvisitas" id="refsolicitudesvisitas" value="<?php echo $refsolicitudesvisitas; ?>"/>
            <input type="hidden" name="accion" id="accion" value="insertarSolicitudvisitadetalles"/>
            
            
          </div>
          <div class="modal-footer">
              <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn bg-gradient-success nuevo">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
