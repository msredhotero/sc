<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Porterias();
$conductores = new Conductores();


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* fin de los permisos */

/* variables del post */

$id = $_POST['id'];

/* fin de variables del post */

/* seteo del objeto */

$entity->buscarPorValor(array('refcamiones'=>$id,'refporterias'=>0));
$lstConductores = $conductores->traerTodosFilter(array('refporterias'=>$entity->getId()));

/* fin del seteo */


if ($entity != null) {
  $resV['datos'] = $entity->devolverArray();
  $resV['conductores'] = $lstConductores;
  $resV['error'] = false;
} else {
  $resV['datos'] ='';
  $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

