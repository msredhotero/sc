<?php

$Estados = new Estados();

$lstEstados = $Estados->traerTodos();

?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar OT</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">
                <?php if ($Ordenestrabajos->getRefcamiones()> 0) { ?>
              <input type="hidden" name="refcamiones" id="refcamiones" value="<?php echo $Ordenestrabajos->getRefcamiones(); ?>"/>
            <?php } else { ?>
            <div class="col-6">
              <div class="form-group">
                <label for="refcamiones">Activos</label>
                <select class="form-control" id="refcamiones" name="refcamiones">
                  <?php foreach ($Ordenestrabajos->getCamiones()->traerTodosEspecificoActivos() as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['activo']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <?php } ?>

            <div class="col-6">
              <div class="form-group">
                <label for="reftareas">Areas</label>
                <select class="form-control" id="reftareas" name="reftareas">
                  <?php foreach ($Ordenestrabajos->getTareas()->traerTodosFilter(array($labeltipo=> '1')) as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['tarea']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="refestados">Estados</label>
                <select class="form-control" id="refestados" name="refestados">
                  <?php foreach ($lstEstados as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['estado']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-6">
              <label for="fechainicio" class="control-label">Fecha Inicio</label>
              <div class="form-group">
                <input class="form-control" type="date" value="2018-11-23T10:30:00" id="fechainicio" name="fechainicio" required>
              </div>
            </div>

            <div class="col-6">
              <label for="fechafin" class="control-label">Fecha Fin</label>
              <div class="form-group">
                <input class="form-control" type="date" value="2018-11-23T10:30:00" id="fechafin" name="fechafin" >
              </div>
            </div>

            <div class="col-6">
              <label for="fecharealfinalizacion" class="control-label">Fecha Real Finalizacion</label>
              <div class="form-group">
                <input class="form-control" type="date" value="2018-11-23T10:30:00" id="fecharealfinalizacion" name="fecharealfinalizacion" >
              </div>
            </div>

            <div class="col-12">
              <label for="observacion" class="control-label">Observaciones</label>
              <div class="form-group">
                <textarea class="form-control" id="observacion" name="observacion" ></textarea>
              </div>
            </div>

                    <input type="hidden" name="idmodificar" id="idmodificar" value=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn bg-gradient-warning modificar">Modificar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
