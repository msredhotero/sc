<?php

  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Pregunta</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">


            <div class="col-6">
              <label for="respuesta" class="control-label">Respuesta</label>
              <div class="form-group">
                <input type="text" class="form-control" id="respuesta" name="respuesta" placeholder="respuesta" required>
              </div>
            </div>

            <div class="col-6">
              <label for="orden" class="control-label">Orden</label>
              <div class="form-group">
                <input type="text" class="form-control" id="orden" name="orden" placeholder="orden" required>
              </div>
            </div>

            <div class="col-12">
              <label for="leyenda" class="control-label">Leyenda</label>
              <div class="form-group">
                <input type="text" class="form-control" id="leyenda" name="leyenda" placeholder="leyenda" >
              </div>
            </div>

            <div class="col-xs-3">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="activo" checked="" name="activo" required>
                  <label class="custom-control-label" for="activo">Activo</label>
                </div>
              </div>
            </div>


            <input type="hidden" name="accion" id="accion" value="insertarRespuestas"/>
            <input type="hidden" name="refpreguntascuestionario" id="refpreguntascuestionario" value="<?php echo $refpreguntascuestionario; ?>"/>
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
