<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Porterias();
$Camiones = new Camiones();

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

$Camiones->buscarPorId($refcamiones);
/* fin del seteo */

if ($refcamiones==0) {
  $resV['mensaje'] = $Globales::ERROR_INSERT;
  $resV['error'] = true;
} else {

  //valido que no me cargue los km menores a los ya validados por diego
  if ($Camiones->getKilometros() > $km) {
    $resV['mensaje'] = $Globales::ERROR_CAMIONES_KM_INSERT;
    $resV['error'] = true;
  } else {
    $idNuevo = $entity->save();
    if ($idNuevo>0) {
        if (($refporterias>0) && ($refacciones==2)) {
        
          $entity->buscarPorId($refporterias);
          $entity->modificarFilter(array('refporterias'=>$idNuevo));
        }

        $ConductoresPrincipal = new Conductores();
        $ConductoresPrincipal->setRefpersonal($_POST['refconductor']);
        $ConductoresPrincipal->setRefporterias($idNuevo);
        $ConductoresPrincipal->setConduce('1');
        $ConductoresPrincipal->save();

        if(!empty($_POST['refpasajeros'])) {
          foreach($_POST['refpasajeros'] as $check) {
            $Conductores = new Conductores();
            $Conductores->setRefpersonal($check);
            $Conductores->setRefporterias($idNuevo);
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
  }
  
}


header('Content-type: application/json');
echo json_encode($resV);


?>

