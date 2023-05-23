<?php


class Solicitudesvisitas {

    const TABLA = 'dbsolicitudesvisitas';
    const CAMPOS = 'refclientes,refsucursales,fecha,fechacrea,usuariocrea,refsemaforo,descripcion,refestados,reftipoactividades,refzonas,nroaviso,claseaviso,autoraviso';
    const CAMPOSVAR = ':refclientes,:refsucursales,:fecha,:fechacrea,:usuariocrea,:refsemaforo,:descripcion,:refestados,:reftipoactividades,:refzonas,:nroaviso,:claseaviso,:autoraviso';
    const RUTA = 'solicitudesvisitas';

    private $id;
    private $refclientes;
    private $refsucursales;
    private $fecha;
    private $fechacrea;
    private $usuariocrea;
    private $refsemaforo;
    private $descripcion;
    private $refestados;
    private $reftipoactividades;
    private $refzonas;
    private $nroaviso;
    private $claseaviso;
    private $autoraviso;

    private $clientes;
    private $sucursales;
    private $estados;
    private $semaforo;
    private $tipoactividades;
    private $zonas;

    private $error;
    private $descripcionError;


    public function __construct($usuariocrea)
    {
        $this->usuariocrea = $usuariocrea;
        $this->clientes = new Clientes();
        $this->sucursales = new Sucursales(1,0);
        $this->estados = new Estados();
        $this->semaforo = new Semaforo();
        $this->tipoactividades = new Tipoactividades();
        $this->zonas = new Zonas();
        
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

    public function traerTodosFilter($arCampos,$option='') {
        $db = new Database();

        $cadSet = '';
        $cadOption = '';

        foreach ($arCampos as $clave => $valor) {
        // $array[3] se actualizará con cada valor de $array...
           $cadSet .= "{$clave} = :{$clave} and ";
        }

        if ($option != '') {
            if (isset($option['refestados'])) {
                
                $cadOption = " refestados ".$option['contenido']." (".$option['refestados'].")";
            }
        }


        $set = substr($cadSet,0,-4);
  
        $sql = "select id from ".self::TABLA." where ".$set.$cadOption." ";

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

    public function traerSolicitudesSinOT() {
        $db = new Database();

        $sql = "SELECT 
            sv.id,
            c.nombre as cliente,
            ta.actividad,
            su.sucursal,
            sv.fecha,
            se.nivel,
            z.zona
        FROM ".self::TABLA." sv 
        inner join dbclientes c on sv.refclientes = c.id
        left join dbsucursales su on su.idreferencia = sv.refclientes and su.reftabla = 1 and su.id = sv.refsucursales
        inner join tbsemaforo se on se.id = sv.refsemaforo
        inner join tbtipoactividades ta on ta.id = sv.reftipoactividades
        left join dbordenestrabajocabecera ot on ot.refsolicitudesvisitas = sv.id
        left join tbzonas z on z.id = sv.refzonas
        where sv.refestados = 1 and ot.id is null
        order by sv.fecha ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function traerSolicitudesMapa() {
        $db = new Database();

        $sql = "SELECT 
            sv.id,
            c.nombre as cliente,
            ta.actividad,
            su.sucursal,
            sv.fecha,
            se.nivel,
            (case when sv.refsucursales = 0 then c.latitud else su.latitud end) as latitud,
            (case when sv.refsucursales = 0 then c.longitud else su.longitud end) as longitud,
            (case when se.id = 1 then 'baja'
            when se.id = 2 then 'media'
            when se.id = 3 then 'alta'
            end) as colormapa
        FROM ".self::TABLA." sv 
        inner join dbclientes c on sv.refclientes = c.id
        left join dbsucursales su on su.idreferencia = sv.refclientes and su.reftabla = 1 and su.id = sv.refsucursales
        inner join tbsemaforo se on se.id = sv.refsemaforo
        inner join tbtipoactividades ta on ta.id = sv.reftipoactividades
        left join dbordenestrabajocabecera ot on ot.refsolicitudesvisitas = sv.id
        where sv.refestados not in (3,4,6) and ot.id is null ";

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
                'refclientes'           => $this->refclientes,
                'refsucursales'         => $this->refsucursales,
                'fecha'                 => $this->fecha,
                'fechacrea'             => $this->fechacrea,
                'usuariocrea'           => $this->usuariocrea,
                'refsemaforo'           => $this->refsemaforo,
                'descripcion'           => $this->descripcion,
                'refestados'            => $this->refestados,
                'reftipoactividades'    => $this->reftipoactividades,
                'refzonas'              => $this->refzonas,
                'nroaviso'              => $this->nroaviso,
                'claseaviso'            => $this->claseaviso,
                'autoraviso'            => $this->autoraviso
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
            
           $this->cargar($res['refclientes'],$res['refsucursales'],$res['fecha'],$res['fechacrea'],$res['usuariocrea'],$res['refsemaforo'],$res['descripcion'],$res['refestados'],$res['reftipoactividades'],$res['refzonas'],$res['nroaviso'],$res['claseaviso'],$res['autoraviso']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        $this->getEstados()->buscarPorId($this->refestados);
        $this->getSemaforo()->buscarPorId($this->refsemaforo);
        $this->getClientes()->buscarPorId($this->refclientes);
        $this->getSucursales()->buscarPorId($this->refsucursales);
        $this->getTipoactividades()->buscarPorId($this->reftipoactividades);
        $this->getZonas()->buscarPorId($this->refzonas);
        return array(
            'refclientes'           => $this->refclientes,
            'refsucursales'         => $this->refsucursales,
            'fecha'                 => $this->fecha,
            'fechacrea'             => $this->fechacrea,
            'usuariocrea'           => $this->usuariocrea,
            'refsemaforo'           => $this->refsemaforo,
            'descripcion'           => $this->descripcion,
            'refestados'            => $this->refestados,
            'reftipoactividades'    => $this->reftipoactividades,
            'refzonas'              => $this->refzonas,
            'nroaviso'              => $this->nroaviso,
            'claseaviso'            => $this->claseaviso,
            'autoraviso'            => $this->autoraviso,
            'estado'                => $this->getEstados()->getEstado(),
            'cliente'               => $this->getClientes()->getNombre(),
            'sucursal'              => $this->getSucursales()->getSucursal(),
            'prioridad'             => $this->getSemaforo()->getNivel(),
            'actividad'             => $this->getTipoactividades()->getActividad(),
            'zona'                  => $this->getZonas()->getZona()
        );
    }

    public function cargar($refclientes, $refsucursales, $fecha, $fechacrea, $usuariocrea, $refsemaforo, $descripcion, $refestados, $reftipoactividades,$refzonas,$nroaviso,$claseaviso,$autoraviso) {

        $this->setRefclientes($refclientes);
        $this->setRefsucursales($refsucursales);
        $this->setFecha($fecha);
        $this->setFechacrea($fechacrea);
        $this->setUsuariocrea($usuariocrea);
        $this->setRefsemaforo($refsemaforo);
        $this->setDescripcion($descripcion);
        $this->setRefestados($refestados);
        $this->setReftipoactividades($reftipoactividades);
        $this->setRefzonas($refzonas);
        $this->setNroaviso($nroaviso);
        $this->setClaseaviso($claseaviso);
        $this->setAutoraviso($autoraviso);
        
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

    public function traerAjax($length, $start, $busqueda,$colSort,$colSortDir,$min,$max,$prioridad) {
        $where = '';

        $db = new Database();

        $cadFecha = '';
		if ($min != '' && $max != '') {
			$cadFecha = " and t.fecha between '".$min."' and '".$max."' ";
		} else {
			if ($min != '' && $max == '') {
				$cadFecha = " and t.fecha >= '".$min."' ";
			} else {
				if ($min == '' && $max != '') {
					$cadFecha = " and t.fecha <= '".$max."' ";
				}
			}
		}

        $cadPrioridad = '';
		if ($prioridad > 0) {
			$cadPrioridad = " and t.refsemaforo =".$prioridad.' ';
		}


        $busqueda = str_replace("'","",$busqueda);
        if ($busqueda != '') {
            $where = " and (c.nombre like '%".$busqueda."%' or su.sucursal like '%".$busqueda."%' or se.nivel like '%".$busqueda."%' or e.estado like '%".$busqueda."%' or z.zona like '%".$busqueda."%' )";
        }

        $sql = "select
            t.id,
            c.nombre as cliente,
            su.sucursal,
            ta.actividad,
            t.nroaviso,
            t.fecha,
            se.nivel,
            e.estado,
            z.zona
        from ".self::TABLA." t
        inner join dbclientes c on c.id = t.refclientes
        left join dbsucursales su on su.idreferencia = t.refclientes and su.reftabla = 1 and su.id = t.refsucursales
        inner join tbsemaforo se on se.id = t.refsemaforo
        inner join tbestados e on e.id = t.refestados
        inner join tbtipoactividades ta on ta.id = t.reftipoactividades
        left join tbzonas z on z.id = t.refzonas
        where ta.activo = '1' ".$where.$cadFecha.$cadPrioridad."
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
     * Get the value of refclientes
     */ 
    public function getRefclientes()
    {
        return $this->refclientes;
    }

    /**
     * Set the value of refclientes
     *
     * @return  self
     */ 
    public function setRefclientes($refclientes)
    {
        $this->refclientes = $refclientes;

        return $this;
    }

    /**
     * Get the value of refsucursales
     */ 
    public function getRefsucursales()
    {
        return $this->refsucursales;
    }

    /**
     * Set the value of refsucursales
     *
     * @return  self
     */ 
    public function setRefsucursales($refsucursales)
    {
        $this->refsucursales = $refsucursales;

        return $this;
    }

    /**
     * Get the value of fecha
     */ 
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of fechacrea
     */ 
    public function getFechacrea()
    {
        return $this->fechacrea;
    }

    /**
     * Set the value of fechacrea
     *
     * @return  self
     */ 
    public function setFechacrea($fechacrea)
    {
        $this->fechacrea = $fechacrea;

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
     * Get the value of refsemaforo
     */ 
    public function getRefsemaforo()
    {
        return $this->refsemaforo;
    }

    /**
     * Set the value of refsemaforo
     *
     * @return  self
     */ 
    public function setRefsemaforo($refsemaforo)
    {
        $this->refsemaforo = $refsemaforo;

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
     * Get the value of clientes
     */ 
    public function getClientes()
    {
        return $this->clientes;
    }

    /**
     * Set the value of clientes
     *
     * @return  self
     */ 
    public function setClientes($clientes)
    {
        $this->clientes = $clientes;

        return $this;
    }

    /**
     * Get the value of sucursales
     */ 
    public function getSucursales()
    {
        return $this->sucursales;
    }

    /**
     * Set the value of sucursales
     *
     * @return  self
     */ 
    public function setSucursales($sucursales)
    {
        $this->sucursales = $sucursales;

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
     * Get the value of semaforo
     */ 
    public function getSemaforo()
    {
        return $this->semaforo;
    }

    /**
     * Set the value of semaforo
     *
     * @return  self
     */ 
    public function setSemaforo($semaforo)
    {
        $this->semaforo = $semaforo;

        return $this;
    }

    /**
     * Get the value of reftipoactividades
     */ 
    public function getReftipoactividades()
    {
        return $this->reftipoactividades;
    }

    /**
     * Set the value of reftipoactividades
     *
     * @return  self
     */ 
    public function setReftipoactividades($reftipoactividades)
    {
        $this->reftipoactividades = $reftipoactividades;

        return $this;
    }

    /**
     * Get the value of tipoactividades
     */ 
    public function getTipoactividades()
    {
        return $this->tipoactividades;
    }

    /**
     * Set the value of tipoactividades
     *
     * @return  self
     */ 
    public function setTipoactividades($tipoactividades)
    {
        $this->tipoactividades = $tipoactividades;

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

    /**
     * Get the value of zonas
     */ 
    public function getZonas()
    {
        return $this->zonas;
    }

    /**
     * Set the value of zonas
     *
     * @return  self
     */ 
    public function setZonas($zonas)
    {
        $this->zonas = $zonas;

        return $this;
    }

    /**
     * Get the value of nroaviso
     */ 
    public function getNroaviso()
    {
        return $this->nroaviso;
    }

    /**
     * Set the value of nroaviso
     *
     * @return  self
     */ 
    public function setNroaviso($nroaviso)
    {
        $this->nroaviso = $nroaviso;

        return $this;
    }

    /**
     * Get the value of claseaviso
     */ 
    public function getClaseaviso()
    {
        return $this->claseaviso;
    }

    /**
     * Set the value of claseaviso
     *
     * @return  self
     */ 
    public function setClaseaviso($claseaviso)
    {
        $this->claseaviso = $claseaviso;

        return $this;
    }

    /**
     * Get the value of autoraviso
     */ 
    public function getAutoraviso()
    {
        return $this->autoraviso;
    }

    /**
     * Set the value of autoraviso
     *
     * @return  self
     */ 
    public function setAutoraviso($autoraviso)
    {
        $this->autoraviso = $autoraviso;

        return $this;
    }
}
    

?>