<?php

$lst = $Mantenimientoflota->generarMantenimiento();

//die(var_dump($lst));

?>


<div class="col-lg-12 col-md-6 mb-md-0 mb-4">
  <div class="card">
    <div class="card-header pb-0">
      <div class="row">
        <div class="col-lg-6 col-7">
          <h6>Mantenciones Programadas Generadas</h6>
        </div>
        
      </div>
    </div>
    <div class="card-body px-2 pb-4">
      <div class="table-responsive">
        
        <table id="example" class="table align-items-center mb-0 display " style="width:100%">
            <thead>
                <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PATENTE Y COLOR</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">KILOMETRAJE</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">AREA</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">KM MANTENIMIENTO</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($lst as $row) { ?>
                <tr>
                    <td><?php echo $row[1]; ?></td>
                    <td><?php echo $row[2]; ?></td>
                    <td><?php echo $row[3]; ?></td>
                    <td><?php echo $row[4]; ?></td>
                </tr>
            <?php } ?>
            </tbody>
          

            
        </table>
        
        <div style="margin-bottom: 140px;"></div>
        
      </div>
    </div>
  </div>
</div>


      