<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Tiposervicios();

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
$tiposervicio = $_POST['tiposervicio'];

/* fin de variables del post */

/* seteo del objeto */
$entity->setTiposervicio($tiposervicio);

/* fin del seteo */

if ($idNuevo=$entity->save()> 0) {
    $resV['mensaje'] = $Globales::SUCCESS_INSERT;
    $resV['error'] = false;

    if(!empty($_POST['refactivos'])) {
      foreach($_POST['refactivos'] as $check) {
        $Activos = new Activostiposervicios();
        $Activos->setRefactivos($check);
        $Activos->setReftiposervicios($idNuevo);
        $Activos->save();
  
        //die(var_dump($Cuadrillas->save()));
      }
    }
} else {
    $resV['mensaje'] = $Globales::ERROR_INSERT;
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

