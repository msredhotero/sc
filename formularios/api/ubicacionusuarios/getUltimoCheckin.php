<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();

$entity = new Solicitudesvisitas('');

$ot = new Ordenestrabajocabecera();
$Ubicacion = new Ubicacionesusuarios();


/* verificar permisos */


/* fin de los permisos */

/* variables del post */

$idusuario = $_POST['idusuario'];
$Usuarios = new Usuarios('','');

$Usuarios->buscarPorId($idusuario);

/* fin de variables del post */
$resOT = $ot::traerTodosPorUsuario($idusuario);

$Ubicacion->setRefusuarios($idusuario);
$resUltimaUbicacion = $Ubicacion->traerUltimoCheckin();
$ultimocheck = '0';
if (count($resUltimaUbicacion)>0) {
    foreach ($resUltimaUbicacion as $uu) {
        $ultimocheck = $uu;
    }
}

$resV['dato']=$ultimocheck;

header('Content-type: application/json');
echo json_encode($resV);

?>