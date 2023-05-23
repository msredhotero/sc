<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');


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
$username = $_POST['username'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$password = $_POST['password'];
$email = $_POST['email'];
$refroles = $_POST['refroles'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$refcargos = $_POST['refcargos'];
$refzonas = $_POST['refzonas'];
if (isset($_POST['activo'])) {
  $activo = '1';
} else {
  $activo = '0';
}
if (isset($_POST['validoemail'])) {
  $validoemail = '1';
} else {
  $validoemail = '0';
}


/* fin de variables del post */

$entity = new Usuarios($email,$password);

/* seteo del objeto */
$entity->setUsername($username);
$entity->setNombre($nombre);
$entity->setApellido($apellido);
$entity->setRefroles($refroles);
$entity->setEmail($email);
$entity->setNombre($nombre);
$entity->setApellido($apellido);
$entity->setActivo($activo);
$entity->setValidoemail($validoemail);

$entity->setDireccion($direccion);
$entity->setTelefono($telefono);
$entity->setRefcargos($refcargos);
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

