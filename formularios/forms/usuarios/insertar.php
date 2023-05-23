<?php
spl_autoload_register(function($clase){
  include_once "../includes/" .$clase. ".php";        
});

  $Roles = new Roles();
  $Cargos = new Cargos();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
            <div class="col-6">
              <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="nombre de usuario" required>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="nombre" required>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="apellido" required>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="direccion">Direccion</label>
                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="direccion" required>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="telefono">Telefono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="telefono">
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="email@gmail.com" required>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="*********" required>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="refroles">Rol</label>
                <select class="form-control" id="refroles" name="refroles">
                  <?php foreach ($Roles->traerRoles() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['rol']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="refcargos">Cargo</label>
                <select class="form-control" id="refcargos" name="refcargos">
                  <option value="0">-- Seleccionar --</option>
                  <?php foreach ($Cargos->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['cargo']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="refzonas">Zona</label>
                <select class="form-control" id="refzonas" name="refzonas">
                  <option value="0">-- Seleccionar --</option>
                  <?php foreach ($Usuarios->getZonas()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['zona']; ?></option>
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
