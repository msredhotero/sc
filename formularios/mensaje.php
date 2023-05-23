<?php

$nombre     = trim($_POST['nombre']);
$email      = trim($_POST['nombre']);
$telefono   = trim($_POST['telefono']);
$mensaje    = trim($_POST['mensaje']);

$cad = "<p>Nombre del contacto: ".$nombre."</p><p>Email: ".$email."</p><p>Telefono: ".$telefono."</p><p>Mensaje: ".$mensaje."</p>";


define("MAILQUEUE_BATCH_SIZE",0);

//para el envío en formato HTML
$headers = "MIME-Version: 1.0\r\n";

// Cabecera que especifica que es un HMTL
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

//dirección del remitente
$headers .= utf8_decode("From: JCS - JCSolutions <consultas@jota.com.ar>\r\n");

$destinatario = 'juanjo@jcsolutions.com.ar';
$asunto = 'contacto por la pagina web';

$cuerpo = $cad;

mail($destinatario,$asunto,$cuerpo,$headers);


$resV['error'] = false;

 header('Content-type: application/json');
 echo json_encode($resV);




?>