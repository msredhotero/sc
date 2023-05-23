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

$id = $_POST['idmodificar'];
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
$entity->setTelefono($telefono);
$entity->setEmail($email);
$entity->setLatitud($latitud);
$entity->setLongitud($longitud);
$entity->setCodpostal($codpostal);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('nombre'=>$nombre,'direccion'=>$direccion,'cuit'=>$cuit,'contacto'=>$contacto,'telefono'=>$telefono,'email'=>$email,'latitud'=>$latitud,'longitud'=>$longitud,'codpostal'=>$codpostal));
    
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

