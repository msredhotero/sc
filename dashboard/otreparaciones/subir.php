<?php

spl_autoload_register(function($clase){
    include_once "../../includes/" .$clase. ".php";        
  });
  

$Globales = new Globales();
$Session = new Session('user');
$Camiones = new Camiones();
$OT = new Ordenestrabajos(0);


$idreferencia = $_POST['idreferencia'];

$archivo = $_FILES['file'];

$templocation = $archivo['tmp_name'];

$OT->buscarPorId($idreferencia);
$OT->setId($idreferencia);

//$name = $Documentaciones->sanear_string(str_replace(' ','',basename($archivo['name'])));

$archivoAnterior = '';

$resV['elimnar'] = $archivo['name'];
if (!$templocation) {

    $resV['mensaje'] = $Globales::ERROR_ARCHIVO_NO_CARGADO;
    $resV['error'] = true;
} else {

    //elimino lo cargado
    
    if ($OT->getArchivo!=='') {
        $resV['elimnar'] = 'Si';
        $archivoAnterior = '../../data/ordenestrabajo/'.$OT->getId().'/'.$OT->getArchivo();
        
        $OT::borrarArchivo($archivoAnterior);
        $OT->modificarFilter(['archivo'=>'','type'=>'']);
    }

    
    if ($OT->storeImage($archivo,'ordenestrabajo') != '') {
        $resV['mensaje'] = $Globales::SUCCESS_ARCHIVO_CARGADO;
        $resV['error'] = false;
    } else {
        $resV['mensaje'] = $Globales::ERROR_ARCHIVO_NO_CARGADO;
        $resV['error'] = true;
    }
    

    

}

header('Content-type: application/json');
echo json_encode($resV);

?>
