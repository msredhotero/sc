<?php

spl_autoload_register(function($clase){
    include_once "../../includes/" .$clase. ".php";        
  });

  $Respuestascuestionario = new Respuestascuestionario();
/*
Preguntascuestionario
Formularios
*/
$lstPreguntas = $Preguntascuestionario->traerTodosFilter(array('refformularios'=> $refformularios));
$materiales = new Materiales();

$lstMateriales = $materiales->traerTodos();

//die(var_dump($lstPreguntas));
?>

<form class="formulario frmNuevo needs-validation" role="form" id="sign_in" novalidate>
        
     
        <div class="row mb-3 tema<?php echo $primero; ?>" style="<?php echo $escondidoTema; ?>">
            <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <h5>Formulario: <?php echo $Formularios->getFormulario(); ?></h5>
                                
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <?php 
                        $required = '';
                        $escondidoPregunta = '';
                        $activarPregunta = '';
                        $activarId = '';
                        $marcaRespuesta = '';
                        $desactivarRespuestaId = '';
                        $i=0;
                        
                        foreach ($lstPreguntas as $rowpreguntas) { 
                            $i+=1;
                            //verifico si debo activar el aparecer

                            $lstRespuestas = $Respuestascuestionario->traerTodosFilter(array('refpreguntascuestionario'=>$rowpreguntas['id'] ));


                            echo "<div ".$escondidoPregunta.">";
                            //imprimo la pregunta x) xxxxx.
                            echo $i.') '.$rowpreguntas['pregunta'];
                            
                            if ($rowpreguntas['obligatoria']=='1') {
                                $required = 'required';
                            } else {
                                $required = '';
                            }
                            
                            // respuesta donde se carga escribiendo
                            if ($rowpreguntas['reftiporespuesta'] == 1) {
                                echo '<textarea '.$required.' class="form-control" rows="3" name="pregunta'.$rowpreguntas['id'].'" id="pregunta'.$rowpreguntas['id'].'"></textarea>';
                            }

                            // respuesta select simple
                            if ($rowpreguntas['reftiporespuesta'] == 2) {
                                if (count($lstRespuestas)>1) {
                                    foreach ($lstRespuestas as $rowrespuestas) {
                                        

                                        echo '<div class="form-check mb-3 mt-3">
                                        <input class="form-check-input '.$activarPregunta.' '.$marcaRespuesta.' " '.$activarId.' '.$desactivarRespuestaId.' type="radio" value="'.$rowrespuestas['id'].'" name="pregunta'.$rowpreguntas['id'].'" id="customRadio'.$rowrespuestas['id'].'" '.$required.'>
                                        <label class="custom-control-label" for="customRadio'.$rowrespuestas['id'].'">'.$rowrespuestas['respuesta'].'</label>
                                        <div class="invalid-feedback">Debe elegir alguna respuesta</div>
                                    </div>';
                                    }
                                }
                            }

                            // respuesta select multiple
                            if ($rowpreguntas['reftiporespuesta'] == 3) {
                                if (count($lstRespuestas)>1) {
                                    foreach ($lstRespuestas as $rowrespuestas) {
                                        

                                        echo '<div class="form-check mb-3 mt-3">
                                        <input class="form-check-input respuestavarias '.$activarPregunta.' '.$marcaRespuesta.' " '.$activarId.' '.$desactivarRespuestaId.' type="checkbox" value="'.$rowrespuestas['id'].'" name="pregunta'.$rowrespuestas['id'].'" id="customRadio'.$rowrespuestas['id'].'" >
                                        <label class="custom-control-label" for="customRadio'.$rowrespuestas['id'].'">'.$rowrespuestas['respuesta'].'</label>
                                        <div class="invalid-feedback">Debe elegir alguna respuesta</div>
                                    </div>';
                                    }
                                }
                            }


                            // respuesta archivo
                            if ($rowpreguntas['reftiporespuesta'] == 4) {
                                echo '<input type="file" '.$required.' class="form-control" name="pregunta'.$rowpreguntas['id'].'" id="pregunta'.$rowpreguntas['id'].'">';
                            }

                            // respuesta Geolocalizacion
                            if ($rowpreguntas['reftiporespuesta'] == 5) {
                                echo '<div class="col-6">
                                    <label for="latitud" class="control-label">Latitud</label>
                                    <div class="form-group">
                                    <input type="text" class="form-control" id="latitud" name="latitud" placeholder="latitud"  '.$required.'>
                                    </div>
                                </div>
                    
                                <div class="col-6">
                                    <label for="longitud" class="control-label">Longitud</label>
                                    <div class="form-group">
                                    <input type="text" class="form-control" id="longitud" name="longitud" placeholder="longitud"  '.$required.'>
                                    </div>
                                </div>
                    
                                <div class="col-12">
                                    <div class="row" id="contMapa2" style="margin-left:25px; margin-right:25px;">
                                        <div id="map" ></div>
                                    </div>
                                </div>';
                            }

                            // respuesta Firma
                            if ($rowpreguntas['reftiporespuesta'] == 6) {
                                echo '
                                
                                <canvas id="canvas"></canvas>
                                <button type="button" class="btn bg-gradient-secondary" id="btnLimpiar">Limpiar</button>
                                <button type="button" class="btn bg-gradient-warning" id="btnDescargar">Descargar</button>
                                <h5>Descargar y Subir archivo firmado</5>
                                <input type="file" '.$required.' class="form-control firma" name="pregunta'.$rowpreguntas['id'].'" id="pregunta'.$rowpreguntas['id'].'"  '.$required.'>
                                
                                <script>
                                    if (window.opener) {
                                        document.querySelector("#firma").src = window.opener.obtenerImagen();
                                        // Imprimir documento. Si no quieres imprimir, remueve la siguiente línea
                                        window.print();
                                    }
                                </script>
                                ';
                            }

                            $cadPresupuesto = '';
                            // respuesta select simple
                            if ($rowpreguntas['reftiporespuesta'] == 7) {
                                $cadPresupuesto = '<div class="row"><div class="col-6">
                                <label for="materiales" class="control-label">Materiales</label>
                                    <div class="form-group">
                                        <select name="refmateriales1" id="refmateriales1" class="form-control">';
                                foreach ($lstMateriales as $rowM) {
                                    $cadPresupuesto .= '<option value="'.$rowM['id'].'">'.$rowM['material'].'</option>';
                                }
                                $cadPresupuesto .= '</select>
                                    </div>
                                </div>';

                                $cadPresupuesto .= '<div class="col-6">
                                <label for="cantidad" class="control-label">Cantidad</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cantidad1" name="cantidad1" placeholder="Cantidad" >
                                    </div>
                                </div></div><br>';

                                echo $cadPresupuesto;
                            }

                            
                            // respuesta select simple
                            if ($rowpreguntas['reftiporespuesta'] == 8) {
                                
                                $tablas = new Tablas($rowpreguntas['reftabladatos']);
                                $tablas->setIdreferencia(2); // le seteo el dos porque este lugar no llega a las solicitudes
                                $tablas->setColumna($rowpreguntas['columna']);

                                $lstDato = $tablas->devolverValor();

                                foreach ($lstDato as $dato) {
                                    echo '<div class="col-6">
                                <label for="cantidad" class="control-label">'.$tablas->getArSolicitudes($rowpreguntas['columna']).': '.$dato[0].'</label>
                                </div>';
                                }
                                

                                
                            }

                            
                            
                            
                        ?>
         
                        <?php 
                            echo "</div>";
                        ?>
                                
                        <?php 
                        } 
                        ?>
                        
                    </div>
                </div>
            </div>
            
        </div>
        <input type="hidden" name="reftabla" id="reftabla" value="<?php echo $reftabla; ?>"/>
        <input type="hidden" name="idreferencia" id="idreferencia" value="<?php echo $idreferencia; ?>"/>
        <input type="hidden" name="refformulariosconector" id="refformulariosconector" value="<?php echo $refformulariosconector; ?>"/>
        <input type="hidden" name="refformularios" id="refformularios" value="<?php echo $refformularios; ?>"/>
        <div class="modal-footer">
            <button type="submit" class="btn bg-gradient-success btnPreguardar">Finalizar</button>
        </div>
        </form>
