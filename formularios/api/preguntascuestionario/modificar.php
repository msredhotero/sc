<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Preguntascuestionario();


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
$refformularios = $_POST['refformularios'];
$reftiporespuesta = $_POST['reftiporespuesta'];
$pregunta = $_POST['pregunta'];
$orden = $_POST['orden'];
$valor = 0;
$tiempo = 0;
$depende = 0;
$dependerespuesta = 0;
$leyenda = $_POST['leyenda'];
if (isset($_POST['activo'])) {
  $activo = '1';
} else {
  $activo = '0';
}
if (isset($_POST['obligatoria'])) {
  $obligatoria = '1';
} else {
  $obligatoria = '0';
}

/* fin de variables del post */

/* seteo del objeto */

$entity->setRefformularios($refformularios);
$entity->setReftiporespuesta($reftiporespuesta);
$entity->setPregunta($pregunta);
$entity->setOrden($orden);
$entity->setValor($valor);
$entity->setActivo($activo);
$entity->setTiempo($tiempo);
$entity->setObligatoria($obligatoria);
$entity->setDepende($depende);
$entity->setDependerespuesta($dependerespuesta);
$entity->setLeyenda($leyenda);

$entity->buscarPorId($id);

/* fin del seteo */


if ($entity != null) {
    $entity->modificarFilter(array('refformularios'=>$refformularios,'reftiporespuesta'=>$reftiporespuesta,'pregunta'=>$pregunta,'orden'=>$orden,'valor'=>$valor,'activo'=>$activo,'tiempo'=>$tiempo,'obligatoria'=>$obligatoria,'depende'=>$depende,'dependerespuesta'=>$dependerespuesta,'leyenda'=>$leyenda));
    
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

