<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Presupuestos = new Presupuestos();
  
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
                            <label for="refmateriales">Materiales</label>
                            <select class="form-control" id="refmateriales" name="refmateriales">
                            <option value="0">-- Seleccionar --</option>
                            <?php foreach ($Presupuestos->getMateriales()->traerTodos() as $row) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['material']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <label for="cantidad" class="control-label">Cantidad</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="cantidad" name="cantidad" placeholder="cantidad" required>
                        </div>
                    </div>
                
                    <input type="hidden" name="refordenestrabajodetalle" id="refordenestrabajodetalle" value="<?php echo $refordenestrabajodetalle; ?>"/>

                
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
