<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Sucursales(0,0);


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* fin de los permisos */

/* variables del post */

$reftabla = $_POST['reftabla'];
$idreferencia = $_POST['idreferencia'];

/* fin de variables del post */

/* seteo del objeto */

$lstRow = $entity->traerTodosFilter(array('reftabla'=>$reftabla,'idreferencia'=>$idreferencia));

$cadResHtml = '';
foreach ($lstRow as $row) {
    $cadResHtml .= '<option value="'.$row['id'].'">'.$row['sucursal'].' - '.$row['direccion'].'</option>';
}
/* fin del seteo */


if ($entity != null) {
  $resV['datos'] = $cadResHtml;
  $resV['error'] = false;
} else {
  $resV['datos'] ='';
  $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

