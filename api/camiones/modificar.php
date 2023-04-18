<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Camiones();


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
if (isset($_POST['fueradeservicio'])) {
  $fueradeservicio = '1';
} else {
  $fueradeservicio = '0';
}
$kilometros = $_POST['kilometros'];
$color = $_POST['color'];
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
$entity->setFueradeservicio($fueradeservicio);
$entity->setKilometros($kilometros);
$entity->setActivo($activo);
$entity->setColor($color);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('refactivos'=>$refactivos,'refmarcas'=>$refmarcas,'modelo'=>$modelo,'anio'=>$anio,'patente'=>$patente,'chasis'=>$chasis,'nromotor'=>$nromotor,'activo'=>$activo,'fueradeservicio'=>$fueradeservicio,'kilometros'=>$kilometros,'color'=>$color));
    
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

