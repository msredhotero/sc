<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Presupuestos();

/* verificar permisos */

/*
if (!($Session->exists())) {
  header('Location: ../../error.php');
}



if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}
*/
/* fin de los permisos */

/* variables del post */

$id = $_POST['idmodificar'];
$refordenestrabajodetalle = $_POST['refordenestrabajodetalle'];
$refmateriales = $_POST['refmateriales'];
$cantidad = $_POST['cantidad'];

/* fin de variables del post */

/* seteo del objeto */
$entity->setRefordenestrabajodetalle($refordenestrabajodetalle);
$entity->setRefmateriales($refmateriales);
$entity->setCantidad($cantidad);

$entity->buscarPorId($id);


/* fin del seteo */


if ($entity != null) {

    $entity->modificarFilter(array('refordenestrabajodetalle'=>$refordenestrabajodetalle,'refmateriales'=>$refmateriales,'cantidad'=>$cantidad));
    
    if ($entity->getError() == 0) {
        $resV['mensaje'] = $Globales::SUCCESS_INSERT;
        $resV['error'] = false;
    } else {
        $resV['mensaje'] = $Globales::ERROR_INSERT;
        $resV['error'] = true;
    }
  
    
} else {
    $resV['mensaje'] = $Globales::ERROR_INSERT;
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

