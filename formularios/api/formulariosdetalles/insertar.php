<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');

$Preguntascuestionario = new Preguntascuestionario();
$Respuestascuestionario = new Respuestascuestionario();

$resV['error'] = '';
$resV['mensaje'] = '';

if (!($Session->exists())) {
  header('Location: ../../error.php');
}

//verificar permisos

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* variables del post */
$refformulariosconector = $_POST['refformulariosconector'];
$reftabla = $_POST['reftabla'];
$idreferencia = $_POST['idreferencia'];
$refformularios = $_POST['refformularios'];

/* fin de variables del post */

/* seteo del objeto */
$lstPreguntas = $Preguntascuestionario->traerTodosFilter(array('refformularios'=> $refformularios));

$required = '';
$escondidoPregunta = '';
$activarPregunta = '';
$activarId = '';
$marcaRespuesta = '';
$desactivarRespuestaId = '';
$i=0;

$primero = 0;

$ar = array();
$error = 0;
$respuestacliente = '';


foreach ($lstPreguntas as $rowpreguntas) { 
  $i+=1;
  //verifico si debo activar el aparecer

  

  $lstRespuestas = $Respuestascuestionario->traerTodosFilter(array('refpreguntas'=>$rowpreguntas['id'] ));

  $Respuestas = new Respuestascuestionario();

  if ($rowpreguntas['obligatoria']=='1') {
    $required = 'obligatoria';
  } else {
    $required = '';
  }

  //pregunta de texto
  if ($rowpreguntas['reftiporespuesta'] == 1) {
    if (isset($_POST['pregunta'.$rowpreguntas['id']])) {

      $entity = new Formulariosdetalles('marcos');
      
      $entity->setReftabla($reftabla);
      $entity->setIdreferencia($idreferencia);
      $entity->setRefformulariosconector($refformulariosconector);
      $entity->setRefpreguntascuestionario($rowpreguntas['id']);
      $entity->setRefrespuestascuestionario(0);
      $entity->setPregunta($rowpreguntas['pregunta']);
      $entity->setRespuesta($_POST['pregunta'.$rowpreguntas['id']]);
      $entity->setReftiporespuesta($rowpreguntas['reftiporespuesta']);
      $entity->setArchivo('');
      $entity->setTipo('');
      $entity->setCarpeta('');
      $entity->setLatitud('');
      $entity->setLongitud('');
      $entity->setFechacrea(date('Y-m-d H:i:s'));

      
    } else {
      if ($required != '') {
        array_push($ar, array('pregunta'=>$rowpreguntas['pregunta'], 'error'=> 'Debe Responder la pregunta'));
        $error = 1;
      }
      
    }
    
  }

  // pregunta respuesta unica
  if ($rowpreguntas['reftiporespuesta'] == 2) {
    if (isset($_POST['pregunta'.$rowpreguntas['id']])) {

      $Respuestas->buscarPorId($_POST['pregunta'.$rowpreguntas['id']]);

      $entity = new Formulariosdetalles('marcos');
      
      $entity->setReftabla($reftabla);
      $entity->setIdreferencia($idreferencia);
      $entity->setRefformulariosconector($refformulariosconector);
      $entity->setRefpreguntascuestionario($rowpreguntas['id']);
      $entity->setRefrespuestascuestionario($_POST['pregunta'.$rowpreguntas['id']]);
      $entity->setPregunta($rowpreguntas['pregunta']);
      $entity->setRespuesta($Respuestas->getRespuesta());
      $entity->setReftiporespuesta($rowpreguntas['reftiporespuesta']);
      $entity->setArchivo('');
      $entity->setTipo('');
      $entity->setCarpeta('');
      $entity->setLatitud('');
      $entity->setLongitud('');
      $entity->setFechacrea(date('Y-m-d H:i:s'));

    } else {
      if ($required != '') {
        array_push($ar, array('pregunta'=>$rowpreguntas['pregunta'], 'error'=> 'Debe Responder la pregunta'));
        $error = 1;
      }
    }
    
  }

  //pregunta varias respuestas
  if ($rowpreguntas['reftiporespuesta'] == 3) {
    foreach ($lstRespuestas as $rowR) {
      if (isset($_POST['pregunta'.$rowR['id']])) {

        $Respuestas->buscarPorId($_POST['pregunta'.$rowR['id']]);
  
        $entity = new Formulariosdetalles('marcos');
        
        $entity->setReftabla($reftabla);
        $entity->setIdreferencia($idreferencia);
        $entity->setRefformulariosconector($refformulariosconector);
        $entity->setRefpreguntascuestionario($rowpreguntas['id']);
        $entity->setRefrespuestascuestionario(0);
        $entity->setPregunta($rowpreguntas['pregunta']);
        $entity->setRespuesta($Respuestas->getRespuesta());
        $entity->setReftiporespuesta($rowpreguntas['reftiporespuesta']);
        $entity->setArchivo('');
        $entity->setTipo('');
        $entity->setCarpeta('');
        $entity->setLatitud('');
        $entity->setLongitud('');
        $entity->setFechacrea(date('Y-m-d H:i:s'));
  
      } 
    } 
  } 



  //pregunta  con subida de archivos
  if (($rowpreguntas['reftiporespuesta'] == 4) || ($rowpreguntas['reftiporespuesta'] == 6)) {
    $entity = new Formulariosdetalles('marcos');
    if (isset($_FILES['pregunta'.$rowpreguntas['id']])) {


      $entity->storeImage($_FILES['pregunta'.$rowpreguntas['id']],'');
      
      $entity->setReftabla($reftabla);
      $entity->setIdreferencia($idreferencia);
      $entity->setRefformulariosconector($refformulariosconector);
      $entity->setRefpreguntascuestionario($rowpreguntas['id']);
      $entity->setRefrespuestascuestionario(0);
      $entity->setPregunta($rowpreguntas['pregunta']);
      //$entity->setRespuesta($_POST['pregunta'.$rowpreguntas['id']]);
      $entity->setReftiporespuesta($rowpreguntas['reftiporespuesta']);
      $entity->setArchivo(file_get_contents('../../data/ot/'.$entity->getRespuesta()));
      //$entity->setTipo('');
      //$entity->setCarpeta('');
      $entity->setLatitud('');
      $entity->setLongitud('');
      $entity->setFechacrea(date('Y-m-d H:i:s'));

    } else {
      if ($required != '') {
        array_push($ar, array('pregunta'=>$rowpreguntas['pregunta'], 'error'=> 'Debe Responder la pregunta'));
        $error = 1;
      }
      
    }
  } 


  //pregunta de geolocalizacion
  if ($rowpreguntas['reftiporespuesta'] == 5) {
    $entity = new Formulariosdetalles('marcos');
    if ((isset($_POST['latitud'])) && (isset($_POST['longitud']))) {

      
      
      $entity->setReftabla($reftabla);
      $entity->setIdreferencia($idreferencia);
      $entity->setRefformulariosconector($refformulariosconector);
      $entity->setRefpreguntascuestionario($rowpreguntas['id']);
      $entity->setRefrespuestascuestionario(0);
      $entity->setPregunta($rowpreguntas['pregunta']);
      $entity->setRespuesta('');
      $entity->setReftiporespuesta($rowpreguntas['reftiporespuesta']);
      $entity->setArchivo('');
      $entity->setTipo('');
      $entity->setCarpeta('');
      $entity->setLatitud($_POST['latitud']);
      $entity->setLongitud($_POST['longitud']);
      $entity->setFechacrea(date('Y-m-d H:i:s'));

    } else {
      if ($required != '') {
        array_push($ar, array('pregunta'=>$rowpreguntas['pregunta'], 'error'=> 'Debe Responder la pregunta'));
        $error = 1;
      }
      
    }
  } 


  // verifico si el priimer detalle formulario fue creado bien
  if ($entity->getRefpreguntascuestionario()>0) {
    //guardo todo
    $entity->save();
  }


  unset($entity);
  unset($Respuestas);




}


/* fin del seteo */

if ($error == 0) {
    $resV['mensaje'] = $Globales::SUCCESS_INSERT;
    $resV['error'] = false;
} else {
    $resV['mensaje'] = $Globales::ERROR_INSERT;
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

