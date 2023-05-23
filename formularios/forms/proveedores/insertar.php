<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Activos = new Proveedores();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Proveedores</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">
            <div class="col-xs-6">
              <label for="proveedor" class="control-label">Proveedor</label>
              <div class="form-group">
                <input type="text" class="form-control" id="proveedor" name="proveedor" placeholder="proveedor" required>
              </div>
            </div>

            <div class="col-xs-6">
              <label for="direccion" class="control-label">Dirección</label>
              <div class="form-group">
                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="direccion" required>
              </div>
            </div>

            <div class="col-xs-6">
              <label for="movil" class="control-label">Movil</label>
              <div class="form-group">
                <input type="text" class="form-control" id="movil" name="movil" placeholder="movil" required>
              </div>
            </div>



            <input type="hidden" name="accion" id="accion" value="insertarProveedores"/>
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
