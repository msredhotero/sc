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

$id = (int)$_POST['id'];

/* fin de variables del post */

/* seteo del objeto */

$entity->buscarPorId($id);

/* fin del seteo */

//si es el asignado, asigno al que sigue cuando lo borro
$reasignar = 0;
if ($entity->getAsignado()=='1') {
  $reasignar = 1;     
}


if ($entity->getId() != null) {
  $entity->borrar();
  
  if ($entity->getError() == 0) {
    $lst = $entity->traerTodosFilter(['refordenestrabajocabecera'=>$entity->getRefordenestrabajocabecera()]);

    $lstExisteAsignado = $entity->traerTodosFilter(['refordenestrabajocabecera'=>$entity->getRefordenestrabajocabecera(),'asignado'=>'1']);
    if (count($lstExisteAsignado)<1) {
      foreach ($lst as $row) {
        $entityM = new Cuadrillas();
        $entityM->buscarPorId($row['id']);
        $entityM->modificarFilter(array('asignado'=>'1'));
  
        break;
      }
    }
    
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

