<?php

$Porterias = new Porterias();

$Camiones = new Camiones();
$Camiones->setRefactivos(1);
$Acoplado = new Camiones();
$Acoplado->setRefactivos(2);

$TipoServicios = new Tiposervicios();

$Acciones = new Acciones();
$Acciones->buscarPorId($refacciones);

$lstCamiones = $Camiones->activosDisponiblesPorTipo();

$lstAcoplados = $Acoplado->activosDisponiblesPorTipo();

$Personal = new Conductores();
if ($refacciones == 1) {
  $lstConductores = $Personal->traerDisponibles();
} else {
  $lstConductores = $Personal->getPersonal()->traerTodosFilter(['refcargos'=>5]);
}

//die(var_dump($lstConductores));

?>



        <form class="formulario frmNuevo" role="form" id="sign_in">
          <div class="text-center"><h1><?php echo strtoupper($Acciones->getAccion()); ?></h1></div>
          <div class="row">
            <div class="col-4">
              <div class="form-group">
              <label for="refcamiones" class="control-label">Flota</label>
                <select class="form-control" id="refcamiones" name="refcamiones">
                <option value="" data-documentacion="2">-- Seleccionar --</option>
                  <?php 
                  $nodisponible = '';
                  // va a depender si cargo una salida o una entrada
                  if ($refacciones == 1) {
                    foreach ($lstCamiones as $row) { 
                      if (($row['entaller']==1) || ($row['ensalida']>0)) {
                        $nodisponible .= ' '.$row['patente'].' ,';
                      } else {
                    ?>
                    <option data-documentacion="<?php echo $row['documentacionvencida']; ?>" value="<?php echo $row['refcamiones']; ?>"><?php echo $row['patente']; ?></option>
                    <?php 
                      }
                    } 
                  } else {
                    foreach ($lstCamiones as $row) { 
                      $select = '';
                      if ($idCamiones == $row['refcamiones']) {
                        $select = 'selected';
                      } else {
                        $select = '';
                      }
                      if (($row['entaller'] ==0) && ($row['ensalida']>0)) {
                  ?>
                    <option <?php echo $select; ?> data-documentacion="<?php echo $row['documentacionvencida']; ?>" value="<?php echo $row['refcamiones']; ?>"><?php echo $row['patente']; ?></option>
                  <?php 
                      }
                    } 
                  } 
                  ?>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
              <label for="reftiposervicios" class="control-label">Tipo de Servicio</label>
                <select class="form-control" id="reftiposervicios" name="reftiposervicios">
                  <?php 
                    foreach ($TipoServicios->traerTodos() as $row) { 
                    ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['tiposervicio']; ?></option>
                  <?php 
                  } 
                  ?>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
              <label for="refacoplados" class="control-label">Acoplado</label>
                <select class="form-control" id="refacoplados" name="refacoplados" require>
                  <?php 
                  
                  $nodisponibleacoplado = '';
                  // va a depender si cargo una salida o una entrada
                  if ($refacciones == 1) {
                    foreach ($lstAcoplados as $row) { 
                      if ($row['ensalidaacoplado']>0) {
                        $nodisponibleacoplado .= ' '.$row['patente'].' ,';
                      } else {
                    ?>
                    <option data-documentacion="<?php echo $row['documentacionvencida']; ?>" value="<?php echo $row['refcamiones']; ?>"><?php echo $row['patente']; ?></option>
                    <?php 
                      }
                    } 
                  } else {
                    foreach ($lstAcoplados as $row) { 
                      if (($row['entaller'] ==0) && ($row['ensalidaacoplado']>0)) {
                  ?>
                    <option data-documentacion="<?php echo $row['documentacionvencida']; ?>" value="<?php echo $row['refcamiones']; ?>"><?php echo $row['patente']; ?></option>
                  <?php 
                      }
                    } 
                  } 
                  ?>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row">

            <div class="col-3">
              <label for="fecha" class="control-label">Fecha</label>
              <div class="form-group">
                <input class="form-control" type="datetime-local" value="<?php echo date('Y-m-d H:i:s'); ?>" id="fecha" name="fecha" required>
              </div>
            </div>

            <div class="col-3">
              <label for="km" class="control-label">Km</label>
              <div class="form-group">
                <input type="number" class="form-control" id="km" name="km" placeholder="km" required>
              </div>
            </div>

            <div class="col-3 contLitros">
              <label for="litros" class="control-label">Litros</label>
              <div class="form-group">
                <input type="number" class="form-control" id="litros" name="litros" placeholder="litros" required>
              </div>
            </div>
            <?php if ($refacciones == 1) { ?>
            <div class="col-3 contMtrs3">
              <label for="mtrscubicos" class="control-label">Mtrs Cubicos</label>
              <div class="form-group">
                <input type="number" class="form-control" id="mtrscubicos" name="mtrscubicos" placeholder="mtrs cubicos" required>
              </div>
            </div>
            <?php } else { ?>
              <input type="hidden" class="form-control" id="mtrscubicos" name="mtrscubicos" value="0" placeholder="mtrs cubicos">
            <?php } ?>

          </div>

          <div class="row">
          <?php if ($refacciones == 1) { ?>
            <div class="col-md-6">
              <label for="destino" class="control-label">Destino</label>
              <div class="form-group">
                <input type="text" class="form-control" id="destino" name="destino" placeholder="destino" required>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <div class="fcheck2">
                  <input class="form-check-input2" type="checkbox" value="" id="documentacion" name="documentacion">
                  <label class="custom-control-label" for="documentacion">Documentacion</label>
                  
                </div>
                <span class="text-white bg-danger alertDocumentacion"></span>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <div class="fcheck2">
                  <input class="form-check-input2" type="checkbox" value="" id="checklist" name="checklist">
                  <label class="custom-control-label" for="checklist">Check-List</label>
                </div>
              </div>
            </div>
            <?php } else { ?>
              <div class="col-md-6">
                <label for="destino" class="control-label">Destino</label>
                <div class="form-group">
                  <input type="text" class="form-control" id="destino" name="destino" placeholder="destino" required readonly>
                </div>
              </div>
              <input type="hidden" class="form-control" id="documentacion" name="documentacion" value="0">
              <input type="hidden" class="form-control" id="checklist" name="checklist" value="0">
            <?php } ?>

            <input type="hidden" id="refporterias" name="refporterias" value="<?php echo $refporterias; ?>"/>
            <input type="hidden" id="refacciones" name="refacciones" value="<?php echo $refacciones; ?>"/>


          </div>
          <div class="row">

            <div class="col-6">
              <div class="form-group">
                <label for="fecha" class="control-label">Conductor</label>
                <select class="form-control" id="refconductor" name="refconductor">
                  <?php foreach ($lstConductores as $row) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['primerapellido'].' '.$row['segundoapellido'].' '.$row['nombres']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-6" style="margin-top: 50px;">
              <button type="button" class="btn bg-gradient-info btn-lg" data-bs-toggle="modal" data-bs-target="#lgmPasajeros">
                PASAJEROS
              </button>
            </div>
            <?php require('../../forms/'.$ruta.'/pasajeros.php'); ?>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-lg bg-gradient-secondary btnVolver">Volver</button>
              <button type="submit" class="btn btn-lg bg-gradient-success nuevo">Guardar</button>
          </div>
        </form>

