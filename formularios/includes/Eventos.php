<?php


class Eventos {

    const TABLA = 'tbeventos';
    const CAMPOS = 'evento,generaaccion,depende';
    const CAMPOSVAR = ':evento,:generaaccion,:depende';
    const RUTA = 'eventos';

    private $id;
    private $evento;
    private $generaaccion;
    private $depende;

    private $error;
    private $descripcionError;


    public function __construct()
    {

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
                'cargo'      => $this->evento,
                'cargo'      => $this->generaaccion,
                'cargo'      => $this->depende
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
            
           $this->cargar($res['evento'],$res['generaaccion'],$res['depende']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'evento'=> $this->evento,
            'generaaccion'=> $this->generaaccion,
            'depende'=> $this->depende
        );
    }

    public function cargar($evento,$generaaccion,$depende) {

        $this->setEvento($evento);
        $this->setGeneraaccion($generaaccion);
        $this->setDepende($depende);
        
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
            $where = " where (t.cargo like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        t.evento,
        (case when t.generaaccion='1' then 'Si' else 'No' end) as generaaccion,
        t.depende
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
     * Get the value of evento
     */ 
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * Set the value of evento
     *
     * @return  self
     */ 
    public function setEvento($evento)
    {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get the value of generaaccion
     */ 
    public function getGeneraaccion()
    {
        return $this->generaaccion;
    }

    /**
     * Set the value of generaaccion
     *
     * @return  self
     */ 
    public function setGeneraaccion($generaaccion)
    {
        $this->generaaccion = $generaaccion;

        return $this;
    }

    /**
     * Get the value of depende
     */ 
    public function getDepende()
    {
        return $this->depende;
    }

    /**
     * Set the value of depende
     *
     * @return  self
     */ 
    public function setDepende($depende)
    {
        $this->depende = $depende;

        return $this;
    }
}
    

?>