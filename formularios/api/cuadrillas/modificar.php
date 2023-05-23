<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Cuadrillas();


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* fin de los permisos */

/* variables del post */

$id = $_POST['idmodificar'];
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

$entity->buscarPorId($id);

if ($asignado=='1') {
  $entity->resetAsigandos();
}

/* fin del seteo */


if ($entity != null) {
  if (($entity->existeAsignadoPorOrden()==1) && ($asignado == '1')) {
    $resV['mensaje'] = $Globales::ERROR_CUADRILLAS_VALIDAR_ASIGNADO;
    $resV['error'] = true;
  } else {
    $entity->modificarFilter(array('refordenestrabajocabecera'=>$refordenestrabajocabecera,'refusuarios'=>$refusuarios,'asignado'=>$asignado));
    
    if ($entity->getError() == 0) {
        $resV['mensaje'] = $Globales::SUCCESS_INSERT;
        $resV['error'] = false;
    } else {
        $resV['mensaje'] = $Globales::ERROR_INSERT;
        $resV['error'] = true;
    }
  }
    
} else {
    $resV['mensaje'] = $Globales::ERROR_INSERT;
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

