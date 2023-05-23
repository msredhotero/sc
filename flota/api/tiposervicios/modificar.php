<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Tiposervicios();


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
$tiposervicio = $_POST['tiposervicio'];

/* fin de variables del post */

/* seteo del objeto */

$entity->setTiposervicio($tiposervicio);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
  $entity->modificarFilter(array('tiposervicio'=>$tiposervicio));
  
  if ($entity->getError() == 0) {

    if(!empty($_POST['refactivos'])) {
      $ActivosBorrar = new Activostiposervicios();
      //borro todo
      $ActivosBorrar->setReftiposervicios($id);
      $ActivosBorrar->borrarPorTiposervicio();

      //vuelvo a grabar
      foreach($_POST['refactivos'] as $check) {
        $Activos = new Activostiposervicios();
        $Activos->setRefactivos($check);
        $Activos->setReftiposervicios($id);
        $Activos->save();
  
        //die(var_dump($Cuadrillas->save()));
      }
    }

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

