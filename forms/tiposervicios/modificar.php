<?php


?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Tipo de Servicio</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="tiposervicio" class="control-label">Tipo Servicio</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="tiposervicio" name="tiposervicio" placeholder="tipo servicio" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-warning">Activos</h6>
                            <div class="checkbox-group required">
                            <ul class="list-inline">
                            <?php foreach ($Activos->traerTodos() as $row) { ?>
                            
                                <li>
                                <div class="form-check" style="float: left;">
                                    <input class="form-check-input refactivos" type="checkbox" name="refactivos[]" value="<?php echo $row['id']; ?>" id="refactivos<?php echo $row['id']; ?>" >
                                    <label class="custom-control-label" for="customCheck<?php echo $row['id']; ?>"><?php echo $row['activo']; ?></label>
                                </div>
                                </li>
                            
                            <?php } ?>
                            </ul>
                            </div>
                        </div>
                    </div>


                
                    <input type="hidden" name="accion" id="accion" value="modificarCargos"/>
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
