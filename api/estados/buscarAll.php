<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Estados();


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

/* fin de los permisos */

/* variables del post */

$id = $_POST['id'];

/* fin de variables del post */

/* seteo del objeto */
$entity->setId($id);
$entity->setTipo($_SESSION['user']->getRefroles());

$lst = $entity->nivelesByTipo();

/* fin del seteo */


if ($entity != null) {
  $resV['datos'] = $lst;
  $resV['error'] = false;
} else {
  $resV['datos'] ='';
  $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

