<?php



?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Usuarios</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <?php if (count($Cuadrillas->traerTodosDisponibles())>0) { ?>
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">


            <div class="col-6">
              <div class="form-group">
                <label for="refusuarios">Usuarios</label>
                <select class="form-control" id="refusuarios" name="refusuarios">
                  <option value="0">-- Seleccionar --</option>
                  <?php foreach ($Cuadrillas->traerTodosDisponibles() as $row) { 
                    if ($Ordenestrabajocabecera::libre($Ordenestrabajocabecera->getFecha(),$row['id']) == 0) {  
                  ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['apellido'].' '.$row['nombre']; ?></option>
                  <?php } 
                  }?>
                </select>
              </div>
            </div>

            <div class="col-xs-3">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="asignado" name="asignado">
                  <label class="custom-control-label" for="asignado">Asignado</label>
                </div>
              </div>
            </div>
          

            <input type="hidden" name="accion" id="accion" value="insertarTareas"/>
            <input type="hidden" name="refordenestrabajocabecera" id="refordenestrabajocabecera" value="<?php echo $refordenestrabajocabecera; ?>"/>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn bg-gradient-success nuevo">Guardar</button>
          </div>
        </form>
        <?php } else { ?>
          <h4>No existe personal disponible</h4>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
