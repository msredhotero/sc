<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  $Session = new Session('user');

  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Formularios de Tareas</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">
                    <div class="col-6">
                        <label for="refformularios" class="control-label">Formularios Cargados</label>
                        <div class="form-group">
                            <select class="form-control" name="refformularios" id="refformularios">
                            <?php foreach ($Formulariosconector->getFormularios()->traerTodos() as $row) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['formulario']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>


                
                    <input type="hidden" name="accion" id="accion" value="modificarFormulariosconector"/>
                    <input type="hidden" name="reftabla" id="reftabla" value="<?php echo $reftabla; ?>"/>
                    <input type="hidden" name="idreferencia" id="idreferencia" value="<?php echo $idreferencia; ?>"/>
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
