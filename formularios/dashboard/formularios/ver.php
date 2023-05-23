<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');

$reftablaorigen = 3;
$idreferenciaorigen = 1;

$Formulariosconector = new Formulariosconector($reftablaorigen,$idreferenciaorigen);
$Formulariosconector->setReftabla($reftablaorigen);
$Formulariosconector->setIdreferencia($idreferenciaorigen);
//$Formulariosconector->traerPorReferencia();

//
$reftabladestino = 0;
$idreferenciadestino = 0;
$idfc = 0;

foreach ($Formulariosconector->traerPorReferencia() as $rowFF) {
    $reftabladestino = $rowFF['reftabla'];
    $idreferenciadestino = $rowFF['idreferencia'];
    $idfc = $rowFF['id'];
    //
}




if (!($Session->exists())) {
  header('Location: ../../error.php');
}

$Menu = new Menu($_SESSION['user']->getRefroles(),'../');
//$_SESSION['user']->getRefroles()

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../');
}


$entity = new Formulariosdetalles($_SESSION['user']->getUsername());

$entity->setReftabla($reftabladestino);
$entity->setIdreferencia($idreferenciadestino);
$entity->setRefformulariosconector($idfc);


/*
$entity->setReftabla(1);
$entity->setIdreferencia(1);
$entity->setRefformulariosconector(1);
$entity->setRefpreguntascuestionario(1);
$entity->setRefrespuestascuestionario(0);
$entity->setPregunta('prueba');
$entity->setRespuesta('archivo');
$entity->setReftiporespuesta(1);
$entity->setArchivo(file_get_contents('../../data/ot/logo_scc.png'));
$entity->setTipo('');
$entity->setCarpeta('');
$entity->setLatitud('');
$entity->setLongitud('');
$entity->setFechacrea(date('Y-m-d H:i:s'));

//die(var_dump($Formulariosconector->getId()));
$entity->save();

*/




$options['activo'] = 'Formularios';
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
    <style type="text/css">
		#map
		{
			width: 100%;
			height: 600px;
			border: 1px solid #d0d0d0;
		}
        #canvas {
            display: block;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #d0d0d0;
        }
		
	  </style>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
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
        
            <?php require('../../forms/formularios/ver.php'); ?>
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

    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzxyoH5wuPmahQIZLUBjPfDuu_cUHUBQY&callback=initMap&libraries=places"
      defer
    ></script>

    
    <script src="../../json/maps.js?version=1.0"></script>
    <script src="../../json/firma.js"></script>

    <script>
        $(document).ready(function(){
            $('.btnVolver').click(function() {
              
              $(location).attr('href','index.php');
              
            });

            
        });
    </script>
</body>

</html>