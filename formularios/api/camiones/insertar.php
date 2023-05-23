<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Camiones();

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
$refactivos = $_POST['refactivos'];
$refmarcas = $_POST['refmarcas'];
$modelo = $_POST['modelo'];
$anio = $_POST['anio'];
$patente = $_POST['patente'];
$chasis = $_POST['chasis'];
$nromotor = $_POST['nromotor'];
$tipo = $_POST['tipo'];
$usuariocrea = $_SESSION['user']->getUsername();
$fechacrea = date('Y-m-d');
if (isset($_POST['activo'])) {
  $activo = '1';
} else {
  $activo = '0';
}

/* fin de variables del post */

/* seteo del objeto */
$entity->setRefactivos($refactivos);
$entity->setRefmarcas($refmarcas);
$entity->setModelo($modelo);
$entity->setAnio($anio);
$entity->setPatente($patente);
$entity->setChasis($chasis);
$entity->setNromotor($nromotor);
$entity->setTipo($tipo);
$entity->setUsuariocrea($usuariocrea);
$entity->setFechacrea($fechacrea);
$entity->setActivo($activo);
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

