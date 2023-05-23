<?php


class Tags {

    const TABLA = 'dbtags';
    const CAMPOS = 'reftabla,idreferencia,tag';
    const CAMPOSVAR = ':reftabla,:idreferencia,:tag';
    const RUTA = 'tags';

    private $id;
    private $reftabla;
    private $idreferencia;
    private $tag;

    private $error;
    private $descripcionError;


    public function __construct($reftabla, $idreferencia)
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
    

    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

            $query->execute([
                'reftabla'      => $this->reftabla,
                'idreferencia'    => $this->idreferencia,
                'tag'   => $this->tag
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
            
           $this->cargar($res['tag']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'tag'=> $this->tag
        );
    }

    public function cargar($tag) {

        $this->setTag($tag);
        
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
            $where = " and (t.tag like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        tt.especifico,
        t.tag
        from ".self::TABLA." t
        inner
        join    tbtablas tt on tt.idtabla = t.reftabla and t.idreferencia = ".$this->idreferencia."
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
     * Get the value of tag
     */ 
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set the value of tag
     *
     * @return  self
     */ 
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }
}
    

?>