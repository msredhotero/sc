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

$id = $_GET['id'];

$Clientes->buscarPorId($id);

$ruta = $Clientes::RUTA;

$options['activo'] = 'Clientes';
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
    
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzxyoH5wuPmahQIZLUBjPfDuu_cUHUBQY" type="text/javascript"></script>-->
    <style type="text/css">
		#map
		{
			width: 100%;
			height: 600px;
			border: 1px solid #d0d0d0;
		}
  
		
	  </style>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    
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
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                    <h6>Modificar Cliente</h6>
                    </div>
                    
                </div>
                </div>
                <div class="card-body px-2 pb-4">
                    <button type="button" class="btn bg-gradient-secondary btnVolver">
                        VOLVER
                    </button>
                    <div class="row">
                    <form class="formulario frmNuevo" role="form" id="sign_in">
                    <div class="row">
                        <div class="col-6">
                        <label for="nombre" class="control-label">Nombre</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="nombre" value="<?php echo $Clientes->getNombre(); ?>" name="nombre" placeholder="nombre" required>
                        </div>
                        </div>

                        <div class="col-12">
                        <label for="direccion" class="control-label">Direccion</label>
                        <div class="form-group">
                        <input type="text" class="form-control" id="direccion" value="<?php echo $Clientes->getDireccion(); ?>" name="direccion" placeholder="direccion..." />
                        </div>
                        </div>

                        <div class="col-6">
                        <label for="cuit" class="control-label">Cuit</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="cuit" name="cuit" value="<?php echo $Clientes->getCuit(); ?>" placeholder="cuit" required>
                        </div>
                        </div>

                        <div class="col-6">
                        <label for="contacto" class="control-label">Contacto</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="contacto" value="<?php echo $Clientes->getContacto(); ?>" name="contacto" placeholder="contacto" required>
                        </div>
                        </div>

                        <div class="col-6">
                        <label for="telefono" class="control-label">Telefono</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $Clientes->getTelefono(); ?>" placeholder="telefono" required>
                        </div>
                        </div>

                        <div class="col-6">
                        <label for="email" class="control-label">Email</label>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $Clientes->getEmail(); ?>" placeholder="email" required>
                        </div>
                        </div>

                        <div class="col-6">
                        <label for="codpostal" class="control-label">Cod.Postal</label>
                        <div class="form-group">
                            <input type="codpostal" class="form-control" id="codpostal" value="<?php echo $Clientes->getCodpostal(); ?>" name="codpostal" placeholder="Cod.Postal" required>
                        </div>
                        </div>

                        <div class="col-6">
                        <label for="latitud" class="control-label">Latitud</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="latitud" name="latitud" value="<?php echo $Clientes->getLatitud(); ?>" placeholder="latitud" >
                        </div>
                        </div>

                        <div class="col-6">
                        <label for="longitud" class="control-label">Longitud</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="longitud" name="longitud" value="<?php echo $Clientes->getLongitud(); ?>" placeholder="longitud" >
                        </div>
                        </div>

                        <div class="col-12">
                        <div class="row" id="contMapa2" style="margin-left:25px; margin-right:25px;">
                            <div id="map" ></div>
                        </div>
                        </div>
                        <input type="hidden" name="idmodificar" id="idmodificar" value="<?php echo $Clientes->getId(); ?>"/>

                        <input type="hidden" name="accion" id="accion" value="insertarClientes"/>
                    </div>
                    <div class="modal-footer">
                        
                        <button type="submit" class="btn bg-gradient-success nuevo">Guardar</button>
                    </div>
                    </form>
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

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxMFdevPFgOqYhnaNMiItJ2p1TyVD3YUM&libraries=places&callback=initMap" async defer></script>

    <script>
      var markers = [];
      function initMap() {
        <?php
            if (($Clientes->getLatitud() != '') && ($Clientes->getLongitud() != '')) {
            ?>
            const center = { lat: <?php echo $Clientes->getLatitud(); ?>, lng: <?php echo $Clientes->getLongitud(); ?> };
            <?php } else { ?>
                const center = { lat: -34.5893799, lng: -58.3855431 };
            <?php } ?>
            

            // The map, centered at center
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: center
            });

            

            // The marker, positioned at center
            const marker = new google.maps.Marker({
                position: center,
                map: map,
            });

            map.addListener('click', function(e) {
                    
                    if (markers.length > 0) {
                        clearMarkers();
                    }
                    $('#latitud').val(e.latLng.lat());
                    $('#longitud').val(e.latLng.lng());	
                    placeMarkerAndPanTo(e.latLng, map);
                });
    
                function placeMarkerAndPanTo(latLng, map) {
                    var marker = new google.maps.Marker({
                        position: latLng,
                        map: map
                    });
                    markers.push(marker);
                    map.panTo(latLng);
                    
                }

            function clearMarkers() {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
            }

            var options = {
              componentRestrictions: {country: "arg"},
              fields: ["address_components", "geometry"],
              types: ["geocode"],
            };

            var input = document.getElementById('direccion');
            var autocomplete = new google.maps.places.Autocomplete(input,options);
            autocomplete.addListener('place_changed', function() {
                clearMarkers();
                var place = autocomplete.getPlace();

                $('#latitud').val(place.geometry.location.lat());
                $('#longitud').val(place.geometry.location.lng());

                var position = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());

                var marker2 = new google.maps.Marker({
                  position: position,
                  map: map,
                  title: place.formatted_address
                });
                markers.push(marker2);
                map.setCenter(position);

                for(var j=0;j < place.address_components.length; j++){
                  /* 
                  0 => 'nro'
                  1 => 'calle'
                  2 => 'municipio'
                  3 => 'localidad'
                  4 => 'provincia'
                  5 => 'pais'
                  6 => 'cod postal'
                  */
                  $('#codpostal').val(place.address_components[6].long_name);
                }

            });
      }
    </script>

    
    
    <script>
        //var searchInput = 'search_input';
        
        $(document).ready(function(){
            

            
          

            $('.btnVolver').click(function() {
                idTable =  $(this).attr("id");
                $(location).attr('href','index.php');
                
            });//fin del boton modificar

            

            $('.frmNuevo').submit(function(e){

                e.preventDefault();
                if ($('#sign_in')[0].checkValidity()) {
                    //información del formulario
                    var formData = new FormData($(".formulario")[0]);
                    var message = "";
                    //hacemos la petición ajax
                    $.ajax({
                        url: '../../api/<?php echo $Clientes::RUTA; ?>/modificar.php',
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
                                    timer: 2500,
                                    showConfirmButton: false
                                });

                                $('#lgmNuevo').modal('hide');

                                $('#lgmNuevo #nombre').val('');
                                $('#lgmNuevo #direccion').val('');
                                $('#lgmNuevo #email').val('');
                                $('#lgmNuevo #contacto').val('');
                                $('#lgmNuevo #cuit').val('');
                                $('#lgmNuevo #codpostal').val('');
                                $('#lgmNuevo #latitud').val('');
                                $('#lgmNuevo #longitud').val('');
                                $('#lgmNuevo #telefono').val('');
                                
                                table.ajax.reload();


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
            
        });
    </script>
</body>

</html>