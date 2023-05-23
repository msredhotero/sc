<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Tareas = new Tareas();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Tareas</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <h4 for="refusuarios" class="labelUsuario"></h4>
                            <input type="hidden" class="form-control" id="refusuarios" name="refusuarios" value="">
                        </div>
                    </div>

                    <div class="col-xs-3">
                    <div class="form-group">
                        <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="asignado" name="asignado">
                        <label class="custom-control-label" for="asignado">Asignado</label>
                        </div>
                    </div>
                    </div>

                
                    <input type="hidden" name="refordenestrabajocabecera" id="refordenestrabajocabecera" value="<?php echo $refordenestrabajocabecera; ?>"/>
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
