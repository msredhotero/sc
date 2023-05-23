<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Respuestascuestionario();

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
$refpreguntascuestionario = $_POST['refpreguntascuestionario'];
$respuesta = $_POST['respuesta'];
$orden = $_POST['orden'];
$inhabilita = '0';
$leyenda = $_POST['leyenda'];
if (isset($_POST['activo'])) {
  $activo = '1';
} else {
  $activo = '0';
}

/* fin de variables del post */

/* seteo del objeto */
$entity->setRefpreguntascuestionario($refpreguntascuestionario);
$entity->setRespuesta($respuesta);
$entity->setOrden($orden);
$entity->setActivo($activo);
$entity->setInhabilita($inhabilita);
$entity->setLeyenda($leyenda);
/* fin del seteo */

if ($entity->save()) {
    $resV['mensaje'] = $Globales::SUCCESS_INSERT;
    $resV['error'] = false;
} else {
    $resV['mensaje'] = $Globales::ERROR_INSERT;
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

