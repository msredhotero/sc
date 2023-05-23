<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Ordenestrabajodetalle($_SESSION['user']->getUsername(),$_POST['refordenestrabajocabecera']);


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
$refordenestrabajocabecera = $_POST['refordenestrabajocabecera'];
$reftareas = $_POST['reftareas'];
$refestados = $_POST['refestados'];
$fechamodi = date('Y-m-d H:i:s');
$observaciones = $_POST['observaciones'];

/* fin de variables del post */

/* seteo del objeto */

$entity->setRefordenestrabajocabecera($refordenestrabajocabecera);
$entity->setReftareas($reftareas);
$entity->setRefestados($refestados);
$entity->setFechamodi($fechamodi);
$entity->setObservaciones($observaciones);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('refordenestrabajocabecera'=>$refordenestrabajocabecera,'reftareas'=>$reftareas,'refestados'=>$refestados,'fechamodi'=>$fechamodi,'observaciones'=>$observaciones));
    
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

