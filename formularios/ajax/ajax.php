<?php


error_reporting(E_ALL);

ini_set('ignore_repeated_errors', TRUE); // always use TRUE

ini_set('display_errors', true); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment

ini_set('log_errors', TRUE); // Error/Exception file logging engine.


spl_autoload_register(function($clase){
    include_once "../includes/" .$clase. ".php";        
});

$accion = $_POST['accion'];

$resV['error'] = '';
$resV['mensaje'] = '';

//var_dump($accion);

switch ($accion) {
    //negocio
    
    
    //fin negocio
    // basico
    case 'registrarUsuarioWeb':
        registrarUsuarioWeb();
    break;
    case 'iniciarSessionUsuarioWeb':
        iniciarSessionUsuarioWeb();
    break;
    case 'frmAjaxModificar':
        frmAjaxModificar();
    break;
    case 'insertarUsuarios':
        insertarUsuarios();
    break;
    case 'modificarUsuarios':
        modificarUsuarios();
    break;
    case 'eliminarUsuarios':
        eliminarUsuarios();
    break;
    case 'recuperarpasswordUsuarioWeb':
        recuperarpasswordUsuarioWeb();
    break;
    case 'nuevopasswordUsuarioWeb':
        nuevopasswordUsuarioWeb();
    break;
    // fin basico

}

//negocio

//fin negocio

//ajax formulario modificar general

function frmAjaxModificar() {
    $tabla = $_POST['tabla'];
    $id = $_POST['id'];

    switch ($tabla) {
        
        case 'dbusuarios':
            $Usuarios = new Usuarios('','');
            $Usuarios->buscarPorId($id);
            if ($Usuarios != null) {
                $resV['datos'] = $Usuarios->devolverArray();
                $resV['error'] = false;
            } else {
                $resV['datos'] ='';
                $resV['error'] = true;
            }    
        break;

     
 
    }

    header('Content-type: application/json');
    echo json_encode($resV);
}

//fin ajax formulario modificar general


// basico
function modificarUsuarios() {
    $Usuarios = new Usuarios('','');
    $Globales = new Globales();

    $id = $_POST['idmodificar'];
    $email          = $_POST['email'];
    $nombre         = $_POST['nombre'];
    $apellido       = $_POST['apellido'];
    $refroles       = $_POST['refroles'];

    if (isset($_POST['validoemail'])) {
        $validoemail    = '1';
    } else {
        $validoemail    = '0';
    }
    if (isset($_POST['activo'])) {
        $activo    = '1';
    } else {
        $activo    = '0';
    }
    
    $username       = $_POST['username'];

    
    $Usuarios->buscarPorId($id);

    if ($Usuarios != null) {
        $Usuarios->modificarFilter(array('email'=>$email,'nombre'=>$nombre,'apellido'=>$apellido,'refroles'=>$refroles,'validoemail'=>$validoemail,'activo'=>$activo,'username'=>$username));
        
        if ($Usuarios->getError() == 0) {
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
}


function eliminarUsuarios() {
    $Usuarios = new Usuarios('','');
    $Globales = new Globales();

    $id = (int)$_POST['id'];

    $Usuarios->buscarPorId($id);

    if ($Usuarios->getId() != null) {
        $Usuarios->borrar();
        
        if ($Usuarios->getError() == 0) {
            $resV['mensaje'] = $Globales::SUCCESS_ELIMINAR;
            $resV['error'] = false;
        } else {
            $resV['mensaje'] = $Globales::ERROR_ELIMINAR;
            $resV['error'] = true;
        }
    } else {
        $resV['mensaje'] = $Globales::ERROR_ELIMINAR;
        $resV['error'] = true;
    }
    
    header('Content-type: application/json');
    echo json_encode($resV);
}


function iniciarSessionUsuarioWeb() {
    $Globales = new Globales();

    $email          = $_POST['email'];
    $password       = $_POST['password'];

    if (($email === '') || ($password === '')) {
        $resV['mensaje'] = $Globales::ERROR_LOGIN_FALTA_EMAIL_PASSWORD;
        $resV['error'] = true;
    } else {
        $Usuarios = new Usuarios($email,$password);

        if ($Usuarios->login($email,$password)) {
            $Session = new Session('user');

            $Session->setCurrentUser($Usuarios);

            //var_dump($_SESSION['user']);
            if ($_SESSION['user']->getActivo() == '0') {
                $resV['mensaje'] = $Globales::ERROR_LOGIN_INACTIVO;
                $resV['error'] = true;
            } else {
                $Usuarios->modificarFilter(array('logueado'=> '1'));
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
}


function insertarUsuarios() {

    $Globales = new Globales();
    
    $email          = $_POST['email'];
    $password       = $_POST['password'];
    $nombre         = $_POST['nombre'];
    $apellido       = $_POST['apellido'];
    $refroles       = $_POST['refroles'];
    $validoemail    = $_POST['validoemail'];
    $activo         = $_POST['activo'];
    $username       = $_POST['username'];

    if (($email === '') || ($password === '') || ($nombre === '') || ($apellido === '')) {
        $resV['mensaje'] = $Globales::ERROR_USUARIOS_FALTA_EMAIL_PASSWORD_NOMBRECOMPLETO;
        $resV['error'] = true;
    } else {
        
        $Usuarios = new Usuarios($email,$password);

        if ($Usuarios->validarEmail()) {
            if ($Usuarios::exists($email)) {
                $resV['mensaje'] = $Globales::ERROR_USUARIOS_EMAIL_EXISTE;
                $resV['error'] = true;
            } else {
                $Usuarios->setRefroles($refroles);
                $Usuarios->setUsername($username);
                $Usuarios->setNombre($nombre);
                $Usuarios->setApellido($apellido);
                $Usuarios->setValidoemail($validoemail);
                $Usuarios->setActivo($activo);

                if ($Usuarios->save()) {
                    $Session = new Session($username);
                    $Session->setCurrentUser($Usuarios);
                    $resV['mensaje'] = $Globales::SUCCESS_USUARIOS_CREAR;
                    $resV['error'] = false;
                } else {
                    $resV['mensaje'] = $Globales::ERROR_USUARIOS_CREAR;
                    $resV['error'] = true;
                }
            }
        } else {
            $resV['mensaje'] = $Globales::ERROR_USUARIOS_INVALIDO_EMAIL;
            $resV['error'] = true;
        }
    }

    header('Content-type: application/json');
    echo json_encode($resV);

}


function nuevopasswordUsuarioWeb() {
    $Globales = new Globales();
    $token = $_POST['token'];
    $password = $_POST['password'];

    $Autologin = new Autologin(0,'','','');

    $Autologin->traerToken($token);
    
    if ($Autologin->getRefusuarios() < 1) {
        $resV['mensaje'] = $Globales::ERROR_VERIFICACION;
        $resV['error'] = true;

        
    } else {
        $resV['error'] = false;

        $Usuarios = new Usuarios('',$password);
        
        $Usuarios->buscarPorId($Autologin->getRefusuarios());
        if ($Usuarios->savePassword()) {
            $resV['mensaje'] = $Globales::SUCCESS_MODIFICAR_PASSWORD;
            $resV['error'] = false;
            $Usuarios->modificarFilter(array('logueado'=> '0'));
            $Autologin->borrar();
        } else {
            $resV['mensaje'] = $Globales::ERROR_MODIFICAR_PASSWORD;
            $resV['error'] = true;
        }
    }

    header('Content-type: application/json');
    echo json_encode($resV);
}

function recuperarpasswordUsuarioWeb() {
    $Globales = new Globales();
    
    $email          = $_POST['email'];


    if ($email === '') {
        $resV['mensaje'] = $Globales::ERROR_USUARIOS_FALTA_EMAIL_PASSWORD_NOMBRECOMPLETO;
        $resV['error'] = true;
    } else {
        
        $Usuarios = new Usuarios($email,'');

        if ($Usuarios->validarEmail()) {
            if ($Usuarios::exists($email)) {
                
                $resV['mensaje'] = $Globales::SUCCESS_RECUPERAR;
                $resV['error'] = false;
                $Autologin = new Autologin(0,'passwordnuevo.php',$email,'recupero');
                $Autologin->setToken();
                $Autologin->save();
                $Mensajes = new Mensajes($email,'Recupero de Password','recupero',$Autologin->getToken());
                $Mensajes->enviarMensaje();
            } else {
                $resV['mensaje'] = $Globales::ERROR_USUARIOS_INVALIDO_EMAIL;
                $resV['error'] = true;
            }
            

        } else {
            $resV['mensaje'] = $Globales::ERROR_USUARIOS_INVALIDO_EMAIL;
            $resV['error'] = true;
        }
    }


    header('Content-type: application/json');
    echo json_encode($resV);
}

function registrarUsuarioWeb() {

    $Globales = new Globales();
    
    $email          = $_POST['email'];
    $password       = $_POST['password'];
    $nombre         = $_POST['nombre'];
    $apellido       = $_POST['apellido'];
    $refroles       = 2;
    $username       = str_replace(' ','',trim($_POST['nombre'])).' '.str_replace(' ','',trim($_POST['apellido']));

    if (($email === '') || ($password === '') || ($nombre === '') || ($apellido === '')) {
        $resV['mensaje'] = $Globales::ERROR_USUARIOS_FALTA_EMAIL_PASSWORD_NOMBRECOMPLETO;
        $resV['error'] = true;
    } else {
        
        $Usuarios = new Usuarios($email,$password);

        if ($Usuarios->validarEmail()) {
            if ($Usuarios::exists($email)) {
                $resV['mensaje'] = $Globales::ERROR_USUARIOS_EMAIL_EXISTE;
                $resV['error'] = true;
            } else {
                $Usuarios->setRefroles($refroles);
                $Usuarios->setUsername($username);
                $Usuarios->setNombre($nombre);
                $Usuarios->setApellido($apellido);
                $Usuarios->setValidoemail('0');
                $Usuarios->setActivo('0');

                if ($Usuarios->save()) {
                    $Session = new Session($username);

                    $Session->setCurrentUser($Usuarios);

                    $resV['mensaje'] = $Globales::SUCCESS_USUARIOS_CREAR;
                    $resV['error'] = false;
                } else {
                    $resV['mensaje'] = $Globales::ERROR_USUARIOS_CREAR;
                    $resV['error'] = true;
                }

                
            }
            

        } else {
            $resV['mensaje'] = $Globales::ERROR_USUARIOS_INVALIDO_EMAIL;
            $resV['error'] = true;
        }
    }


    header('Content-type: application/json');
    echo json_encode($resV);
}

// fin del basico

?>