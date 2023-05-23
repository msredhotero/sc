<?php

error_reporting(E_ALL);

ini_set('ignore_repeated_errors', TRUE); // always use TRUE

ini_set('display_errors', true); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment

ini_set('log_errors', TRUE); // Error/Exception file logging engine.

spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Usuarios = new Usuarios('','');
  $Roles = new Roles();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


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
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">
            <div class="col-xs-6">
              <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="nombre de usuario" required>
              </div>
            </div>

            <div class="col-xs-6">
              <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="nombre" required>
              </div>
            </div>

            <div class="col-xs-6">
              <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="apellido" required>
              </div>
            </div>

            <div class="col-xs-6">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="email@gmail.com" required>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="*********" required>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label for="refroles">Rol</label>
                <select class="form-control" id="refroles" name="refroles">
                  <?php foreach ($Roles->traerRoles() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['rol']; ?></option>
                  <?php } ?>
                </select>
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
            <div class="col-xs-3">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="validoemail" checked="" name="validoemail">
                  <label class="custom-control-label" for="validoemail">Valido su email</label>
                </div>
              </div>
            </div>
            <input type="hidden" name="accion" id="accion" value="insertarUsuarios"/>
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
