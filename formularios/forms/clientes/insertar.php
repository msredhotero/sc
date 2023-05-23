<?php



?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Clientes</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">
            <div class="col-6">
              <label for="nombre" class="control-label">Nombre</label>
              <div class="form-group">
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="nombre" required>
              </div>
            </div>

            <div class="col-12">
              <label for="direccion" class="control-label">Direccion</label>
              <div class="form-group">
              <input type="text" class="form-control" id="direccion" name="direccion" placeholder="direccion..." />
              </div>
            </div>

            <div class="col-6">
              <label for="cuit" class="control-label">Cuit</label>
              <div class="form-group">
                <input type="text" class="form-control" id="cuit" name="cuit" placeholder="cuit" required>
              </div>
            </div>

            <div class="col-6">
              <label for="contacto" class="control-label">Contacto</label>
              <div class="form-group">
                <input type="text" class="form-control" id="contacto" name="contacto" placeholder="contacto" required>
              </div>
            </div>

            <div class="col-6">
              <label for="telefono" class="control-label">Telefono</label>
              <div class="form-group">
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="telefono" required>
              </div>
            </div>

            <div class="col-6">
              <label for="email" class="control-label">Email</label>
              <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder="email" required>
              </div>
            </div>

            <div class="col-6">
              <label for="codpostal" class="control-label">Cod.Postal</label>
              <div class="form-group">
                <input type="codpostal" class="form-control" id="codpostal" name="codpostal" placeholder="Cod.Postal" required>
              </div>
            </div>

            <div class="col-6">
              <label for="latitud" class="control-label">Latitud</label>
              <div class="form-group">
                <input type="text" class="form-control" id="latitud" name="latitud" placeholder="latitud" >
              </div>
            </div>

            <div class="col-6">
              <label for="longitud" class="control-label">Longitud</label>
              <div class="form-group">
                <input type="text" class="form-control" id="longitud" name="longitud" placeholder="longitud" >
              </div>
            </div>

            <div class="col-12">
              <div class="row" id="contMapa" style="margin-left:25px; margin-right:25px;">
            	  <div id="map" ></div>
              </div>
              
            </div>

            <input type="hidden" name="accion" id="accion" value="insertarClientes"/>
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

