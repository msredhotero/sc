<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Tareas();


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
$tarea = $_POST['tarea'];

if (isset($_POST['activo'])) {
  $activo = '1';
} else {
  $activo = '0';
}
if (isset($_POST['esreparacion'])) {
  $esreparacion = '1';
} else {
  $esreparacion = '0';
}
if (isset($_POST['esmantenimiento'])) {
  $esmantenimiento = '1';
} else {
  $esmantenimiento = '0';
}
if (isset($_POST['verificakilometros'])) {
  $verificakilometros = '1';
} else {
  $verificakilometros = '0';
}
if (isset($_POST['verificavencimientos'])) {
  $verificavencimientos = '1';
} else {
  $verificavencimientos = '0';
}
/* fin de variables del post */

/* seteo del objeto */

$entity->setActivo($activo);

$entity->setTarea($tarea);
$entity->setEsreparacion($esreparacion);
$entity->setEsmantenimiento($esmantenimiento);
$entity->setVerificakilometros($verificakilometros);
$entity->setVerificavencimientos($verificavencimientos);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('activo'=>$activo,'tarea'=>$tarea,'esreparacion'=>$esreparacion,'esmantenimiento'=>$esmantenimiento,'verificakilometros'=>$verificakilometros,'verificavencimientos'=>$verificavencimientos));
    
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

