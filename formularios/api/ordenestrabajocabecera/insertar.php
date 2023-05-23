<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Ordenestrabajocabecera();



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
$refsolicitudesvisitas = $_POST['refsolicitudesvisitas'];
$fecha = $_POST['fecha'];
$fechafin = $_POST['fechafin'];
$refsemaforo = $_POST['refsemaforo'];
$refestados = $_POST['refestados'];

/* fin de variables del post */

/* seteo del objeto */
$entity->setRefsolicitudesvisitas($refsolicitudesvisitas);
$entity->setFecha($fecha);
$entity->setRefsemaforo($refsemaforo);
$entity->setRefestados($refestados);
$entity->setFechafin($fechafin);
/* fin del seteo */

//variable para marcar el primer usuario como el asignado
$primero = 0;

if ($entity->save()) {

  
  
  if(!empty($_POST['refusuarios'])) {
    foreach($_POST['refusuarios'] as $check) {
      $Cuadrillas = new Cuadrillas();
      $Cuadrillas->setRefusuarios($check);
      $Cuadrillas->setRefordenestrabajocabecera($entity->getId());
      if ($primero==0) {
        $Cuadrillas->setAsignado('1');
        $primero=1;
      } else {
        $Cuadrillas->setAsignado('0');
      }
      $Cuadrillas->save();

      $entity->modificarFilter(array('refestados'=>2));

      //die(var_dump($Cuadrillas->save()));
    }
  }

  if(!empty($_POST['reftareas'])) {
    foreach($_POST['reftareas'] as $check) {
      $Ordenestrabajodetalle = new Ordenestrabajodetalle($_SESSION['user']->getUsername(),$entity->getId());
      $Ordenestrabajodetalle->setRefordenestrabajocabecera($entity->getId());
      $Ordenestrabajodetalle->setReftareas($check);
      $Ordenestrabajodetalle->setRefestados(1);
      $Ordenestrabajodetalle->setFechamodi(date('Y-m-d H:i:s'));
      $Ordenestrabajodetalle->setObservaciones('');
      $Ordenestrabajodetalle->save();
    }
  }

  // marco la solicitud como asignada, id 2
  $Solitud = new Solicitudesvisitas('');
  $Solitud->buscarPorId($refsolicitudesvisitas);

  $Solitud->modificarFilter(array('refestados'=>2));


  $resV['mensaje'] = $Globales::SUCCESS_INSERT;
  $resV['error'] = false;
} else {
  $resV['mensaje'] = $Globales::ERROR_INSERT;
  $resV['error'] = true;
}


header('Content-type: application/json');
echo json_encode($resV);


?>

