<?php


class Preguntascuestionario {

    const TABLA = 'dbpreguntascuestionario';
    const CAMPOS = 'refformularios,reftiporespuesta,pregunta,orden,valor,tiempo,activo,obligatoria,depende,dependerespuesta,leyenda,reftabladatos,columna';
    const CAMPOSVAR = ':refformularios,:reftiporespuesta,:pregunta,:orden,:valor,:tiempo,:activo,:obligatoria,:depende,:dependerespuesta,:leyenda,:reftabladatos,:columna';
    const RUTA = 'preguntascuestionario';

    private $id;
    private $refformularios;
    private $reftiporespuesta;
    private $pregunta;
    private $orden;
    private $valor;
    private $tiempo;
    private $obligatoria;
    private $depende;
    private $dependerespuesta;
    private $leyenda;
    private $activo;
    private $reftabladatos;
    private $columna;

    private $formularios;
    private $tiporespuesta;

    private $error;
    private $descripcionError;


    public function __construct()
    {
        $this->formularios = new Formularios();
        $this->tiporespuesta = new Tiporespuestas();
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

        if (isset($options['refformularios'])) {
            $where .= 'and refformularios = ('.$options['refformularios'].') ';
        }

        if (isset($options['reftiporespuesta'])) {
            $where .= 'and reftiporespuesta = ('.$options['reftiporespuesta'].') ';
        }

        if (isset($options['obligatoria'])) {
            $where .= " and obligatoria = '".$options['obligatoria']."'";
        }


        $sql = "select 
            t.id,t.pregunta,t.obligatoria,t.leyenda,t.reftiporespuesta , tr.tiporespuesta, t.reftabladatos, t.columna
        from dbpreguntascuestionario t
        inner join tbtiporespuesta tr on tr.id = t.reftiporespuesta
        where t.activo='1' ".$where." order by t.orden";

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
                'refformularios' => $this->refformularios,
                'reftiporespuesta' => $this->reftiporespuesta,
                'pregunta' => $this->pregunta,
                'orden' => $this->orden,
                'valor' => $this->valor,
                'tiempo' => $this->tiempo,
                'activo' => $this->activo,
                'obligatoria' => $this->obligatoria,
                'depende' => $this->depende,
                'dependerespuesta' => $this->dependerespuesta,
                'leyenda'         => $this->leyenda,
                'reftabladatos' => $this->reftabladatos,
                'columna' => $this->columna
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
            
            $this->cargar($res['refformularios'],$res['reftiporespuesta'],$res['pregunta'],$res['orden'],$res['valor'],$res['activo'],$res['tiempo'],$res['obligatoria'],$res['depende'],$res['dependerespuesta'],$res['leyenda'],$res["reftabladatos"],$res["columna"]);
            $this->setId($id);
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'refformularios' => $this->refformularios,
            'reftiporespuesta' => $this->reftiporespuesta,
            'pregunta' => $this->pregunta,
            'orden' => $this->orden,
            'valor' => $this->valor,
            'activo' => $this->activo,
            'tiempo' => $this->tiempo,
            'obligatoria' => $this->obligatoria,
            'depende' => $this->depende,
            'dependerespuesta' => $this->dependerespuesta,
            'leyenda'         => $this->leyenda,
            'reftabladatos' => $this->reftabladatos,
            'columna' => $this->columna
        );
    }

    public function cargar($refformularios, $reftiporespuesta, $pregunta, $orden, $valor, $activo, $tiempo, $obligatoria, $depende, $dependerespuesta, $leyenda,$reftabladatos,$columna) {

        $this->setRefformularios($refformularios);
        $this->setReftiporespuesta($reftiporespuesta);
        $this->setPregunta($pregunta);
        $this->setOrden($orden);
        $this->setValor($valor);
        $this->setActivo($activo);
        $this->setTiempo($tiempo);
        $this->setObligatoria($obligatoria);
        $this->setDepende($depende);
        $this->setDependerespuesta($dependerespuesta);
        $this->setLeyenda($leyenda);
        $this->setReftabladatos($reftabladatos);
        $this->setColumna($columna);
        
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
            $where = " where (tr.tiporespuesta like '%".$busqueda."%' or t.pregunta like '%".$busqueda."%' )";
        }

        $cadWhere = '';
        if ($this->refformularios > 0) {
            $cadWhere = ' and f.id = '.$this->refformularios;
        } 
       
        $sql = "select
        t.id,
        f.formulario,
        tr.tiporespuesta,
        t.pregunta,
        t.orden,
        (case when t.obligatoria = '1' then 'Si' else 'No' end) as obligatoria,
        (case when t.activo = '1' then 'Si' else 'No' end) as activo
        from ".self::TABLA." t
        inner join tbformularios f on f.id = t.refformularios ".$cadWhere."
        inner join tbtiporespuesta tr on tr.id = t.reftiporespuesta
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
     * Get the value of refformularios
     */ 
    public function getRefformularios()
    {
        return $this->refformularios;
    }

    /**
     * Set the value of refformularios
     *
     * @return  self
     */ 
    public function setRefformularios($refformularios)
    {
        $this->refformularios = $refformularios;

        return $this;
    }

    /**
     * Get the value of reftiporespuesta
     */ 
    public function getReftiporespuesta()
    {
        return $this->reftiporespuesta;
    }

    /**
     * Set the value of reftiporespuesta
     *
     * @return  self
     */ 
    public function setReftiporespuesta($reftiporespuesta)
    {
        $this->reftiporespuesta = $reftiporespuesta;

        return $this;
    }

    /**
     * Get the value of pregunta
     */ 
    public function getPregunta()
    {
        return $this->pregunta;
    }

    /**
     * Set the value of pregunta
     *
     * @return  self
     */ 
    public function setPregunta($pregunta)
    {
        $this->pregunta = $pregunta;

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
     * Get the value of valor
     */ 
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @return  self
     */ 
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get the value of tiempo
     */ 
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set the value of tiempo
     *
     * @return  self
     */ 
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    /**
     * Get the value of obligatoria
     */ 
    public function getObligatoria()
    {
        return $this->obligatoria;
    }

    /**
     * Set the value of obligatoria
     *
     * @return  self
     */ 
    public function setObligatoria($obligatoria)
    {
        $this->obligatoria = $obligatoria;

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

    /**
     * Get the value of dependerespuesta
     */ 
    public function getDependerespuesta()
    {
        return $this->dependerespuesta;
    }

    /**
     * Set the value of dependerespuesta
     *
     * @return  self
     */ 
    public function setDependerespuesta($dependerespuesta)
    {
        $this->dependerespuesta = $dependerespuesta;

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
     * Get the value of formularios
     */ 
    public function getFormularios()
    {
        return $this->formularios;
    }

    /**
     * Set the value of formularios
     *
     * @return  self
     */ 
    public function setFormularios($formularios)
    {
        $this->formularios = $formularios;

        return $this;
    }

    /**
     * Get the value of tiporespuesta
     */ 
    public function getTiporespuesta()
    {
        return $this->tiporespuesta;
    }

    /**
     * Set the value of tiporespuesta
     *
     * @return  self
     */ 
    public function setTiporespuesta($tiporespuesta)
    {
        $this->tiporespuesta = $tiporespuesta;

        return $this;
    }

    /**
     * Get the value of reftabladatos
     */ 
    public function getReftabladatos()
    {
        return $this->reftabladatos;
    }

    /**
     * Set the value of reftabladatos
     *
     * @return  self
     */ 
    public function setReftabladatos($reftabladatos)
    {
        $this->reftabladatos = $reftabladatos;

        return $this;
    }

    /**
     * Get the value of columna
     */ 
    public function getColumna()
    {
        return $this->columna;
    }

    /**
     * Set the value of columna
     *
     * @return  self
     */ 
    public function setColumna($columna)
    {
        $this->columna = $columna;

        return $this;
    }
}
    

?>