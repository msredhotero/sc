<?php


class Sucursales {

    const TABLA = 'dbsucursales';
    const CAMPOS = 'reftabla,idreferencia,sucursal,latitud,longitud,direccion,telefono,codpostal,refzonas';
    const CAMPOSVAR = ':reftabla,:idreferencia,:sucursal,:latitud,:longitud,:direccion,:telefono,:codpostal,:refzonas';
    const RUTA = 'sucursales';

    private $id;
    private $reftabla;
    private $idreferencia;
    private $sucursal;
    private $latitud;
    private $longitud;
    private $direccion;
    private $telefono;
    private $codpostal;
    private $refzonas;

    private $error;
    private $descripcionError;


    public function __construct($reftabla,$idreferencia)
    {
        $this->reftabla = $reftabla;
        $this->idreferencia = $idreferencia;
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

    public function traerTodosFilter($options) {
        $db = new Database();
        $where = '';
        if (isset($options['notin'])) {
            $where .= 'and id <> ('.$options['notin'].') ';
        }

        if (isset($options['reftabla'])) {
            $where .= 'and reftabla = ('.$options['reftabla'].') ';
        }

        if (isset($options['idreferencia'])) {
            $where .= 'and idreferencia = ('.$options['idreferencia'].') ';
        }


        $sql = "select id,sucursal,latitud,longitud,direccion,telefono,codpostal,refzonas from dbsucursales where '1'='1' ".$where." order by 1 ";

        $consulta = $db->connect()->prepare($sql);

        $consulta->execute();

        $resultado = $consulta->fetchAll();

        return $resultado;
    }
    
    
    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

            $query->execute([
                'reftabla'      => $this->reftabla,
                'idreferencia'    => $this->idreferencia,
                'sucursal'    => $this->sucursal,
                'latitud'    => $this->latitud,
                'longitud'    => $this->longitud,
                'direccion'    => $this->direccion,
                'telefono'    => $this->telefono,
                'codpostal'    => $this->codpostal,
                'refzonas'     => $this->refzonas
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
            
           $this->cargar($res['sucursal'],$res['latitud'],$res['longitud'],$res['direccion'],$res['telefono'],$res['codpostal'],$res['refzonas']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'sucursal'    => $this->sucursal,
            'latitud'    => $this->latitud,
            'longitud'    => $this->longitud,
            'direccion'    => $this->direccion,
            'telefono'    => $this->telefono,
            'codpostal'    => $this->codpostal,
            'refzonas'     => $this->refzonas
        );
    }

    public function cargar($sucursal,$latitud,$longitud,$direccion,$telefono,$codpostal,$refzonas) {

        $this->setSucursal($sucursal);
        $this->setLatitud($latitud);
        $this->setLongitud($longitud);
        $this->setDireccion($direccion);
        $this->setTelefono($telefono);
        $this->setCodpostal($codpostal);
        $this->setRefzonas($refzonas);
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
            $where = " and (tt.especifico like '%".$busqueda."%' or t.sucursal like '%".$busqueda."%' or t.direccion like '%".$busqueda."%' or t.telefono like '%".$busqueda."%' or t.codpostal like '%".$busqueda."%')";
        }
       
       
        $sql = "select
        t.id,
        tt.especifico,
        t.sucursal,
        t.direccion,
        t.telefono,
        t.codpostal,
        z.zona
        from ".self::TABLA." t
        inner
        join    tbtablas tt on tt.idtabla = t.reftabla AND t.idreferencia = ".$this->idreferencia."
        left
        join    tbzonas z on z.id = t.refzonas
        where tt.idtabla = ".$this->reftabla." ".$where."
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
     * Get the value of reftabla
     */ 
    public function getReftabla()
    {
        return $this->reftabla;
    }

    /**
     * Set the value of reftabla
     *
     * @return  self
     */ 
    public function setReftabla($reftabla)
    {
        $this->reftabla = $reftabla;

        return $this;
    }

    /**
     * Get the value of idreferencia
     */ 
    public function getIdreferencia()
    {
        return $this->idreferencia;
    }

    /**
     * Set the value of idreferencia
     *
     * @return  self
     */ 
    public function setIdreferencia($idreferencia)
    {
        $this->idreferencia = $idreferencia;

        return $this;
    }

    /**
     * Get the value of sucursal
     */ 
    public function getSucursal()
    {
        return $this->sucursal;
    }

    /**
     * Set the value of sucursal
     *
     * @return  self
     */ 
    public function setSucursal($sucursal)
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    /**
     * Get the value of latitud
     */ 
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set the value of latitud
     *
     * @return  self
     */ 
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get the value of longitud
     */ 
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set the value of longitud
     *
     * @return  self
     */ 
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

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
     * Get the value of telefono
     */ 
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */ 
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of codpostal
     */ 
    public function getCodpostal()
    {
        return $this->codpostal;
    }

    /**
     * Set the value of codpostal
     *
     * @return  self
     */ 
    public function setCodpostal($codpostal)
    {
        $this->codpostal = $codpostal;

        return $this;
    }

    /**
     * Get the value of refzonas
     */ 
    public function getRefzonas()
    {
        return $this->refzonas;
    }

    /**
     * Set the value of refzonas
     *
     * @return  self
     */ 
    public function setRefzonas($refzonas)
    {
        $this->refzonas = $refzonas;

        return $this;
    }
}
    

?>