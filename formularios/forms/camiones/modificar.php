<?php
spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
  });
  
  
  $Globales = new Globales();
  $Session = new Session('user');
  $Proveedores = new Proveedores();
  
  if (!($Session->exists())) {
    header('Location: ../error.php');
  }


?>


<div class="modal fade modal2" id="lgmModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modificar Proveedores</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form class="formulario frmModificar" role="form" id="sign_in">
                <div class="row">
                    <div class="col-6">
                    <div class="form-group">
                        <label for="refactivos">Activos</label>
                        <select class="form-control" id="refactivos" name="refactivos">
                        <?php foreach ($Camiones->getActivos()->traerTodos() as $row) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['activo']; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    </div>

                    <div class="col-6">
                    <div class="form-group">
                        <label for="refmarcas">Marcas</label>
                        <select class="form-control" id="refmarcas" name="refmarcas">
                        <?php foreach ($Camiones->getMarcas()->traerTodos() as $row) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['marca']; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                    <label for="modelo" class="control-label">Modelo</label>
                    <div class="form-group">
                        <input type="text" class="form-control" id="modelo" name="modelo" placeholder="modelo" required>
                    </div>
                    </div>

                    <div class="col-6">
                    <label for="anio" class="control-label">Año</label>
                    <div class="form-group">
                        <input type="text" class="form-control" id="anio" name="anio" placeholder="anio" required>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                    <label for="patente" class="control-label">Patente</label>
                    <div class="form-group">
                        <input type="text" class="form-control" id="patente" name="patente" placeholder="patente" required>
                    </div>
                    </div>

                    <div class="col-6">
                    <label for="chasis" class="control-label">Chasis</label>
                    <div class="form-group">
                        <input type="text" class="form-control" id="chasis" name="chasis" placeholder="chasis" required>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                    <label for="nromotor" class="control-label">Nro Motor</label>
                    <div class="form-group">
                        <input type="text" class="form-control" id="nromotor" name="nromotor" placeholder="nro motor" >
                    </div>
                    </div>

                    <div class="col-6">
                    <label for="tipo" class="control-label">Tipo</label>
                    <div class="form-group">
                        <input type="text" class="form-control" id="tipo" name="tipo" placeholder="tipo" >
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="activo" checked="" name="activo">
                            <label class="custom-control-label" for="activo">Activo</label>
                            </div>
                        </div>
                    </div>

                        <input type="hidden" name="accion" id="accion" value="modificarCamiones"/>
                        <input type="hidden" name="idmodificar" id="idmodificar" value=""/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn bg-gradient-warning modificar">Modificar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
