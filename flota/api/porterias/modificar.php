<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Porterias();


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

$refacciones = $_POST['refacciones'];
$refcamiones = $_POST['refcamiones'];
$refacoplados = $_POST['refacoplados'];
$fecha = $_POST['fecha'];
$km = $_POST['km'];
$litros = $_POST['litros'];
$destino = $_POST['destino'];
if (isset($_POST['documentacion'])) {
  $documentacion = '1';
} else {
  $documentacion = '0';
}
if (isset($_POST['checklist'])) {
  $checklist = '1';
} else {
  $checklist = '0';
}
$mtrscubicos = $_POST['mtrscubicos'];
$refporterias = $_POST['refporterias'];
$reftiposervicios = $_POST['reftiposervicios'];

/* fin de variables del post */

/* seteo del objeto */

$entity->setRefacciones($refacciones);
$entity->setRefcamiones($refcamiones);
$entity->setRefacoplados($refacoplados);
$entity->setFecha($fecha);
$entity->setKm($km);
$entity->setLitros($litros);
$entity->setDestino($destino);
$entity->setDocumentacion($documentacion);
$entity->setChecklist($checklist);
$entity->setMtrscubicos($mtrscubicos);
$entity->setRefporterias($refporterias);
$entity->setReftiposervicios($reftiposervicios);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
  $entity->modificarFilter(array('refacciones'=>$refacciones,'refcamiones'=>$refcamiones,'refacoplados'=>$refacoplados,'fecha'=>$fecha,'km'=>$km,'litros'=>$litros,'destino'=>$destino,'documentacion'=>$documentacion,'checklist'=>$checklist,'mtrscubicos'=>$mtrscubicos,'refporterias'=>$refporterias,'reftiposervicios'=>$reftiposervicios));
  
  if ($entity->getError() == 0) {


    $ConductoresPrincipal = new Conductores();
    //borro y vuelvo a crear
    $ConductoresPrincipal->borrarPorPorteria($id);
    //grabo lo modificado
    $ConductoresPrincipal->setRefpersonal($_POST['refconductor']);
    $ConductoresPrincipal->setRefporterias($id);
    $ConductoresPrincipal->setConduce('1');
    $ConductoresPrincipal->save();

    if(!empty($_POST['refpasajeros'])) {
      foreach($_POST['refpasajeros'] as $check) {
        $Conductores = new Conductores();
        $Conductores->setRefpersonal($check);
        $Conductores->setRefporterias($id);
        $Conductores->setConduce('0');
        $Conductores->save();
  
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

