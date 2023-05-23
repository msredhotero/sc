<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Archivospersonal($_POST['refpersonal']);


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
$realizado = $_POST['realizado'];
$vencimiento = $_POST['vencimiento'];
$refarchivos = $_POST['refarchivos'];
/* fin de variables del post */

/* seteo del objeto */

$entity->setRealizado($realizado);
$entity->setVencimiento($vencimiento);
$entity->setRefarchivos($refarchivos);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('realizado'=>$realizado,'vencimiento'=>$vencimiento,'refarchivos'=>$refarchivos));
    
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

