<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$usaurio = $_POST['usuario'];
$entity = new Formulariosdetalles($usaurio);
$preguntas = new Preguntascuestionario();

$resV['error'] = '';
$resV['mensaje'] = '';


/* variables del post */
$refformulariosconector = $_POST['refformulariosconector'];
$reftabla = $_POST['reftabla'];
$idreferencia = $_POST['idreferencia'];
$refpreguntascuestionario = $_POST['refpreguntascuestionario'];

$preguntas->buscarPorId($refpreguntascuestionario);

$refrespuestascuestionario = $_POST['refrespuestascuestionario'];
$pregunta = $preguntas->getPregunta();
$respuesta = $_POST['respuesta'];
$reftiporespuesta = $preguntas->getReftiporespuesta();
$archivo = $_POST['archivo'];
$tipo = $_POST['tipo'];
$carpeta = $_POST['carpeta'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$fechacrea = date('Y-m-d H:i:s');

  // recorro el array de respuestas

if ($reftiporespuesta == 8) {
  $refmateriales  = 0;
  $cantidad       = 0;
  $reftabladatos  = $_POST['reftabladatos'];
  $columna        = $_POST['columna'];
} else {
  $refmateriales  = 0;
  $cantidad       = 0;
  $reftabladatos  = 0;
  $columna        = '';
}


//die(var_dump(json_decode($respuesta)));

/* fin de variables del post */

/* seteo del objeto */
$entity->setRefformulariosconector($refformulariosconector);
$entity->setReftabla($reftabla);
$entity->setIdreferencia($idreferencia);
$entity->setRefpreguntascuestionario($refpreguntascuestionario);
$entity->setPregunta($pregunta);
$entity->setRefrespuestascuestionario($refrespuestascuestionario);
$entity->setRespuesta($respuesta);
$entity->setReftiporespuesta($reftiporespuesta);
$entity->setArchivo($archivo);
$entity->setTipo($tipo);
$entity->setCarpeta($carpeta);
$entity->setLatitud($latitud);
$entity->setLongitud($longitud);
$entity->setFechacrea($fechacrea);

$entity->setReftabladatos($reftabladatos);
$entity->setColumna($columna);

/* fin del seteo */

// tipo de respuesta presupuesto
if ($reftiporespuesta == 7) {
  //primero seteo respuesta con vacio para no insertar un array
  $entity->setRespuesta('');

  // deberia de controlar de otra manera este insert masivo
  $ar = explode(',',$_POST['respuesta']);
  //die(var_dump($ar));
  $i=1;
  foreach ($ar as $rowR) {
    //die(var_dump($rowR->id));
    if ($i==1) {
      $entity->setRefmateriales($rowR);
      $i+=1;
    } else {
      $entity->setCantidad($rowR);
      $entity->save();
      $i=1;
    }

  }

  $resV['mensaje'] = $Globales::SUCCESS_INSERT;
  $resV['error'] = false;

  

  
} else {
  $entity->setRefmateriales($refmateriales);
  $entity->setCantidad($cantidad);

  if ($entity->save()) {
    $resV['mensaje'] = $Globales::SUCCESS_INSERT;
    $resV['error'] = false;

    
  } else {
      $resV['mensaje'] = $Globales::ERROR_INSERT;
      $resV['error'] = true;
  }
}

if ($resV['error'] == false) {

  //si cargo el primer formulario, pongo en asignado la orden de trabajo
  $otd = new Ordenestrabajodetalle($usaurio,0);
  $otd->buscarPorId($idreferencia);
  $ot = new Ordenestrabajocabecera();
  $ot->buscarPorId($otd->getRefordenestrabajocabecera());


  // cambio el estado de la tarea a finalizado si todas las preguntas fueron respondidas
  if ($entity->puedeFinalizarFormulario()) {
    if ($ot->getId()>0) {
      //5 estado iniciado
      $ot->modificarFilter(['refestados'=>3]);
    }
  } else {
    if ($ot->getId()>0) {
      //5 estado iniciado
      $ot->modificarFilter(['refestados'=>5]);
    }
  }

  // finalizo la ot, si todas las tareas fueron finalizadas

}


header('Content-type: application/json');
echo json_encode($resV);


?>

