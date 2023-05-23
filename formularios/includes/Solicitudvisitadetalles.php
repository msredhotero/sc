<?php


class Solicitudvisitadetalles {

    const TABLA = 'dbsolicitudvisitadetalles';
    const CAMPOS = 'refsolicitudesvisitas,reftareas,refestados,fechamodi,usuariomodi,observaciones';
    const CAMPOSVAR = ':refsolicitudesvisitas,:reftareas,:refestados,:fechamodi,:usuariomodi,:observaciones';
    const RUTA = 'solicitudvisitadetalles';

    private $id;
    private $refsolicitudesvisitas;
    private $reftareas;
    private $fechamodi;
    private $usuariomodi;
    private $observaciones;
    private $refestados;

    private $solicitudesvisitas;
    private $estados;
    private $tareas;

    private $error;
    private $descripcionError;


    public function __construct($usuariomodi,$refsolicitudesvisitas)
    {
        $this->usuariomodi = $usuariomodi;
        $this->solicitudesvisitas = new Solicitudesvisitas($usuariomodi);
        $this->estados = new Estados();
        $this->tareas = new Tareas();
        
        if ($refsolicitudesvisitas > 0) {
            $this->getSolicitudesvisitas()->buscarPorId($refsolicitudesvisitas);
        }
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

    public function traerTodosPorCabecera() {
        $db = new Database();

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." where refsolicitudesvisitas = :refsolicitudesvisitas order by 1 ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute(['refsolicitudesvisitas' => $this->refsolicitudesvisitas]);

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
                'refsolicitudesvisitas' => $this->refsolicitudesvisitas,
                'reftareas'             => $this->reftareas,
                'refestados'            => $this->refestados,
                'fechamodi'             => $this->fechamodi,
                'usuariomodi'           => $this->usuariomodi,
                'observaciones'         => $this->observaciones
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
            
           $this->cargar($res['refsolicitudesvisitas'],$res['reftareas'],$res['refestados'],$res['fechamodi'],$res['usuariomodi'],$res['observaciones']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        $this->getTareas()->buscarPorId($this->reftareas);
        $this->getEstados()->buscarPorId($this->refestados);

        return array(
            'refsolicitudesvisitas' => $this->refsolicitudesvisitas,
            'reftareas'             => $this->reftareas,
            'refestados'            => $this->refestados,
            'fechamodi'             => $this->fechamodi,
            'usuariomodi'           => $this->usuariomodi,
            'observaciones'         => $this->observaciones,
            'tarea'                 => $this->getTareas()->getTarea(),
            'estado'                => $this->getEstados()->getEstado()
        );
    }

    public function cargar($refsolicitudesvisitas, $reftareas, $refestados, $fechamodi, $usuariomodi, $observaciones) {

        $this->setRefsolicitudesvisitas($refsolicitudesvisitas);
        $this->setReftareas($reftareas);
        $this->setRefestados($refestados);
        $this->setFechamodi($fechamodi);
        $this->setUsuariomodi($usuariomodi);
        $this->setObservaciones($observaciones);
        
        
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
            $where = " and (tt.tarea like '%".$busqueda."%' or e.estado like '%".$busqueda."%' )";
        }

        $sql = "select
            t.id,
            tt.tarea,
            t.fechamodi,
            e.estado
        from ".self::TABLA." t
        inner join tbtareas tt on tt.id = t.reftareas
        inner join tbestados e on e.id = t.refestados
        where t.refsolicitudesvisitas = ".$this->refsolicitudesvisitas." ".$where."
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
     * Get the value of estados
     */ 
    public function getEstados()
    {
        return $this->estados;
    }

    /**
     * Set the value of estados
     *
     * @return  self
     */ 
    public function setEstados($estados)
    {
        $this->estados = $estados;

        return $this;
    }


    /**
     * Get the value of refsolicitudesvisitas
     */ 
    public function getRefsolicitudesvisitas()
    {
        return $this->refsolicitudesvisitas;
    }

    /**
     * Set the value of refsolicitudesvisitas
     *
     * @return  self
     */ 
    public function setRefsolicitudesvisitas($refsolicitudesvisitas)
    {
        $this->refsolicitudesvisitas = $refsolicitudesvisitas;

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
     * Get the value of fechamodi
     */ 
    public function getFechamodi()
    {
        return $this->fechamodi;
    }

    /**
     * Set the value of fechamodi
     *
     * @return  self
     */ 
    public function setFechamodi($fechamodi)
    {
        $this->fechamodi = $fechamodi;

        return $this;
    }

    /**
     * Get the value of usuariomodi
     */ 
    public function getUsuariomodi()
    {
        return $this->usuariomodi;
    }

    /**
     * Set the value of usuariomodi
     *
     * @return  self
     */ 
    public function setUsuariomodi($usuariomodi)
    {
        $this->usuariomodi = $usuariomodi;

        return $this;
    }

    /**
     * Get the value of observaciones
     */ 
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set the value of observaciones
     *
     * @return  self
     */ 
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get the value of solicitudesvisitas
     */ 
    public function getSolicitudesvisitas()
    {
        return $this->solicitudesvisitas;
    }

    /**
     * Set the value of solicitudesvisitas
     *
     * @return  self
     */ 
    public function setSolicitudesvisitas($solicitudesvisitas)
    {
        $this->solicitudesvisitas = $solicitudesvisitas;

        return $this;
    }

    /**
     * Get the value of refestados
     */ 
    public function getRefestados()
    {
        return $this->refestados;
    }

    /**
     * Set the value of refestados
     *
     * @return  self
     */ 
    public function setRefestados($refestados)
    {
        $this->refestados = $refestados;

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