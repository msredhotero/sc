<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Ordenestrabajocabecera = new Ordenestrabajocabecera();
$SolicitudVisita = new Solicitudesvisitas('');
$Respuestas = new Respuestascuestionario();

$refordenestrabajocabecera = $_GET['id'];

$Ordenestrabajocabecera->buscarPorId($refordenestrabajocabecera);

$SolicitudVisita->buscarPorId($Ordenestrabajocabecera->getRefsolicitudesvisitas());

$arSV = $SolicitudVisita->devolverArray();

$arOT = $Ordenestrabajocabecera->devolverArray();

$Ordenestrabajodetalle = new Ordenestrabajodetalle($_SESSION['user']->getUsername(),$refordenestrabajocabecera);

$resTareasOD = $Ordenestrabajodetalle::traerTodosPorCabeceraNuevo($refordenestrabajocabecera);

$Cuadrillas = new Cuadrillas();

$Cuadrillas->setRefordenestrabajocabecera($refordenestrabajocabecera);

if (!($Session->exists())) {
  header('Location: ../../error.php');
}

$Menu = new Menu($_SESSION['user']->getRefroles(),'../');
//$_SESSION['user']->getRefroles()

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../');
}

$ruta = $Ordenestrabajodetalle::RUTA;

$options['activo'] = 'ordenestrabajocabecera';
$options['ids'] = '8,9,10';

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
    <title>
    <?php echo $Globales::$tituloWeb; ?>
    </title>
    <?php echo $Menu->printCSS(); ?>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main" data-color="<?php echo $Globales::COLORHTML;?>">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://jota.com.ar" target="_blank">
        <img src="../../assets/img/LOGO1-02.png" class="navbar-brand-img h-100" alt="main_logo">
        
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        
        
        <?php echo $Menu->MenuStr($options); ?>
        <li class="nav-item">
          <a class="nav-link  " href="../../logout.php">
            <div class="icon icon-shape icon-sm bg-gradient-warning shadow text-center border-radius-md d-flex align-items-center justify-content-center">
            <i class="fas fa-arrow-right text-lg opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Salir</span>
          </a>
        </li>
      </ul>
    </div>
    
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
         
          <ul class="navbar-nav  justify-content-end">
            
            
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            
            
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <!-- la vista de lo cargado -->
    <div class="container-fluid py-4">

        <button type="button" class="btn bg-gradient-secondary btnVolver">
            VOLVER
        </button>
        <div class="row">
          <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Solicitud de Visita</h6>
                    </div>
                    
                </div>
                </div>
                <div class="card-body px-2 pb-4">
                    <div class="row">
                        <div class="col-4">CLIENTE: <b><?php echo $arSV['cliente']; ?></b></div>
                        <div class="col-4">SUCURSAL: <b><?php echo $arSV['sucursal']; ?></b></div>
                        <div class="col-4">ACTIVIDAD: <b><?php echo $arSV['actividad']; ?></b></div>
                        <div class="col-4">FECHA: <b><?php echo $arSV['fecha']; ?></b></div>
                        <div class="col-4">PRIORIDAD-SOLICITUD: <b><?php echo $arSV['prioridad']; ?></b></div>
                        <div class="col-4">PRIORIDAD-OT: <b><?php echo $arOT['prioridad']; ?></b></div>
                        <div class="col-4">ESTADO: <b><?php echo $arOT['estado']; ?></b></div>
                        <div class="col-4">ZONA: <b><?php echo $arSV['zona']; ?></b></div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="row" style="margin-top: 15px;">
            <?php require('../../grillas/'.$ruta.'.php'); ?>
        </div>
        <div class="row" style="margin-top: 15px;">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                        <h6>Formularios Cargados</h6>
                        </div>
                        
                    </div>
                    </div>
                    <div class="card-body px-2 pb-4">

          <?php
            $primeroTarea='';
            
            foreach ($resTareasOD as $row) {
              
              $tareasOTsimple = new Ordenestrabajodetalle('',$refordenestrabajocabecera);
              $tareasOTsimple->buscarPorId($row['id']);
              $arOTsimple = $tareasOTsimple->devolverArray();
      
              $formularios = new Formulariosconector(3,$row['reftareas']);

              foreach ($formularios->traerPorReferencia() as $rowF) {
                $form = new Formularios();
                $form->buscarPorId($rowF['refformularios']);
    
                $arFormulariosPreguntasOT = [];
                $arFormulariosRespuestasOT = [];
    
                $preguntas = new Preguntascuestionario();

                
                //preguntas
                foreach ($preguntas->traerTodosFilter(array('refformularios'=> $rowF['refformularios'])) as $rowP) {
                  
                  
                  $FormuladriosDetalles = new Formulariosdetalles('');
                  $FormuladriosDetalles->setRefformulariosconector($rowF['id']);
                  $FormuladriosDetalles->setReftabla(4);
                  $FormuladriosDetalles->setIdreferencia($row['id']);
                  $FormuladriosDetalles->setRefpreguntascuestionario($rowP['id']);
                  $lstFormularioDetalleRespuestas = $FormuladriosDetalles->traerPorReferenciaSimple();

                  if (count($lstFormularioDetalleRespuestas)>0) {
                    //aca hacemos todo

                    if ($primeroTarea !== $arOTsimple['tarea']) {
                      echo "<h3>Tarea: ".$arOTsimple['tarea']."</h3>";
                      $primeroTarea = $arOTsimple['tarea'];
                    }

                    echo '<h4>Pregunta:'.$rowP['pregunta'].'</h4>';
                    
                    //multiple
                    if ($rowP['reftiporespuesta'] == 3) {
                      foreach($lstFormularioDetalleRespuestas as $rv) {
                        if (strpos($rv['respuesta'], '/**/') !== false) {
                          //varias respuestas
                          $arResp = explode('/***/',$rv['respuesta']);
                          foreach ($arResp as $rr) {
                            $Respuestas->buscarPorId((integer)$rr);
                            echo '<p>Respuesta: '.$Respuestas->getRespuesta().'</p>';  
                          }
                        } else {
                          //solo una respuesta
                          $Respuestas->buscarPorId((integer)$rv['respuesta']);
                          echo '<p>Respuesta: '.$Respuestas->getRespuesta().'</p>';
                        }
                        
                      }
                    } else {
                      //archivo o firma
                      if (($rowP['reftiporespuesta'] == 4) || ($rowP['reftiporespuesta'] == 6)) {
                        foreach($lstFormularioDetalleRespuestas as $rv) {
                          echo '<p>Respuesta:</p>';
                          echo '<img src = "data:image/png;base64,' . ($rv['archivo']) . '" width = "30%" height = "30%"/>';
                        }
                      } else {
                        
                          if ($rowP['reftiporespuesta'] == 5) {
                            foreach($lstFormularioDetalleRespuestas as $rv) {
                              echo '<p>Respuesta:</p>';
                            echo '<h4>Latitud: '.$rv['latitud'].'</h4>';
                            echo '<h4>Longitud: '.$rv['longitud'].'</h4>';
                            }
                          } else {
                            if ($rowP['reftiporespuesta'] == 7) {
                              $materiales = new Materiales();
                              foreach($lstFormularioDetalleRespuestas as $rv) {
                                $materiales->buscarPorId($rv['refmateriales']);
                                echo "Material: ".$materiales->getMaterial()." - Cantidad: ".$rv['cantidad'].'<br>';
                              }
                            } else {
                              if ($rowP['reftiporespuesta'] == 8) {
                                
                                foreach($lstFormularioDetalleRespuestas as $rv) {
                                  
                                  $tablas = new Tablas($rv['reftabladatos']);
                                  $tablas->setIdreferencia($rv['idreferencia']); // le seteo el dos porque este lugar no llega a las solicitudes
                                  $tablas->setColumna($rv['columna']);

                                  $lstDato = $tablas->devolverValor();

                                  foreach ($lstDato as $dato) {
                                      echo '<div class="col-6">
                                  <label for="cantidad" class="control-label">'.$tablas->getArSolicitudes($rv['columna']).': '.$dato[0].'</label>
                                  </div>';
                                }
                                
                                }
                              } else {
                                foreach($lstFormularioDetalleRespuestas as $rv) {
                        
                                  echo '<p>Respuesta: '.$rv['respuesta'].'</p>';
                                }
                              }
                            }
                            
                          }
                      }
                    }
                    
                    
                    
                    
                  }
                  
          ?>

                <?php } ?>
            <?php } ?>
          <?php } ?>
          </div>
            </div>
        </div>

        </div>
        <div class="row" style="margin-top: 15px;">
            <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                        <h6>Cuadrilla Cargadas</h6>
                        </div>
                        
                    </div>
                    </div>
                    <div class="card-body px-2 pb-4">
                    <div class="table-responsive">
                        
                        <table id="example2" class="table align-items-center mb-0 display " style="width:100%">
                        <thead>
                            <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Apellido</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cargo</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                            </tr>
                        </thead>
                            
                        </table>
                        
                        <div style="margin-bottom: 140px;"></div>
                        
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- fin del formulario -->
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                desarrollado por
                <?php echo $Globales::FOOTERDASHBOARD; ?>
                
              </div>
            </div>
            
          </div>
        </div>
      </footer>
    </div>

    

    </main>

    <!-- Modal insert -->
    <?php require('../../forms/'.$ruta.'/insertar.php'); ?>

    <?php require('../../forms/'.$ruta.'/modificar.php'); ?>

    <?php require('../../forms/'.$ruta.'/eliminar.php'); ?>
  
    <?php echo $Menu->printJS(); ?>
    <script>
        $(document).ready(function(){
          $('.btnVolver').click(function() {
              
              $(location).attr('href','index.php');
              
          });//fin del boton modificar
          
            var table = $('#example').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../../json/jstablasajax.php?tabla=<?php echo $ruta; ?>&refordenestrabajocabecera=<?php echo $refordenestrabajocabecera; ?>",
                "language": {
                    "emptyTable":     "No hay datos cargados",
                    "info":           "Mostrar _START_ hasta _END_ del total de _TOTAL_ filas",
                    "infoEmpty":      "Mostrar 0 hasta 0 del total de 0 filas",
                    "infoFiltered":   "(filtrados del total de _MAX_ filas)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "Mostrar _MENU_ filas",
                    "loadingRecords": "Cargando...",
                    "processing":     "Procesando...",
                    "search":         "Buscar:",
                    "zeroRecords":    "No se encontraron resultados",
                    "paginate": {
                        "first":      "Primero",
                        "last":       "Ultimo",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                }
            });
            var table2 = $('#example2').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../../json/jstablasajax.php?tabla=cuadrillas&refordenestrabajocabecera=<?php echo $refordenestrabajocabecera; ?>",
                "language": {
                    "emptyTable":     "No hay datos cargados",
                    "info":           "Mostrar _START_ hasta _END_ del total de _TOTAL_ filas",
                    "infoEmpty":      "Mostrar 0 hasta 0 del total de 0 filas",
                    "infoFiltered":   "(filtrados del total de _MAX_ filas)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "Mostrar _MENU_ filas",
                    "loadingRecords": "Cargando...",
                    "processing":     "Procesando...",
                    "search":         "Buscar:",
                    "zeroRecords":    "No se encontraron resultados",
                    "paginate": {
                        "first":      "Primero",
                        "last":       "Ultimo",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                }
            });

            <?php require('../../forms/'.$ruta.'/baseJS.php'); ?>
            
        });
    </script>
</body>

</html>