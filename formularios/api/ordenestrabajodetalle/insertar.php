<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Ordenestrabajodetalle($_SESSION['user']->getUsername(),$_POST['refordenestrabajocabecera']);

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
$refordenestrabajocabecera = $_POST['refordenestrabajocabecera'];
$reftareas = $_POST['reftareas'];
$refestados = $_POST['refestados'];
$fechamodi = date('Y-m-d H:i:s');
$observaciones = $_POST['observaciones'];

/* fin de variables del post */

/* seteo del objeto */
$entity->setRefordenestrabajocabecera($refordenestrabajocabecera);
$entity->setReftareas($reftareas);
$entity->setRefestados($refestados);
$entity->setFechamodi($fechamodi);
$entity->setObservaciones($observaciones);
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

