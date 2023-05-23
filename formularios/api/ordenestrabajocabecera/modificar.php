<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Ordenestrabajocabecera();


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
$refsolicitudesvisitas = $_POST['refsolicitudesvisitas'];
$fecha = $_POST['fecha'];
$refsemaforo = $_POST['refsemaforo'];
$refestados = $_POST['refestados'];
$fechafin = $_POST['fechafin'];
/* fin de variables del post */

/* seteo del objeto */

$entity->setRefsolicitudesvisitas($refsolicitudesvisitas);
$entity->setFecha($fecha);
$entity->setRefsemaforo($refsemaforo);
$entity->setRefestados($refestados);
$entity->setFechafin($fechafin);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('refsolicitudesvisitas'=>$refsolicitudesvisitas,'fecha'=>$fecha,'fecha'=>$fecha,'refsemaforo'=>$refsemaforo,'refestados'=>$refestados,'fechafin'=>$fechafin));
    
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

