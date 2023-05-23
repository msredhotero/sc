<?php

spl_autoload_register(function($clase){
    include_once "../../includes/" .$clase. ".php";        
  });
  

$Globales = new Globales();
$Session = new Session('user');
$Camiones = new Camiones();
$Activos = new Activos();
$Marcas = new Marcas();
$Archivos = new Archivos();

$idreferencia = $_POST['idreferencia'];
$reftabla = $_POST['reftabla'];

$archivo = $_FILES['file'];

$templocation = $archivo['tmp_name'];



switch ($reftabla) {
    case 1:
        $carpeta = 'emisioncontaminante';
    break;
    case 2:
        $carpeta = 'permisocirculacion';
    break;
    case 3:
        $carpeta = 'revisiontecnica';
    break;
    case 4:
        $carpeta = 'seguro';
    break;
    case 6:
        $Archivos->buscarPorId($_POST['idarchivo']);
        $carpeta = $Archivos->getCarpeta();
    break;
}


$Documentaciones = new Documentaciones($reftabla,$idreferencia);

$Documentaciones->buscarPorValor(array('reftabla'=>$reftabla, 'idreferencia'=> $idreferencia));

//$name = $Documentaciones->sanear_string(str_replace(' ','',basename($archivo['name'])));

$archivoAnterior = '';

$resV['elimnar'] = $archivo['name'];
if (!$templocation) {

    $resV['mensaje'] = $Globales::ERROR_ARCHIVO_NO_CARGADO;
    $resV['error'] = true;
} else {

    //elimino lo cargado
    if ($Documentaciones->getId()>0) {
        $resV['elimnar'] = 'Si';
        $archivoAnterior = '../../data/'.$Documentaciones->getCarpeta().'/'.$Documentaciones->getIdreferencia().'/'.$Documentaciones->getArchivo();
        $Documentaciones->borrar();
        $Documentaciones::borrarArchivo($archivoAnterior);
    }

    
    if ($Documentaciones->storeImage($archivo,$carpeta) != '') {
        if ($Documentaciones->save()) {
            $resV['mensaje'] = $Globales::SUCCESS_ARCHIVO_CARGADO;
            $resV['error'] = false;
        } else {
            $resV['mensaje'] = $Globales::ERROR_ARCHIVO_NO_CARGADO;
            $resV['error'] = true;
        }
    } else {
        $resV['mensaje'] = $Globales::ERROR_ARCHIVO_NO_CARGADO;
        $resV['error'] = true;
    }
    
    

}

header('Content-type: application/json');
echo json_encode($resV);

?>
