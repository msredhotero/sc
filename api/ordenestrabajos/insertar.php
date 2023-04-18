<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Ordenestrabajos(0);

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
$refcamiones = $_POST['refcamiones'];
$reftareas = $_POST['reftareas'];
$refestados = $_POST['refestados'];
$fechainicio = $_POST['fechainicio'];
$fechafin = $_POST['fechafin'];
$fecharealfinalizacion = $_POST['fecharealfinalizacion'];
$usuariocrea = $_SESSION['user']->getUsername();
$observacion = $_POST['observacion'];

/* fin de variables del post */

/* seteo del objeto */
$entity->setRefcamiones($refcamiones);
$entity->setReftareas($reftareas);
$entity->setRefestados($refestados);
$entity->setFechainicio($fechainicio);
$entity->setFechafin($fechafin);
$entity->setFecharealfinalizacion($fecharealfinalizacion);
$entity->setIndice(0);
$entity->setUsuariocrea($usuariocrea);
$entity->setObservacion($observacion);
$entity->setArchivo('');
$entity->setType('');
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

