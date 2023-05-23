<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Ordenestrabajocabecera();
$Cuadrillas = new Cuadrillas();
$SV = new Solicitudesvisitas('');

if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* fin de los permisos */

/* variables del post */
$fecha = $_POST['fecha'];
$idsv = $_POST['idsv'];
/* fin de variables del post */

/* seteo del objeto */
$SV->buscarPorId($idsv);


$entity->traerTodos();

$arDisponibles = [];
foreach ($Cuadrillas->getUsuarios()->traerTodosFilter(array('refroles'=>2,'refzonas'=>$SV->getRefzonas())) as $row) {
    if ($entity::libre($fecha,$row['id']) == 0) {
        array_push($arDisponibles, array('id'=> $row['id'], 'nombre'=>$row['nombre'], 'apellido'=>$row['apellido'], 'cargo'=>$row['cargo']));
    }
    
}
/* fin del seteo */

$datos = '';
if ($entity != null) {

    foreach ($arDisponibles as $row) {
        $datos .= '<li><div class="form-check" style="float: left;">
        <input class="form-check-input refusuarios" type="checkbox" name="refusuarios[]" value="'.$row['id'].'" id="refusuarios.'.$row['id'].'" >
        <label class="custom-control-label" for="customCheck'.$row['id'].'">'.$row['apellido'].' '.$row['nombre'].' - Cargo: '.$row['cargo'].'</label>
      </div></li>';
    }
    $resV['datos'] = $datos;
    $resV['error'] = false;
} else {
    $resV['datos'] ='';
    $resV['mensaje'] ='No existen usuarios disponibles';
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

