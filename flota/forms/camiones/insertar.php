<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Camiones = new Camiones();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Flota</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="refactivos">Activos</label>
                <select class="form-control" id="refactivos" name="refactivos">
                  <?php foreach ($Camiones->getActivos()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['activo']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="refmarcas">Marcas</label>
                <select class="form-control" id="refmarcas" name="refmarcas">
                  <?php foreach ($Camiones->getMarcas()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['marca']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <label for="modelo" class="control-label">Modelo</label>
              <div class="form-group">
                <input type="text" class="form-control" id="modelo" name="modelo" placeholder="modelo" required>
              </div>
            </div>

            <div class="col-6">
              <label for="anio" class="control-label">Año</label>
              <div class="form-group">
                <input type="text" class="form-control" id="anio" name="anio" placeholder="anio" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <label for="patente" class="control-label">Patente</label>
              <div class="form-group">
                <input type="text" class="form-control" id="patente" name="patente" placeholder="patente" required>
              </div>
            </div>

            <div class="col-6">
              <label for="chasis" class="control-label">Chasis</label>
              <div class="form-group">
                <input type="text" class="form-control" id="chasis" name="chasis" placeholder="chasis" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <label for="nromotor" class="control-label">Nro Motor</label>
              <div class="form-group">
                <input type="text" class="form-control" id="nromotor" name="nromotor" placeholder="nro motor" >
              </div>
            </div>

            <div class="col-4">
              <label for="tipo" class="control-label">Tipo</label>
              <div class="form-group">
                <input type="text" class="form-control" id="tipo" name="tipo" placeholder="tipo" >
              </div>
            </div>
            <div class="col-4">
              <label for="kilometros" class="control-label">Km</label>
              <div class="form-group">
                <input type="number" class="form-control" id="kilometros" name="kilometros" placeholder="0" >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-3">
              <label for="color" class="control-label">Color</label>
              <div class="form-group">
                <input type="color" class="form-control" id="color" name="color" placeholder="#000000" >
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
                  <input class="form-check-input" type="checkbox" value="" id="fueradeservicio" checked="" name="fueradeservicio">
                  <label class="custom-control-label" for="fueradeservicio">Fuera de Servicio</label>
                </div>
              </div>
            </div>

            <input type="hidden" name="accion" id="accion" value="insertarCamiones"/>
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
