<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$entity = new Solicitudesvisitas('');

$ot = new Ordenestrabajocabecera();



/* verificar permisos */


/* fin de los permisos */

/* variables del post */

$id = $_POST['id'];

/* fin de variables del post */

/* seteo del objeto */

$entity->buscarPorId($id);

/***** arrays para las solicitudes  ******/
$arSV = [];
$arSVsimple = [];
$arTareasSolicitudes = [];
$arFormulariosSolicitudes = [];
$arFormulariosPreguntasSolicitudes = [];
$arFormulariosRespuestasSolicitudes = [];
/******  fin de los array *******/


/***** arrays para las OT  ******/
$arOT = [];
$arOTsimple = [];
$arTareasOT = [];
$arFormulariosOT = [];
$arFormulariosPreguntasOT = [];
$arFormulariosRespuestasOT = [];
/******  fin de los array *******/


/* fin del seteo */


if ($entity != null) {
    $arSV  = $entity->devolverArray();



    

    $tareasSV = new Solicitudvisitadetalles('',$id);
    $tareasSV->setRefsolicitudesvisitas($id);

    foreach ($tareasSV->traerTodosPorCabecera() as $row) {
        $tareasSVsimple = new Solicitudvisitadetalles('',$id);
        $tareasSVsimple->buscarPorId($row['id']);
        $arSVsimple = $tareasSVsimple->devolverArray();

        $formularios = new Formulariosconector(3,$row['reftareas']);

        
        $arFormulariosSolicitudes = [];
        $arFormulariosPreguntasSolicitudes = [];
        $arFormulariosRespuestasSolicitudes = [];

        foreach ($formularios->traerPorReferencia() as $rowF) {
            
            $form = new Formularios();
            $form->buscarPorId($rowF['refformularios']);

            $arFormulariosPreguntasSolicitudes = [];
            $arFormulariosRespuestasSolicitudes = [];

            $preguntas = new Preguntascuestionario();
            foreach ($preguntas->traerTodosFilter(['refformularios'=> $rowF['refformularios']]) as $rowP) {
                $respuestas = new Respuestascuestionario();

                $arFormulariosRespuestasSolicitudes = [];

                foreach ($respuestas->traerTodosFilter(['refpreguntascuestionario'=> $rowP['id']]) as $rowR) {
                    array_push($arFormulariosRespuestasSolicitudes,[
                        'id'=> $rowR['id'],
                        'respuesta'=> $rowR['respuesta'],
                        'leyenda'=> $rowR['leyenda']
                    ]);
                }

                array_push($arFormulariosPreguntasSolicitudes,[
                    'id'=> $rowP['id'],
                    'pregunta'=> $rowP['pregunta'],
                    'obligatoria'=> $rowP['obligatoria'],
                    'leyenda'=> $rowP['leyenda'],
                    'tiporespuesta'=> $rowP['tiporespuesta'],
                    'respuestas' => $arFormulariosRespuestasSolicitudes
                ]);
            }

            array_push($arFormulariosSolicitudes,[
                'id'=> $rowF['refformularios'],
                'formulario'=> $form->getFormulario(),
                'preguntas'=> $arFormulariosPreguntasSolicitudes
            ]);
        }

        array_push($arTareasSolicitudes,array(
            'id' => $row['reftareas'],
            'tarea'=> $arSVsimple['tarea'],
            'observaciones'=> $row['observaciones'],
            'estado' => $arSVsimple['estado'],
            'formularios' => $arFormulariosSolicitudes
        ));

    }

    array_push($arSV,array('tareas'=> $arTareasSolicitudes));

    $ot = new Ordenestrabajocabecera();

    $ot->buscarPorValor('refsolicitudesvisitas',$id);

    //existen ot
    if ($ot->getId()>0) {
        $arOT = $ot->devolverArray();

        $tareasOT = new Ordenestrabajodetalle('',$ot->getId());
        $tareasOT->setRefordenestrabajocabecera($ot->getId());

        foreach ($tareasOT->traerTodosPorCabecera() as $row) {
            $tareasOTsimple = new Ordenestrabajodetalle('',$ot->getId());
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
                foreach ($preguntas->traerTodosFilter(['refformularios'=> $rowF['refformularios']]) as $rowP) {
                    $respuestas = new Respuestascuestionario();
    
                    $arFormulariosRespuestasOT = [];
    
                    foreach ($respuestas->traerTodosFilter(['refpreguntascuestionario'=> $rowP['id']]) as $rowR) {
                        array_push($arFormulariosRespuestasOT,[
                            'id'=> $rowR['id'],
                            'respuesta'=> $rowR['respuesta'],
                            'leyenda'=> $rowR['leyenda']
                        ]);
                    }
    
                    array_push($arFormulariosPreguntasOT,[
                        'id'=> $rowP['id'],
                        'pregunta'=> $rowP['pregunta'],
                        'obligatoria'=> $rowP['obligatoria'],
                        'leyenda'=> $rowP['leyenda'],
                        'tiporespuesta'=> $rowP['tiporespuesta'],
                        'respuestas' => $arFormulariosRespuestasOT
                    ]);
                }
    
                array_push($arFormulariosOT,[
                    'id'=> $rowF['refformularios'],
                    'formulario'=> $form->getFormulario(),
                    'preguntas'=> $arFormulariosOT
                ]);
            }
    
            array_push($arTareasOT,array(
                'id' => $row['reftareas'],
                'tarea'=> $arSVsimple['tarea'],
                'observaciones'=> $row['observaciones'],
                'estado' => $arSVsimple['estado'],
                'formularios' => $arFormulariosOT
            ));
    
        }

    }

    array_push($arOT,array('tareas'=> $arTareasOT));

    $resV['solicitudvisita'] = $arSV;
    $resV['ot'] = $arOT;

    /*


    $resV['tareassolicitudes'] = $arTareasSolicitudes;

    $ot->buscarPorValor('refsolicitudesvisitas',$id);

    //existen ot
    if ($ot->getId()>0) {
        $ot->getEstados()->buscarPorId($ot->getRefestados());
        $ot->getSemaforo()->buscarPorId($ot->getRefsemaforo());

        $resV['ot'] = array(
            'estado' => $ot->getEstados()->getEstado(),
            'semaforo'=> $ot->getSemaforo()->getNivel(),
            'fecha' => $ot->getFecha()
        );

        $tareasOT = new Ordenestrabajodetalle('',$ot->getId());
        $tareasOT->setRefordenestrabajocabecera($ot->getId());

        foreach ($tareasOT->traerTodosPorCabecera() as $row) {
            $tareas = new Tareas();
            $tareas->buscarPorId($row['reftareas']);
            $estados = new Estados();
            $estados->buscarPorId($row['refestados']);
            array_push($arTareasOT,array(
                'tarea'=> $tareas->getTarea(),
                'observaciones'=> $row['observaciones'],
                'estado' => $estados->getEstado()
            ));
        }

        $resV['tareasot'] = $arTareasOT;
    }
    */

    $resV['error'] = false;
} else {
    $resV['solicitudvisita'] ='';
    $resV['error'] = true;
}

header('Content-type: application/json');
echo json_encode($resV);


?>

