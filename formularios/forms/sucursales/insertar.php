<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Activos = new Sucursales($reftabla,0);
  $Zonas = new Zonas();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Sucursal</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">
            <div class="col-6">
              <label for="sucursal" class="control-label">Sucursal</label>
              <div class="form-group">
                <input type="text" class="form-control" id="sucursal" name="sucursal" placeholder="sucursal" required>
              </div>
            </div>

            <div class="col-12">
              <label for="direccion" class="control-label">Direccion</label>
              <div class="form-group">
                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="direccion" required>
              </div>
            </div>

            <div class="col-6">
              <label for="telefono" class="control-label">Telefono</label>
              <div class="form-group">
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="telefono" required>
              </div>
            </div>

            <div class="col-6">
              <label for="codpostal" class="control-label">Cod.Postal</label>
              <div class="form-group">
                <input type="text" class="form-control" id="codpostal" name="codpostal" placeholder="cod postal" required>
              </div>
            </div>

            <div class="col-6">
              <label for="refzonas" class="control-label">Zona</label>
              <div class="form-group">
                <select class="form-control" name="refzonas" id="refzonas">
                  <?php foreach ($Zonas->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['zona']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>


            <div class="col-6" style="display:none;">
              <label for="latitud" class="control-label">Latitud</label>
              <div class="form-group">
                <input type="text" class="form-control" id="latitud" name="latitud" placeholder="latitud" required>
              </div>
            </div>

            <div class="col-6" style="display:none;">
              <label for="longitud" class="control-label">Longitud</label>
              <div class="form-group">
                <input type="text" class="form-control" id="longitud" name="longitud" placeholder="sucursal" required>
              </div>
            </div>

            <div class="col-12">
              <div class="row" id="contMapa2" style="margin-left:25px; margin-right:25px;">
            	  <div id="map" ></div>
              </div>
            </div>

            <input type="hidden" name="accion" id="accion" value="insertarSucursal"/>
            <input type="hidden" name="reftabla" id="reftabla" value="<?php echo $reftabla; ?>"/>
            <input type="hidden" name="idreferencia" id="idreferencia" value="<?php echo $idreferencia; ?>"/>
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
