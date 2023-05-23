<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Presupuestos();

$resV['error'] = '';
$resV['mensaje'] = '';

//verificar permisos

/*
if (!($Session->exists())) {
  header('Location: ../../error.php');
}



if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}
*/


/* variables del post */
$refordenestrabajodetalle = $_POST['refordenestrabajodetalle'];
$refmateriales = $_POST['refmateriales'];
$cantidad = $_POST['cantidad'];


/* fin de variables del post */

/* seteo del objeto */
$entity->setRefordenestrabajodetalle($refordenestrabajodetalle);
$entity->setRefmateriales($refmateriales);
$entity->setCantidad($cantidad);



/* fin del seteo */
if ($_POST['cantidad'] == 0) {
  $resV['mensaje'] = 'La cantidad no puede ser 0';
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


header('Content-type: application/json');
echo json_encode($resV);


?>

