<?php


?>



          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label for="refactivos">Activos</label>
                <p><?php echo $Activos->getActivo(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <div class="form-group">
                <label for="refmarcas">Marcas</label>
                <p><?php echo $Marcas->getMarca(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <label for="modelo" class="control-label">Modelo</label>
              <div class="form-group">
              <p><?php echo $Camiones->getModelo(); ?></p>
              </div>
            </div>

          </div>
          <div class="row">
            
            <div class="col-4">
              <label for="anio" class="control-label">Año</label>
              <div class="form-group">
              <p><?php echo $Camiones->getAnio(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <label for="patente" class="control-label">Patente</label>
              <div class="form-group">
              <p><?php echo $Camiones->getPatente(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <label for="chasis" class="control-label">Chasis</label>
              <div class="form-group">
              <p><?php echo $Camiones->getChasis(); ?></p>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-4">
              <label for="nromotor" class="control-label">Nro Motor</label>
              <div class="form-group">
              <p><?php echo $Camiones->getNromotor(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <label for="tipo" class="control-label">Tipo</label>
              <div class="form-group">
              <p><?php echo $Camiones->getTipo(); ?></p>
              </div>
            </div>
            <div class="col-4">
              <label for="kilometros" class="control-label">Km</label>
              <div class="form-group">
              <h5><?php echo $Camiones->getKilometros(); ?></h5>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
            <label for="tipo" class="control-label">Activo</label>
              <div class="form-group">
              <p><?php echo $Camiones->getActivoStr(); ?></p>
              </div>
            </div>
            <div class="col-4">
            <label for="tipo" class="control-label">Fuera de Servicio</label>
              <div class="form-group">
              <p><?php echo $Camiones->getFueradeservicioStr(); ?></p>
              </div>
            </div>
          </div>

