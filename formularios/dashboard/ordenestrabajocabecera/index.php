<?php

spl_autoload_register(function($clase){
  include_once "../../includes/" .$clase. ".php";        
});


$Globales = new Globales();
$Session = new Session('user');
$Ordenestrabajocabecera = new Ordenestrabajocabecera();


if (!($Session->exists())) {
  header('Location: ../../error.php');
}

$Menu = new Menu($_SESSION['user']->getRefroles(),'../');
//$_SESSION['user']->getRefroles()

if ($_SESSION['user']->getRefroles()==2) {
  header('Location: ../');
}



$ruta = $Ordenestrabajocabecera::RUTA;

$options['activo'] = 'Ordenes de Trabajo';
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
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
        <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#lgmNuevo">
            AGREGAR
        </button>
        <button type="button" class="btn bg-gradient-danger" id="filtrar">Filtrar</button>
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
          
          <div class="col">
            <label for="refsemaforo_filtro" class="control-label">Prioridad</label>
            <div class="form-group">
              <select class="form-control" name="refsemaforo_filtro" id="refsemaforo_filtro">
                <option value="0">-- Seleccionar --</option>
                <?php foreach ($Ordenestrabajocabecera->getSemaforo()->traerTodos() as $row) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nivel']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

        </div>
        <div class="row">
            <?php require('../../grillas/'.$ruta.'.php'); ?>
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
    <?php require('../../forms/'.$ruta.'/insertar.php'); ?>

    <?php require('../../forms/'.$ruta.'/modificar.php'); ?>

    <?php require('../../forms/'.$ruta.'/eliminar.php'); ?>
  
    <?php echo $Menu->printJS(); ?>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function(){
          

            var table = $('#example').DataTable({
              'dom': 'Bfrtip',
              'buttons': [
                   {
                    'extend': 'excelHtml5',
                    'text': 'Exportar a excel',
                    className: ' btn btn-info bg-success',
                    'title': 'Listado OT'                      
                   },     
                  ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "../../json/jstablasajax.php?tabla=<?php echo $ruta; ?>",
                "order": [[4, 'asc']],
                "fnServerData": function ( sSource, aoData, fnCallback ) {
                  /* Add some extra data to the sender */
                  aoData.push( { "name": "start", "value": $('#fechadesde').val(), } );
                  aoData.push( { "name": "end", "value": $('#fechahasta').val(), } );
                  aoData.push( { "name": "prioridad", "value": $('#refsemaforo_filtro').val(), } );
                  $.getJSON( sSource, aoData, function (json) {
                  /* Do whatever additional processing you want on the callback, then tell DataTables */
                  fnCallback(json)
                  } );
                },
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

            $('#filtrar').click( function() {
              table.draw();
            } );

            <?php require('../../forms/'.$ruta.'/baseJS.php'); ?>
            
        });
    </script>
</body>

</html>