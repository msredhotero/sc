<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Proveedores = new Proveedores();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Proveedores</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form class="formulario frmModificar" role="form" id="sign_in">
            <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="refcargos">Cargos</label>
                <select class="form-control" id="refcargos" name="refcargos" required>
                  <?php foreach ($Personal->getCargos()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['cargo']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="refareas">Areas</label>
                <select class="form-control" id="refareas" name="refareas" required>
                  <?php foreach ($Personal->getAreas()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['area']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-4">
              <label for="nombres" class="control-label">Nombres</label>
              <div class="form-group">
                <input type="text" class="form-control" id="nombres" name="nombres" placeholder="nombres" required>
              </div>
            </div>

            <div class="col-4">
              <label for="primerapellido" class="control-label">Primer Apellido</label>
              <div class="form-group">
                <input type="text" class="form-control" id="primerapellido" name="primerapellido" placeholder="primer apellido" required>
              </div>
            </div>

            <div class="col-4">
              <label for="segundoapellido" class="control-label">Segundo Apellido</label>
              <div class="form-group">
                <input type="text" class="form-control" id="segundoapellido" name="segundoapellido" placeholder="segundo apellido" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <label for="rut" class="control-label">RUT</label>
              <div class="form-group">
                <input type="numbre" maxlength="10" class="form-control" id="rut" name="rut" placeholder="rut" required>
              </div>
            </div>

            <div class="col-6">
              <label for="email" class="control-label">Email</label>
              <div class="form-group">
                <input type="text" class="form-control" id="email" name="email" placeholder="email" >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <label for="movil" class="control-label">Movil</label>
              <div class="form-group">
                <input type="text" class="form-control" id="movil" name="movil" placeholder="movil" >
              </div>
            </div>

            <div class="col-4">
              <label for="fechaalta" class="control-label">Fecha Alta</label>
              <div class="form-group">
                <input type="date" class="form-control" id="fechaalta" name="fechaalta" placeholder="fecha alta" >
              </div>
            </div>
            <div class="col-4">
              <label for="fechabaja" class="control-label">Fecha Baja</label>
              <div class="form-group">
                <input type="date" class="form-control" id="fechabaja" name="fechabaja" placeholder="fecha baja" >
              </div>
            </div>
          </div>
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="activo" checked="" name="activo">
                            <label class="custom-control-label" for="activo">Activo</label>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="idmodificar" id="idmodificar" value=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn bg-gradient-warning modificar">Modificar</button>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
