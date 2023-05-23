<?php


class Ordenestrabajos {

    const TABLA = 'dbordenestrabajos';
    const CAMPOS = 'refcamiones,reftareas,refestados,fechainicio,fechafin,fecharealfinalizacion,indice,usuariocrea,observacion,archivo,type';
    const CAMPOSVAR = ':refcamiones,:reftareas,:refestados,:fechainicio,:fechafin,:fecharealfinalizacion,:indice,:usuariocrea,:observacion,:archivo,:type';
    const RUTA = 'ordenestrabajos';

    private $id;
    private $refcamiones;
    private $reftareas;
    private $refestados;
    private $fechainicio;
    private $fechafin;
    private $fecharealfinalizacion;
    private $usuariocrea;
    private $observacion;
    private $archivo;
    private $type;

    private $indice;

    private $error;
    private $descripcionError;

    private $camiones;
    private $tareas;
    private $estados;
    private $tipo;

    public function __construct($refcamiones,$tipo=0)
    {
        $this->camiones = new Camiones();
        $this->tareas = new Tareas();
        $this->estados = new Estados();
        $this->setRefcamiones($refcamiones);
        $this->tipo = $tipo;

        if ($refcamiones>0) {
            $this->camiones->buscarPorId($refcamiones);
        }
    }

    public static function borrarArchivo($direccion) {
        if (unlink($direccion)) {
            return true;
        } else {
            return false;
        }
    }

    public function storeImage( $photo, $carpeta='ordenestrabajo') {
        $target_dir = "../../data/".$carpeta.'/'.$this->id.'/';

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777);
        }
        $extarr     = explode('.', $photo['name']);
        $filename   = $extarr[sizeof($extarr)-2];
        $ext        = $extarr[sizeof($extarr)-1];
        $hash       = md5(date('Ymdgi').$filename).'.'.$ext;
        $target_file= $target_dir.$hash;
        $uploadOk   = 1;
        //$check      = getimagesize($photo['tmp_name']);

        //if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            //$uploadOk = 1;
        //} else {
            //echo "File is not an image.";
            //$uploadOk = 0;
        //}

        if ($uploadOk == 0) {
            //echo "Sorry, your file was not uploaded.";
            //$this->redirect('user', ['error' => Errors::ERROR_USER_UPDATEPHOTO_FORMAT]);
        // if everything is ok, try to upload file
            return '';
        } else {
            if (move_uploaded_file($photo["tmp_name"], $target_file)) {
                $this->setArchivo($hash);
                $this->setType($ext);
                $this->modificarFilter(array('archivo'=>$this->getArchivo(),'type'=>$this->getType()));
                
                return $this->getError();
            } else {
                return "";
            }
        }
    }

    public function rptDashboard($tipo) {
        $db = new Database();

        if ($tipo == 1) {
            $sql = "select coalesce(cantidad,0) as cantiad,m.mes 
            from tbmeses m
                left join (
                select count(t.id) as cantidad, month(t.fechainicio) as mes
                from dbordenestrabajos t 
                inner join tbtareas ta on ta.id = t.reftareas and ta.esmantenimiento = '1'
                group by month(t.fechainicio)
                ) r on r.mes = m.id
                order by m.id";
        } else {
            $sql = "select coalesce(cantidad,0) as cantiad,m.mes 
            from tbmeses m
                left join (
                select count(t.id) as cantidad, month(t.fechainicio) as mes
                from dbordenestrabajos t 
                inner join tbtareas ta on ta.id = t.reftareas and ta.esreparacion = '1'
                group by month(t.fechainicio)
                ) r on r.mes = m.id
                order by m.id";
        }

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
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

    public function traerPendientes() {
        $db = new Database();

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." where fechafin is not null and now() > fechafin order by 1 ";

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
  
        $sql = "select id from ".self::TABLA." where ".$set." ";

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
                'refcamiones'      => $this->refcamiones,
                'reftareas'      => $this->reftareas,
                'refestados'      => $this->refestados,
                'fechainicio'      => $this->fechainicio,
                'fechafin'      => $this->fechafin,
                'fecharealfinalizacion'      => $this->fecharealfinalizacion,
                'indice'        => $this->indice,
                'usuariocrea'   => $this->usuariocrea,
                'observacion'   => $this->observacion,
                'archivo'   => $this->archivo,
                'type'   => $this->type
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
            
           $this->cargar($res['refcamiones'],$res['reftareas'],$res['refestados'],$res['fechainicio'],$res['fechafin'],$res['fecharealfinalizacion'],$res['usuariocrea'],$res['observacion'],$res['archivo'],$res['type']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        $this->getTareas()->buscarPorId($this->reftareas);
        return array(
            'refcamiones'      => $this->refcamiones,
            'reftareas'      => $this->reftareas,
            'refestados'      => $this->refestados,
            'fechainicio'      => $this->fechainicio,
            'fechafin'      => $this->fechafin,
            'fecharealfinalizacion'      => $this->fecharealfinalizacion,
            'usuariocrea'   => $this->usuariocrea,
            'observacion'   => $this->observacion,
            'archivo'   => $this->archivo,
            'type'   => $this->type,
            'archivourl' => $this->getArchivoUrl(),
            'tarea' => $this->getTareas()->getTarea(),
            'esreparacion' => $this->getTareas()->getEsreparacion()
        );
    }

    public function cargar($refcamiones,$reftareas,$refestados,$fechainicio,$fechafin,$fecharealfinalizacion,$usuariocrea,$observacion,$archivo,$type) {

        $this->setRefcamiones($refcamiones);
        $this->setReftareas($reftareas);
        $this->setRefestados($refestados);
        $this->setFechainicio($fechainicio);
        $this->setFechafin($fechafin);
        $this->setFecharealfinalizacion($fecharealfinalizacion);
        $this->setUsuariocrea($usuariocrea);
        $this->setObservacion($observacion);
        $this->setArchivo($archivo);
        $this->setType($type);
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

    public function traerAjax($length, $start, $busqueda,$colSort,$colSortDir,$sinhistorico='') {
        $where = '';

        $db = new Database();

       
        $busqueda = str_replace("'","",strtoupper($busqueda));
        if ($busqueda != '') {
            $where = " where (upper(c.patente) like '%".$busqueda."%' )";
        }

        $cadTareas = '';
        if ($this->tipo == 2) {
            $cadTareas = " and (tr.esreparacion = '1' or tr.esmantenimiento = '1') ";
        } else {
            if ($this->tipo == 1) {
                $cadTareas = " and tr.esmantenimiento = '1' ";
            } else {
                $cadTareas = " and tr.esreparacion = '1' ";
            }
        }
        

        $cadestados = '';
        if ($sinhistorico != '') {
            $cadestados = ' and e.id not in (6,5) ';
        }
       
        
        if ($this->refcamiones > 0) {
            $sql = "select
            t.id,
            tr.tarea,
            concat(a.activo, ' ', m.marca, ' ', c.modelo, ' ', c.anio, ' ', patente) as activo,
            e.estado,
            t.fechainicio,
            t.fechafin,
            t.fecharealfinalizacion,
            (concat('../../data/ordenestrabajo/',t.id,'/',t.archivo)) as archivo
            from ".self::TABLA." t 
            inner join dbcamiones c on c.id = t.refcamiones and c.id = ".$this->refcamiones."
            inner join tbmarcas m on m.id = c.refmarcas
            inner join tbactivos a on a.id = c.refactivos
            inner join tbtareas tr on tr.id = t.reftareas ".$cadTareas."
            inner join tbestados e on e.id = t.refestados ".$cadestados."
            ".$where."
            ORDER BY ".$colSort." ".$colSortDir." ";
            $limit = "limit ".$start.",".$length;
        } else {
            $sql = "select
            t.id,
            tr.tarea,
            concat(a.activo, ' ', m.marca, ' ', c.modelo, ' ', c.anio, ' ', patente) as activo,
            e.estado,
            t.fechainicio,
            t.fechafin,
            t.fecharealfinalizacion,
            (concat('../../data/ordenestrabajo/',t.id,'/',t.archivo)) as archivo
            from ".self::TABLA." t 
            inner join dbcamiones c on c.id = t.refcamiones
            inner join tbmarcas m on m.id = c.refmarcas
            inner join tbactivos a on a.id = c.refactivos
            inner join tbtareas tr on tr.id = t.reftareas ".$cadTareas."
            inner join tbestados e on e.id = t.refestados ".$cadestados."
            ".$where."
            ORDER BY ".$colSort." ".$colSortDir." ";
            $limit = "limit ".$start.",".$length;
        }
        
        //die(var_dump($sql));
        //$sql = "select id,tema,urlvideo from tbtemas";
            //tp.meses
        //die(var_dump($this->refcamiones));
            //having (case when max(v.version) > 1 then 13 else COUNT(pvd.idperiodicidadventadetalle) end) >= 1
        $queryLimit = $db->connect()->prepare($sql.$limit);

        $queryLimit->execute();

        $dataLimit = $queryLimit->fetchAll(PDO::FETCH_NUM);

        $query = $db->connect()->prepare($sql);

        $query->execute();
            
        $data = $query;

        

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
     * Get the value of fechainicio
     */ 
    public function getFechainicio()
    {
        return $this->fechainicio;
    }

    /**
     * Set the value of fechainicio
     *
     * @return  self
     */ 
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    /**
     * Get the value of fechafin
     */ 
    public function getFechafin()
    {
        return $this->fechafin;
    }

    /**
     * Set the value of fechafin
     *
     * @return  self
     */ 
    public function setFechafin($fechafin)
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    /**
     * Get the value of fecharealfinalizacion
     */ 
    public function getFecharealfinalizacion()
    {
        return $this->fecharealfinalizacion;
    }

    /**
     * Set the value of fecharealfinalizacion
     *
     * @return  self
     */ 
    public function setFecharealfinalizacion($fecharealfinalizacion)
    {
        $this->fecharealfinalizacion = $fecharealfinalizacion;

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
     * Get the value of tipo
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of indice
     */ 
    public function getIndice()
    {
        return $this->indice;
    }

    /**
     * Set the value of indice
     *
     * @return  self
     */ 
    public function setIndice($indice)
    {
        $this->indice = $indice;

        return $this;
    }

    /**
     * Get the value of usuariocrea
     */ 
    public function getUsuariocrea()
    {
        return $this->usuariocrea;
    }

    /**
     * Set the value of usuariocrea
     *
     * @return  self
     */ 
    public function setUsuariocrea($usuariocrea)
    {
        $this->usuariocrea = $usuariocrea;

        return $this;
    }

    /**
     * Get the value of observacion
     */ 
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set the value of observacion
     *
     * @return  self
     */ 
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get the value of archivo
     */ 
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Get the value of archivo
     */ 
    public function getArchivoUrl()
    {
        return 'data/ordenestrabajo/'.$this->getId().'/'.$this->archivo;
    }

    /**
     * Set the value of archivo
     *
     * @return  self
     */ 
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
    

?>