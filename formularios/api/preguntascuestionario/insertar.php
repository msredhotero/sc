<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Preguntascuestionario();

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
$refformularios = $_POST['refformularios'];
$reftiporespuesta = $_POST['reftiporespuesta'];
$pregunta = $_POST['pregunta'];
$orden = $_POST['orden'];
$valor = 0;
$tiempo = 0;
$depende = 0;
$dependerespuesta = 0;
$leyenda = $_POST['leyenda'];

if ($reftiporespuesta == 8) {
  $reftabladatos = $_POST['reftabladatos'];
  $columna = $_POST['columna'];
} else {
  $reftabladatos = 0;
  $columna = '';
}



if (isset($_POST['activo'])) {
  $activo = '1';
} else {
  $activo = '0';
}
if (isset($_POST['obligatoria'])) {
  $obligatoria = '1';
} else {
  $obligatoria = '0';
}

// siempre no obligatorio
if ($reftiporespuesta == 8) {
  $obligatoria = '0';
}
/* fin de variables del post */

/* seteo del objeto */
$entity->setRefformularios($refformularios);
$entity->setReftiporespuesta($reftiporespuesta);
$entity->setPregunta($pregunta);
$entity->setOrden($orden);
$entity->setValor($valor);
$entity->setActivo($activo);
$entity->setTiempo($tiempo);
$entity->setObligatoria($obligatoria);
$entity->setDepende($depende);
$entity->setDependerespuesta($dependerespuesta);
$entity->setLeyenda($leyenda);
$entity->setReftabladatos($reftabladatos);
$entity->setColumna($columna);
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

