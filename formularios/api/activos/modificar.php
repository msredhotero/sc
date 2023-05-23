<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Activos();


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
$activo = $_POST['activo'];

if (isset($_POST['conmotor'])) {
  $conmotor = '1';
} else {
  $conmotor = '0';
}

/* fin de variables del post */

/* seteo del objeto */

$entity->setActivo($activo);
$entity->setConmotor($conmotor);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('activo'=>$activo,'conmotor'=>$conmotor));
    
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

