<?php


?>



          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="refactivos">Area</label>
                <p><?php echo $Areas->getArea(); ?></p>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label for="refmarcas">Cargo</label>
                <p><?php echo $Cargos->getCargo(); ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <label for="modelo" class="control-label">Nombre</label>
              <div class="form-group">
              <p><?php echo $Personal->getNombres(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <label for="anio" class="control-label">Primer Apellido</label>
              <div class="form-group">
              <p><?php echo $Personal->getPrimerapellido(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <label for="patente" class="control-label">Segundo Apellido</label>
              <div class="form-group">
              <p><?php echo $Personal->getSegundoapellido(); ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            

            <div class="col-4">
              <label for="chasis" class="control-label">RUT</label>
              <div class="form-group">
              <p><?php echo $Personal->getRut(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <label for="nromotor" class="control-label">Email</label>
              <div class="form-group">
              <p><?php echo $Personal->getEmail(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <label for="tipo" class="control-label">Movil</label>
              <div class="form-group">
              <p><?php echo $Personal->getMovil(); ?></p>
              </div>
            </div>

          </div>
          <div class="row">
            
            <div class="col-4">
              <label for="kilometros" class="control-label">Fecha Alta</label>
              <div class="form-group">
              <p><?php echo $Personal->getFechaalta(); ?></p>
              </div>
            </div>

            <div class="col-4">
              <label for="kilometros" class="control-label">Fecha Baja</label>
              <div class="form-group">
              <p><?php echo $Personal->getFechabaja(); ?></p>
              </div>
            </div>
            <div class="col-4">
            <label for="tipo" class="control-label">Activo</label>
              <div class="form-group">
              <p><?php echo $Personal->getActivoStr(); ?></p>
              </div>
            </div>
          </div>


