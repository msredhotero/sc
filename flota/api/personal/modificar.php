<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Personal();


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

$nombres = $_POST['nombres'];
$primerapellido = $_POST['primerapellido'];
$segundoapellido = $_POST['segundoapellido'];
$rut = $_POST['rut'];
$email = $_POST['email'];
$movil = $_POST['movil'];
$refareas = $_POST['refareas'];
$refcargos = $_POST['refcargos'];
$fechaalta = $_POST['fechaalta'];
$fechabaja = $_POST['fechabaja'];

if (isset($_POST['activo'])) {
  $activo = '1';
} else {
  $activo = '0';
}

/* fin de variables del post */

/* seteo del objeto */

$entity->setNombres($nombres);
$entity->setPrimerapellido($primerapellido);
$entity->setSegundoapellido($segundoapellido);
$entity->setRut($rut);
$entity->setEmail($email);
$entity->setMovil($movil);
$entity->setRefareas($refareas);
$entity->setRefcargos($refcargos);
$entity->setFechaalta($fechaalta);
$entity->setFechabaja($fechabaja);
$entity->setActivo($activo);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('nombres'=>$nombres,'primerapellido'=>$primerapellido,'segundoapellido'=>$segundoapellido,'rut'=>$rut,'email'=>$email,'movil'=>$movil,'refareas'=>$refareas,'refcargos'=>$refcargos,'fechaalta'=>$fechaalta,'fechabaja'=>$fechabaja,'activo'=>$activo));
    
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

