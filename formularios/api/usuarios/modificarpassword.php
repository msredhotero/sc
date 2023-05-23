<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* fin de los permisos */

/* variables del post */

$id = $_POST['idmodificarPassword'];
$password = $_POST['password'];


/* fin de variables del post */
$entity = new Usuarios('','');
/* seteo del objeto */

$entity->setPassword($password);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarPassword();
    
    if ($entity->getError() == 0) {
        $resV['mensaje'] = $Globales::SUCCESS_MODIFICAR_PASSWORD;
        $resV['error'] = false;
    } else {
        $resV['mensaje'] = $Globales::ERROR_MODIFICAR_PASSWORD;
        $resV['error'] = true;
    }
} else {
    $resV['mensaje'] = $Globales::ERROR_MODIFICAR_PASSWORD;
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

