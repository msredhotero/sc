<?php

  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade lgmNuevoModal" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Solicitud de Visita</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row">
            <div class="col-6">
              <label for="refclientes" class="control-label">Clientes</label>
              <div class="form-group">
                <select class="form-control" name="refclientes" id="refclientes">
                  <?php foreach ($Solicitudesvisitas->getClientes()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-6">
              <label for="refsucursales" class="control-label">Sucursales</label>
              <div class="form-group">
                <select class="form-control" name="refsucursales" id="refsucursales">
                  <option value=""></option>
                </select>
              </div>
            </div>

            <div class="col-6">
              <label for="reftipoactividades" class="control-label">Actividad</label>
              <div class="form-group">
                <select class="form-control" name="reftipoactividades" id="reftipoactividades">
                  <?php foreach ($Solicitudesvisitas->getTipoactividades()->traerTodosActivos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['actividad']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-6">
              <label for="refzonas" class="control-label">Zona</label>
              <div class="form-group">
                <select class="form-control" name="refzonas" id="refzonas">
                  <?php foreach ($Solicitudesvisitas->getZonas()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['zona']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-4">
              <label for="nroaviso" class="control-label">Nro de Aviso</label>
              <div class="form-group">
                <input class="form-control" id="nroaviso" name="nroaviso" type="text" value="" >
              </div>
            </div>

            <div class="col-4">
              <label for="claseaviso" class="control-label">Clase de Aviso</label>
              <div class="form-group">
                <input class="form-control" id="claseaviso" name="claseaviso" type="text" value="" >
              </div>
            </div>

            <div class="col-4">
              <label for="autoraviso" class="control-label">Autor de Aviso</label>
              <div class="form-group">
                <input class="form-control" id="autoraviso" name="autoraviso" type="text" value="">
              </div>
            </div>

            <div class="col-4">
              <label for="fecha" class="control-label">Fecha</label>
              <div class="form-group">
                <input class="form-control" id="fecha" name="fecha" type="date" value="<?php echo date('Y-m-d'); ?>" id="fecha">
              </div>
            </div>

            <div class="col-4">
              <label for="refsemaforo" class="control-label">Prioridad</label>
              <div class="form-group">
                <select class="form-control" name="refsemaforo" id="refsemaforo">
                  <?php foreach ($Solicitudesvisitas->getSemaforo()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['nivel']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-4">
              <label for="refestados" class="control-label">Estado</label>
              <div class="form-group">
                <select class="form-control" name="refestados" id="refestados">
                  <?php foreach ($Solicitudesvisitas->getEstados()->traerTodosFilter(['id'=>1]) as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['estado']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-12">
              <label for="descripcion" class="control-label">Descripcion</label>
              <div class="form-group">
                <textarea rows="3" class="form-control" id="descripcion" name="descripcion" placeholder="descripcion" ></textarea>
              </div>
            </div>



            <input type="hidden" name="accion" id="accion" value="insertarSolicitudesvisitas"/>
            
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
