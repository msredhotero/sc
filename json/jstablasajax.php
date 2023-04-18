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
$archivo=0;

if (isset($_GET['methodAcciones'])) {
	$methodAcciones = $_GET['methodAcciones'];
} else {
	$methodAcciones = 1;
}


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
					<button class="btn bg-gradient-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
	case 'porteriashistorico':

		$accion = $_GET['accion'];

		$data = new Porterias();
		$data->setRefacciones($accion);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir,$accion);

		$resAjax = $datos[0];
		$res = $datos[1];

		//die(var_dump($datos[1]));

		$label = array('btnConductores');
		$class = array('bg-ambar');
		$icon = array('Pasajeros');

		$indiceID = 0;
		$empieza = 1;
		$termina = 9;

	break;
	case 'porterias':

		$accion = $_GET['accion'];

		$data = new Porterias();
		$data->setRefacciones($accion);

		$datos = $data->traerAjax($length, $start, $busqueda,$colSort,$colSortDir,$accion);

		$resAjax = $datos[0];
		$res = $datos[1];

		//die(var_dump($datos[1]));

		$label = array('btnConductores');
		$class = array('bg-ambar');
		$icon = array('Pasajeros');

		$indiceID = 0;
		$empieza = 1;
		$termina = 8;

	break;
	case 'mantenimiento':

		$data = new Mantenimientoflota();
		if (isset($_GET['refcamiones'])) {
			$data->setRefcamiones($_GET['refcamiones']);
		}

		$datos = $data->traerAjaxMantenimiento($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		//die(var_dump($datos[1]));

		$label = array();
		$class = array();
		$icon = array('');

		$indiceID = 0;
		$empieza = 1;
		$termina = 5;

	break;
	case 'tiposervicios':

		$data = new Tiposervicios();

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
	case 'marcas':

		$data = new Marcas();

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
	case 'aseguradoras':

		$entity = new Aseguradoras();

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

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

		$entity = new Tareas();

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 6;

	break;
	case 'cargos':

		$entity = new Cargos();

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 1;

	break;
	case 'areas':

		$entity = new Areas();

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 1;

	break;
	case 'personal':

		$entity = new Personal();

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnGestionDocumental');
		$class = array('bg-blue','bg-orange','bg-orange');
		$icon = array('Modificar','Eliminar','Gestion Documental');

		$indiceID = 0;
		$empieza = 1;
		$termina = 9;

	break;
	case 'activos':

		$entity = new Activos();

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'emisionescontaminantes':
		$refcamiones = $_GET['refcamiones'];
		
		$entity = new Emisionescontaminantes($refcamiones);

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnDocumentos');
		$class = array('bg-blue','bg-orange','Archivo');
		$icon = array('Modificar','Eliminar','Archivo');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'mantenimientoflota':

		$entity = new Mantenimientoflota();

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'archivosflota':
		$refcamiones = $_GET['refcamiones'];
		$refarchivos = $_GET['refarchivos'];
		$entity = new Archivosflota($refcamiones);
		$entity->setRefarchivos($refarchivos);

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnDocumentos');
		$class = array('bg-blue','bg-orange','Archivo');
		$icon = array('Modificar','Eliminar','Archivo');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'archivospersonal':
		$refpersonal = $_GET['refpersonal'];
		$refarchivos = $_GET['refarchivos'];
		$entity = new Archivospersonal($refpersonal);
		$entity->setRefarchivos($refarchivos);

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnDocumentos');
		$class = array('bg-blue','bg-orange','Archivo');
		$icon = array('Modificar','Eliminar','Archivo');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'permisoscirculacion':
		$refcamiones = $_GET['refcamiones'];
		$entity = new Permisoscirculacion($refcamiones);

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnDocumentos');
		$class = array('bg-blue','bg-orange','Archivo');
		$icon = array('Modificar','Eliminar','Archivo');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'revisionestecnicas':
		$refcamiones = $_GET['refcamiones'];
		$entity = new Revisionestecnicas($refcamiones);

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnDocumentos');
		$class = array('bg-blue','bg-orange','btnDocumentos');
		$icon = array('Modificar','Eliminar','Archivo');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'seguros':
		$refcamiones = $_GET['refcamiones'];
		$entity = new Seguros($refcamiones);

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnDocumentos');
		$class = array('bg-blue','bg-orange','bg-orange');
		$icon = array('Modificar','Eliminar','Archivo');

		$indiceID = 0;
		$empieza = 1;
		$termina = 5;

	break;
	case 'proveedores':

		$entity = new Proveedores();

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

	break;
	case 'camiones':
		$refactivos = $_GET['refactivos'];
		$entity = new Camiones();

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir,$refactivos);

		$resAjax = $datos[0];
		$res = $datos[1];

		$label = array('btnModificar','btnEliminar','btnGestionDocumental');
		$class = array('bg-blue','bg-orange','bg-orange');
		$icon = array('Modificar','Eliminar','Gestion Documental');

		$indiceID = 0;
		$empieza = 1;
		$termina = 11;

	break;

	case 'ordenestrabajos':
		if (isset($_GET['refcamiones'])) {
			$refcamiones = (integer)$_GET ['refcamiones'];
		} else {
			$refcamiones = 0;
		}

		if (isset($_GET['archivo'])) {
			$archivo = (integer)$_GET ['archivo'];
		} else {
			$archivo = 0;
		}

		if (isset($_GET['sinhistorico'])) {
			$sinhistorico = '1';
		} else {
			$sinhistorico = '';
		}

		

		$tipo = (integer)$_GET ['tipo'];
		//die(var_dump($refcamiones));
		$entity = new Ordenestrabajos($refcamiones,$tipo);
		

		$datos = $entity->traerAjax($length, $start, $busqueda,$colSort,$colSortDir,$sinhistorico);

		$resAjax = $datos[0];
		$res = $datos[1];
		// **roles
        // si los roles son diferentes de mecanico y jefe de taller
        if (!(($_SESSION['user']->getRefroles()==5)||($_SESSION['user']->getRefroles()==6))) { 
			$label = array('btnModificar','btnEliminar','btnArchivo');
			$class = array('bg-blue','bg-orange','bg-orange');
			$icon = array('Modificar','Eliminar','Archivo');
		} else {
			$label = array('btnModificar');
			$class = array('bg-blue');
			$icon = array('Modificar');
		}
		

		$indiceID = 0;
		$empieza = 1;
		if ($archivo==1) {
			$termina = 7;
		} else {
			$termina = 6;
		}
		

	break;

	case 'usuarios':

		$Usuarios = new Usuarios('','');

		$datos = $Usuarios->traerAjax($length, $start, $busqueda,$colSort,$colSortDir);

		//var_dump($datos);

		$resAjax = $datos[0];
		$res = $datos[1];

		//var_dump($resAjax);

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-blue','bg-orange');
		$icon = array('Modificar','Eliminar');

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

	foreach ($resAjax as $row) {
		//$id = $row[$indiceID];
		// forma local utf8_decode
		//var_dump($row[0][0]);
		//die();

		switch ($tabla) {
			case 'camiones':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						if ($i == 1) {
							if ($row[$i] == 'Si') {
								$cad = '<span class="badge badge-sm bg-gradient-success">Si</span>';
							} else {
								$cad = '<span class="badge badge-sm bg-gradient-danger">No</span>';
							}
							array_push($arAux, ( $cad));
						} else {
							if ($i == 2) {
								if ($row[$i] == 'Si') {
									$cad = '<span class="badge badge-sm bg-gradient-danger">Si</span>';
								} else {
									$cad = '<span class="badge badge-sm bg-gradient-success">No</span>';
								}
								array_push($arAux, ( $cad));
							} else {
								array_push($arAux, ( substr($row[$i],0,60)));
							}
						}
						
						
					} else {
						array_push($arAux, ( $row[$i]));
					}
					
				}
			break;
			case 'archivosflota':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						if ($i == 3) {
							
							$cad = '<a target="_blank" href="'.$row[$i].'"><i class="fas fa-file-pdf text-lg me-1"></i> Descargar</a>';
							array_push($arAux, ( $cad));
						} else {
							array_push($arAux, ( substr($row[$i],0,60)));
						}
					} else {
						array_push($arAux, ( $row[$i]));
					}
				}
			break;
			case 'archivospersonal':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						if ($i == 3) {
							
							$cad = '<a target="_blank" href="'.$row[$i].'"><i class="fas fa-file-pdf text-lg me-1"></i> Descargar</a>';
							array_push($arAux, ( $cad));
						} else {
							array_push($arAux, ( substr($row[$i],0,60)));
						}
					} else {
						array_push($arAux, ( $row[$i]));
					}
				}
			break;
			case 'emisionescontaminantes':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						if ($i == 3) {
							
							$cad = '<a target="_blank" href="'.$row[$i].'"><i class="fas fa-file-pdf text-lg me-1"></i> Descargar</a>';
							array_push($arAux, ( $cad));
						} else {
							array_push($arAux, ( substr($row[$i],0,60)));
						}
					} else {
						array_push($arAux, ( $row[$i]));
					}
				}
			break;
			case 'revisionestecnicas':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						if ($i == 3) {
							
							$cad = '<a target="_blank" href="'.$row[$i].'"><i class="fas fa-file-pdf text-lg me-1"></i> Descargar</a>';
							array_push($arAux, ( $cad));
						} else {
							array_push($arAux, ( substr($row[$i],0,60)));
						}
					} else {
						array_push($arAux, ( $row[$i]));
					}
				}
			break;
			case 'seguros':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						if ($i == 5) {
							$cad = '<a target="_blank" href="'.$row[$i].'"><i class="fas fa-file-pdf text-lg me-1"></i> Descargar</a>';
							array_push($arAux, ( $cad));
						} else {
							array_push($arAux, ( substr($row[$i],0,60)));
						}
					} else {
						array_push($arAux, ( $row[$i]));
					}
				}
			break;
			case 'permisoscirculacion':
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						if ($i == 3) {
							$cad = '<a target="_blank" href="'.$row[$i].'"><i class="fas fa-file-pdf text-lg me-1"></i> Descargar</a>';
							array_push($arAux, ( $cad));
						} else {
							array_push($arAux, ( substr($row[$i],0,60)));
						}
					} else {
						array_push($arAux, ( $row[$i]));
					}
				}
			break;
			case 'ordenestrabajos' && $archivo==1:
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						if ($i == 7) {
							$cad = '<a target="_blank" href="'.$row[$i].'"><i class="fas fa-file-pdf text-lg me-1"></i> Descargar</a>';
							array_push($arAux, ( $cad));
						} else {
							array_push($arAux, ( substr($row[$i],0,60)));
						}
					} else {
						array_push($arAux, ( $row[$i]));
					}
				}
			break;
			default:
				for ($i=$empieza;$i<=$termina;$i++) {
					if ($row[$i] != '') {
						array_push($arAux, ( substr($row[$i],0,60)));
					} else {
						array_push($arAux, ( $row[$i]));
					}
				}	
			break;
		}


		

		
		if ((count($label)>0) && ($methodAcciones == 1)) {
			array_push($arAux, armarAccionesDropDown($row[0],$label,$class,$icon));
		}

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
