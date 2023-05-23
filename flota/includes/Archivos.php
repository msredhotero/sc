<?php


class Archivos {

    const TABLA = 'dbarchivos';
    const CAMPOS = 'descripcion,reftipodocumentacion,fechadesde,fechavencimiento,activo,carpeta';
    const CAMPOSVAR = ':descripcion,:reftipodocumentacion,:fechadesde,:fechavencimiento,:activo,:carpeta';
    const RUTA = 'archivos';

    private $id;
    private $descripcion;
    private $reftipodocumentacion;
    private $fechadesde;
    private $fechavencimiento;
    private $activo;
    private $carpeta;

    private $error;
    private $descripcionError;

    private $tipodocumentacion;

    public function __construct()
    {
        $this->tipodocumentacion = new Tipodocumentacion();
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
    

    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

            $query->execute([
                'descripcion'      => $this->descripcion,
                'reftipodocumentacion'      => $this->reftipodocumentacion,
                'fechadesde'      => $this->fechadesde,
                'fechavencimiento'      => $this->fechavencimiento,
                'activo'      => $this->activo,
                'carpeta'       => $this->carpeta
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
            
           $this->cargar($res['descripcion'],$res['reftipodocumentacion'],$res['fechadesde'],$res['fechavencimiento'],$res['activo'],$res['carpeta']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'descripcion'      => $this->descripcion,
            'reftipodocumentacion'      => $this->reftipodocumentacion,
            'fechadesde'      => $this->fechadesde,
            'fechavencimiento'      => $this->fechavencimiento,
            'activo'      => $this->activo,
            'carpeta'       => $this->carpeta
        );
    }

    public function cargar($descripcion,$reftipodocumentacion,$fechadesde,$fechavencimiento,$activo,$carpeta) {

        $this->setDescripcion($descripcion);
        $this->setReftipodocumentacion($reftipodocumentacion);
        $this->setFechadesde($fechadesde);
        $this->setFechavencimiento($fechavencimiento);
        $this->setActivo($activo);
        $this->setCarpeta($carpeta);

        $this->getTipodocumentacion()->buscarPorId($reftipodocumentacion);
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

    public function traerAjax($length, $start, $busqueda,$colSort,$colSortDir) {
        $where = '';

        $db = new Database();

       
        $busqueda = str_replace("'","",$busqueda);
        if ($busqueda != '') {
            $where = " where (t.area like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        t.area
        from ".self::TABLA." t
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
     * Get the value of descripcion
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of reftipodocumentacion
     */ 
    public function getReftipodocumentacion()
    {
        return $this->reftipodocumentacion;
    }

    /**
     * Set the value of reftipodocumentacion
     *
     * @return  self
     */ 
    public function setReftipodocumentacion($reftipodocumentacion)
    {
        $this->reftipodocumentacion = $reftipodocumentacion;

        return $this;
    }

    /**
     * Get the value of fechadesde
     */ 
    public function getFechadesde()
    {
        return $this->fechadesde;
    }

    /**
     * Get the value of fechadesde
     */ 
    public function getFechadesdeStr()
    {
        return ($this->fechadesde == '1' ? 'Si' : 'No');;
    }

    /**
     * Set the value of fechadesde
     *
     * @return  self
     */ 
    public function setFechadesde($fechadesde)
    {
        $this->fechadesde = $fechadesde;

        return $this;
    }

    /**
     * Get the value of fechavencimiento
     */ 
    public function getFechavencimiento()
    {
        return $this->fechavencimiento;
    }

    /**
     * Get the value of fechavencimiento
     */ 
    public function getFechavencimientoStr()
    {
        return ($this->fechavencimiento == '1' ? 'Si' : 'No');
    }

    /**
     * Set the value of fechavencimiento
     *
     * @return  self
     */ 
    public function setFechavencimiento($fechavencimiento)
    {
        $this->fechavencimiento = $fechavencimiento;

        return $this;
    }

    /**
     * Get the value of activo
     */ 
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Get the value of activo
     */ 
    public function getActivoStr()
    {
        return ($this->activo == '1' ? 'Si' : 'NO');
    }

    /**
     * Set the value of activo
     *
     * @return  self
     */ 
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get the value of tipodocumentacion
     */ 
    public function getTipodocumentacion()
    {
        return $this->tipodocumentacion;
    }

    /**
     * Set the value of tipodocumentacion
     *
     * @return  self
     */ 
    public function setTipodocumentacion($tipodocumentacion)
    {
        $this->tipodocumentacion = $tipodocumentacion;

        return $this;
    }

    /**
     * Get the value of carpeta
     */ 
    public function getCarpeta()
    {
        return $this->carpeta;
    }

    /**
     * Set the value of carpeta
     *
     * @return  self
     */ 
    public function setCarpeta($carpeta)
    {
        $this->carpeta = $carpeta;

        return $this;
    }
}
    

?>