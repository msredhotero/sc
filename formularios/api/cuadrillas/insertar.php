<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Cuadrillas();

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
$refusuarios = $_POST['refusuarios'];
if (isset($_POST['asignado'])) {
  $asignado = '1';
} else {
  $asignado = '0';
}

/* fin de variables del post */

/* seteo del objeto */
$entity->setRefordenestrabajocabecera($refordenestrabajocabecera);
$entity->setRefusuarios($refusuarios);
$entity->setAsignado($asignado);

if ($asignado=='1') {
  $entity->resetAsigandos();
}

/* fin del seteo */
if ($_POST['refusuarios'] == 0) {
  $resV['mensaje'] = $Globales::ERROR_INSERT;
  $resV['error'] = true;
} else {
  if (($entity->existeAsignadoPorOrden()==1) && ($asignado == '1')) {
    $resV['mensaje'] = $Globales::ERROR_CUADRILLAS_VALIDAR_ASIGNADO;
    $resV['error'] = true;
  } else {
    if ($entity->save()) {
      $resV['mensaje'] = $Globales::SUCCESS_INSERT;
      $resV['error'] = false;
    } else {
        $resV['mensaje'] = $Globales::ERROR_INSERT;
        $resV['error'] = true;
    }
  }
  
}


header('Content-type: application/json');
echo json_encode($resV);


?>

