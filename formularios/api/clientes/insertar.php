<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Clientes();

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
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$cuit = $_POST['cuit'];
$contacto = $_POST['contacto'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$codpostal = $_POST['codpostal'];
/* fin de variables del post */

/* seteo del objeto */
$entity->setNombre($nombre);
$entity->setDireccion($direccion);
$entity->setCuit($cuit);
$entity->setContacto($contacto);
$entity->setEmail($email);
$entity->setLatitud($latitud);
$entity->setLongitud($longitud);
$entity->setCodpostal($codpostal);
$entity->setTelefono($telefono);
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

