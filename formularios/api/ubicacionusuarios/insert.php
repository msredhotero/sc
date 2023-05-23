<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Ubicacionesusuarios();

$resV['error'] = '';
$resV['mensaje'] = '';


/* variables del post */
$refusuarios = $_POST['refusuarios'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$fecha = $_POST['fecha'];
$checkin = $_POST['checkin'];
if ($_POST['checkin']==2) {
    $fechacheckout = $_POST['fecha'];
    $fecharealcheckout = date('Y-m-d H:i:s');
} else {
    $fechacheckout = '1900-01-01 00:00:00';
    $fecharealcheckout = '1900-01-01 00:00:00';
}

$fechareal = date('Y-m-d H:i:s');

/* fin de variables del post */


/* seteo del objeto */
$entity->setRefusuarios($refusuarios);
$entity->setLatitud($latitud);
$entity->setLongitud($longitud);
$entity->setFecha($fecha);
$entity->setCheckin($checkin);
$entity->setFechacheckout($fechacheckout);
$entity->setFechareal($fechareal);
$entity->setFecharealcheckout($fecharealcheckout);
$entity->setLatitudcheckout($latitud);
$entity->setLongitudcheckout($longitud);
/* fin del seteo */


/* valido */
// valido si ya existe un checkin
if ($checkin == '1') {
    if (count($entity->traerTodosFilter(array('refusuarios'=>$refusuarios,'checkin'=>1)))>0) {
        $resV['error'] = true;
        $resV['mensaje'] = 'Ya realizo su checkin';
    } else {
        $entity->setLatitudcheckout(0);
        $entity->setLongitudcheckout(0);
        if ($entity->save()) {
            $resV['mensaje'] = $Globales::SUCCESS_INSERT;
            $resV['error'] = false;
        } else {
            $resV['mensaje'] = $Globales::ERROR_INSERT;
            $resV['error'] = true;
        }
    }
} else {
    if ($checkin == '2') {
        if (count($entity->traerTodosFilter(array('refusuarios'=>$refusuarios,'checkin'=>1)))>0) {
            foreach($entity->traerTodosFilter(array('refusuarios'=>$refusuarios,'checkin'=>1)) as $row) {
                $entity->setId($row['id']);
            }


            $entity->modificarFilter(array('checkin'=>$checkin,'fechacheckout'=>$fechacheckout,'fecharealcheckout'=>$fecharealcheckout,'latitudcheckout'=>$latitud,'longitudcheckout'=>$longitud));
    
            if ($entity->getError() == 0) {
                $resV['mensaje'] = $Globales::SUCCESS_INSERT;
                $resV['error'] = false;
            } else {
                $resV['mensaje'] = $Globales::ERROR_INSERT;
                $resV['error'] = true;
            }
        } else {
            $resV['error'] = true;
            $resV['mensaje'] = 'No realizo su checkin';
            
        }
    } else {
        if ($entity->save()) {
            $resV['mensaje'] = $Globales::SUCCESS_INSERT;
            $resV['error'] = false;
        } else {
            $resV['mensaje'] = $Globales::ERROR_INSERT;
            $resV['error'] = true;
        }
    }
}
/* fin */





header('Content-type: application/json');
echo json_encode($resV);


?>

