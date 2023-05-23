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

$id = $_POST['idmodificar'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$username = $_POST['username'];

$email = $_POST['email'];
$refroles = (int)$_POST['refroles'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$refcargos = (int)$_POST['refcargos'];
$refzonas = (int)$_POST['refzonas'];
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
$actualizacion_gps = $_POST['actualizacion_gps'];

/* fin de variables del post */
$entity = new Usuarios($email,'');
/* seteo del objeto */

$entity->setUsername($username);
$entity->setNombre($nombre);
$entity->setApellido($apellido);
$entity->setRefroles($refroles);
$entity->setEmail($email);
$entity->setActivo($activo);
$entity->setValidoemail($validoemail);

$entity->setDireccion($direccion);
$entity->setTelefono($telefono);
$entity->setRefcargos($refcargos);
$entity->setRefzonas($refzonas);
$entity->setActualizacion_gps($actualizacion_gps);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('username'=>$username,'nombre'=>$nombre,'apellido'=>$apellido,'refroles'=>$refroles,'email'=>$email,'activo'=>$activo,'validoemail'=>$validoemail,'direccion'=>$direccion,'telefono'=>$telefono,'refcargos'=>$refcargos,'refzonas'=>$refzonas,'actualizacion_gps'=>$actualizacion_gps));
    
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

