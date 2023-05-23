<?php


    spl_autoload_register(function($clase){
        include_once "../../includes/" .$clase. ".php";        
    });
  
  
    $Globales = new Globales();
    $Session = new Session('user');
    $entity = new Usuarios('','');


    $email          = $_GET['email'];

    if ($email === '') {
        $resV['mensaje'] = $Globales::ERROR_LOGIN_FALTA_EMAIL_PASSWORD;
        $resV['error'] = true;
    } else {
        $Usuarios = new Usuarios($email,'');

        $Usuarios->buscarUsuarioPorValor('email',$email);

        if ($Usuarios->getId()>0) {
            if ($Usuarios->getLogueado() == '0') {
                $resV['mensaje'] = $Globales::ERROR_LOGIN;
                $resV['error'] = true;
            } else {
                $resV['mensaje'] = $Globales::SUCCESS_LOGIN;
                $resV['error'] = false;
            }
            
            
        } else {
            $resV['mensaje'] = $Globales::ERROR_LOGIN;
            $resV['error'] = true;
        }
    }

    header('Content-type: application/json');
    echo json_encode($resV);





?>