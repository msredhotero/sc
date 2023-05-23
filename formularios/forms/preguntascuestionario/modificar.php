<?php

  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Preguntas</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">
                    <div class="col-6">
                        <label for="reftiporespuesta" class="control-label">Tipo Pregunta</label>
                        <div class="form-group">
                            <select class="form-control" name="reftiporespuesta" id="reftiporespuesta">
                            <?php foreach ($Preguntascuestionario->getTiporespuesta()->traerTodos() as $row) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['tiporespuesta']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="pregunta" class="control-label">Pregunta</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="pregunta" name="pregunta" placeholder="pregunta" required>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="orden" class="control-label">Orden</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="orden" name="orden" placeholder="orden" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="leyenda" class="control-label">Leyenda</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="leyenda" name="leyenda" placeholder="leyenda">
                        </div>
                    </div>


                    <div class="col-6 contTablas">
                        <label for="leyenda" class="control-label">Tablas</label>
                        <div class="form-group">
                            <select class="form-control" name="reftabladatos" id="reftabladatos">
                            <option value="7">Solcitud de Visitas</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-6 contDatos">
                        <label for="leyenda" class="control-label">Dato</label>
                        <div class="form-group">
                            <select class="form-control" name="columna" id="columna">

                            </select>
                        </div>
                    </div>

                    <div class="col-xs-3">
                        <div class="form-group">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="obligatoria" checked="" name="obligatoria" >
                            <label class="custom-control-label" for="obligatoria">Obligatoria</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-3">
                        <div class="form-group">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="activo" checked="" name="activo" >
                            <label class="custom-control-label" for="activo">Activo</label>
                            </div>
                        </div>
                    </div>


                
                    <input type="hidden" name="accion" id="accion" value="modificarPreguntas"/>
                    <input type="hidden" name="refformularios" id="refformularios" value="<?php echo $refformularios; ?>"/>
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
