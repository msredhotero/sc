<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Respuestascuestionario();


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
$refpreguntascuestionario = $_POST['refpreguntascuestionario'];
$respuesta = $_POST['respuesta'];
$orden = $_POST['orden'];
$inhabilita = '0';
$leyenda = $_POST['leyenda'];
if (isset($_POST['activo'])) {
  $activo = '1';
} else {
  $activo = '0';
}

/* fin de variables del post */

/* seteo del objeto */

$entity->setRefpreguntascuestionario($refpreguntascuestionario);
$entity->setRespuesta($respuesta);
$entity->setOrden($orden);
$entity->setActivo($activo);
$entity->setInhabilita($inhabilita);
$entity->setLeyenda($leyenda);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('refpreguntascuestionario'=>$refpreguntascuestionario,'respuesta'=>$respuesta,'orden'=>$orden,'activo'=>$activo,'inhabilita'=>$inhabilita,'leyenda'=>$leyenda));
    
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

