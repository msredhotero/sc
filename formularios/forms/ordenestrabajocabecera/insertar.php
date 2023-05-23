<?php

$Usuarios = new Usuarios('','');
$Tareas = new Tareas();

?>


<div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar OT</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <?php 
          if (count($Ordenestrabajocabecera->getSolicitudesvisitas()->traerSolicitudesSinOT())>0) { 
            //die(var_dump($Ordenestrabajocabecera->getSolicitudesvisitas()->traerSolicitudesSinOT()));
        ?>
        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="row contSV">

          </div>
          <hr>
          <div class="row">
            

            <div class="col-12">
              <label for="refsolicitudesvisitas" class="control-label">Solicitud de Visita</label>
              <div class="form-group">
                <select class="form-control" name="refsolicitudesvisitas" id="refsolicitudesvisitas">
                  <?php foreach ($Ordenestrabajocabecera->getSolicitudesvisitas()->traerSolicitudesSinOT() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo 'Cliente: '.$row['cliente'].' | Sucursal: '.$row['sucursal'].' | Fecha: '.$row['fecha'].' | Prioridad:'.$row['nivel'].' | Zona:'.$row['zona']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-4">
              <label for="fecha" class="control-label">Fecha</label>
              <div class="form-group">
                <input class="form-control" id="fecha" name="fecha" type="datetime-local" value="" id="fecha" required>
              </div>
            </div>

            <div class="col-4">
              <label for="fechafin" class="control-label">Fecha Fin</label>
              <div class="form-group">
                <input class="form-control" id="fechafin" name="fechafin" type="datetime-local" value="" id="fechafin" required>
              </div>
            </div>

            <div class="col-4">
              <label for="refsemaforo" class="control-label">Prioridad</label>
              <div class="form-group">
                <select class="form-control" name="refsemaforo" id="refsemaforo">
                  <?php foreach ($Ordenestrabajocabecera->getSemaforo()->traerTodos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['nivel']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-4">
              <label for="refestados" class="control-label">Estado</label>
              <div class="form-group">
                <select class="form-control" name="refestados" id="refestados">
                  <?php foreach ($Ordenestrabajocabecera->getEstados()->traerTodosFilter(['id'=>1]) as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['estado']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            
          </div>
          <div class="row">
            <div class="col-6">
              <h6 class="text-warning">Tareas</h6>
              <div class="checkbox-group required">
              <ul class="list-inline">
              <?php foreach ($Tareas->traerTodos() as $row) { ?>
                
                  <li>
                    <div class="form-check" style="float: left;">
                      <input class="form-check-input reftareas" type="checkbox" name="reftareas[]" value="<?php echo $row['id']; ?>" id="reftareas<?php echo $row['id']; ?>" >
                      <label class="custom-control-label" for="customCheck<?php echo $row['id']; ?>"><?php echo $row['tarea']; ?></label>
                    </div>
                  </li>
                
              <?php } ?>
              </ul>
              </div>
            </div>

            <div class="col-6">
              <h6 class="text-warning">Usuarios Disponibles</h6>
              <div class="checkbox-group required">
              <ul class="list-inline lstUsuarios">

              </ul>
              </div>
            </div>


            


            <input type="hidden" name="accion" id="accion" value="insertarActivos"/>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn bg-gradient-success nuevo">Guardar</button>
          </div>
        </form>
        <?php } else { ?>
          <h5>No existen Solicitudes de Visitas para cargar</h5>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
