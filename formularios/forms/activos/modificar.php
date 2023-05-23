<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Activos = new Activos();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Activos</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="activo" class="control-label">Activo</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="activo" name="activo" placeholder="activo" required>
                        </div>
                    </div>

                    <div class="col-xs-3">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="conmotor" checked="" name="conmotor">
                                <label class="custom-control-label" for="conmotor">Con Motor</label>
                            </div>
                        </div>
                    </div>

                
                    <input type="hidden" name="accion" id="accion" value="modificarActivos"/>
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
