<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Roles = new Roles();
  $Cargos = new Cargos();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade modal2" id="lgmModificarPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Usuarios</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="formulario frmModificarPassword" role="form" id="sign_in">
                <div class="row">

                    <div class="col-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="*********" required>
                        </div>
                    </div>

                    <input type="hidden" name="accion" id="accion" value="modificarUsuarios"/>
                    <input type="hidden" name="idmodificarPassword" id="idmodificarPassword" value=""/>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn bg-gradient-warning modificarPassword">Modificar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
