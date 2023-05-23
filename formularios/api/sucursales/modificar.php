<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Sucursales($_POST['reftabla'],$_POST['idreferencia']);


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
$sucursal = $_POST['sucursal'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$codpostal = $_POST['codpostal'];
$refzonas = $_POST['refzonas'];
/* fin de variables del post */

/* seteo del objeto */

$entity->setSucursal($sucursal);
$entity->setLatitud($latitud);
$entity->setLongitud($longitud);
$entity->setDireccion($direccion);
$entity->setTelefono($telefono);
$entity->setCodpostal($codpostal);
$entity->setRefzonas($refzonas);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('sucursal'=>$sucursal,'latitud'=>$latitud,'longitud'=>$longitud,'direccion'=>$direccion,'telefono'=>$telefono,'codpostal'=>$codpostal,'refzonas'=>$refzonas));
    
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

