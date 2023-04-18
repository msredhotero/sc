<?php

spl_autoload_register(function($clase){
    include_once "includes/" .$clase. ".php";        
});
  
  
$Globales = new Globales();


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
    <section class="min-vh-100 mb-1">
      <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('assets/img/curved-images/curved14.jpg');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5 text-center mx-auto">
              <h1 class="text-white mb-2 mt-5">Bienvenido! a <?php echo $Globales::$tituloWeb; ?></h1>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10">
          <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
            <div class="card z-index-0">
              <div class="card-header text-center pt-4">
                <h5>Registrese</h5>
              </div>

              <div class="card-body">
                <form class="formulario frmNuevo text-left" role="form" id="sign_in">
                  <div class="mb-3">
                    <input name="nombre" id="nombre" type="text" class="form-control" placeholder="Nombre" aria-label="nombre" aria-describedby="email-addon">
                  </div>
                  <div class="mb-3">
                    <input name="apellido" id="apellido" type="text" class="form-control" placeholder="Apellido" aria-label="apellido" aria-describedby="email-addon">
                  </div>
                  <div class="mb-3">
                    <input name="email" id="email" type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                  </div>
                  <div class="mb-3">
                    <input name="password" id="password" type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                  </div>
                  <div class="form-check form-check-info text-left">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                    <label class="form-check-label" for="flexCheckDefault">
                      Acepto los <a href="javascript:;" class="text-dark font-weight-bolder">Terminos y condiciones</a>
                    </label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2" id="registrarse">Registrarse</button>
                  </div>
                  <p class="text-sm mt-3 mb-0">Si ya tienes una cuenta ingresa con tu email y password? <a href="index.php" class="text-dark font-weight-bolder">Ingresar</a></p>
                  <input type="hidden" name="accion" id="accion" value="registrarUsuarioWeb"/>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer py-5">
      <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-0 mx-auto text-center">
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
  </main>
  <!--   Core JS Files   -->
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="assets/js/soft-ui-dashboard.min.js?v=1.0.5"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    
    <script>
        
        $(document).ready(function(){
            $('.frmNuevo').submit(function(e){
                e.preventDefault();
                if( $('#flexCheckDefault').prop('checked') ) {
                    
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
                              $('#registrarse').hide();
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
                                    $('#registrarse').show();
                                    
                                } else {
                                    

                                    Swal.fire({
                                        title: "Correcto",
                                        text: data.mensaje,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });

                                    $('#registrarse').hide();
                                }
                            },
                            //si ha ocurrido un error
                            error: function(){
                                $(".alert").html('<strong>Error!</strong> Actualice la pagina');
                                $("#load").html('');
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        title: "Error",
                        text: 'Debe aceptar los terminos y condiciones',
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