<?php


class Respuestascuestionario {

    const TABLA = 'dbrespuestascuestionario';
    const CAMPOS = 'refpreguntascuestionario,respuesta,orden,activo,leyenda,inhabilita';
    const CAMPOSVAR = ':refpreguntascuestionario,:respuesta,:orden,:activo,:leyenda,:inhabilita';
    const RUTA = 'respuestascuestionario';

    private $id;
    private $refpreguntascuestionario;
    private $respuesta;
    private $orden;
    private $leyenda;
    private $inhabilita;
    private $activo;

    private $preguntascuestionario;

    private $error;
    private $descripcionError;


    public function __construct()
    {
        $this->preguntascuestionario = new Preguntascuestionario();
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

        if (isset($options['refpreguntascuestionario'])) {
            $where .= 'and refpreguntascuestionario = ('.$options['refpreguntascuestionario'].') ';
        }


        $sql = "select id,respuesta,leyenda from dbrespuestascuestionario where activo='1' ".$where." order by orden";

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
                'refpreguntascuestionario' => $this->refpreguntascuestionario,
                'respuesta' => $this->respuesta,
                'orden' => $this->orden,
                'activo'         => $this->activo,
                'leyenda'         => $this->leyenda,
                'inhabilita'         => $this->inhabilita
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
            
           $this->cargar($res['refpreguntascuestionario'],$res['respuesta'],$res['orden'],$res['activo'],$res['leyenda'],$res['inhabilita']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'refpreguntascuestionario' => $this->refpreguntascuestionario,
            'respuesta' => $this->respuesta,
            'orden' => $this->orden,
            'activo'         => $this->activo,
            'leyenda'         => $this->leyenda,
            'inhabilita'         => $this->inhabilita
        );
    }

    public function cargar($refpreguntascuestionario, $respuesta, $orden, $activo, $leyenda, $inhabilita) {

        $this->setRefpreguntascuestionario($refpreguntascuestionario);
        $this->setRespuesta($respuesta);
        $this->setOrden($orden);
        $this->setActivo($activo);
        $this->setLeyenda($leyenda);
        $this->setInhabilita($inhabilita);
        
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
            $where = " where (t.respuesta like '%".$busqueda."%' )";
        }
       
        $cadWhere = '';
        if ($this->refpreguntascuestionario > 0) {
            $cadWhere = ' and p.id = '.$this->refpreguntascuestionario;
        } 
       
        $sql = "select
        t.id,
        t.respuesta,
        t.orden,
        (case when t.activo = '1' then 'Si' else 'No' end) as activo,
        t.leyenda
        from ".self::TABLA." t
        inner join dbpreguntascuestionario p on p.id = t.refpreguntascuestionario ".$cadWhere."
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
     * Get the value of activo
     */ 
    public function getActivo()
    {
        return $this->activo;
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
     * Get the value of activo
     */ 
    public function getActivoStr()
    {
        return ($this->activo == '1' ? 'Si' : 'No');
    }

    /**
     * Get the value of refpreguntascuestionario
     */ 
    public function getRefpreguntascuestionario()
    {
        return $this->refpreguntascuestionario;
    }

    /**
     * Set the value of refpreguntascuestionario
     *
     * @return  self
     */ 
    public function setRefpreguntascuestionario($refpreguntascuestionario)
    {
        $this->refpreguntascuestionario = $refpreguntascuestionario;

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
     * Get the value of leyenda
     */ 
    public function getLeyenda()
    {
        return $this->leyenda;
    }

    /**
     * Set the value of leyenda
     *
     * @return  self
     */ 
    public function setLeyenda($leyenda)
    {
        $this->leyenda = $leyenda;

        return $this;
    }

    /**
     * Get the value of inhabilita
     */ 
    public function getInhabilita()
    {
        return $this->inhabilita;
    }

    /**
     * Set the value of inhabilita
     *
     * @return  self
     */ 
    public function setInhabilita($inhabilita)
    {
        $this->inhabilita = $inhabilita;

        return $this;
    }

    /**
     * Get the value of preguntascuestionario
     */ 
    public function getPreguntascuestionario()
    {
        return $this->preguntascuestionario;
    }

    /**
     * Set the value of preguntascuestionario
     *
     * @return  self
     */ 
    public function setPreguntascuestionario($preguntascuestionario)
    {
        $this->preguntascuestionario = $preguntascuestionario;

        return $this;
    }

    /**
     * Get the value of respuesta
     */ 
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set the value of respuesta
     *
     * @return  self
     */ 
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }
}
    

?>