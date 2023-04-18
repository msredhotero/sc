<?php

spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
});



$Globales = new Globales();
$Camiones = new Camiones();

$lst = $Camiones->traerTodos();
if ((date('d')%2)!== 0) {
    foreach ($lst as $row) {
        $id = $row['id'];
        $Camiones->setId($id);
    
        $subCuerpo = $Camiones->notificarVencimiento();
    
        if ($subCuerpo !== '') {
            
            $cuerpo = $Globales::EMAIL_VENCIMIENTOS.$subCuerpo.'</body>';
            
            $Mensajes = new Mensajes('msredhotero@gmail.com','Vencimiento Flota a 15 dias',$cuerpo,'');
            $Mensajes->enviarMensaje();
            /*
            $Mensajes = new Mensajes('deisy.godoy.b@gmail.com','Vencimiento Flota a 15 dias',$cuerpo,'');
            $Mensajes->enviarMensaje();
    
            $Mensajes = new Mensajes('expeditor.cuchipuy@gmail.com','Vencimiento Flota a 15 dias',$cuerpo,'');
            $Mensajes->enviarMensaje();
    
            $Mensajes = new Mensajes('lduran.cuchipuy@gmail.com','Vencimiento Flota a 15 dias',$cuerpo,'');
            $Mensajes->enviarMensaje();
    
            $Mensajes = new Mensajes('construcciones.kyd@gmail.com','Vencimiento Flota a 15 dias',$cuerpo,'');
            $Mensajes->enviarMensaje();
            */
            
            //echo $subCuerpo.'<br>';
        }
    }
} 


