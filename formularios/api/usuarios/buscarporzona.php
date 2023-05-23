<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Usuarios('','');


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

/* verificar permisos */

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../../');
}

/* fin de los permisos */

/* variables del post */

$refzonas = $_POST['refzonas'];

/* fin de variables del post */

/* seteo del objeto */

$lstRow = $entity->traerTodosFilter(array('refzonas'=>$refzonas));

$cadResHtml = '';
foreach ($lstRow as $row) {
    $cadResHtml .= '<option value="'.$row['id'].'">'.$row['nombre'].' - '.$row['apellido'].' - Cargo: '.$row['cargo'].'</option>';
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

