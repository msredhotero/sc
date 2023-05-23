<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Tareas = new Tareas();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Tareas</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">
            <div class="col-xs-6">
              <label for="tarea" class="control-label">Tarea</label>
              <div class="form-group">
                <input type="text" class="form-control" id="tarea" name="tarea" placeholder="tarea" required>
              </div>
            </div>

            <div class="col-xs-3">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="activo" checked="" name="activo">
                  <label class="custom-control-label" for="activo">Activo</label>
                </div>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="refpadre">Tareas Padre</label>
                <select class="form-control" id="refpadre" name="refpadre">
                  <option value="0">-- Seleccionar --</option>
                  <?php foreach ($Tareas->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['tarea']; ?></option>
                  <?php } ?>
                </select>
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
