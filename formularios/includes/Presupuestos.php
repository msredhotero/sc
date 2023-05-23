<?php


class Presupuestos {

    const TABLA = 'dbpresupuestos';
    const CAMPOS = 'refmateriales,cantidad,refordenestrabajodetalle';
    const CAMPOSVAR = ':refmateriales,:cantidad,:refordenestrabajodetalle';
    const RUTA = 'presupuestos';

    private $id;
    private $refmateriales;
    private $cantidad;
    private $refordenestrabajodetalle;

    private $materiales;

    private $error;
    private $descripcionError;

    public function __construct()
    {
        $this->materiales = new Materiales();
        
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
  
        $sql = "select id,refmateriales from ".self::TABLA." where ".$set." ";

        //die(var_dump($set));
  
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
                'refmateriales'      => $this->refmateriales,
                'cantidad'      => $this->cantidad,
                'refordenestrabajodetalle'  => $this->refordenestrabajodetalle
            ]);

            return true;

        } catch (PDOException $e) {
            
            error_log($e->getMessage());
            return false;
            
        }
    }

    public function buscarPorValor($campo, $valor) {
        $db = new Database();
  
        $sql = "select id from ".self::TABLA." where ".$campo." = :".$campo." ";
  
        $consulta = $db->connect()->prepare($sql);
        $consulta->bindParam(':'.$campo, $valor);
  
        $consulta->execute();
  
        $res = $consulta->fetch();
  
        if($res){
  
           $this->buscarPorId($res['id']);
  
        }else{
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
            
           $this->cargar($res['refmateriales'],$res['cantidad'],$res['refordenestrabajodetalle']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        
        return array(
            'refmateriales'=> $this->refmateriales,
            'cantidad'=> $this->cantidad,
            'refordenestrabajodetalle'=>$this->refordenestrabajodetalle
        );
    }

    public function cargar($refmateriales,$cantidad,$refordenestrabajodetalle) {

        $this->setRefmateriales($refmateriales);
        $this->setCantidad($cantidad);
        $this->setRefordenestrabajodetalle($refordenestrabajodetalle);
        
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
            $where = " and (m.material like '%".$busqueda."%' )";
        }
        

       
        $sql = "select
        r.id,
        m.material,
        r.cantidad
        from ".self::TABLA." r
        inner join tbmateriales m on m.id = r.refmateriales
        where r.refordenestrabajodetalle = ".$this->getRefordenestrabajodetalle()." ".$where."
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
     * Get the value of refmateriales
     */ 
    public function getRefmateriales()
    {
        return $this->refmateriales;
    }

    /**
     * Set the value of refmateriales
     *
     * @return  self
     */ 
    public function setRefmateriales($refmateriales)
    {
        $this->refmateriales = $refmateriales;

        return $this;
    }

    /**
     * Get the value of cantidad
     */ 
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */ 
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get the value of materiales
     */ 
    public function getMateriales()
    {
        return $this->materiales;
    }

    /**
     * Set the value of materiales
     *
     * @return  self
     */ 
    public function setMateriales($materiales)
    {
        $this->materiales = $materiales;

        return $this;
    }

    /**
     * Get the value of refordenestrabajodetalle
     */ 
    public function getRefordenestrabajodetalle()
    {
        return $this->refordenestrabajodetalle;
    }

    /**
     * Set the value of refordenestrabajodetalle
     *
     * @return  self
     */ 
    public function setRefordenestrabajodetalle($refordenestrabajodetalle)
    {
        $this->refordenestrabajodetalle = $refordenestrabajodetalle;

        return $this;
    }

}
    

?>