<?php

spl_autoload_register(function($clase){
  include_once "../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Usuarios = new Usuarios('','');
$Camiones = new Camiones();
$Ordenestrabajos = new Ordenestrabajos(0);
$Reportes = new Reportes(0);
$Porterias = new Porterias();

if (!($Session->exists())) {
  header('Location: ../error.php');
}

//$_SESSION['user']->getRefroles()

$Menu = new Menu($_SESSION['user']->getRefroles(),'');
$options['activo'] = 'Dashboard';
$options['ids'] = '8,9,10';

if (($_SESSION['user']->getRefroles()==5)||($_SESSION['user']->getRefroles()==6)) {
  header('Location: otreparaciones/index.php');
}

$ruta = $Porterias::RUTA;
//die(var_dump($Ordenestrabajos->traerTodosFilter(array('refestados'=>2))));

$chatMantenimiento = '';
$chatReparacion = '';

foreach ($Ordenestrabajos->rptDashboard(1) as $row) {
  
  $chatMantenimiento .= $row[0].',';
}

foreach ($Ordenestrabajos->rptDashboard(0) as $row) {
  
  $chatReparacion .= $row[0].',';
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    <?php echo $Globales::$tituloWeb; ?>
  </title>
  <?php echo $Menu->printCSS(); ?>
  <style>
  .sinSearch .dataTables_filter, .sinSearch .dataTables_info {
    display: none;
  }
  </style>


</head>

<body class="g-sidenav-show  bg-gray-100">
<?php if ($_SESSION['user']->getRefroles() == 4) { ?>
  <div class="col-lg-12 col-md-6 mb-md-0 mb-4 contGral">
    <div class="card">
      <div class="card-header pb-0">
        <div class="row">
          <div class="col-lg-12 col-12" style="text-align: center;">
            <h2>Bienvenido al Sistema de Porterias</h2>
          </div>
          
        </div>
      </div>
      <div class="card-body px-2 pb-4">
        <div class="table-responsive">
          <div class="col-lg-12 col-12" style="text-align: center;">
            <h4>Seleccione la opción a cargar</h4>
          </div>
          <div class="row">
            <div class="col-6" style="text-align: center;">
              <button type="button" class="btn btn-lg bg-gradient-info btnSalidas" style="display: block; width:100%; height:100px;">
                <h1 class="text-white">SALIDAS</h1>
              </button>
            </div>
            <div class="col-6" style="text-align: center;">
              <button type="button" class="btn btn-lg bg-gradient-success btnEntradas" style="display: block; width:100%; height:100px;">
              <h1 class="text-white">ENTRADAS</h1>
              </button>
            </div>
          </div>
          <div class="row">
            <div class="table-responsive">
          
              <table id="example" class="table align-items-center mb-0 display " style="width:100%">
                <thead>
                  <tr>
                    <th colspan="6">ULTIMAS SALIDAS</th>
                  </tr>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Patente</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Servicio</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Destino</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Conductor</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $PorteriasUltimas = new Porterias();
                  $lstUltimos = $PorteriasUltimas->traerTodosFilter(['refporterias'=>0]);
                  $Conductores = new Conductores();
                  $Camiones = new Camiones();
                  $Servicios = new Tiposervicios();
                  foreach ($lstUltimos as $row) {
                    $Camiones->buscarPorId($row['refcamiones']);
                    $conductor = $Conductores->devolverConductor(['refporterias'=>$row['id']]);
                    $Servicios->buscarPorId($row['reftiposervicios']);
                    //die(var_dump($conductor['apyn']));
                  ?>
                  <tr>
                    <td><?php echo $Camiones->getPatente(); ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td><?php echo $Servicios->getTiposervicio(); ?></td>
                    <td><?php echo $row['destino']; ?></td>
                    <td><?php echo $conductor['apyn']; ?></td>
                    <td><a href="entradas/index.php?idp=<?php echo $row['refcamiones']; ?>">Cargar Entrada</a></td>
                  </tr>
                  <?php } ?>
                </tbody>
                  
              </table>
              
              <div style="margin-bottom: 140px;"></div>
              
            </div>
          </div>
          <div class="row">
            <div class="col-4" style="text-align: center; margin-top:60px;">
              <button type="button" class="btn btn-lg bg-gradient-danger btnSalir" style="display: block; width:100%; height:100px;">
                <h1 class="text-white">SALIR</h1>
              </button>
            </div>

          </div>
          
          <div style="margin-bottom: 140px;"></div>
          
        </div>
      </div>
    </div>
  </div>
  <footer class="footer pt-3  ">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="copyright text-center text-sm text-muted text-lg-start">
            © <script>
              document.write(new Date().getFullYear())
            </script>,
            desarrollado por
            <a href="https://cuchipuy.cl" class="font-weight-bold" target="_blank">CUCHIPY</a>
            
          </div>
        </div>
        
      </div>
    </div>
  </footer>
  <?php echo $Menu->printJS(); ?>
  <script>
    $(document).ready(function(){
      $(".btnSalir").click( function(){
        $(location).attr('href','../logout.php');   
      });

      $(".btnSalidas").click( function(){
        $(location).attr('href','salidas/');   
      });
      $(".btnEntradas").click( function(){
        $(location).attr('href','entradas/');   
      });
    });
    
  </script>
<?php } else { ?>
  <?php if ($_SESSION['user']->getRefroles() == 3) { ?>
    <!-- jefe de porterias -->
    <div class="row contGral sinSearch">
      <div class="col-6">
      <?php require('../grillas/'.$ruta.'.php'); ?>
      </div>
      <div class="col-6">
      <?php require('../grillas/'.$ruta.'historico.php'); ?>
      </div>

      <div class="row">
            <div class="col-12" style="text-align: center; margin-top:60px;">
              <button type="button" class="btn btn-lg bg-gradient-danger btnSalir" >
                <h1 class="text-white">SALIR</h1>
              </button>
            </div>

          </div>
    
    </div>

    <?php require('../forms/conductores/list.php'); ?>
    <?php echo $Menu->printJS(); ?>
  <script>
    $(document).ready(function(){
      var table = $('#example').DataTable({
        "bProcessing": true,
        "bServerSide": true,
        "lengthChange": false,
        "lengthMenu": [ 100 ],
        "pageLength": 200,
        "sAjaxSource": "../json/jstablasajax.php?tabla=<?php echo $ruta; ?>&accion=1&methodAcciones=0",
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
        "lengthChange": false,
        "lengthMenu": [ 100 ],
        "pageLength": 200,
        "sAjaxSource": "../json/jstablasajax.php?tabla=<?php echo $ruta; ?>historico&accion=2&methodAcciones=0",
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

      $(".btnSalir").click( function(){
        $(location).attr('href','../logout.php');   
      });

      $("#example").on("click",'.btnConductores', function(){
        idTable =  $(this).attr("id");
        frmAjaxModificar(idTable);
          
      });//fin del boton modificar

      function frmAjaxModificar(id) {
        $.ajax({
            url: '../api/conductores/buscarall.php',
            type: 'POST',
            // Form data
            //datos del formulario
            data: { id: id},
            //mientras enviamos el archivo
            beforeSend: function(){
                $('.listPasajeros').html('');
            },
            //una vez finalizado correctamente
            success: function(data){

                if (data.error) {
                    Swal.fire({
                        title: "Error",
                        text: data.mensaje,
                        icon: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                } else {
                    //$('#lgmModificar').show();
                    var myModal = new bootstrap.Modal(document.getElementById('lgmModificar'), options);
                    myModal.show();

                    for(let i = 0; i < data.datos.length; i++) {
                      if (data.datos[i].conduce == '1') {
                        $('.listPasajeros').append('<h4>'+data.datos[i].apyn+' (Conductor)</h4>');
                      } else {
                        $('.listPasajeros').append('<h4>'+data.datos[i].apyn+'</h4>');
                      }
                      
                      //console.log(data.datos[i].apyn);  // (o el campo que necesites)
                    }

                }
            },
            //si ha ocurrido un error
            error: function(){
                $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                $("#load").html('');
            }
          });

        }
    });
    
  </script>
  <?php } else { ?>
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main" data-color="<?php echo $Globales::COLORHTML; ?>">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="https://cuchipuy.cl" target="_blank">
        <img src="../assets/img/LOGO1-02.png" class="navbar-brand-img h-100" alt="main_logo">
        
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">

        <?php echo $Menu->MenuStr($options); ?>

        <li class="nav-item">
          <a class="nav-link  " href="../../sistemas.php">
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
        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
          <div class="input-group">
            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
            <input type="text" class="form-control" id="btnBuscarPatente" placeholder="Buscar patente...">
            <input type="hidden" name="altura" id="altura" value=""/>
          </div>
        </div>
        <ul class="navbar-nav  justify-content-end">

          <li class="nav-item d-flex align-items-center">
            <a href="../logout.php" class="nav-link text-body font-weight-bold px-0">
              <i class="fas fa-arrow-right me-sm-1"></i>
              <span class="d-sm-inline d-none">Salir</span>
            </a>
          </li>
        </ul>
      </div>
        
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar" data-color="<?php echo $Globales::COLORHTML; ?>">
         
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
    <div class="container-fluid py-4">
    <?php if ($_SESSION['user']->getRefroles() == 8) { ?>
      <!-- espacio para el role admin flota -->
      <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
          <div class="card-header pb-0">
            <div class="row">
              <div class="col-lg-6 col-7">
                <h6>Bienvenido a Sistema Administrador de Flotas</h6>
              </div>
              
            </div>
          </div>
          <div class="card-body px-2 pb-4">
            <div class="table-responsive">
              
              <p>Todas la novedades seran publicadas en este espacio.</p>
              
              <div style="margin-bottom: 140px;"></div>
              
            </div>
          </div>
        </div>
      </div>
      <!-- fin espacio role admin flota -->
    <?php
    } // fin del if de admin flota
    ?>
      <?php if ($_SESSION['user']->getRefroles() == 7) { ?>
      <!-- espacio para el role admin flota -->
      <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
          <div class="card-header pb-0">
            <div class="row">
              <div class="col-lg-6 col-7">
                <h6>Bienvenido a Sistema Administrador de Flotas</h6>
              </div>
              
            </div>
          </div>
          <div class="card-body px-2 pb-4">
            <div class="table-responsive">
              
              <p>Todas la novedades seran publicadas en este espacio.</p>
              
              <div style="margin-bottom: 140px;"></div>
              
            </div>
          </div>
        </div>
      </div>
      <!-- fin espacio role admin flota -->
    <?php
    } // fin del if de admin flota
    ?>
    <?php if ($_SESSION['user']->getRefroles() == 1) { ?>
    
      <div class="row">
        <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">OTs en Mantenimiento</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php 
                      $Ordenestrabajos->setTipo(1);
                      echo $Ordenestrabajos->traerAjax(999999999, 0, '','1','asc')[1]; 
                      ?>
                      <!--<span class="text-success text-sm font-weight-bolder">+55%</span>-->
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                    <i class="ni ni-chart-pie-35 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">OTs en Reparación</p>
                    <h5 class="font-weight-bolder mb-0">
                    <?php 
                    $Ordenestrabajos->setTipo(0);
                    echo $Ordenestrabajos->traerAjax(999999999, 0, '','1','asc')[1]; 
                    ?>
                      <!--<span class="text-success text-sm font-weight-bolder">+3%</span>-->
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                    <i class="ni ni-chart-pie-35 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        
        
      </div>
      
      
      <div class="row my-4">
        <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>OTs x Mes Mantenimiento</h6>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="chart">
                <canvas id="chart-mantenimiento" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="card h-100">
            <div class="card-header pb-0">
              <h6>OTs x Mes Reparación</h6>
              
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="chart-reparacion" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php } ?>
      
      
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                desarrollado por
                <a href="https://cuchipuy.cl" class="font-weight-bold" target="_blank">CUCHIPY</a>
                
              </div>
            </div>
            
          </div>
        </div>
      </footer>
    </div>
  </main>
  
  <?php echo $Menu->printJS(); ?>
  <script>
    //const ctx = document.getElementById('chart-torta').getContext('2d');
      /*
    new Chart(document.getElementById("chart-torta"), {
      type: 'doughnut',
      data: {
        
        labels: ["Resto","Finalizadas"],
        datasets: [
          {
            label: "% de cumplimiento finalizadas",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [<?php //echo $labelCantTorta; ?>]
          }
        ]
      },
      options: {
        title: {
          display: true,
          text: '% de cumplimiento finalizadas'
        }
      }

      
    });
    */

    var ctx5 = document.getElementById("chart-mantenimiento").getContext("2d");

    new Chart(ctx5, {
        type: "bar",
        data: {
          labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          datasets: [{
            label: "Sales by age",
            weight: 5,
            borderWidth: 0,
            borderRadius: 4,
            backgroundColor: '#3A416F',
            data: [<?php echo $chatMantenimiento; ?>],
            fill: false,
            maxBarThickness: 35
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            }
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                padding: 10,
                color: '#9ca2b7'
              }
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: true,
                drawTicks: true,
              },
              ticks: {
                display: true,
                color: '#9ca2b7',
                padding: 10
              }
            },
          },
        },
      });


      var ctx6 = document.getElementById("chart-reparacion").getContext("2d");

    new Chart(ctx6, {
        type: "bar",
        data: {
          labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          datasets: [{
            label: "Sales by age",
            weight: 5,
            borderWidth: 0,
            borderRadius: 4,
            backgroundColor: '#3A416F',
            data: [<?php echo $chatReparacion; ?>],
            fill: false,
            maxBarThickness: 35
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            }
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                padding: 10,
                color: '#9ca2b7'
              }
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: true,
                drawTicks: true,
              },
              ticks: {
                display: true,
                color: '#9ca2b7',
                padding: 10
              }
            },
          },
        },
      });
  </script>
  <?php } // fin del if si role es 1 admin ?>
  
<?php
  } // fin del primer if
?>
</body>

</html>