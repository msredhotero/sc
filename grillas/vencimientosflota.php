<?php

$CamionesAux = new Camiones();
$CamionesAux->setId($id);

$lst = $CamionesAux->traerVencimientos($convencimiento);

?>

<hr>
<div class="col-lg-12 col-md-6 mb-md-0 mb-4">

    <div class="table-responsive">
    <h6><?php if ($convencimiento==0) { ?>Vencimientos a 15 dias<?php } else { ?>Vencimientos<?php } ?></h6>
    <table id="example" class="table table-striped align-items-center mb-0 display " style="width:100%">
        <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Patente</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipo</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vencimiento</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dias Faltantes</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lst as $row) { ?>
        <tr>
            <td><?php echo $row['patente']; ?></td>
            <td><?php echo $row['tipo']; ?></td>
            <td><?php echo $row['vencimiento']; ?></td>
            <td><?php echo $row['faltandias']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
        
    </table>
    
    <div style="margin-bottom: 40px;"></div>
    
    </div>

</div>


      