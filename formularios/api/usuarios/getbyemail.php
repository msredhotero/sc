<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Usuarios = new Usuarios('','');

$entity = new Solicitudesvisitas('');

$ot = new Ordenestrabajocabecera();

/* verificar permisos */


/* fin de los permisos */

/* variables del post */

$email = $_POST['email'];

/* fin de variables del post */

/* seteo del objeto */

$Usuarios->buscarUsuarioPorValor('email',$email);

/* fin del seteo */


if ($Usuarios != null) {

  /* fin de variables del post */
  $resOT = $ot::traerTodosPorUsuario($Usuarios->getId());
  /* seteo del objeto */

    
  /***** arrays para las solicitudes  ******/
  $arSV = [];
  $arSVsimple = [];
  $arTareasSolicitudes = [];
  $arFormulariosSolicitudes = [];
  $arFormulariosPreguntasSolicitudes = [];
  $arFormulariosRespuestasSolicitudes = [];
  /******  fin de los array *******/


  /***** arrays para las OT  ******/
  $arOTall = [];
  $arOT = [];
  $arOTsimple = [];
  $arTareasOT = [];
  $arFormulariosOT = [];
  $arFormulariosPreguntasOT = [];
  $arFormulariosRespuestasOT = [];
  /******  fin de los array *******/


  /* fin del seteo */
  $i = 0;

  if (count($resOT)>0) {
      
      foreach($resOT as $rowO) {

          //die(var_dump($rowO['id']));

          $arOT = array(
              'id' => $rowO['id'],
              'nivel'=>$rowO['nivel'],
              'estado'=>$rowO['estado'],
              'fecha'=>$rowO['fecha'],
              'fechafin'=>$rowO['fechafin'],
              'actividad'=>$rowO['actividad'],
              'cliente'=>$rowO['cliente'],
              'sucursal'=>$rowO['sucursal'],
              'latitud'=>$rowO['latitud'],
              'longitud'=>$rowO['longitud'],
              'zona'=>$rowO['zona'],
          );

          $arTareasOT = [];


          $tareasOT = new Ordenestrabajodetalle('',$rowO['id']);
          $tareasOT->setRefordenestrabajocabecera($rowO['id']);

          $resTareasOD = $tareasOT->traerTodosPorCabecera();
          //die(var_dump($resTareasOD));
          foreach ($resTareasOD as $row) {

              $tareasOTsimple = new Ordenestrabajodetalle('',$rowO['id']);
              $tareasOTsimple->buscarPorId($row['id']);
              $arOTsimple = $tareasOTsimple->devolverArray();
      
              $formularios = new Formulariosconector(3,$row['reftareas']);
      
              
              $arFormulariosOT = [];
              $arFormulariosPreguntasOT = [];
              $arFormulariosRespuestasOT = [];
      
              foreach ($formularios->traerPorReferencia() as $rowF) {
                  
                  $form = new Formularios();
                  $form->buscarPorId($rowF['refformularios']);
      
                  $arFormulariosPreguntasOT = [];
                  $arFormulariosRespuestasOT = [];
      
                  $preguntas = new Preguntascuestionario();

                  
                  //preguntas
                  foreach ($preguntas->traerTodosFilter(array('refformularios'=> $rowF['refformularios'])) as $rowP) {
                      
                      $respuestas = new Respuestascuestionario();
      
                      $arFormulariosRespuestasOT = [];
                      
                      
                      //respuestas
                      foreach ($respuestas->traerTodosFilter(['refpreguntascuestionario'=> $rowP['id']]) as $rowR) {

                          
                          //junto respuestas
                          array_push($arFormulariosRespuestasOT,[
                              'id'=> $rowR['id'],
                              'respuesta'=> $rowR['respuesta'],
                              'leyenda'=> $rowR['leyenda']
                          ]);
                      }
                      
                      //junto preguntas
                      array_push($arFormulariosPreguntasOT,[
                          'id'=> $rowP['id'],
                          'pregunta'=> $rowP['pregunta'],
                          'obligatoria'=> $rowP['obligatoria'],
                          'leyenda'=> $rowP['leyenda'],
                          'tiporespuesta'=> $rowP['tiporespuesta'],
                          'respuestas' => $arFormulariosRespuestasOT
                      ]);

                      
                  }

                  //die(var_dump($arFormulariosPreguntasOT));
                  
                  //junto formularios
                  array_push($arFormulariosOT,[
                      'id'=> $rowF['refformularios'],
                      'formulario'=> $form->getFormulario(),
                      'preguntas'=> $arFormulariosPreguntasOT
                  ]);

                  
              }
              
              //junto tareas
              array_push($arTareasOT,array(
                  'id' => $row['reftareas'],
                  'tarea'=> $arOTsimple['tarea'],
                  'observaciones'=> $row['observaciones'],
                  'estado' => $arOTsimple['estado'],
                  'formularios' => $arFormulariosOT
              ));
              
              
      
          }
          //
          array_push($arOT,array('tareas'=> $arTareasOT));

          array_push($arOTall,$arOT);

      }

      

      $resV['ot'] = $arOTall;

      $resV['error'] = false;
  } else {
      $resV['ot'] ='';
      $resV['error'] = true;
  }

  $resV['usuario'] = $Usuarios->devolverArray();
  $resV['error'] = false;

} else {
  $resV['usuario'] ='';
  $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

