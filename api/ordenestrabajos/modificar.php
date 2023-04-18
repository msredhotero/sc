<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Ordenestrabajos(0);


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
$refcamiones = $_POST['refcamiones'];
$reftareas = $_POST['reftareas'];
$refestados = $_POST['refestados'];
$fechainicio = $_POST['fechainicio'];
$fechafin = $_POST['fechafin'];
$fecharealfinalizacion = $_POST['fecharealfinalizacion'];
$observacion = $_POST['observacion'];

/* fin de variables del post */

/* seteo del objeto */

$entity->setRefcamiones($refcamiones);
$entity->setReftareas($reftareas);
$entity->setRefestados($refestados);
$entity->setFechainicio($fechainicio);
$entity->setFechafin($fechafin);
$entity->setFecharealfinalizacion($fecharealfinalizacion);
$entity->setObservacion($observacion);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('refcamiones'=>$refcamiones,'reftareas'=>$reftareas,'refestados'=>$refestados,'fechainicio'=>$fechainicio,'fechafin'=>$fechafin,'fecharealfinalizacion'=>$fecharealfinalizacion,'observacion'=>$observacion));
    
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

