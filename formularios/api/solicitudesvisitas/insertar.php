<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Solicitudesvisitas($_SESSION['user']->getUsername());

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
$refclientes = $_POST['refclientes'];
$refsucursales = $_POST['refsucursales'];
$fecha = $_POST['fecha'];
$fechacrea = date('Y-m-d H:i:s');
$refsemaforo = $_POST['refsemaforo'];
$descripcion = $_POST['descripcion'];
$refestados = $_POST['refestados'];
$reftipoactividades = $_POST['reftipoactividades'];
$refzonas = $_POST['refzonas'];
$nroaviso = $_POST['nroaviso'];
$claseaviso = $_POST['claseaviso'];
$autoraviso = $_POST['autoraviso'];

/* fin de variables del post */

/* seteo del objeto */
$entity->setRefclientes($refclientes);
$entity->setRefsucursales($refsucursales);
$entity->setFecha($fecha);
$entity->setFechacrea($fechacrea);
$entity->setRefsemaforo($refsemaforo);
$entity->setDescripcion($descripcion);
$entity->setRefestados($refestados);
$entity->setReftipoactividades($reftipoactividades);
$entity->setRefzonas($refzonas);
$entity->setNroaviso($nroaviso);
$entity->setClaseaviso($claseaviso);
$entity->setAutoraviso($autoraviso);
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

