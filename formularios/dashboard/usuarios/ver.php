<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Usuarios = new Usuarios('','');
$UbicacionUsuarios = new Ubicacionesusuarios();

$idreferencia = $_GET['id'];

$Usuarios->buscarPorId($idreferencia);

$arUsuarios = $Usuarios->devolverArray();

if (!($Session->exists())) {
  header('Location: ../../error.php');
}

$Menu = new Menu($_SESSION['user']->getRefroles(),'../');
//$_SESSION['user']->getRefroles()

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../');
}
$UbicacionUsuarios->setRefusuarios($idreferencia);

if (isset($_GET['fechadesde'])) {
  $resLstMapaSolicitudes = $UbicacionUsuarios->traerUbicacionesMapa(['fechadesde'=>$_GET['fechadesde'],'fechahasta'=>$_GET['fechahasta']]);
} else {
  $resLstMapaSolicitudes = $UbicacionUsuarios->traerUbicacionesMapa(['fechadesde'=>date('Y-m-d 00:00:00'),'fechahasta'=>date('Y-m-d 23:59:00')]);
}


$ruta = $Usuarios::RUTA;

$options['activo'] = 'usuarios';
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
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxMFdevPFgOqYhnaNMiItJ2p1TyVD3YUM&libraries=places"></script>
    <style type="text/css">
		#map
		{
			width: 100%;
			height: 500px;
			border: 1px solid #d0d0d0;
		}
    #map2
		{
			width: 100%;
			height: 500px;
			border: 1px solid #d0d0d0;
		}
    .pac-container { z-index: 100000 !important; }
	  </style>
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
                        <h6>Usuario</h6>
                    </div>
                    
                </div>
                </div>
                <div class="card-body px-2 pb-4">
                    <div class="row">
                        <div class="col-4">NOMBRE: <b><?php echo $Usuarios->getNombre(); ?></b></div>
                        <div class="col-4">APELLIDO: <b><?php echo $Usuarios->getApellido(); ?></b></div>
                        <div class="col-4">CARGO: <b><?php echo $arUsuarios['cargo']; ?></b></div>
                        <div class="col-4">ZONA: <b><?php echo $arUsuarios['zona']; ?></b></div>
                        <div class="col-4">ULTIMA CONEXION: <b><?php echo $Usuarios->getUltimaconexion(); ?></b></div>
                    </div>
                    
                    <div class="row">
                      <div class="col">
                        
                        <label for="fecha" class="control-label">Fecha Desde</label>
                        <div class="form-group">
                          <input class="form-control" id="fechadesde" name="fechadesde" type="datetime-local" value="" id="fechadesde">
                        </div>
                        
                      </div>
                      <div class="col">
                      
                        <label for="fecha" class="control-label">Fecha Hasta</label>
                        <div class="form-group">
                          <input class="form-control" id="fechahasta" name="fechahasta" type="datetime-local" value="" id="fechahasta">
                        </div>
                      </div>
                      

                    </div>
                    <button type="button" class="btn bg-gradient-danger" id="filtrar">Filtrar</button>
                    <div class="row">
                      <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                        <h4>LOCALIZACION USUARIOS</h4>
                        <div id="map"></div>
                      </div>
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


  
    <?php echo $Menu->printJS(); ?>
    <script>
    var markers = [];
    var markers2 = [];
    

    const iconBase =
            "http://maps.google.com/mapfiles/ms/icons/";
          const icons = {
            checkin: {
              icon: iconBase + "green-dot.png",
            },
            checkout: {
              icon: iconBase + "yellow-dot.png",
            },
            baja: {
              icon: iconBase + "green-dot.png",
            },
            usuarios: {
              icon: iconBase + "blue-dot.png",
            },
          };

          
          var pathCoordinates = Array();
      function initMap() {
        <?php
        if (count($resLstMapaSolicitudes)>0) {
        ?>
          var locations = [
              <?php 
                foreach ($resLstMapaSolicitudes as $row) { 
              ?>
              ['<p><?php echo $row['fecha']; ?></p>', <?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>, <?php echo $row['id']; ?>,"<?php echo $row['colormapa']; ?>"],
              
              <?php } ?>
          ];
          const center = { lat: -34.5893799, lng: -58.3855431 };
          <?php } else { ?>
            var locations = [];
              const center = { lat: -34.5893799, lng: -58.3855431 };
          <?php } ?>


          var myOptions2 = {
            zoom: 10,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP, 
            styles: [ 
              { 
                "featureType": "poi", 
                "stylers": [ 
                  { "visibility": "off" } 
                ] 
              } 
            ] 
          }

          var map = new google.maps.Map(document.getElementById("map"), myOptions2);

          var directionsService = new google.maps.DirectionsService();
          var directionsDisplay = new google.maps.DirectionsRenderer();
          directionsDisplay.setMap(map);

          var infowindow = new google.maps.InfoWindow();
          
          var marker, i;

          for (i = 0; i < locations.length; i++) {  
              marker = new google.maps.Marker({
                  position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                  map: map,
                  animation: google.maps.Animation.DROP,
                  icon: icons[locations[i][4]].icon
              });  
              
              pathCoordinates.push({
                      lat : locations[i][1],
                      lng : locations[i][2]
              });

              google.maps.event.addListener(marker, 'click', (function(marker, i) {
                  return function() {
                  infowindow.setContent(locations[i][0]);
                  infowindow.open(map, marker);
              }
              })(marker, i));
          }

          function drawPath() {
            new google.maps.Polyline({
                    path : pathCoordinates,
                    geodesic : true,
                    strokeColor : '#FF0000',
                    strokeOpacity : 1,
                    strokeWeight : 4,
                    map : map
            });
          }

          drawPath();
      }


      google.maps.event.addDomListener(window, 'load', initMap);

      
      
    </script>
    <script>
        $(document).ready(function(){
          $('.btnVolver').click(function() {
              
              $(location).attr('href','index.php');
              
          });//fin del boton modificar

          $('#filtrar').click( function() {
            if (($('#fechadesde').val()!=='') && ($('#fechahasta').val()!=='')) {
              $(location).attr('href','ver.php?id=<?php echo $idreferencia; ?>&fechadesde='+$('#fechadesde').val()+'&fechahasta='+$('#fechahasta').val());
            } else {
              Swal.fire({
                title: "Error",
                text: 'Debe cargar las fechas desde y hasta',
                icon: 'error',
                timer: 2500,
                showConfirmButton: false
              });
            }
            
          } );
          
          
            
        });
    </script>
</body>

</html>