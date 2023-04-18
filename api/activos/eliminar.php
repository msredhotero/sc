<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Activos();


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* fin de los permisos */

/* variables del post */

$id = (int)$_POST['id'];

/* fin de variables del post */

/* seteo del objeto */

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity->getId() != null) {
  $entity->borrar();
  
  if ($entity->getError() == 0) {
      $resV['mensaje'] = $Globales::SUCCESS_ELIMINAR;
      $resV['error'] = false;
  } else {
      $resV['mensaje'] = $Globales::ERROR_ELIMINAR;
      $resV['error'] = true;
  }
} else {
  $resV['mensaje'] = $Globales::ERROR_ELIMINAR;
  $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

