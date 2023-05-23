<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');

class Globales {

    public function __construct()
    {
        
    }

    public static $tituloWeb = 'Simple Carga';

    const COLORHTML = 'info';

    const FOOTERDASHBOARD = '<a href="https://simplecarga.com/" class="font-weight-bold" target="_blank">Simple Carga</a>';
    /*************************** ERRORES ***************************/
    const ERROR_USUARIOS_FALTA_EMAIL_PASSWORD_NOMBRECOMPLETO = 'Debe ingresar un email o un password o el nombre completo';
    const ERROR_USUARIOS_INVALIDO_EMAIL = 'El email ingresado es invalido';
    const ERROR_USUARIOS_EMAIL_EXISTE = 'El email ingresado ya existe';
    const ERROR_USUARIOS_CREAR = 'Se genero un error al crear el usuario, intente nuevamente';
    const ERROR_LOGIN = 'Email o password incorrectos';
    const ERROR_LOGIN_FALTA_EMAIL_PASSWORD = 'Debe ingresar un email y un password';
    const ERROR_VERIFICACION = 'La verificacion es incorrecta, genere otra y comuniquese con el administrador';
    const ERROR_LOGIN_INACTIVO = 'Su usuario se encuentra inactivo';
    const ERROR_INSERT = 'Se produjo un error al insertar los datos, verifique';
    const ERROR_ELIMINAR = 'Se produjo un error al eliminar los datos, verifique';
    const ERROR_MODIFICAR_PASSWORD = 'Se produjo un error al modificar el password';
    const ERROR_CUADRILLAS_VALIDAR_ASIGNADO = 'Ya existe un usuario asignado';
    /******************* fin errores *******************************/

    /************************** CONFIRMACIONES *************************/
    const SUCCESS_USUARIOS_CREAR = 'Usuario Creado correctamente';
    const SUCCESS_LOGIN = 'Credenciales Correctas';
    const SUCCESS_LOGIN_VERIFICADO = 'Su cuenta a sido verificada correctamente';
    const SUCCESS_INSERT = 'Datos guardados correctamente';
    const SUCCESS_ELIMINAR = 'Datos eliminados correctamente';
    const SUCCESS_RECUPERAR = 'Le enviamos un email para recuperar su password';
    const SUCCESS_MODIFICAR_PASSWORD = 'Se modifico su password correctamente';
    /************************** FIN CONFIRMACIONES *************************/

    /************************** cuerpos de emails *************************/
    const EMAIL_VERIFICAION = '<img src="https://saupureinconsulting.com.ar/img/header_email.jpg" alt="jota.com.ar" width="100%"><body><h4>Haga click <a href="verificar.php?token=******">AQUI</a> para verificar su cuenta</h4><p> No responda este mensaje, el remitente es una dirección de notificación</p></body>';
    const EMAIL_RECUPERO = '<img src="https://saupureinconsulting.com.ar/img/header_email.jpg" alt="jota.com.ar" width="100%"><body><h4>Haga click <a href="passwordnuevo.php?token=******">AQUI</a> para volver a generar su password</h4><p> No responda este mensaje, el remitente es una dirección de notificación</p></body>';
    
    /************************** FIN cuerpos de emails *************************/


    
    

}












?>