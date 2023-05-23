<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Clientes = new Clientes();


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

$Menu = new Menu($_SESSION['user']->getRefroles(),'../');
//$_SESSION['user']->getRefroles()

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../');
}



$ruta = $Clientes::RUTA;

$options['activo'] = 'Clientes';
$options['ids'] = '8,9,10';

$idcliente = $_GET['id'];

$Clientes->buscarPorId($idcliente);

$Sucursales = new Sucursales(1,$idcliente);

$res = $Sucursales->traerTodosFilter(array('reftabla'=>1,'idreferencia'=>$idcliente));

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
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxMFdevPFgOqYhnaNMiItJ2p1TyVD3YUM&libraries=places"></script>

    <?php echo $Menu->printCSS(); ?>
    
    <style type="text/css">
		#map
		{
			width: 100%;
			height: 600px;
			border: 1px solid #d0d0d0;
		}

        .pac-container { z-index: 100000 !important; }
  
	  </style>
    
    
</head>

<body class="g-sidenav-show  bg-gray-100" >
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
                        <h6>Mapa de Sucursales del Cliente: <b><?php echo $Clientes->getNombre();?></b></h6>
                        </div>
                        
                    </div>
                    </div>
                    <div class="card-body px-2 pb-4">
                        <div class="row" id="contMapa" style="margin-left:25px; margin-right:25px;">
                            <div id="map"></div>
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
      
      function initMap() {
        <?php
        if (count($res)>0) {
        ?>
            var locations = [
                <?php foreach ($res as $row) { ?>
                ['<h4><?php echo $row['sucursal']; ?></h4><p><?php echo $row['direccion']; ?></p><p><?php echo $row['telefono']; ?></p>', <?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>, <?php echo $row['id']; ?>],
                <?php } ?>
            ];
            const center = { lat: -34.5893799, lng: -58.3855431 };
            <?php } else { ?>
                const center = { lat: -34.5893799, lng: -58.3855431 };
            <?php } ?>

            var myOptions2 = {
              zoom: 8,
              center: center,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            }

            var map = new google.maps.Map(document.getElementById("map"), myOptions2);

            var infowindow = new google.maps.InfoWindow();
            
            var marker, i;

            for (i = 0; i < locations.length; i++) {  
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map
                });    

                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
                })(marker, i));
            }
            
      }


      google.maps.event.addDomListener(window, 'load', initMap);

    </script>
    
    <script>
       
        $(document).ready(function(){

            $('.btnVolver').click(function() {
              
              $(location).attr('href','mapa.php');
              
            });
            
        });
    </script>
</body>

</html>