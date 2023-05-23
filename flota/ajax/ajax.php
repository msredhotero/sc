<?php


error_reporting(E_ALL);

ini_set('ignore_repeated_errors', TRUE); // always use TRUE

ini_set('display_errors', true); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment

ini_set('log_errors', TRUE); // Error/Exception file logging engine.


spl_autoload_register(function($clase){
    include_once "../../flota/includes/" .$clase. ".php";        
});

$accion = $_POST['accion'];

$resV['error'] = '';
$resV['mensaje'] = '';

//var_dump($accion);

switch ($accion) {
    //negocio
    case 'insertarProveedores':
        insertarProveedores();
    break;
    case 'modificarProveedores':
        modificarProveedores();
    break;
    case 'eliminarProveedores':
        eliminarProveedores();
    break;
    case 'insertarSkills':
        insertarSkills();
    break;
    case 'modificarSkills':
        modificarSkills();
    break;
    case 'eliminarSkills':
        eliminarSkills();
    break;
    case 'insertarMaridages':
        insertarMaridages();
    break;
    case 'modificarMaridages':
        modificarMaridages();
    break;
    case 'eliminarMaridages':
        eliminarMaridages();
    break;
    case 'insertarCervezas':
        insertarCervezas();
    break;
    case 'modificarCervezas':
        modificarCervezas();
    break;
    case 'eliminarCervezas':
        eliminarCervezas();
    break;
    
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
    
    case 'recuperarpasswordUsuarioWeb':
        recuperarpasswordUsuarioWeb();
    break;
    case 'nuevopasswordUsuarioWeb':
        nuevopasswordUsuarioWeb();
    break;
    // fin basico

    case 'traerImagen':
        traerImagen();
    break;
    case 'notificarVencimientos':
        notificarVencimientos();
    break;

}

//negocio
function notificarVencimientos() {
    $id = $_POST['id'];

    $Globales = new Globales();
    $Camiones = new Camiones();
    $Camiones->setId($id);

    $subCuerpo = $Camiones->notificarVencimiento();

    if ($subCuerpo == '') {
        $resV['error'] = true;
        $resV['mensaje'] = 'No existen vencimientos';
    } else {
        $cuerpo = $Globales::EMAIL_VENCIMIENTOS.$subCuerpo.'</body>';

        $Mensajes = new Mensajes('construcciones.kyd@gmail.com','Vencimiento Flota',$cuerpo,'');
        $Mensajes->enviarMensaje();

        $resV['error'] = false;
        $resV['mensaje'] = 'Notificaion generada correctamente';
    }
    

    header('Content-type: application/json');
    echo json_encode($resV);
}

function traerImagen() {
    $idreferencia   = $_POST['idreferencia'];
    $reftabla       = $_POST['reftabla'];
    $idarchivo      = $_POST['idarchivo'];

    switch ($reftabla) {
        case 1:
            $entity = new Emisionescontaminantes(0);
            $carpeta = 'emisionescontaminantes';
        break;
        case 2:
            $entity = new Permisoscirculacion(0);
            $carpeta = 'permisoscirculacion';
        break;
        case 3:
            $entity = new Revisionestecnicas(0);
            $carpeta = 'revisionestecnicas';
        break;
        case 4:
            $entity = new Seguros(0);
            $carpeta = 'seguros';
        break;
        case 6:
          $entity = new Archivosflota(0);
          $Archivos = new Archivos();

          $Archivos->buscarPorId($idarchivo);
          $carpeta = $Archivos->getCarpeta();
        break;
        case 7:
            $entity = new Archivospersonal(0);
            $Archivos = new Archivos();
  
            $Archivos->buscarPorId($idarchivo);
            $carpeta = $Archivos->getCarpeta();
          break;
    }
    
    $entity->buscarPorId($idreferencia);
    
    $Documentos = new Documentaciones($reftabla,$idreferencia);
    
    $Documentos->buscarPorValor(array('reftabla'=>$reftabla, 'idreferencia'=> $idreferencia));

    if ($Documentos->getId()>0) {
        $resV['error'] = false;

        $resV['datos'] = array('imagen' => '../../'.$Documentos->getArchivoCompleto(), 'type' => $Documentos->getTipo());

    } else {
        $imagen = '../../assets/img/sin_img.jpg';

        $resV['datos'] = array('imagen' => $imagen, 'type' => 'imagen');
        $resV['error'] = true;
    }

    header('Content-type: application/json');
    echo json_encode($resV);
}

function eliminarCervezas() {
    $Cervezas = new Cervezas();
    $Globales = new Globales();

    $id = (int)$_POST['id'];

    $Cervezas->buscarPorId($id);

    if ($Cervezas->getId() != null) {
        $Cervezas->borrar();
        
        if ($Cervezas->getError() == 0) {
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

function modificarCervezas() {
    $Cervezas = new Cervezas();
    $Globales = new Globales();
    $Maridages  = new Maridages();
    $Skills     = new Skills();
    $Session    = new Session('user');

    $id = $_POST['idmodificar'];
    $tipo       = $_POST['tipo'];
    $alcohol    = $_POST['alcohol'];
    $ibu        = $_POST['ibu'];
    $og         = (int)$_POST['og'];
    $precio     = (float)$_POST['precio'];
    $usuariocrea= $_SESSION['user']->getNombre();
    $refproveedores = $_POST['refproveedores'];
    $pinta      = $_POST['pinta'];
    $botellon      = $_POST['botellon'];

    if (isset($_POST['activo'])) {
        $activo = '1';
    } else {
        $activo = '0';
    }

    
    $Cervezas->buscarPorId($id);

    if ($Cervezas != null) {
        $Cervezas->modificarFilter(array('tipo'=>$tipo,'alcohol'=>$alcohol,'ibu'=>$ibu,'og'=>$og,'precio'=>$precio,'usuariocrea'=>$usuariocrea,'refproveedores'=>$refproveedores,'pinta'=>$pinta,'botellon'=>$botellon,'activo'=>$activo));
        
        if ($Cervezas->getError() == 0) {

            $CervezasSkills = new CervezasCombos('refskills','dbcervezasskills',0);
            $CervezasSkills->setRefcervezas($id);
            $CervezasSkills->borrarPorCerveza();
            $CervezasMaridages = new CervezasCombos('refmaridages','dbcervezasmaridages',0);
            $CervezasMaridages->setRefcervezas($id);
            $CervezasMaridages->borrarPorCerveza();

            foreach ($Skills->traerTodos() as $row) {
                if ( isset( $_POST['refskills'.$row['id']] )) {
                    $CervezasCombos = new CervezasCombos('refskills','dbcervezasskills',$row['id']);
                    $CervezasCombos->setRefcervezas($id);
                    $CervezasCombos->save();
                }
            }
    
            foreach ($Maridages->traerTodos() as $row) {
                if ( isset( $_POST['refmaridages'.$row['id']] )) {
                    $CervezasCombos = new CervezasCombos('refmaridages','dbcervezasmaridages',$row['id']);
                    $CervezasCombos->setRefcervezas($id);
                    $CervezasCombos->save();
                }
            }

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



function insertarCervezas() {
    $Cervezas   = new Cervezas();
    $Maridages  = new Maridages();
    $Skills     = new Skills();
    $Globales   = new Globales();
    $Session    = new Session('user');

    $tipo       = $_POST['tipo'];
    $alcohol    = $_POST['alcohol'];
    $ibu        = $_POST['ibu'];
    $og         = (int)$_POST['og'];
    $precio     = (float)$_POST['precio'];
    $usuariocrea= $_SESSION['user']->getNombre();
    $refproveedores = $_POST['refproveedores'];
    $pinta      = (float)$_POST['pinta'];
    $botellon    = (float)$_POST['botellon'];


    if (isset($_POST['activo'])) {
        $activo = '1';
    } else {
        $activo = '0';
    }

    $Cervezas->setTipo($tipo);
    $Cervezas->setAlcohol($alcohol);
    $Cervezas->setIbu($ibu);
    $Cervezas->setOg($og);
    $Cervezas->setPrecio($precio);
    $Cervezas->setActivo($activo);
    $Cervezas->setUsuariocrea($usuariocrea);
    $Cervezas->setFechacrea(date('Y-m-d H:i:s'));
    $Cervezas->setRefproveedores($refproveedores);
    $Cervezas->setPinta($pinta);
    $Cervezas->setBotellon($botellon);

    $idcerveza = $Cervezas->save();

    if ($idcerveza>0) {
        foreach ($Skills->traerTodos() as $row) {
            if ( isset( $_POST['refskills'.$row['id']] )) {
                $CervezasCombos = new CervezasCombos('refskills','dbcervezasskills',$row['id']);
                $CervezasCombos->setRefcervezas($idcerveza);
                $CervezasCombos->save();
            }
        }

        foreach ($Maridages->traerTodos() as $row) {
            if ( isset( $_POST['refmaridages'.$row['id']] )) {
                $CervezasCombos = new CervezasCombos('refmaridages','dbcervezasmaridages',$row['id']);
                $CervezasCombos->setRefcervezas($idcerveza);
                $CervezasCombos->save();
            }
        }
        $resV['mensaje'] = $Globales::SUCCESS_INSERT;
        $resV['error'] = false;
    } else {
        $resV['mensaje'] = $Globales::ERROR_INSERT;
        $resV['error'] = true;
    }

    header('Content-type: application/json');
    echo json_encode($resV);
}

function eliminarSkills() {
    $Skills = new Skills();
    $Globales = new Globales();

    $id = (int)$_POST['id'];

    $Skills->buscarPorId($id);

    if ($Skills->getId() != null) {
        $Skills->borrar();
        
        if ($Skills->getError() == 0) {
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

function modificarSkills() {
    $Skills = new Skills();
    $Globales = new Globales();

    $id = $_POST['idmodificar'];
    $skill = $_POST['skill'];
    
    $Skills->buscarPorId($id);

    if ($Skills != null) {
        $Skills->modificarFilter(array('skill'=>$skill));
        
        if ($Skills->getError() == 0) {
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



function insertarSkills() {
    $Skills = new Skills();
    $Globales = new Globales();

    $skill = $_POST['skill'];

    $Skills->setSkill($skill);

    if ($Skills->save()) {
        $resV['mensaje'] = $Globales::SUCCESS_INSERT;
        $resV['error'] = false;
    } else {
        $resV['mensaje'] = $Globales::ERROR_INSERT;
        $resV['error'] = true;
    }

    header('Content-type: application/json');
    echo json_encode($resV);
}


function eliminarProveedores() {
    $Proveedores = new Proveedores();
    $Globales = new Globales();

    $id = (int)$_POST['id'];

    $Proveedores->buscarPorId($id);

    if ($Proveedores->getId() != null) {
        $Proveedores->borrar();
        
        if ($Proveedores->getError() == 0) {
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

function modificarProveedores() {
    $Proveedores = new Proveedores();
    $Globales = new Globales();

    $id = $_POST['idmodificar'];
    $proveedor = $_POST['proveedor'];
    $direccion = $_POST['direccion'];
    $movil = $_POST['movil'];
    
    $Proveedores->buscarPorId($id);

    if ($Proveedores != null) {
        $Proveedores->modificarFilter(array('proveedor'=>$proveedor,'direccion'=>$direccion,'movil'=>$movil));
        
        if ($Proveedores->getError() == 0) {
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



function insertarProveedores() {
    $Proveedores = new Proveedores();
    $Globales = new Globales();

    $proveedor = $_POST['proveedor'];
    $direccion = $_POST['direccion'];
    $movil = $_POST['movil'];

    $Proveedores->setProveedor($proveedor);
    $Proveedores->setDireccion($direccion);
    $Proveedores->setMovil($movil);

    if ($Proveedores->save()) {
        $resV['mensaje'] = $Globales::SUCCESS_INSERT;
        $resV['error'] = false;
    } else {
        $resV['mensaje'] = $Globales::ERROR_INSERT;
        $resV['error'] = true;
    }

    header('Content-type: application/json');
    echo json_encode($resV);
}


function eliminarMaridages() {
    $Maridages = new Maridages();
    $Globales = new Globales();

    $id = (int)$_POST['id'];

    $Maridages->buscarPorId($id);

    if ($Maridages->getId() != null) {
        $Maridages->borrar();
        
        if ($Maridages->getError() == 0) {
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

function modificarMaridages() {
    $Maridages = new Maridages();
    $Globales = new Globales();

    $id = $_POST['idmodificar'];
    $maridage = $_POST['maridage'];
    
    $Maridages->buscarPorId($id);

    if ($Maridages != null) {
        $Maridages->modificarFilter(array('maridage'=>$maridage));
        
        if ($Maridages->getError() == 0) {
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



function insertarMaridages() {
    $Maridages = new Maridages();
    $Globales = new Globales();

    $maridage = $_POST['maridage'];

    $Maridages->setMaridage($maridage);

    if ($Maridages->save()) {
        $resV['mensaje'] = $Globales::SUCCESS_INSERT;
        $resV['error'] = false;
    } else {
        $resV['mensaje'] = $Globales::ERROR_INSERT;
        $resV['error'] = true;
    }

    header('Content-type: application/json');
    echo json_encode($resV);
}

//fin negocio

//ajax formulario modificar general

function frmAjaxModificar() {
    $tabla = $_POST['tabla'];
    $id = $_POST['id'];

    switch ($tabla) {
        case 'tbskills':
            $Skills = new Skills();

            $Skills->buscarPorId($id);

            if ($Skills != null) {
                $resV['datos'] = $Skills->devolverArray();
                $resV['error'] = false;
            } else {
                $resV['datos'] ='';
                $resV['error'] = true;
            }
            
        break;
        case 'tbmaridages':
            $entity = new Maridages();

            $entity->buscarPorId($id);

            if ($entity != null) {
                $resV['datos'] = $entity->devolverArray();
                $resV['error'] = false;
            } else {
                $resV['datos'] ='';
                $resV['error'] = true;
            }
            
        break;
        case 'tbproveedores':
            $entity = new Proveedores();

            $entity->buscarPorId($id);

            if ($entity != null) {
                $resV['datos'] = $entity->devolverArray();
                $resV['error'] = false;
            } else {
                $resV['datos'] ='';
                $resV['error'] = true;
            }
            
        break;
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

        case 'dbcervezas':
            $entity = new Cervezas();
            $entity->buscarPorId($id);
            $CervezasSkills = new CervezasCombos('refskills','dbcervezasskills',0);
            $CervezasSkills->setRefcervezas($id);
            $CervezasMaridages = new CervezasCombos('refmaridages','dbcervezasmaridages',0);
            $CervezasMaridages->setRefcervezas($id);
            if ($entity != null) {
                $resV['datos'] = $entity->devolverArray();
                $resV['maridages'] = $CervezasMaridages->traerTodosSeleccionados();
                $resV['skills'] = $CervezasSkills->traerTodosSeleccionados();
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

            //var_dump($_SESSION['user']->usuariossistemas->getLstSistemas());
            if ($_SESSION['user']->getActivo() == '0') {
                $resV['mensaje'] = $Globales::ERROR_LOGIN_INACTIVO;
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