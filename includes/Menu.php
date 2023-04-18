<?php


class Menu {

  const TABLA = 'dbmenu';
  const CAMPOS = 'ico,titulo,ruta,orden,refgrupos';
  const CAMPOSVAR = ':ico,:titulo,:ruta,:orden,:refgrupos';

  private $id;
    private $rol;
    private $altura;
    private $direccion;
    private $ico;
    private $titulo;
    private $ruta;
    private $orden;
    private $refgrupos;

    private $error;
    private $descripcionError;
    
    public function __construct($rol, $altura)
    {
        $this->rol = $rol;
        $this->altura = $altura;
    }

  public function traerTodos() {
    $db = new Database();

    $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." order by orden ";

    try {
      $consulta = $db->connect()->prepare($sql);

      $consulta->execute();

      $resultado = $consulta->fetchAll();

      return $resultado;

    } catch (PDOException $e) {
      return $e->getMessage();
    }
  }

  public function traerTodosFilter($options) {
    $db = new Database();
    $where = '';
    if (isset($options['refgrupos'])) {
        $where .= ' where t.refgrupos in ('.$options['refgrupos'].') ';
    } 
    
    if (isset($options['id'])) {
        $where .= 'where t.id in ('.$options['id'].') ';
    }


    $sql = "select 
        t.id,t.ico, t.titulo, t.ruta, t.orden, t.refgrupos
    from ".self::TABLA." t 
    left join tbgrupos r on t.refgrupos = r.id
    ".$where."
    
    order by t.refgrupos,t.orden";

    //die(var_dump($sql));

    $consulta = $db->connect()->prepare($sql);

    $consulta->execute();

    $resultado = $consulta->fetchAll();

    return $resultado;
  }

  public function MenuStr($options) {
    $cad = '';
    $cadObligatorio = '';
    $cadAux = '';
    $cadGrupoInicio = '';
    $cadGrupoFin = '';

    if (($this->rol == 2)||($this->rol == 5)||($this->rol == 6)) {
      //$datos = $this->traerTodosFilter($options['ids']);
      $datos=[];
      $existeGrupo = 0;
    } else {
        if ($this->rol == 7) {
            
            if (isset($options['refgrupos'])) {
                $datos = $this->traerTodosFilter(array('refgrupos'=> $options['refgrupos']));
                $existeGrupo = 1;
            } else {
                $datos = $this->traerTodosFilter(array('id'=> '8,10'));
                $existeGrupo = 0;
            }
            
        } else {
            if ($this->rol == 8) {
                if (isset($options['refgrupos'])) {
                    $datos = $this->traerTodosFilter(array('refgrupos'=> $options['refgrupos']));
                    $existeGrupo = 1;
                } else {
                    $datos = $this->traerTodosFilter(array('id'=> '8,10'));
                    $existeGrupo = 0;
                }
            } else {
                if (isset($options['refgrupos'])) {
                    $datos = $this->traerTodosFilter(array('refgrupos'=> $options['refgrupos']));
                    $existeGrupo = 1;
                    
                } else {
                    $datos = $this->traerTodosFilter(array('refgrupos'=> '0,1,9'));
                    $existeGrupo = 0;
                }
            }
            
        }
        
    }

    if (isset($options['idobligatorio'])) {
        $cadObligatorio = '.php?&id='.$options['idobligatorio'];
        if ($options['refgrupos'] == 4) {
            $cadAux = 'personal/';
        } else {
            $cadAux = 'camiones/';
        }
        
    }

    $cadGrupos = 0;
    $primero = 0;
    $cadSub = '';

    if (($existeGrupo == 0) && ($this->rol !== 7)) {
        $cad .= '<li class="nav-item movil-top-margen">
                        <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link" aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center border-radius-md d-flex align-items-center justify-content-center">
                            <i class="ni ni-bell-55 text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                        <span class="nav-link-text ms-1">Taller</span>
                        </a>
                        <div class="collapse " id="dashboardsExamples">
                        <ul class="nav ms-4 ps-3">';
        $cad .= '<li class="nav-item">
        <a class="nav-link " href="'.$this->altura.'ordenestrabajos/" style="padding-left:1px !important"><span class="nav-link-text ms-1">OT</span>
            </a>
        </li>
        <li class="nav-item">
        <a class="nav-link " href="'.$this->altura.'mantenimiento/" style="padding-left:1px !important"><span class="nav-link-text ms-1">Mantenciones Programadas</span>
            </a>
        </li>
        ';
    

        $cad .= '</ul></div></li>';
    }

    foreach ($datos as $row) {

        if ($row['refgrupos'] == 9) {
            
        } else {
            if ($existeGrupo > 0) {
                if ($cadGrupos != $row['refgrupos']) {
    
                    if ($primero > 0) {
                        $cad .= $cadGrupoInicio.$cadSub.$cadGrupoFin;
                        //$primero = 0;
                        $cadSub = '';
                    } 
                    switch ($row['refgrupos']) {
                        case 2:
                            $cadGrupoInicio = '<li class="nav-item">
                            <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link " aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                            <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center border-radius-md d-flex align-items-center justify-content-center">
                                <i class="ni ni-bell-55 text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <span class="nav-link-text ms-1">Certificaciones Tipo A</span>
                            </a>
                            <div class="collapse " id="dashboardsExamples">
                            <ul class="nav ms-4 ps-3">';
        
                            $cadGrupoFin = '</ul></div></li>';
                        break;
                        case 3:
                            $cadGrupoInicio = '<li class="nav-item">
                            <a data-bs-toggle="collapse" href="#dashboardsExamples2" class="nav-link " aria-controls="dashboardsExamples2" role="button" aria-expanded="false">
                            <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center border-radius-md d-flex align-items-center justify-content-center">
                                <i class="ni ni-bell-55 text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <span class="nav-link-text ms-1">Certificaciones Tipo B</span>
                            </a>
                            <div class="collapse " id="dashboardsExamples2">
                            <ul class="nav ms-4 ps-3">';
        
                            $cadGrupoFin = '</ul></div></li>';
                        break;
                        case 4:
                            $cadGrupoInicio = '<li class="nav-item">
                            <a data-bs-toggle="collapse" href="#dashboardsExamples2" class="nav-link " aria-controls="dashboardsExamples2" role="button" aria-expanded="false">
                            <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center border-radius-md d-flex align-items-center justify-content-center">
                                <i class="ni ni-bell-55 text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                            <span class="nav-link-text ms-1">Inf. 1ra Categoria</span>
                            </a>
                            <div class="collapse " id="dashboardsExamples2">
                            <ul class="nav ms-4 ps-3">';
        
                            $cadGrupoFin = '</ul></div></li>';
                        break;
                    }
                    $cadGrupos = $row['refgrupos'];
                    $primero = 1;
                    
                }
            }
    
            if ($options['activo']==$row['titulo']) {
                $activo = 'active';
            } else {
                $activo = '';
            }
            if ($row['ruta'] == 'index.php') {
                $alturaAux = '';
            } else {
                $alturaAux = '../';
            }
    
            
            if ($existeGrupo == 0) {
                $cad .= '<li class="nav-item">
                <a class="nav-link '.$activo.' " href="'.$this->altura.$cadAux.$row['ruta'].$cadObligatorio.'">';
            
                $cad .= '<div class="icon icon-shape icon-sm bg-gradient-info shadow text-center border-radius-md d-flex align-items-center justify-content-center">
                <i class="'.$row['ico'].'" aria-hidden="true"></i>
                </div>';
            
                $cad .= '<span class="nav-link-text ms-1">'.$row['titulo'].'</span>
                    </a>
                </li>';
            } else {
                $cadSub .= '<li class="nav-item">
                <a class="nav-link '.$activo.' " href="'.$this->altura.$cadAux.$row['ruta'].$cadObligatorio.'" style="padding-left:1px !important">';
        
            
                $cadSub .= '<span class="nav-link-text ms-1">'.$row['titulo'].'</span>
                    </a>
                </li>';
            }
        }

        
        
    }

    if ($primero == 1) {
        $cad .= $cadGrupoInicio.$cadSub.$cadGrupoFin;
    }

    
    return $cad;
  }
  

  public function save() {
      $db = new Database();
      try {
          
          // TODO: existe el usuario
          $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

          $query->execute([
              'ico'      => $this->ico,
              'titulo'   => $this->titulo,
              'ruta'    => $this->ruta,
              'orden'   => $this->orden,
              'refgrupos'=> $this->refgrupos
          ]);

          return true;

      } catch (PDOException $e) {
          
          error_log($e->getMessage());
          return false;
          
      }
  }

  public function buscarPorId($id) {
      $db = new Database();

      $sql = "SELECT id,
                     ".self::CAMPOS."
            FROM ".self::TABLA." where id = :id";

      $consulta = $db->connect()->prepare($sql);

      $consulta->bindParam(':id', $id);

      $consulta->execute();

      $res = $consulta->fetch();

      

      if($res){
          
         $this->cargar($res['ico'],$res['titulo'],$res['ruta'],$res['orden'],$res['refgrupos']);
         $this->setId($id);

         

      }else{
         return null;
      }
  }

  public function devolverArray() {
      return array(
        'ico'      => $this->ico,
        'titulo'   => $this->titulo,
        'ruta'    => $this->ruta,
        'orden'   => $this->orden,
        'refgrupos'=> $this->refgrupos
      );
  }

  public function cargar($ico,$titulo,$ruta,$orden,$refgrupos) {

    $this->setIco($ico);
    $this->setTitulo($titulo);
    $this->setRuta($ruta);
    $this->setOrden($orden);
    $this->setRefgrupos($refgrupos);
  }


  public function borrar() {
      $db = new Database();
      try {

          $query = $db->connect()->prepare('DELETE FROM '.self::TABLA.' WHERE id = :id');

          try {
              $query->execute([
                  'id'      => $this->id
              ]);

              $this->setError(0);

          }catch(PDOException $e){
              $this->setError(1);
              $this->setDescripcionError('Ha surgido un error y no se puede modificar la solicitud');
              //echo 'Ha surgido un error y no se puede crear la solicitud: ' . $e->getMessage();
             
          }

      } catch (PDOException $e) {
          
          error_log($e->getMessage());
          return false;
          
      }
  }

  public function modificarFilter($arCampos) {

      $db = new Database();

      $cadSet = '';

      foreach ($arCampos as $clave => $valor) {
      // $array[3] se actualizará con cada valor de $array...
         $cadSet .= "{$clave} = :{$clave},";

      }

      $set = substr($cadSet,0,-1);

      //die(var_dump($this->reftipopersonas));
      $consulta = $db->connect()->prepare('UPDATE '.self::TABLA.' SET '.$set.' where id = :id');

      //die(var_dump($consulta));
      foreach ($arCampos as $key => &$val) {
         $consulta->bindParam($key, $val);
      }
      $consulta->bindParam(':id', $this->id);


      try {
          $consulta->execute();

          $this->setError(0);
      }catch(PDOException $e){
         $this->setError(1);
         $this->setDescripcionError('Ha surgido un error y no se puede modificar la solicitud');
          //echo 'Ha surgido un error y no se puede crear la solicitud: ' . $e->getMessage();
         
      }

      //$this->setIdendoso($conexion->lastInsertId());

      $db = null;
  }

    

    public function printCSS() {

        if ($this->getAltura() != '../') {
            return '<!--     Fonts and icons     -->
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
            <!-- Nucleo Icons -->
            <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
            <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
            <!-- Font Awesome Icons -->
            <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
            <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
            <!-- CSS Files -->
            <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.5" rel="stylesheet" />
        
            <link rel="stylesheet" href="../DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
            <link rel="stylesheet" href="../DataTables/DataTables-1.10.18/css/dataTables.bootstrap.css">
            <link rel="stylesheet" href="../DataTables/DataTables-1.10.18/css/dataTables.jqueryui.min.css">
            <link rel="stylesheet" href="../DataTables/DataTables-1.10.18/css/jquery.dataTables.css">';
        } else {
            return '<!--     Fonts and icons     -->
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
            <!-- Nucleo Icons -->
            <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
            <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
            <!-- Font Awesome Icons -->
            <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
            <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
            <!-- CSS Files -->
            <link id="pagestyle" href="../../assets/css/soft-ui-dashboard.css?v=1.0.5" rel="stylesheet" />
        
            <link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
            <link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/dataTables.bootstrap.css">
            <link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/dataTables.jqueryui.min.css">
            <link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/jquery.dataTables.css">
            <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>';
        }   
    }

    public function printJS() {
        if ($this->getAltura() != '../') {
            return '<!--   Core JS Files   -->
            <script src="../assets/js/core/popper.min.js"></script>
            <script src="../assets/js/core/bootstrap.min.js"></script>
            <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
            <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
            <script src="../assets/js/plugins/chartjs.min.js"></script>'."
        
            <script>
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
            </script>".'
            <!-- Github buttons -->
            <script async defer src="https://buttons.github.io/buttons.js"></script>
            <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
            <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.5"></script>
            <script src="../assets/js/jquery.min.js"></script>
            <script src="../DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="../assets/js/inputmask.min.js"></script>
            <script src="../assets/js/jquery.inputmask.min.js"></script>
            <script src="../assets/js/jquery.numeric.min.js"></script>
            <script src="../assets/js/pdfobject.js"></script>
            <script src="../assets/js/jquery.blockUI.js"></script>
            <script src="../assets/js/general.js"></script>';
            
        } else {
            return '<!--   Core JS Files   -->
            <script src="../../assets/js/core/popper.min.js"></script>
            <script src="../../assets/js/core/bootstrap.min.js"></script>
            <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
            <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
            <script src="../../assets/js/plugins/chartjs.min.js"></script>'."
            <script>
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
            </script>".'
            <!-- Github buttons -->
            <script async defer src="https://buttons.github.io/buttons.js"></script>
            <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
            <script src="../../assets/js/soft-ui-dashboard.min.js?v=1.0.5"></script>
            <script src="../../assets/js/jquery.min.js"></script>
            <script src="../../DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="../../assets/js/inputmask.min.js"></script>
            <script src="../../assets/js/jquery.inputmask.min.js"></script>
            <script src="../../assets/js/jquery.numeric.min.js"></script>
            <script src="../../assets/js/pdfobject.js"></script>
            <script src="../../assets/js/jquery.blockUI.js"></script>
            <script src="../../assets/js/general.js"></script>';
            
        } 
    }
    /**
     * Get the value of rol
     */ 
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set the value of rol
     *
     * @return  self
     */ 
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get the value of altura
     */ 
    public function getAltura()
    {
        
        return $this->altura;
    }

    /**
     * Set the value of altura
     *
     * @return  self
     */ 
    public function setAltura($altura)
    {
        $this->altura = $altura;

        return $this;
    }

    /**
     * Get the value of direccion
     */ 
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set the value of direccion
     *
     * @return  self
     */ 
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get the value of ico
     */ 
    public function getIco()
    {
        return $this->ico;
    }

    /**
     * Set the value of ico
     *
     * @return  self
     */ 
    public function setIco($ico)
    {
        $this->ico = $ico;

        return $this;
    }

    /**
     * Get the value of titulo
     */ 
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set the value of titulo
     *
     * @return  self
     */ 
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get the value of ruta
     */ 
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set the value of ruta
     *
     * @return  self
     */ 
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get the value of orden
     */ 
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set the value of orden
     *
     * @return  self
     */ 
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get the value of refgrupos
     */ 
    public function getRefgrupos()
    {
        return $this->refgrupos;
    }

    /**
     * Set the value of refgrupos
     *
     * @return  self
     */ 
    public function setRefgrupos($refgrupos)
    {
        $this->refgrupos = $refgrupos;

        return $this;
    }

  /**
   * Get the value of id
   */ 
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set the value of id
   *
   * @return  self
   */ 
  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }

    /**
     * Get the value of error
     */ 
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set the value of error
     *
     * @return  self
     */ 
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get the value of descripcionError
     */ 
    public function getDescripcionError()
    {
        return $this->descripcionError;
    }

    /**
     * Set the value of descripcionError
     *
     * @return  self
     */ 
    public function setDescripcionError($descripcionError)
    {
        $this->descripcionError = $descripcionError;

        return $this;
    }
}



?>