<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$entity = new Solicitudesvisitas('');


/* verificar permisos */


/* fin de los permisos */

/* variables del post */


/* fin de variables del post */

/* seteo del objeto */

/* fin del seteo */


if ($entity != null) {
    $resV['solicitudvisita']  = $entity->traerTodos();

    $resV['error'] = false;
} else {
    $resV['solicitudvisita'] ='';
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

