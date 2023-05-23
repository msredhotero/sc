<?php

  spl_autoload_register(function($clase){
    include_once "flota/includes/" .$clase. ".php";        
  });

  
  $Globales = new Globales();
  $Session = new Session('user');

  /*
  if (!($Session->exists())) {
    header('Location: index.php');
  }
*/

  //var_dump($_SESSION['user']->usuariossistemas->getLstSistemas());
  //echo $Session['user']->getRefroles();

?>

<?php

spl_autoload_register(function($clase){
  include_once "flota/includes/" .$clase. ".php";        
});


$Globales = new Globales();

$Session = new Session('user');



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="flota/assets/img/favicon.png">
  <title>
    <?php echo $Globales::$tituloWeb; ?>
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="flota/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="flota/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="flota/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="flota/assets/css/soft-ui-dashboard.css?v=1.0.5" rel="stylesheet" />
</head>

<body class="">

  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-lg-4">
              <div class="card h-100 p-3">
                <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('flota/assets/img/curved-images/simple-background-blue-simple-minimalism-wallpaper-preview.jpg');">
                  <span class="mask bg-gradient-dark"></span>
                  <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2">Sistemas de Flotas</h5>
                    <p class="text-white">Wealth creation is an evolutionarily recent positive-sum game. It is all about who take the opportunity first.</p>
                    <a class="text-white text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="flota/dashboard/">
                      Acceder
                      <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card h-100 p-3">
                <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('flota/assets/img/curved-images/abstract-minimalism-simple-background-digital-art-wallpaper-preview.jpg');">
                  <span class="mask bg-gradient-dark"></span>
                  <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2">Sistemas de Formularios</h5>
                    <p class="text-white">Wealth creation is an evolutionarily recent positive-sum game. It is all about who take the opportunity first.</p>
                    <a class="text-white text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="formularios/dashboard/">
                      Acceder
                      <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>


            <div class="col-lg-4">
              <div class="card h-100 p-3">
                <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('flota/assets/img/curved-images/white-curved.jpg');">
                  <span class="mask bg-gradient-dark"></span>
                  <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2">Sistemas de Viajes</h5>
                    <p class="text-white">Wealth creation is an evolutionarily recent positive-sum game. It is all about who take the opportunity first.</p>
                    <a class="text-white text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="viajes/dashboard/">
                      Acceder
                      <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
           
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mb-4 mx-auto text-center">
          <a href="https://www.simplecarga.com/" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
            Web
          </a>
          
        </div>
        
      </div>
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> <?php echo $Globales::$tituloWeb; ?>
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="flota/assets/js/core/popper.min.js"></script>
  <script src="flota/assets/js/core/bootstrap.min.js"></script>
  <script src="flota/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="flota/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="flota/assets/js/soft-ui-dashboard.min.js?v=1.0.5"></script>
  <script src="flota/assets/js/jquery.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
        
        $(document).ready(function(){
            
        });
    </script>
</body>

</html>