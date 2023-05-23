<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Sucursales($_POST['reftabla'],$_POST['idreferencia']);

$resV['error'] = '';
$resV['mensaje'] = '';

if (!($Session->exists())) {
  header('Location: ../../error.php');
}

//verificar permisos

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* variables del post */
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

/* fin del seteo */

if ($entity->save()) {
    $resV['mensaje'] = $Globales::SUCCESS_INSERT;
    $resV['error'] = false;
} else {
    $resV['mensaje'] = $Globales::ERROR_INSERT;
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

