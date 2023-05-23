<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Camiones = new Camiones();
$Activos = new Activos();
$Marcas = new Marcas();
$Archivos = new Archivos();

$idreferencia = $_GET['id'];
$reftabla = $_GET['ref'];

$idarchivo = 0;

$tipo = '';

switch ($reftabla) {
    case 1:
        $entity = new Emisionescontaminantes(0);
        $tipo = 'emisionescontaminantes';
    break;
    case 2:
        $entity = new Permisoscirculacion(0);
        $tipo = 'permisoscirculacion';
    break;
    case 3:
        $entity = new Revisionestecnicas(0);
        $tipo = 'revisionestecnicas';
    break;
    case 4:
        $entity = new Seguros(0);
        $tipo = 'seguros';
    break;
    case 6:
      $entity = new Archivosflota(0);
      $Archivos = new Archivos();
      $idarchivo = $_GET['archivo'];
      $Archivos->buscarPorId($idarchivo);
      $tipo = $Archivos->getCarpeta();
    break;
}

$entity->buscarPorId($idreferencia);

$Documentos = new Documentaciones($reftabla,$idreferencia);

$Documentos->buscarPorValor(array('reftabla'=>$reftabla, 'idreferencia'=> $idreferencia));

if (!($Session->exists())) {
  header('Location: ../../error.php');
}

$Menu = new Menu($_SESSION['user']->getRefroles(),'../');
//$_SESSION['user']->getRefroles()

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../');
}

//die(var_dump($entity->getRefcamiones()));

$Camiones->buscarPorId($entity->getRefcamiones());
$Activos->buscarPorId($Camiones->getRefactivos());
$Marcas->buscarPorId($Camiones->getRefmarcas());

$ruta = $Documentos::RUTA;

$options['activo'] = 'Flota';
$options['ids'] = '8,9,10';
$options['refgrupos'] = '2,3';
$options['idobligatorio'] = $entity->getRefcamiones();
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
    <style>
        .pdfobject-container { height: 40rem; border: 1rem solid rgba(0,0,0,.1); }
        .dropzone {
            border: 2px solid transparent !important;
            background-color: #eee !important;
        }
    </style>
    <link href="../../assets/dropzone/dropzone.css" rel="stylesheet">
    
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



        <div class="col-12 col-6 mb-md-0 mb-4">
            <div class="card">
            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                <a href="javascript:;" class="d-block thumbnail timagen1 text-center">
                    <img class="img-fluid border-radius-lg">
                </a>
            </div>
                <div class="card-body px-2 pb-4">

                    <div class="row px-2 pb-4">
                        <div class="text-center">
                            <div id="example1"></div>
                        </div>
                        

                        <div style="margin-bottom: 20px;"></div>
                    
                        <?php require('../../forms/camiones/documentos.php'); ?>
                        <div style="margin-bottom: 140px;"></div>
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
    <script src="../../assets/dropzone/dropzone.js"></script>
    <script src="../../json/subir.js"></script>
    <script>
        $(document).ready(function(){

          traerImagen('example1','timagen1','');
          function traerImagen(contenedorpdf, contenedor, options) {
            $.ajax({
              data:  {
                idreferencia: <?php echo $idreferencia; ?>,
                reftabla: <?php echo $reftabla; ?>,
                idarchivo: <?php echo $idarchivo; ?>,
                accion: 'traerImagen'
              },
              url:   '../../ajax/ajax.php',
              type:  'post',
              beforeSend: function () {
                $("." + contenedor + " img").attr("src",'');
              },
              success:  function (response) {
                if (response.datos.type != '') {
                  var cadena = response.datos.type.toLowerCase();

                  if (cadena.indexOf("pdf") > -1) {
                    PDFObject.embed(response.datos.imagen, "#example1",options);
                    $('#'+contenedorpdf).show();
                    $("."+contenedor).hide();

                  } else {
                    $("." + contenedor + " img").attr("src",response.datos.imagen);
                    $("."+contenedor).show();
                    $('#'+contenedorpdf).hide();
                  }
                }
              }
            });
          }
          
            $('.btnVolver').click(function() {  
              $(location).attr('href','<?php echo $tipo; ?>.php?id=<?php echo $entity->getRefcamiones(); ?>');
            });

            
            <?php if ($Documentos->getId()>0) { ?>
                //existe archivo
                <?php if (strpos($Documentos->getArchivo(), '.pdf') !== false) { ?>
                    PDFObject.embed('<?php echo '../../'.$Documentos->getArchivoCompleto(); ?>', "#example1");
                    $(".timagen1").hide();
					          $('#example1').show();
                <?php } else { ?>        
                    $(".timagen1 img").attr("src",'<?php echo '../../'.$Documentos->getArchivoCompleto(); ?>');
                    $(".timagen1").show();
					          $('#example1').hide();
                <?php } ?>
            <?php } else { ?>
                $('#example1').hide();
                $(".timagen1 img").attr("src",'../../assets/img/theme/sin_img.jpg');
            <?php } ?>

      Dropzone.prototype.defaultOptions.dictFileTooBig = "Este archivo es muy grande ({{filesize}}MiB). Peso Maximo: {{maxFilesize}}MiB.";

		  Dropzone.options.frmFileUpload = {
			maxFilesize: 30,
			acceptedFiles: ".pdf,.xml,.png,.jpg",
			accept: function(file, done) {
				done();
			},
			init: function() {
				this.on("sending", function(file, xhr, formData){
					formData.append("reftabla", '<?php echo $reftabla; ?>');
					formData.append("idreferencia", '<?php echo $idreferencia; ?>');
          formData.append("idarchivo", '<?php echo $idarchivo; ?>');
                    $.blockUI({ message: '<h4>Estamos procesando la solicitud...</h4>' });
				});
				this.on('success', function( file, resp ){
					if (resp.error) {
                        Swal.fire({
                            title: "Error",
                            text: resp.mensaje,
                            icon: 'error',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: "Correcto",
                            text: resp.mensaje,
                            icon: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });

                        setTimeout($.unblockUI, 3000);

                        traerImagen('example1','timagen1','');
                    }
                    
					
				});

				this.on('error', function( file, resp ){
					Swal.fire({
                        title: "Error",
                        text: resp.mensaje,
                        icon: 'error',
                        timer: 2500,
                        showConfirmButton: false
                    });
				});
			}
		};



		var myDropzone = new Dropzone("#archivos", {
			params: {
				 reftabla: <?php echo $reftabla; ?>,
				 idreferencia: <?php echo $idreferencia; ?>,
         idarchivo: <?php echo $idarchivo; ?>
			},
			url: 'subir.php'
		});

            

        });
    </script>
</body>

</html>