<?php
error_reporting(E_ALL);

ini_set('ignore_repeated_errors', TRUE); // always use TRUE

ini_set('display_errors', true); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment

ini_set('log_errors', TRUE); // Error/Exception file logging engine.

//ini_set("error_log", "/php8/base/php-error.log");
spl_autoload_register(function($clase){
	include_once "../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');

if (!($Session->exists())) {
	header('Location: ../error.php');
}



$tabla = $_GET['tabla'];
$draw = $_GET['sEcho'];
$start = $_GET['iDisplayStart'];
$length = $_GET['iDisplayLength'];
$busqueda = $_GET['sSearch'];



$colSort = (integer)$_GET['iSortCol_0'] + 2;
$colSortDir = $_GET['sSortDir_0'];

function armarAcciones($id,$label='',$class,$icon) {
	$cad = "";

	for ($j=0; $j<count($class); $j++) {
		$cad .= '<button type="button" class="btn '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button> ';
	}

	return $cad;
}

function armarAccionesDropDown($id,$label='',$class,$icon) {
	$cad = '<div class="btn-group">
					<button class="btn bg-gradient-info dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
					ACCIONES
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

	for ($j=0; $j<count($class); $j++) {
		$cad .= '<li><a href="javascript:;" id="'.$id.'" class="dropdown-item '.$label[$j].'">'.$icon[$j].'</a></li>';

	}

	$cad .= '</ul></div>';

	return $cad;
}

switch ($tabla) {
	case 'rptListadoCheckinout':

		$min = $_GET['start'];
		$max = $_GET['end'];

		$data = new Ubicacionesusuarios();

		$datos = $data->rptListadoCheckinout($length, $start, $busqueda,$colSort,$colSortDir,$min,$max);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array();
		$class = array();
		$icon = array();

		$indiceID = 0;
		$empieza = 1;
		$termina = 6;

	break;
	case 'cargos':

		$data = new Cargos();

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 1;

	break;
	case 'materiales':

		$data = new Materiales();

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar');
		$class = array('bg-blue');
		$icon = array('Modificar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 1;

	break;
	case 'zonas':

		$data = new Zonas();

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 1;

	break;
	case 'tareas':

		$data = new Tareas();

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnFormularios');
		$class = array('bg-blue','bg-orange','bg-orange');
		$icon = array('Modificar','Eliminar','Formularios');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'cuadrillas':
		$refordenestrabajocabecera = $_GET['refordenestrabajocabecera'];
		$data = new Cuadrillas();
		$data->setOrdenestrabajocabecera($refordenestrabajocabecera);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 4;

	break;

	case 'presupuestos':
		$refordenestrabajodetalle = $_GET['refordenestrabajodetalle'];

		$data = new Presupuestos();
		$data->setRefordenestrabajodetalle($refordenestrabajodetalle);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 2;

	break;

	case 'tipoactividades':

		$data = new Tipoactividades();

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 2;

	break;

	case 'formularios':

		$data = new Formularios();

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnPreguntas','btnPrevisualizar');
		$class = array('bg-blue','bg-orange','bg-orange','bg-orange');
		$icon = array('Modificar','Eliminar','Preguntas','Previsualizar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 2;

	break;

	case 'ordenestrabajocabecera':

		$min = $_GET['start'];
		$max = $_GET['end'];
		$prioridad = $_GET['prioridad'];

		$data = new Ordenestrabajocabecera();

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir,$min,$max,$prioridad);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnTareas','btnCuadrilla','btnVer');
		$class = array('bg-blue','bg-orange','bg-success','bg-success','bg-success');
		$icon = array('Modificar','Eliminar','Tareas','Cuadrilla','Ver');

		$indiceID = 0;
		$empieza = 1;
		$termina = 10;

	break;

	case 'ordenestrabajodetalle':

		$refordenestrabajocabecera =(int)$_GET['refordenestrabajocabecera'];

		$data = new Ordenestrabajodetalle('',$refordenestrabajocabecera);
		$data->setRefordenestrabajocabecera($refordenestrabajocabecera);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnPresupuestos');
		$class = array('bg-blue','bg-orange','bg-orange');
		$icon = array('Modificar','Eliminar','Presupuestos');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	
	case 'solicitudesvisitas':

		$min = $_GET['start'];
		$max = $_GET['end'];
		$prioridad = $_GET['prioridad'];

		$data = new Solicitudesvisitas('');

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir,$min,$max,$prioridad);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 8;

	break;
	case 'solicitudvisitadetalles':

		$refsolicitudesvisitas =(int)$_GET['refsolicitudesvisitas'];

		$data = new Solicitudvisitadetalles('',$refsolicitudesvisitas);
		$data->setRefsolicitudesvisitas($refsolicitudesvisitas);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'clientes':

		$data = new Clientes();

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnSucursales');
		$class = array('bg-blue','bg-orange','bg-success');
		$icon = array('Modificar','Eliminar','Sucursales');

		$indiceID = 0;
		$empieza = 1;
		$termina = 6;

	break;

	case 'sucursales':
		$reftabla = $_GET['reftabla'];
		$idreferancia = $_GET['idreferencia'];

		$data = new Sucursales($reftabla,$idreferancia);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 6;

	break;

	case 'tags':
		$reftabla = $_GET['reftabla'];
		$idreferancia = $_GET['idreferencia'];

		$data = new Tags($reftabla,$idreferancia);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 2;

	break;
	case 'formulariosconector':
		$reftabla = $_GET['reftabla'];
		$idreferancia = $_GET['idreferencia'];

		$data = new Formulariosconector($reftabla,$idreferancia);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 1;

	break;
	case 'preguntascuestionario':
		$refformularios = $_GET['refformularios'];

		$data = new Preguntascuestionario();
		$data->setRefformularios($refformularios);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnRespuestas');
		$class = array('bg-blue','bg-orange','bg-orange');
		$icon = array('Modificar','Eliminar','Respuestas');

		$indiceID = 0;
		$empieza = 1;
		$termina = 6;

	break;
	case 'respuestascuestionario':
		$refpreguntascuestionario = $_GET['refpreguntascuestionario'];

		$data = new Respuestascuestionario();
		$data->setRefpreguntascuestionario($refpreguntascuestionario);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 4;

	break;

	
	

	case 'usuarios':

		$data = new Usuarios('','');

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		//var_dump($datos);

		$resAjax = $datos[0];
		$res = $datos[1];

		//var_dump($resAjax);

		$label = array('btnModificar','btnEliminar','btnSucursales','btnTags','btnVer','btnPassword');
		$class = array('bg-blue','bg-orange','bg-success','bg-success','bg-success','bg-warning');
		$icon = array('Modificar','Eliminar','Sucursales','Tags','Ver','Modificar Password');

		$indiceID = 0;
		$empieza = 1;
		$termina = 7;

	break;

	default:
		// code...
		break;
}


$cantidadFilas = $res;


header("content-type: Access-Control-Allow-Origin: *");

$ar = array();
$arAux = array();
$cad = '';
$id = 0;

$UbicacionUsuarios = new Ubicacionesusuarios();

	foreach ($resAjax as $row) {
		//$id = $row[$indiceID];
		// forma local utf8_decode
		//var_dump($row[0][0]);
		//die();
		switch ($tabla) {
			case 'rptListadoCheckinout':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						switch ($i) {
							case 5:
								//die(var_dump($row[7]));
								$cad = $UbicacionUsuarios->devolverDireccion($row[7],$row[8]);
								array_push($arAux, ( $cad));
							break;
							case 6:
								
								$cad = '<span class="badge badge-sm bg-gradient-info"><a href="'.$row[$i].'" target="_blank" style="color:white;">Ver</a></span>';
								array_push($arAux, ( $cad));
							break;
							default:
								array_push($arAux, ( substr($row[$i],0,40)));
							break;
						}
	
					} else {
						array_push($arAux, ( $row[$i]));
					}
					
				}

			break;
			case 'ordenestrabajocabecera':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						switch ($i) {
							case 6:
								if ($row[$i] == '3-Baja') {
									$cad = '<span class="badge badge-sm bg-gradient-success">Baja</span>';
								} else {
									if ($row[$i] == '2-Media') {
										$cad = '<span class="badge badge-sm bg-gradient-warning">Media</span>';
									} else {
										$cad = '<span class="badge badge-sm bg-gradient-danger">Alta</span>';
									}
								}
								array_push($arAux, ( $cad));
							break;
							default:
								array_push($arAux, ( substr($row[$i],0,40)));
							break;
						}
	
					} else {
						array_push($arAux, ( $row[$i]));
					}
					
				}
			break;
			case 'solicitudesvisitas':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						switch ($i) {
							case 6:
								if ($row[$i] == '3-Baja') {
									$cad = '<span class="badge badge-sm bg-gradient-success">Baja</span>';
								} else {
									if ($row[$i] == '2-Media') {
										$cad = '<span class="badge badge-sm bg-gradient-warning">Media</span>';
									} else {
										$cad = '<span class="badge badge-sm bg-gradient-danger">Alta</span>';
									}
								}
								array_push($arAux, ( $cad));
							break;
							default:
								array_push($arAux, ( substr($row[$i],0,40)));
							break;
						}
	
					} else {
						array_push($arAux, ( $row[$i]));
					}
					
				}
			break;
			default:
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						if (strlen($row[$i])>=40) {
							array_push($arAux, ( substr($row[$i],0,39)));
						} else {
							array_push($arAux, ( $row[$i]));
						}
						
					} else {
						array_push($arAux, ( $row[$i]));
					}
					
				}
			break;
		}
		
		
		array_push($arAux, armarAccionesDropDown($row[0],$label,$class,$icon));
		
		
		array_push($ar, $arAux);

		$arAux = array();
		//die(var_dump($ar));
	}

$cad = substr($cad, 0, -1);

$data = '{ "sEcho" : '.$draw.', "iTotalRecords" : '.$cantidadFilas.', "iTotalDisplayRecords" : 10, "aaData" : ['.$cad.']}';

//echo "[".substr($cad,0,-1)."]";
echo json_encode(array(
			"draw"            => $draw,
			"recordsTotal"    => $cantidadFilas,
			"recordsFiltered" => $cantidadFilas,
			"data"            => $ar
		));

?>
