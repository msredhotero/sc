<?php

spl_autoload_register(function($clase){
  include_once "includes/" .$clase. ".php";        
});

$error = 0;
$descripcionError = '';
$Globales = new Globales();

// no le paso ningun token
if ((!(isset($_GET['token']))) || ($_GET['token'] == '')) {
    $error = 1;
    
} else {
    $Autologin = new Autologin(0,'','','');

    $Autologin->traerToken($_GET['token']);

    //var_dump($Autologin->getUsuarios()->getValidoemail());
    //die();

    // no encuentro el token
    if ($Autologin->getRefusuarios() == 0) {
        $error = 1;
        
    } else {
        // encuentro el token, marco al usuario activo y que valido su email, borro token
        $ar = ['validoemail'=>'1', 'activo'=> '1'];
        //$Autologin->getUsuarios->modificarFilter($ar);
        $Autologin->getUsuarios()->modificarFilter($ar);
        
    }
    
}

if ($error == 0) {
    $descripcionError = $Globales::SUCCESS_LOGIN_VERIFICADO;
} else {
    $descripcionError = $Globales::ERROR_VERIFICACION;
}





?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    <?php echo $Globales::$tituloWeb; ?>
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.0.5" rel="stylesheet" />
</head>

<body class="">

  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-4">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-info text-gradient text-primary">Recuperar Password</h3>
                  <p class="mb-0"><?php echo $descripcionError; ?></p>
                </div>
                <div class="card-body">
                <?php if ($error == 0) { ?>
                  <form class="formulario frmNuevo text-left" role="form" id="sign_in">
                    
                    <label>Password Nuevo</label>
                    <div class="mb-3">
                      <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                    </div>

                    <label>Repetir Password Nuevo</label>
                    <div class="mb-3">
                      <input type="password" name="passwordaux" id="passwordaux" class="form-control" placeholder="Repetir Password" aria-label="passwordaux" aria-describedby="password-addon">
                    </div>
                    
                    <div class="text-center">
                      <button type="submit" class="btn bg-gradient-warning w-100 mt-4 mb-0">Guardar</button>
                    </div>
                    <input type="hidden" name="accion" id="accion" value="nuevopasswordUsuarioWeb"/>
                    <input type="hidden" name="token" id="token" value="<?php echo $_GET['token']; ?>"/>
                  </form>
                
                
                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <p class="text-sm mt-3 mb-0">Si ya tienes una cuenta ingresa con tu email y password? <a href="index.php" class="text-dark font-weight-bolder">Ingresar</a></p>
                    </div>
                <?php } else { ?>
                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <p class="text-sm mt-3 mb-0">Si ya tienes una cuenta ingresa con tu email y password? <a href="index.php" class="text-dark font-weight-bolder">Ingresar</a></p>
                    </div>
                <?php } ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/banner1-pagina_Mesa-de-trabajo-1.png')"></div>
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
          <a href="https://www.cuchipuy.cl/" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
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
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
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
  <script src="assets/js/soft-ui-dashboard.min.js?v=1.0.5"></script>
  <script src="assets/js/jquery.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
        
        $(document).ready(function(){
            $('.frmNuevo').submit(function(e){
                e.preventDefault();
                
                if ($('#password').val() === $('#passwordaux').val()) {
                    if ($('#sign_in')[0].checkValidity()) {
                        //información del formulario
                        var formData = new FormData($(".formulario")[0]);
                        var message = "";
                        //hacemos la petición ajax
                        $.ajax({
                            url: 'ajax/ajax.php',
                            type: 'POST',
                            // Form data
                            //datos del formulario
                            data: formData,
                            //necesario para subir archivos via ajax
                            cache: false,
                            contentType: false,
                            processData: false,
                            //mientras enviamos el archivo
                            beforeSend: function(){

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
                                    

                                    Swal.fire({
                                        title: "Correcto",
                                        text: data.mensaje,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    //url = "index.php";
                                    //$(location).attr('href',url);
                                }
                            },
                            //si ha ocurrido un error
                            error: function(){
                            Swal.fire({
                                title: "Error",
                                text: 'Se genero un error al enviar los datos',
                                icon: 'error',
                                timer: 2500,
                                showConfirmButton: false
                            });
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        title: "Error",
                        text: 'Los password no coinciden',
                        icon: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
   
            });
        });
    </script>
</body>

</html>