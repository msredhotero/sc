<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Seguros($_POST['refcamiones']);

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
$refaseguradoras = $_POST['refaseguradoras'];
$nropoliza = $_POST['nropoliza'];
$vencimiento = $_POST['vencimiento'];
$rige = $_POST['rige'];
/* fin de variables del post */

/* seteo del objeto */
$entity->setRefaseguradoras($refaseguradoras);
$entity->setNropoliza($nropoliza);
$entity->setVencimiento($vencimiento);
$entity->setRige($rige);
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

