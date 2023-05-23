<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Proveedores();


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* fin de los permisos */

/* variables del post */

$id = $_POST['idmodificar'];
$proveedor = $_POST['proveedor'];
$direccion = $_POST['direccion'];
$movil = $_POST['movil'];


/* fin de variables del post */

/* seteo del objeto */

$entity->setProveedor($proveedor);
$entity->setDireccion($direccion);
$entity->setMovil($movil);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('proveedor'=>$proveedor,'direccion'=>$direccion,'movil'=>$movil));
    
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

