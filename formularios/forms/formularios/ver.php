


<div class="row mb-3">
            <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-12 col-12">

                                Formulario cargado
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
<?php

$preguntaValor = '';
foreach ($entity->traerPorReferencia() as $row) {
    if ($preguntaValor != $row['pregunta']) {
        $preguntaValor = $row['pregunta'];
        echo '<hr><h5>Pregunta: '.$row['pregunta'].'<h5>';
    }

    switch ($row['reftiporespuesta']) {
        case 1:
            echo '<h4>'.$row['respuesta'].'</h4>';
        break;
        case 2:
            echo '<h4>'.$row['respuesta'].'</h4>';
        break;
        case 3:
            echo '<h4>'.$row['respuesta'].'</h4>';
        break;
        case 4:
            switch ($row['tipo']) {
                case 'pdf':
                    echo '<td>' .
                    '<img src = "data:image/png;base64,' . base64_encode($row['archivo']) . '" width = "100%" height = "100%"/>'
                    . '</td>';
                break;
                default:
                    echo '<td>' .
                    '<img src = "data:image/png;base64,' . base64_encode($row['archivo']) . '" width = "100%" height = "100%"/>'
                    . '</td>';
                break;
            }
            
        break;
        case 5:
            echo '<h4>Latitud: '.$row['latitud'].'</h4>';
            echo '<h4>Longitud: '.$row['longitud'].'</h4>';
        break;

        case 6:
            echo '<td>' .
                    '<img src = "data:image/png;base64,' . base64_encode($row['archivo']) . '" width = "100%" height = "100%"/>'
                    . '</td>';
        break;
        case 7:
            // presupuesto cargado
        break;
        case 8:
            // dato de la solicitud de visita
            $tablas = new Tablas($row['reftabladatos']);
            $tablas->setIdreferencia($row['idreferencia']);
            $tablas->setColumna($row['columna']);

        break;
    }
}



?>

</div>
                </div>
            </div>
            
        </div>