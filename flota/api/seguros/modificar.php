<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Seguros($_POST['refcamiones']);


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
$refaseguradoras = $_POST['refaseguradoras'];
$nropoliza = $_POST['nropoliza'];
$vencimiento = $_POST['vencimiento'];
$rige = $_POST['rige'];
/* fin de variables del post */

/* seteo del objeto */

$entity->setRefaseguradoras($refaseguradoras);
$entity->setNropoliza($nropoliza);
$entity->setVencimiento($vencimiento);
$entity->setRige($rige);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('refaseguradoras'=>$refaseguradoras,'nropoliza'=>$nropoliza,'vencimiento'=>$vencimiento,'rige'=>$rige));
    
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

