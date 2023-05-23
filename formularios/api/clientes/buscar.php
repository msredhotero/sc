<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Clientes();


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* fin de los permisos */

/* variables del post */

$tabla = $_POST['tabla'];
$id = $_POST['id'];

/* fin de variables del post */

/* seteo del objeto */

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
  $resV['datos'] = $entity->devolverArray();
  $resV['error'] = false;
} else {
  $resV['datos'] ='';
  $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

