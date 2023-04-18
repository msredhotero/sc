<?php


class Tareas {

    const TABLA = 'tbtareas';
    const CAMPOS = 'tarea,activo,esreparacion,esmantenimiento,verificakilometros,verificavencimientos';
    const CAMPOSVAR = ':tarea,:activo,:esreparacion,:esmantenimiento,:verificakilometros,:verificavencimientos';
    const RUTA = 'tareas';

    private $id;
    private $tarea;
    private $activo;
    private $esreparacion;
    private $esmantenimiento;
    private $verificakilometros;
    private $verificavencimientos;

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

    public function traerTodosFilter($arCampos) {
        $db = new Database();

        $cadSet = '';
  
        foreach ($arCampos as $clave => $valor) {
        // $array[3] se actualizará con cada valor de $array...
           $cadSet .= "{$clave} = :{$clave} and ";
        }


        $set = substr($cadSet,0,-4);
  
        $sql = "select id,tarea from ".self::TABLA." where ".$set." order by 1 ";

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


    public function traerTodosMenos($refpadre) {
        $db = new Database();

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." where id <> :idtarea order by tarea ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute(['refpadre'=> $refpadre]);

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
        
    }

    public function traerTodosPorPadre($refpadre) {
        $db = new Database();

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." where id = :idtarea order by tarea ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute(['refpadre'=> $refpadre]);

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
                'activo'                    => $this->activo,
                'tarea'                     => $this->tarea,
                'esreparacion'              => $this->esreparacion,
                'esmantenimiento'           => $this->esmantenimiento,
                'verificakilometros'        => $this->verificakilometros,
                'verificavencimientos'      => $this->verificavencimientos
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
            
           $this->cargar($res['tarea'],$res['activo'],$res['esreparacion'],$res['esmantenimiento'],$res['verificakilometros'],$res['verificavencimientos']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'activo'                    => $this->activo,
            'tarea'                     => $this->tarea,
            'esreparacion'              => $this->esreparacion,
            'esmantenimiento'           => $this->esmantenimiento,
            'verificakilometros'        => $this->verificakilometros,
            'verificavencimientos'      => $this->verificavencimientos
        );
    }

    public function cargar($tarea,$activo,$esreparacion,$esmantenimiento,$verificakilometros,$verificavencimientos) {
        $this->setTarea($tarea);
        $this->setActivo($activo);
        $this->setEsreparacion($esreparacion);
        $this->setEsmantenimiento($esmantenimiento);
        $this->setVerificakilometros($verificakilometros);
        $this->setVerificavencimientos($verificavencimientos);
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
            $where = " where (t.tarea like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        t.tarea,
        (case when t.activo = '1' then 'Si' else 'No' end) as activo,
        (case when t.esreparacion = '1' then 'Si' else 'No' end) as esreparacion,
        (case when t.esmantenimiento = '1' then 'Si' else 'No' end) as esmantenimiento,
        (case when t.verificakilometros = '1' then 'Si' else 'No' end) as verificakilometros,
        (case when t.verificavencimientos = '1' then 'Si' else 'No' end) as verificavencimientos
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
     * Get the value of tarea
     */ 
    public function getTarea()
    {
        return $this->tarea;
    }

    /**
     * Set the value of tarea
     *
     * @return  self
     */ 
    public function setTarea($tarea)
    {
        $this->tarea = $tarea;

        return $this;
    }

    /**
     * Get the value of esreparacion
     */ 
    public function getEsreparacion()
    {
        return $this->esreparacion;
    }

    /**
     * Set the value of esreparacion
     *
     * @return  self
     */ 
    public function setEsreparacion($esreparacion)
    {
        $this->esreparacion = $esreparacion;

        return $this;
    }

    /**
     * Get the value of esmantenimiento
     */ 
    public function getEsmantenimiento()
    {
        return $this->esmantenimiento;
    }

    /**
     * Set the value of esmantenimiento
     *
     * @return  self
     */ 
    public function setEsmantenimiento($esmantenimiento)
    {
        $this->esmantenimiento = $esmantenimiento;

        return $this;
    }

    /**
     * Get the value of verificakilometros
     */ 
    public function getVerificakilometros()
    {
        return $this->verificakilometros;
    }

    /**
     * Set the value of verificakilometros
     *
     * @return  self
     */ 
    public function setVerificakilometros($verificakilometros)
    {
        $this->verificakilometros = $verificakilometros;

        return $this;
    }

    /**
     * Get the value of verificavencimientos
     */ 
    public function getVerificavencimientos()
    {
        return $this->verificavencimientos;
    }

    /**
     * Set the value of verificavencimientos
     *
     * @return  self
     */ 
    public function setVerificavencimientos($verificavencimientos)
    {
        $this->verificavencimientos = $verificavencimientos;

        return $this;
    }
}
    

?>