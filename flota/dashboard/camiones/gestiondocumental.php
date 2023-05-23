<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Camiones = new Camiones();
$Activos = new Activos();
$Marcas = new Marcas();
if (!($Session->exists())) {
  header('Location: ../../error.php');
}

$Menu = new Menu($_SESSION['user']->getRefroles(),'../');
//$_SESSION['user']->getRefroles()

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../');
}

$id = $_GET['id'];

$Camiones->buscarPorId($id);
$Activos->buscarPorId($Camiones->getRefactivos());
$Marcas->buscarPorId($Camiones->getRefmarcas());

$ruta = $Camiones::RUTA;

$options['activo'] = 'Flota';
$options['ids'] = '8,9,10';
$options['refgrupos'] = '2,3';
$options['idobligatorio'] = $id;

$convencimiento = 0;
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
      <a class="navbar-brand m-0" href=" https://cuchipuy.cl" target="_blank">
        <img src="../../assets/img/LOGO1-02.png" class="navbar-brand-img h-100" alt="main_logo">
        
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        
        
        <?php echo $Menu->MenuStr($options); ?>
        <li class="nav-item">
          <a class="nav-link  " href="../../logout.php">
            <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center border-radius-md d-flex align-items-center justify-content-center">
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
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                        <h6><i class="ni ni-settings-gear-65"></i> Gestion Documental del activo</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body px-2 pb-4">
                  <div class="row">
                      <?php require('../../forms/camiones/ver.php'); ?>
                  </div>
                  <div class="row">
                    <?php require('../../grillas/vencimientosflota.php'); ?>
                    <button type="button" class="btn bg-gradient-warning btnGenerar">
                        GENERAR NOTIFICACION
                    </button>
                  </div>
                  <hr>
                  <!-- mantenimiento cargados -->
                  <div class="row" style="margin-top: 15px;">
                      <h6>OT Mantenimiento</h6>
                      <?php 
                      $labeltipo = 'esmantenimiento';
                      ?>
                      <div class="table-responsive">
                          <table id="example2" class="table align-items-center mb-0 display " style="width:100%">
                              <thead>
                                  <tr>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tarea</th>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Activo</th>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Inicio</th>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fin</th>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Realizado</th>
                                  </tr>
                              </thead>
                          </table>
                          <div style="margin-bottom: 40px;"></div>
                      </div>
                  </div>
                  <!-- repareciones cargadas -->
                  <hr>
                    <div class="row" style="margin-top: 15px;">
                        <?php 
                        $labeltipo = 'esreparacion';
                        ?>
                        <h6>OT Reparaciones</h6>
                            <div class="table-responsive">
                                <table id="example3" class="table align-items-center mb-0 display " style="width:100%">
                                    <thead>
                                        <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tarea</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Activo</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Inicio</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fin</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Realizado</th>
                                        </tr>
                                    </thead> 
                                </table>
                                <div style="margin-bottom: 40px;"></div>
                            </div>
                    </div>
                </div><!-- fin del body de la card -->

                    
                    
            </div><!-- fin card -->
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
    

   
  
    <?php echo $Menu->printJS(); ?>
    <script>
        $(document).ready(function(){
          
            $('.btnVolver').click(function() {  
              $(location).attr('href','index.php');
            });

            $('.btnGenerar').click(function() {
              generarNotificacion();
            });

            function generarNotificacion() {
              $.ajax({
                url: '../../ajax/ajax.php',
                type: 'POST',
                // Form data
                //datos del formulario
                data: {
                  accion: 'notificarVencimientos',
                  id: <?php echo $id; ?>
                },
                //mientras enviamos el archivo
                beforeSend: function(){

                },
                //una vez finalizado correctamente
                success: function(data){

                  if (data.error == true) {
                    Swal.fire({
                      title: "Error",
                      text: data.mensaje,
                      icon: 'error',
                      timer: 1500,
                      showConfirmButton: false
                    });

                  } else {
                    Swal.fire({
                      title: "Correcto",
                      text: data.mensaje,
                      icon: 'success',
                      timer: 1500,
                      showConfirmButton: false
                    });


                  }
                },
                //si ha ocurrido un error
                error: function(){
                  Swal.fire({
                      title: "Error",
                      text: 'se genero un error al enviar la peticion',
                      icon: 'error',
                      timer: 2500,
                      showConfirmButton: false
                    });
                }
              });
            }

            var table2 = $('#example2').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../../json/jstablasajax.php?tabla=ordenestrabajos&tipo=1&methodAcciones=0&refcamiones=<?php echo $id; ?>",
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

            var table3 = $('#example3').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../../json/jstablasajax.php?tabla=ordenestrabajos&tipo=2&methodAcciones=0&refcamiones=<?php echo $id; ?>",
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

        });
    </script>
</body>

</html>