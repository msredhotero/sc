<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');





/* fin de los permisos */

/* variables del post */

$id = $_POST['id'];

/* fin de variables del post */

/* seteo del objeto */

$entity = new Tablas($id);

/* fin del seteo */


if ($entity != null) {
  $resV['datos'] = $entity->devolverColumnasHTML();
  $resV['error'] = false;
} else {
  $resV['datos'] ='';
  $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

