<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();

$entity = new Solicitudesvisitas('');

$ot = new Ordenestrabajocabecera();
$Ubicacion = new Ubicacionesusuarios();
$Materiales = new Materiales();

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
            'asignado'=>$rowO['asignado'],
            'nroaviso'=>$rowO['nroaviso'],
            'nombre_suc'=>$rowO['nombre_suc']
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
                    if ($rowP['reftiporespuesta'] == 7) {
                        foreach($Materiales->traerTodos() as $rowMateriales) {
                            //junto respuestas
                            array_push($arFormulariosRespuestasOT,[
                                'id'=> $rowMateriales['id'],
                                'respuesta'=> $rowMateriales['material'],
                                'leyenda'=> ''
                            ]);
                        }
                    } else {
                        if ($rowP['reftiporespuesta'] == 8) {
                            //die(var_dump($row['id']));
                            $Tabla = new Tablas($rowP['reftabladatos']);
                            $Tabla->setIdreferencia($row['id']);
                            $Tabla->setColumna($rowP['columna']);

                            $lstDato = $Tabla->devolverValor();

                            foreach ($lstDato as $dato) {
                                array_push($arFormulariosRespuestasOT,[
                                    'id'=> $rowP['columna'],
                                    'respuesta'=> $Tabla->getArSolicitudes($rowP['columna']).': '.$dato[0],
                                    'leyenda'=> ''
                                ]);
                            }
                        } else {
                            foreach ($respuestas->traerTodosFilter(['refpreguntascuestionario'=> $rowP['id']]) as $rowR) {

                        
                                //junto respuestas
                                array_push($arFormulariosRespuestasOT,[
                                    'id'=> $rowR['id'],
                                    'respuesta'=> $rowR['respuesta'],
                                    'leyenda'=> $rowR['leyenda']
                                ]);
                            }
                        }
                        
                    }
                    
                    $respuestaValor = '';
                    //traigo las respuestas que pueden haber sido cargadas, siempre y cuando la ot no este finalizada
                    if ($rowO['refestados'] !== 3) {
                        //busco la respuesta si existe
                        $FormuladriosDetalles = new Formulariosdetalles('');
                        $FormuladriosDetalles->setRefformulariosconector($rowF['id']);
                        $FormuladriosDetalles->setReftabla(4);
                        $FormuladriosDetalles->setIdreferencia($row['id']);
                        $FormuladriosDetalles->setRefpreguntascuestionario($rowP['id']);
                        $lstFormularioDetalleRespuestas = $FormuladriosDetalles->traerPorReferenciaSimple();
                        if ($rowP['reftiporespuesta'] == 7) {
                            
                            $respuestaValor = $FormuladriosDetalles->traerPorReferenciaSimplePresupuesto();
                        } else {
                            foreach($lstFormularioDetalleRespuestas as $rv) {

                                if ($rowP['reftiporespuesta'] == 8) {
                                    $respuestaValor = array('id'=>$rowP['columna'],'respuesta'=>$Tabla->getArSolicitudes($rowP['columna']).': '.$dato[0]);
                                } else {
                                    if ($rowP['reftiporespuesta'] == 7) {
                                        $respuestaValor = array('id'=>$rv['refrespuestascuestionario'],'respuesta'=>$rv['respuesta']);
                                    } else {
                                        //$respuestaValor = array('id'=>$rv['refrespuestascuestionario'],'respuesta'=>$rv['respuesta']);
                                        $respuestaValor = $rv['respuesta'];
                                    }
                                    
                                }
    
                            }
                        }
                        
                    }

                    
                    //junto preguntas
                    array_push($arFormulariosPreguntasOT,[
                        'id'=> $rowP['id'],
                        'pregunta'=> $rowP['pregunta'],
                        'obligatoria'=> $rowP['obligatoria'],
                        'leyenda'=> $rowP['leyenda'],
                        'tiporespuesta'=> $rowP['tiporespuesta'],
                        'respuestas' => $arFormulariosRespuestasOT,
                        'respuestaCargada'=> $respuestaValor
                    ]);

                    
                }

                //die(var_dump($arFormulariosPreguntasOT));
                
                //junto formularios
                array_push($arFormulariosOT,[
                    'id'=> $rowF['refformularios'],
                    'formulario'=> $form->getFormulario(),
                    'preguntas'=> $arFormulariosPreguntasOT,
                    'refformularioconector' => $rowF['id']
                ]);

                
            }
            
            //junto tareas
            array_push($arTareasOT,array(
                'id' => $row['reftareas'],
                'tarea'=> $arOTsimple['tarea'],
                'observaciones'=> $row['observaciones'],
                'estado' => $arOTsimple['estado'],
                'formularios' => $arFormulariosOT,
                'idotd' => $row['id']
            ));
            
            
    
        }
        //
        array_push($arOT,array('tareas'=> $arTareasOT));

        array_push($arOTall,$arOT);

    }

    

    $resV['ot'] = $arOTall;
    $resV['ultimocheckin'] = $ultimocheck;

    $resV['error'] = false;
} else {
    $resV['ot'] ='';
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

