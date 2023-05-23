<?php


class Ordenestrabajocabecera {

    const TABLA = 'dbordenestrabajocabecera';
    const CAMPOS = 'refsolicitudesvisitas,refsemaforo,fecha,refestados,fechafin';
    const CAMPOSVAR = ':refsolicitudesvisitas,:refsemaforo,:fecha,:refestados,:fechafin';
    const RUTA = 'ordenestrabajocabecera';
    const SQLBASE = 'inner join dbsolicitudesvisitas v on v.id = t.refsolicitudesvisitas
    inner join dbclientes c on c.id = v.refclientes
    left join dbsucursales su on su.idreferencia = v.refclientes and su.reftabla = 1 and su.id = v.refsucursales
    inner join tbsemaforo se on se.id = t.refsemaforo
    inner join tbestados e on e.id = t.refestados';

    private $id;
    private $refsolicitudesvisitas;
    private $refsemaforo;
    private $fecha;
    private $fechafin;
    private $refestados;

    private $solicitudesvisitas;
    private $estados;
    private $semaforo;

    private $error;
    private $descripcionError;


    public function __construct()
    {
        $this->solicitudesvisitas = new Solicitudesvisitas((''));
        $this->estados = new Estados();
        $this->semaforo = new Semaforo();
    }

    public static function libre($fecha, $idusuario) {
        $db = new Database();

        $sql = "select
            ot.id
        from		dbordenestrabajocabecera ot
        inner join	dbcuadrillas cu on cu.refordenestrabajocabecera = ot.id and cu.refusuarios = ".$idusuario."
        where		'".$fecha."' between ot.fecha and ot.fechafin";

        //die(var_dump($sql));

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            if (count($resultado)>0) {
                return 1;
            } else {
                return 0;
            }

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function traerTodos() {
        $db = new Database();

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." order by orden ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public static function traerTodosPorUsuario($idusuario) {
        $db = new Database();

        $sql = "SELECT 
                    ot.id, 
                    c.nombre as cliente,
                    (case when sv.refsucursales is null then c.direccion else su.direccion end) as sucursal,
                    (case when sv.refsucursales is null then c.latitud else su.latitud end) as latitud,
                    (case when sv.refsucursales is null then c.longitud else su.longitud end) as longitud,
                    ta.actividad,
                    s.nivel,
                    e.estado,
                    ot.fecha,
                    ot.fechafin,
                    z.zona,
                    ot.refestados,
                    cu.asignado,
                    uu.username,
                    sv.nroaviso,
                    (case when sv.refsucursales is null then '' else su.sucursal end) as nombre_suc
                FROM dbordenestrabajocabecera ot 
                inner join dbsolicitudesvisitas sv on sv.id = ot.refsolicitudesvisitas 
                inner join tbtipoactividades ta on ta.id = sv.reftipoactividades
                inner join dbclientes c on c.id = sv.refclientes
                left join dbsucursales su on su.idreferencia = sv.refclientes and su.reftabla = 1 and su.id = sv.refsucursales
                inner join tbsemaforo s on s.id = ot.refsemaforo and s.id in (1,2,3)
                inner join tbestados e on e.id = ot.refestados and e.id not in (3,4,6)
                left join tbzonas z on z.id = sv.refzonas
                inner join dbcuadrillas cu on cu.refordenestrabajocabecera = ot.id and cu.refusuarios= ".$idusuario."
                left join dbcuadrillas cuu on cuu.refordenestrabajocabecera = ot.id and cuu.asignado='1'
                left join dbusuarios uu on uu.id = cuu.refusuarios
                order by ot.fecha asc ";

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

    public static function traerUsuariosMapa() {
        $db = new Database();

        $sql = "select
        a.apyn,
        uu.latitud,
        uu.longitud,
        uu.fecha
        from	(select
            concat(u.nombre, ' ', u.apellido) as apyn,
            max(uu.id) as id
            from		dbusuarios u 
            inner
            join		dbubicacionesusuarios uu
            on			uu.refusuarios = u.id
            inner join	dbcuadrillas cu
            on			cu.refusuarios = uu.refusuarios
            inner join	dbordenestrabajocabecera ot
            on			ot.id = cu.refordenestrabajocabecera
            group by	u.nombre, u.apellido) a
            inner join  dbubicacionesusuarios uu
            on			uu.id = a.id";

        //die(var_dump($sql));
        
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
            ot.id,
            c.nombre as cliente,
            ta.actividad,
            su.sucursal,
            ot.fecha,
            se.nivel,
            (case when sv.refsucursales = 0 then c.latitud else su.latitud end) as latitud,
            (case when sv.refsucursales = 0 then c.longitud else su.longitud end) as longitud,
            (case when se.id = 1 then 'baja'
            when se.id = 2 then 'media'
            when se.id = 3 then 'alta'
            end) as colormapa
        FROM ".self::TABLA." ot
        inner join dbsolicitudesvisitas sv on ot.refsolicitudesvisitas = sv.id
        inner join dbclientes c on sv.refclientes = c.id
        left join dbsucursales su on su.idreferencia = sv.refclientes and su.reftabla = 1 and su.id = sv.refsucursales
        inner join tbsemaforo se on se.id = ot.refsemaforo
        inner join tbtipoactividades ta on ta.id = sv.reftipoactividades
        where ot.refestados not in (3,4,6) ";

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
            $pdo = $db->connect();
            $query = $pdo->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

            $query->execute([
                'refsolicitudesvisitas'  => $this->refsolicitudesvisitas,
                'fecha'                 => $this->fecha,
                'refsemaforo'           => $this->refsemaforo,
                'refestados'            => $this->refestados,
                'fechafin'              => $this->fechafin
            ]);

            $lastInsertId = $pdo->lastInsertId();

            $this->setId($lastInsertId);

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
            
           $this->cargar($res['refsolicitudesvisitas'],$res['fecha'],$res['refsemaforo'],$res['refestados'],$res['fechafin']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        $this->getEstados()->buscarPorId($this->refestados);
        $this->getSemaforo()->buscarPorId($this->refsemaforo);

        return array(
            'refsolicitudesvisitas' => $this->refsolicitudesvisitas,
            'fecha'                 => $this->fecha,
            'fechafin'              => $this->fechafin,
            'refsemaforo'           => $this->refsemaforo,
            'refestados'            => $this->refestados,
            'estado'                => $this->getEstados()->getEstado(),
            'prioridad'             => $this->getSemaforo()->getNivel()
        );
    }

    public function cargar($refsolicitudesvisitas, $fecha, $refsemaforo, $refestados, $fechafin) {

        $this->setRefsolicitudesvisitas($refsolicitudesvisitas);
        $this->setFecha($fecha);
        $this->setRefsemaforo($refsemaforo);
        $this->setRefestados($refestados);
        $this->setFechafin($fechafin);
        
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
            $where = " and (c.nombre like '%".$busqueda."%' or su.sucursal like '%".$busqueda."%' or se.nivel like '%".$busqueda."%' or e.estado like '%".$busqueda."%' or ta.actividad like '%".$busqueda."%' )";
        }

        $sql = "select
            t.id,
            c.nombre as cliente,
            su.sucursal,
            ta.actividad,
            t.fecha,
            t.fechafin,
            se.nivel,
            e.estado,
            concat(usu.apellido, ' ',usu.nombre) as usuario,
            v.nroaviso,
            t.id as nroot
        from ".self::TABLA." t
        ".self::SQLBASE."
        inner join tbtipoactividades ta on ta.id = v.reftipoactividades
        left join dbcuadrillas cu on cu.refordenestrabajocabecera = t.id and cu.asignado='1'
        left join dbusuarios usu on usu.id = cu.refusuarios
        where 1=1 ".$where.$cadFecha.$cadPrioridad."
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
}
    

?>