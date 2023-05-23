<?php


class Mantenimientoflota {

    const TABLA = 'dbmantenimientoflota';
    const CAMPOS = 'refcamiones,reftareas,kilometros';
    const CAMPOSVAR = ':refcamiones,:reftareas,:kilometros';
    const RUTA = 'mantenimientoflota';

    private $id;
    private $refcamiones;
    private $reftareas;
    private $kilometros;

    private $error;
    private $descripcionError;

    private $camiones;
    private $tareas;

    public function __construct()
    {
        $this->camiones = new Camiones();
        $this->tareas = new Tareas();
    }

    

    public function traerTodos() {
        $db = new Database();

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." order by 1 ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
        
    }

    public function traerTodosFilter($arCampos) {
        $db = new Database();

        $cadSet = '';
  
        foreach ($arCampos as $clave => $valor) {
        // $array[3] se actualizará con cada valor de $array...
           $cadSet .= "{$clave} = :{$clave} and ";
        }


        $set = substr($cadSet,0,-4);
  
        $sql = "select id,reftareas,refcamiones,kilometros from ".self::TABLA." where ".$set." order by 1 ";

        //die(var_dump($sql));
  
        $consulta = $db->connect()->prepare($sql);
        foreach ($arCampos as $key => &$val) {
            $consulta->bindParam($key, $val);
        }

        try {
            $consulta = $db->connect()->prepare($sql);
            
            foreach ($arCampos as $key => &$val) {
                //die(var_dump($val));
                $consulta->bindParam($key, $val);
            }

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
        
    }
    

    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

            $query->execute([
                'refcamiones'      => $this->refcamiones,
                'reftareas'      => $this->reftareas,
                'kilometros'      => $this->kilometros
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
            
           $this->cargar($res['refcamiones'],$res['reftareas'],$res['kilometros']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'refcamiones'      => $this->refcamiones,
            'reftareas'      => $this->reftareas,
            'kilometros'      => $this->kilometros
        );
    }

    public function cargar($refcamiones,$reftareas,$kilometros) {

        $this->setRefcamiones($refcamiones);
        $this->setReftareas($reftareas);
        $this->setKilometros($kilometros);
        
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

    public function devolverCalculo($kilometros, $kilometrosmantenimiento) {
        $subC = 0;
        $resultado = 0;
        if ($kilometros < $kilometrosmantenimiento) {
            $resultado = $kilometrosmantenimiento - $kilometros;
          } else {
            if ($kilometros == $kilometrosmantenimiento) {
              $resultado = 0;
            } else {
              $subC = (round($kilometros / $kilometrosmantenimiento) - ($kilometros / $kilometrosmantenimiento)) * $kilometrosmantenimiento;
              if ($subC < 0) {
                $resultado = $kilometrosmantenimiento + $subC;
              } else {
                $resultado = $subC;
              }
            }
        }

        return $resultado;
    }

    public function calcularIndice($kilometros, $kilometrosmantenimiento) {
        $indice = 0;
        if ($kilometros < $kilometrosmantenimiento) {
            $indice = 1;
        } else {
            $indice = round($kilometros / $kilometrosmantenimiento);
        }

        return $indice;
    }

    public function traerAjaxMantenimiento($length, $start, $busqueda,$colSort,$colSortDir) {
        $where = '';

        $db = new Database();

       
        $busqueda = str_replace("'","",$busqueda);
        if ($busqueda != '') {
            $where = " where (c.patente like '%".$busqueda."%' or ta.tarea like '%".$busqueda."%')";
        }
        $cadCamiones = '';
        if ($this->getRefcamiones() > 0) {
            $cadCamiones = " and t.refcamiones = ".$this->getRefcamiones();
        }
       
       
        $sql = "select
        t.id,
        c.patente,
        ta.tarea,
        t.kilometros as kilometrosmantenimiento,
        c.kilometros,
        ta.id as idtarea,
        c.id as idcamion,
        c.color
        from ".self::TABLA." t
        inner join dbcamiones c on c.id = t.refcamiones ".$cadCamiones."
        inner join tbtareas ta on ta.id = t.reftareas
        ".$where."
        ORDER BY ".$colSort." ".$colSortDir." ";
        $limit = "limit ".$start.",".$length;
        
        //$sql = "select id,tema,urlvideo from tbtemas";
          //tp.meses
        //die(var_dump($sql));
          //having (case when max(v.version) > 1 then 13 else COUNT(pvd.idperiodicidadventadetalle) end) >= 1
        $queryLimit = $db->connect()->prepare($sql.$limit);

        $queryLimit->execute();
        
        $dataLimit = $queryLimit->fetchAll();

        $query = $db->connect()->prepare($sql);

        $query->execute();
            
        $data = $query;

        $OT = new Ordenestrabajos(0);
        
        $arCad = [];
        $arFijo = array();
        $color = 0;
        $i = 0;
        
        // del 3 al 9
        foreach ($dataLimit as $row) {
            
 
            $i += 1;


            $arFijo[0] = $row['id'];
            $arFijo['indices'] = $i;
            // busco si existe una ot qcon el indice que me indique que no se repita la ot de mantenimiento
            $resOT = $OT->traerTodosFilter(array('indice'=> $this->calcularIndice($row['kilometros'],$row['kilometrosmantenimiento']),'reftareas'=>$row['idtarea'],'refcamiones'=>$row['idcamion']));

            //seteo el color
            $color = '';
            /*
            $arFijo['patente'] = $row['patente'];
            $arFijo['kilometros'] = number_format($row['kilometros'],0,'','.');
            */

            $div = "<div class='c' style='background-color:".$row['color']."'></div>";
            $arFijo[1] = $row['patente'].$div;
            $arFijo[2] = number_format($row['kilometros'],0,'','.');

            $tareaT = new Tareas();
            $tareaT->buscarPorId($row['idtarea']);

            if ($color != 3) {
                if ($this->devolverCalculo($row['kilometros'], $row['kilometrosmantenimiento']) <= 3000 ) {
                    $color = 3;
                } else {
                    if ($color != 2) {
                        if (($this->devolverCalculo($row['kilometros'], $row['kilometrosmantenimiento']) > 3000 ) && ($this->devolverCalculo($row['kilometros'], $row['kilometrosmantenimiento']) <= 6000 )) {
                            $color = 2;
                        } else {
                            $color = 1;
                        }
                    }
                }
            }

            /*
            $arFijo['kilometrosmantenimiento'] = number_format($rowM['kilometros'],0,'','.').' <b>Faltan: '.number_format($this->devolverCalculo($row['kilometros'], $rowM['kilometros']),0,'','.').'</b>';
            $arFijo['tareas'] = $tareaT->getTarea();
            */
            $arFijo[5] = number_format($row['kilometrosmantenimiento'],0,'','.').' <b>Faltan: '.number_format($this->devolverCalculo($row['kilometros'], $row['kilometrosmantenimiento']),0,'','.').'</b>';
            $arFijo[4] = $tareaT->getTarea();

            if (count($resOT) > 0) {
                //$arFijo['estado'] = '<span class="badge badge-sm bg-gradient-info">OT ok</span>';
                $arFijo[3] = '<span class="badge badge-sm bg-gradient-info">OT ok</span>';
            } else {
                switch ($color) {
                    case 3:
                        //$arFijo['estado'] = '<span class="badge badge-sm bg-gradient-danger">< 3000</span>';
                        $arFijo[3] = '<span class="badge badge-sm bg-gradient-danger">< 3000</span>';
                    break;
                    case 2:
                        //$arFijo['estado'] = '<span class="badge badge-sm bg-gradient-warning">< 6000</span>';
                        $arFijo[3] = '<span class="badge badge-sm bg-gradient-warning">< 6000</span>';
                    break;
                    case 1:
                        //$arFijo['estado'] = '<span class="badge badge-sm bg-gradient-success">> 6000</span>';
                        $arFijo[3] = '<span class="badge badge-sm bg-gradient-success">> 6000</span>';
                    break;
                    default:
                        //$arFijo['estado'] = '<span class="badge badge-sm bg-gradient-light">Estado</span>';
                        $arFijo[3] = '<span class="badge badge-sm bg-gradient-light">Estado</span>';
                    break;
                }
            }
            

            array_push($arCad,$arFijo);

        }

        //return $arCad;
        $res = array($arCad , $data->rowCount());
        return $res;
    }


    public function generarMantenimiento() {
        $where = '';

        $db = new Database();

       
        $sql = "select
        t.id,
        c.patente,
        ta.tarea,
        t.kilometros as kilometrosmantenimiento,
        c.kilometros,
        ta.id as idtarea,
        c.id as idcamion
        from ".self::TABLA." t
        inner join dbcamiones c on c.id = t.refcamiones
        inner join tbtareas ta on ta.id = t.reftareas
        ".$where."
        ORDER BY 1 ";
        
        //$sql = "select id,tema,urlvideo from tbtemas";
          //tp.meses
        //die(var_dump($sql));
          //having (case when max(v.version) > 1 then 13 else COUNT(pvd.idperiodicidadventadetalle) end) >= 1
        $queryLimit = $db->connect()->prepare($sql);

        $queryLimit->execute();
        
        $dataLimit = $queryLimit->fetchAll();

        $OT = new Ordenestrabajos(0);
        
        $arCad = [];
        $arFijo = array();
        
        // del 3 al 9
        foreach ($dataLimit as $row) {
            // busco si existe una ot qcon el indice que me indique que no se repita la ot de mantenimiento
            $resOT = $OT->traerTodosFilter(array('indice'=> $this->calcularIndice($row['kilometros'],$row['kilometrosmantenimiento']),'reftareas'=>$row['idtarea'],'refcamiones'=>$row['idcamion']));

            // si ya estan en rojo, cargo las ot nuevas
            if (($this->devolverCalculo($row['kilometros'], $row['kilometrosmantenimiento']) <= 3000 ) && (count($resOT) < 1)) {
                $arFijo[0] = $row['id'];

                $arFijo[1] = $row['patente'];
                $arFijo[2] = number_format($row['kilometros'],0,'','.');

                $tareaT = new Tareas();
                $tareaT->buscarPorId($row['idtarea']);

                $arFijo[4] = number_format($row['kilometrosmantenimiento'],0,'','.').' <b>Faltan: '.number_format($this->devolverCalculo($row['kilometros'], $row['kilometrosmantenimiento']),0,'','.').'</b>';
                $arFijo[3] = $tareaT->getTarea();

                $OTnueva = new Ordenestrabajos($row['idcamion']);
                $OTnueva->setRefcamiones($row['idcamion']);
                $OTnueva->setReftareas($row['idtarea']);
                $OTnueva->setRefestados(1);
                $OTnueva->setFechainicio(date('Y-m-d'));
                $OTnueva->setFechafin('');
                $OTnueva->setFecharealfinalizacion('');
                $OTnueva->setIndice($this->calcularIndice($row['kilometros'],$row['kilometrosmantenimiento']));
                $OTnueva->setUsuariocrea('automatico');

                $OTnueva->save();

                array_push($arCad,$arFijo);
            }


            

        }

        return $arCad;
    }

    public function traerAjax($length, $start, $busqueda,$colSort,$colSortDir) {
        $where = '';

        $db = new Database();

       
        $busqueda = str_replace("'","",$busqueda);
        if ($busqueda != '') {
            $where = " where (c.patente like '%".$busqueda."%' or ta.tarea like '%".$busqueda."%')";
        }
       
       
        $sql = "select
        t.id,
        c.patente,
        ta.tarea,
        t.kilometros
        from ".self::TABLA." t
        inner join dbcamiones c on c.id = t.refcamiones
        inner join tbtareas ta on ta.id = t.reftareas
        ".$where."
        ORDER BY ".$colSort." ".$colSortDir." ";
        $limit = "limit ".$start.",".$length;
        
        //$sql = "select id,tema,urlvideo from tbtemas";
          //tp.meses
        //die(var_dump($sql));
          //having (case when max(v.version) > 1 then 13 else COUNT(pvd.idperiodicidadventadetalle) end) >= 1
        $queryLimit = $db->connect()->prepare($sql.$limit);

        $queryLimit->execute();
        
        $dataLimit = $queryLimit->fetchAll(PDO::FETCH_NUM);

        $query = $db->connect()->prepare($sql);

        $query->execute();
            
        $data = $query;
        
        //var_dump($dataLimit);

        $res = array($dataLimit , $data->rowCount());
        return $res;
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

   
    /**
     * Get the value of refcamiones
     */ 
    public function getRefcamiones()
    {
        return $this->refcamiones;
    }

    /**
     * Set the value of refcamiones
     *
     * @return  self
     */ 
    public function setRefcamiones($refcamiones)
    {
        $this->refcamiones = $refcamiones;

        return $this;
    }

    /**
     * Get the value of reftareas
     */ 
    public function getReftareas()
    {
        return $this->reftareas;
    }

    /**
     * Set the value of reftareas
     *
     * @return  self
     */ 
    public function setReftareas($reftareas)
    {
        $this->reftareas = $reftareas;

        return $this;
    }

    /**
     * Get the value of kilometros
     */ 
    public function getKilometros()
    {
        return $this->kilometros;
    }

    /**
     * Set the value of kilometros
     *
     * @return  self
     */ 
    public function setKilometros($kilometros)
    {
        $this->kilometros = $kilometros;

        return $this;
    }

    /**
     * Get the value of camiones
     */ 
    public function getCamiones()
    {
        return $this->camiones;
    }

    /**
     * Set the value of camiones
     *
     * @return  self
     */ 
    public function setCamiones($camiones)
    {
        $this->camiones = $camiones;

        return $this;
    }

    /**
     * Get the value of tareas
     */ 
    public function getTareas()
    {
        return $this->tareas;
    }

    /**
     * Set the value of tareas
     *
     * @return  self
     */ 
    public function setTareas($tareas)
    {
        $this->tareas = $tareas;

        return $this;
    }
}
    

?>