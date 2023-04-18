<?php


?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Areas</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">
            <div class="col-12">
              <label for="tarea" class="control-label">Area</label>
              <div class="form-group">
                <input type="text" class="form-control" id="tarea" name="tarea" placeholder="area" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-3">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="activo" checked="" name="activo">
                  <label class="custom-control-label" for="activo">Activo</label>
                </div>
              </div>
            </div>

            <div class="col-3">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="esreparacion" checked="" name="esreparacion">
                  <label class="custom-control-label" for="esreparacion">Es Reparación</label>
                </div>
              </div>
            </div>

            <div class="col-3">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="esmantenimiento" checked="" name="esmantenimiento">
                  <label class="custom-control-label" for="esmantenimiento">Es Mantenimiento</label>
                </div>
              </div>
            </div>

            <div class="col-3">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="verificakilometros" checked="" name="verificakilometros">
                  <label class="custom-control-label" for="verificakilometros">Verifica Km</label>
                </div>
              </div>
            </div>

            <div class="col-3">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="verificavencimientos" checked="" name="verificavencimientos">
                  <label class="custom-control-label" for="verificavencimientos">Verifica Venc.</label>
                </div>
              </div>
            </div>

          

            <input type="hidden" name="accion" id="accion" value="insertarTareas"/>
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
