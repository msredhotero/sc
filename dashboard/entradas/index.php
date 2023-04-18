<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Porterias = new Porterias();


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

$Menu = new Menu($_SESSION['user']->getRefroles(),'../');
//$_SESSION['user']->getRefroles()

if ($_SESSION['user']->getRefroles() !==4) {
  header('Location: ../');
}

if (isset($_GET['idp'])) {
  $idCamiones = $_GET['idp'];
} else {
  $idCamiones = 0;
}


$ruta = $Porterias::RUTA;

$refporterias = 0;
$refacciones = 2;

$options['activo'] = 'Porterias';
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
    <style>
      body input {
        padding: 15px !important;
        font-size: x-large !important;
      }
      body label {
        font-size: x-large !important;
      }

      body select {
        padding: 15px !important;
        font-size: x-large !important;
      }
      body .form-check-input2 {
        width:50px;
        height:50px;
        margin-top: 18%;
      }
      .fcheck2 label {
        font-size: x-large !important;
      }

      body .form-check-input3 {
        width:30px;
        height:30px;
        margin-top: 5%;
      }

      body .custom-control-label3 {
        margin-right: 10px;
        font-size: large !important;
      }

      .fcheck2 label {
        font-size: x-large !important;
      }

      .modal-dialog {
        width: 1300px !important;
      }

    </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
  
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

    <!-- la vista de lo cargado -->
    <div class="container-fluid py-4">

        <div class="row">
          <?php require('../../forms/'.$ruta.'/insertar.php'); ?>
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

    <!-- Modal insert -->
  
    <?php echo $Menu->printJS(); ?>
    <script>
        $(document).ready(function(){
            var table = $('#example').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../../json/jstablasajax.php?tabla=<?php echo $ruta; ?>",
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

            

            function traerSalidas(id) {
              $.ajax({
                url: "../../api/porterias/buscar.php",
                type: 'POST',
                data: {
                  'id': id,
                  'tabla': 'dbcamiones'
                },
                beforeSend: function() {
                  $('#destino').val('');
                  $('#refacoplados').val(0);
                  $('#refporterias').val(0);
                  desCheck();
                },
                success: function(data) {
                  if (data.error === false) {
                    $('#destino').val(data.datos.destino);
                    $('#refacoplados').val(data.datos.refacoplados);
                    if (data.datos.refacoplados>0) {
                      $('#refacoplados option:not(:selected)').prop('disabled', true);
                    } else {
                      $('#refacoplados option').prop('disabled', false);
                    }
                    $('#refporterias').val(data.datos.id);
                    $('#reftiposervicios').val(data.datos.reftiposervicios);

                    $('#reftiposervicios option:not(:selected)').prop('disabled', true);

                    for(let i = 0; i < data.conductores.length; i++) {
                      //alert(data.conductores[i].conduce);
                      if (data.conductores[i].conduce == '1') {
                        $('#refconductor').val(data.conductores[i].refpersonal);
                      } else {
                        $('#refpasajeros'+data.conductores[i].refpersonal).prop('checked', true);
                      }
                      //console.log(data.conductores[i].id);  // (o el campo que necesites)
                    }
                  }
                },
                error: function() {
                  Swal.fire({
                    title: "Error",
                    text: 'actualice la pagina',
                    icon: 'error',
                    timer: 2500,
                    showConfirmButton: false
                  });
                }
              })
            }

            function desCheck() {
              $('.form-check3 input[type=checkbox]').prop('checked', false);
            }

            $('#refcamiones').change(function() {

              if ($('#refcamiones option:selected').val() > 0) {
                traerSalidas($('#refcamiones option:selected').val());
              }
              
            });

            <?php if ($idCamiones > 0) { ?>
              traerSalidas(<?php echo $idCamiones; ?>);
            <?php } ?>

            $(".btnVolver").click( function(){
              $(location).attr('href','../');   
            });

            <?php require('../../forms/'.$ruta.'/baseJS.php'); ?>

            
            
        });
    </script>
</body>

</html>