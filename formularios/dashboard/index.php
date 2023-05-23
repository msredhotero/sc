<?php

spl_autoload_register(function($clase){
  include_once "../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Usuarios = new Usuarios('','');

$Solicitud = new Solicitudesvisitas('');
$OT = new Ordenestrabajocabecera();
$Cuadrillas = new Cuadrillas();
$UbicacionUsuarios = new Ubicacionesusuarios();

if (!($Session->exists())) {
  header('Location: ../error.php');
}

$Menu = new Menu($_SESSION['user']->getRefroles(),1);

$resLstMapaSolicitudes = $Solicitud->traerSolicitudesMapa();
$resLstMapaOT = $OT->traerSolicitudesMapa();

//$_SESSION['user']->getRefroles()

$Menu = new Menu($_SESSION['user']->getRefroles(),'');
$options['activo'] = 'Dashboard';
$options['ids'] = '8,9,10';

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
    .cursorOT { cursor: pointer;}
	  </style>
  
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main" data-color="<?php echo $Globales::COLORHTML; ?>">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="https://jota.com.ar" target="_blank">
        <img src="../assets/img/LOGO1-02.png" class="navbar-brand-img h-100" alt="main_logo">
        
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">

        <?php echo $Menu->MenuStr($options); ?>

        <li class="nav-item">
          <a class="nav-link  " href="../../sistemas.php">
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
      <div class="row">
        <div class='col-3'><span class="badge badge-sm bg-gradient-success">Prioridad: Baja</span></div>
        <div class='col-3'><span class="badge badge-sm bg-gradient-warning">Prioridad: Media</span></div>
        <div class='col-3'><span class="badge badge-sm bg-gradient-danger">Prioridad: Alta</span></div>
        <div class='col-3'><span class="badge badge-sm bg-gradient-info">Ubicacion Usuario</span></div>
      
      
      </div>
      <div class="row">
        <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
          <h4>Solicitudes de Visitas</h4>
          <div id="map"></div>
        </div>
        <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
          <h4>OT</h4>
          <div id="map2"></div>
        </div>
        
        
      </div>
      
      
      <div class="row my-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Solicitud de Visitas no Asignadas</h6>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table id="example" class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cliente</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sucursal</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actividad</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nro Aviso</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prioridad</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach( $Solicitud->traerTodosFilter([],array('contenido'=>'in','refestados'=> '1')) as $row) { 
                      $Solicitud->buscarPorId($row['id']);
                      $res = $Solicitud->devolverArray();
                    ?>
                    <tr>
                      <td><?php echo $res['cliente']; ?></td>
                      <td><?php echo $res['sucursal']; ?></td>
                      <td><?php echo $res['actividad']; ?></td>
                      <td><?php echo $res['nroaviso']; ?></td>
                      <td><?php echo $res['fecha']; ?></td>
                      <?php
                      if ($res['prioridad'] == '3-Baja') {
                        echo '<td><span class="badge badge-sm bg-gradient-success">Baja</span></td>';
                      } else {
                        if ($res['prioridad'] == '2-Media') {
                          echo '<td><span class="badge badge-sm bg-gradient-warning">Media</span></td>';
                        } else {
                          echo '<td><span class="badge badge-sm bg-gradient-danger">Alta</span></td>';
                        }
                      }
                      ?>
                      <td><?php echo $res['estado']; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row my-4">
        <div class="col-lg-12 col-md-12">
          <div class="card h-100">
            <div class="card-header pb-0">
              <h6>OT en curso y pendientes</h6>
              
            </div>
            <div class="card-body p-3">
              <div class="table-responsive">
                <table id="example2" class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cliente</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sucursal</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actividad</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha Fin</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prioridad</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach( $OT->traerTodosFilter([],array('contenido'=>'in','refestados'=> '1,2,5')) as $row) { 
                      $OT->buscarPorId($row['id']);
                      $resOT = $OT->devolverArray();

                      $Solicitud->buscarPorId($resOT['refsolicitudesvisitas']);
                      $res = $Solicitud->devolverArray();
                    ?>
                    <tr class="cursorOT" onclick="location.href='ordenestrabajocabecera/ver.php?id=<?php echo $row['id']; ?>'">
                      <td><?php echo $res['cliente']; ?></td>
                      <td><?php echo $res['sucursal']; ?></td>
                      <td><?php echo $res['actividad']; ?></td>
                      <td><?php echo $resOT['fecha']; ?></td>
                      <td><?php echo $resOT['fechafin']; ?></td>
                      <?php
                      if ($resOT['prioridad'] == '3-Baja') {
                        echo '<td><span class="badge badge-sm bg-gradient-success">Baja</span></td>';
                      } else {
                        if ($resOT['prioridad'] == '2-Media') {
                          echo '<td><span class="badge badge-sm bg-gradient-warning">Media</span></td>';
                        } else {
                          echo '<td><span class="badge badge-sm bg-gradient-danger">Alta</span></td>';
                        }
                      }
                      ?>
                      <td><?php echo $resOT['estado']; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      
      </div>
      <div class="row my-4">
        <div class="col-lg-12 col-md-12">
          <div class="card h-100">
            <div class="card-header pb-0">
              <h6>Usuarios con Check-In</h6>
              
            </div>
            <div class="card-body p-3">
            <div class="table-responsive">
                <table id="example3" class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Apellido</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Comunicarse</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach( $UbicacionUsuarios->traerTodosSinCheckOut() as $row) { 

                    ?>
                    <tr>
                      <td><?php echo $row['nombre']; ?></td>
                      <td><?php echo $row['apellido']; ?></td>
                      <td><?php echo $row['fecha']; ?></td>
                      <td>
                        <div class="btn-whatsapp">
                        <a href="https://api.whatsapp.com/send?phone=<?php echo $row['telefono']; ?>&amp;text=Hola!" target="_blank">
                        <img src="http://s2.accesoperu.com/logos/btn_whatsapp.png" alt="" width="10%">
                        </a>
                        </div>
                        </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
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
                <a href="https://jota.com.ar" class="font-weight-bold" target="_blank"><?php echo $Globales::$tituloWeb; ?></a>
                
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
            alta: {
              icon: iconBase + "red-dot.png",
            },
            media: {
              icon: iconBase + "yellow-dot.png",
            },
            baja: {
              icon: iconBase + "green-dot.png",
            },
            usuarios: {
              icon: iconBase + "blue-dot.png",
            },
          };

          
      
      function initMap() {
        <?php
        if (count($resLstMapaSolicitudes)>0) {
        ?>
          var locations = [
              <?php 
                foreach ($resLstMapaSolicitudes as $row) { 
              ?>
              ['<h4><?php echo $row['cliente']; ?></h4><p><?php echo $row['actividad']; ?></p><p><?php echo $row['sucursal']; ?></p><p><?php echo $row['fecha']; ?></p><p><?php echo $row['nivel']; ?></p>', <?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>, <?php echo $row['id']; ?>,"<?php echo $row['colormapa']; ?>"],
              
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

          var infowindow = new google.maps.InfoWindow();
          
          var marker, i;

          for (i = 0; i < locations.length; i++) {  
              marker = new google.maps.Marker({
                  position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                  map: map,
                  icon: icons[locations[i][4]].icon
              });    

              google.maps.event.addListener(marker, 'click', (function(marker, i) {
                  return function() {
                  infowindow.setContent(locations[i][0]);
                  infowindow.open(map, marker);
              }
              })(marker, i));
          }
      }

      function initMap2() {
        <?php
        if (count($resLstMapaOT)>0) {
        ?>
          var locations2 = [
              <?php 
                foreach ($resLstMapaOT as $row) { 
              ?>
              ['<h4><?php echo $row['cliente']; ?></h4><p><?php echo $row['actividad']; ?></p><p><?php echo $row['sucursal']; ?></p><p><?php echo $row['fecha']; ?></p><p><?php echo $row['nivel']; ?></p>', <?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>, <?php echo $row['id']; ?>,"<?php echo $row['colormapa']; ?>" ],
              <?php
                
              }
              ?>
              <?php
                foreach ($OT::traerUsuariosMapa() as $usuarios) {

              ?>
                ['<h4><?php echo $usuarios['apyn']; ?></h4><p><?php echo $usuarios['fecha']; ?></p>', <?php echo $usuarios['latitud']; ?>, <?php echo $usuarios['longitud']; ?>, 0,"usuarios"],
              <?php
                
              }
              ?>
              
          ];
          const center = { lat: -34.5893799, lng: -58.3855431 };
          <?php } else { ?>
            var locations2 = [];
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

          var map2 = new google.maps.Map(document.getElementById("map2"), myOptions2);

          var infowindow = new google.maps.InfoWindow();
          
          var marker, i;

          for (i = 0; i < locations2.length; i++) {  
              marker = new google.maps.Marker({
                  position: new google.maps.LatLng(locations2[i][1], locations2[i][2]),
                  map: map2,
                  icon: icons[locations2[i][4]].icon
              });    

              google.maps.event.addListener(marker, 'click', (function(marker, i) {
                  return function() {
                  infowindow.setContent(locations2[i][0]);
                  infowindow.open(map2, marker);
              }
              })(marker, i));
          }
      }


      google.maps.event.addDomListener(window, 'load', initMap);
      google.maps.event.addDomListener(window, 'load', initMap2);

    </script>
</body>

</html>