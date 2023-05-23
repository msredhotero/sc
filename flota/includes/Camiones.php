<?php


class Camiones {

    const TABLA = 'dbcamiones';
    const CAMPOS = 'refactivos,refmarcas,modelo,anio,patente,chasis,nromotor,tipo,usuariocrea,fechacrea,activo,fueradeservicio,kilometros,color';
    const CAMPOSVAR = ':refactivos,:refmarcas,:modelo,:anio,:patente,:chasis,:nromotor,:tipo,:usuariocrea,:fechacrea,:activo,:fueradeservicio,:kilometros,:color';
    const RUTA = 'camiones';

    private $id;
    private $refactivos;
    private $refmarcas;
    private $modelo;
    private $anio;
    private $patente;
    private $chasis;
    private $nromotor;
    private $tipo;
    private $usuariocrea;
    private $fechacrea;
    private $activo;
    private $fueradeservicio;
    private $kilometros;
    private $color;

    private $error;
    private $descripcionError;

    private $activos;
    private $marcas;


    public function __construct()
    {
        $this->activos = new Activos();
        $this->marcas = new Marcas();
    }

    public function activosAfueraPorTipo()
    {
        $db = new Database();
        $where = '';
        if ($this->getRefactivos()==1) {
            $where = "  where c.refactivos = 1 ";
        } else {
            $where = "  where c.refactivos > 1 ";
        }
        // saco esta validacion, por pedido de diego
        //max(coalesce(ot.id,0)) as entaller,

        $sql = "select
        c.id as refcamiones,
        c.patente,
        max(case when r.vencimiento <= now() then 1 else 0 end) as documentacionvencida,
        0 as entaller,
        max(coalesce(p.id,0)) as ensalida,
        max(coalesce(aco.id,0)) as ensalidaacoplado
        from (
                SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Emisiones Contaminantes' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbemisionescontaminantes e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones 
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Permisos de Circulacion' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbpermisoscirculacion e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Revisiones Tecnicas' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbrevisionestecnicas e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones 
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Seguros' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbseguros e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones 
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                a.descripcion AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbarchivosflota e
                    inner join
                dbarchivos a on a.id = e.refarchivos
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones
            ) r
            right join
            dbcamiones c on c.id = r.refcamiones
            left join
            dbordenestrabajos ot on ot.refcamiones = r.refcamiones and ot.refestados in (1,2,3) and now() >= ot.fechainicio
            left join
            dbporterias p on p.refcamiones = c.id and p.refporterias = 0 and p.refacciones = 1  
            left join
            dbporterias aco on aco.refacoplados = c.id and aco.refporterias = 0 and aco.refacciones = 1
            ".$where."
            group by c.id,c.patente
            ";

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

    public function activosDisponiblesPorTipo()
    {
        $db = new Database();
        $where = '';
        if ($this->getRefactivos()==1) {
            $where = "  where c.refactivos = 1 ";
        } else {
            $where = "  where c.refactivos > 1 ";
        }

        $sql = "select
        c.id as refcamiones,
        c.patente,
        max(case when r.vencimiento <= now() then 1 else 0 end) as documentacionvencida,
        max(coalesce(ot.id,0)) as entaller,
        max(coalesce(p.id,0)) as ensalida,
        max(coalesce(aco.id,0)) as ensalidaacoplado
        from (
                SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Emisiones Contaminantes' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbemisionescontaminantes e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones 
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Permisos de Circulacion' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbpermisoscirculacion e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Revisiones Tecnicas' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbrevisionestecnicas e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones 
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Seguros' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbseguros e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones 
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                a.descripcion AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbarchivosflota e
                    inner join
                dbarchivos a on a.id = e.refarchivos
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones
            ) r
            right join
            dbcamiones c on c.id = r.refcamiones
            left join
            dbordenestrabajos ot on ot.refcamiones = r.refcamiones and ot.refestados in (1,2,3) and now() >= ot.fechainicio
            left join
            dbporterias p on p.refcamiones = c.id and p.refporterias = 0 and p.refacciones = 1  
            left join
            dbporterias aco on aco.refacoplados = c.id and aco.refporterias = 0 and aco.refacciones = 1
            ".$where."
            group by c.id,c.patente
            ";

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

    public function traerTodosLimit($start, $length) {
        $db = new Database();

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA."  order by 1 ";
        $sql .= "limit ".$start.",".$length;

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
        
    }

    public function traerTodosEspecificoActivos() {
        $db = new Database();

        $sql = "SELECT 
            t.id,
            concat(a.activo, ' ',m.marca, ' ', t.modelo, ' ', t.anio, ' ', t.patente) as activo
        FROM ".self::TABLA." t 
        inner join tbmarcas m on m.id = t.refmarcas
        inner join tbactivos a on a.id = t.refactivos
        order by 1 ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
        
    }

    public function notificarVencimiento() {
        $lst = $this->traerVencimientos();
        $cadCuerpo = '';
        foreach ($lst as $row) {
            $cadCuerpo .= "<p>Patente: ".$row['patente']."</p>"."<p>Tipo: ".$row['tipo']."</p>"."<p>Vencimiento: ".$row['vencimiento']."</p>"."<p>Dias que faltan: ".$row['faltandias']."</p><hr>";
        }

        return $cadCuerpo;
    }

    function traerVencimientos($convencimientos=0) {
        $db = new Database();
        if ($convencimientos==0) {
            $where = 'where r.faltandias <= 15';
        } else {
            $where = '';
        }
        $sql = "select
            max(r.id) as id,
            r.refcamiones,
            r.patente,
            r.tipo,
            max(r.vencimiento) as vencimiento,
            max(r.faltandias) as faltandias
        from (
                SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Emisiones Contaminantes' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbemisionescontaminantes e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones and c.id = ".$this->getId()."
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Permisos de Circulacion' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbpermisoscirculacion e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones and c.id = ".$this->getId()."
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Revisiones Tecnicas' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbrevisionestecnicas e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones and c.id = ".$this->getId()." 
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                'Seguros' AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbseguros e
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones and c.id = ".$this->getId()."
                UNION ALL SELECT 
                e.id,
                e.refcamiones,
                c.patente,
                a.descripcion AS tipo,
                e.vencimiento,
                (CASE
                    WHEN DATEDIFF(e.vencimiento, CURDATE()) < 0 THEN 0
                    ELSE DATEDIFF(e.vencimiento, CURDATE())
                END) AS faltandias
                FROM
                dbarchivosflota e
                    inner join
                dbarchivos a on a.id = e.refarchivos
                    INNER JOIN
                dbcamiones c ON c.id = e.refcamiones and c.id = ".$this->getId()."
            ) r
            ".$where."
            group by 
        r.refcamiones,
        r.patente,
        r.tipo";

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
            //die(var_dump($query));
            $query->execute([
                'refactivos'        => (int)$this->refactivos,
                'refmarcas'         => (int)$this->refmarcas,
                'modelo'            => $this->modelo,
                'anio'              => (int)$this->anio,
                'patente'           => $this->patente,
                'chasis'            => $this->chasis,
                'nromotor'          => $this->nromotor,
                'tipo'              => $this->tipo,
                'usuariocrea'       => $this->usuariocrea,
                'fechacrea'         => $this->fechacrea,
                'activo'            => $this->activo,
                'fueradeservicio'   => $this->fueradeservicio,
                'kilometros'        => $this->kilometros,
                'color'             => $this->color
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
            
           $this->cargar($res['refactivos'],$res['refmarcas'],$res['modelo'],$res['anio'],$res['patente'],$res['chasis'],$res['nromotor'],$res['tipo'],$res['usuariocrea'],$res['fechacrea'],$res['activo'],$res['fueradeservicio'],$res['kilometros'],$res['color']);
           $this->setId($id);

  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        $this->getActivos()->buscarPorId($this->refactivos);
        $this->getMarcas()->buscarPorId($this->refmarcas);
        return array(
            'refactivos'        => $this->refactivos,
            'refmarcas'         => $this->refmarcas,
            'modelo'            => $this->modelo,
            'anio'              => $this->anio,
            'patente'           => $this->patente,
            'chasis'            => $this->chasis,
            'nromotor'          => $this->nromotor,
            'tipo'              => $this->tipo,
            'usuariocrea'       => $this->usuariocrea,
            'fechacrea'         => $this->fechacrea,
            'activo'            => $this->activo,
            'fueradeservicio'   => $this->fueradeservicio,
            'kilometros'        => $this->kilometros,
            'color'             => $this->color,
            'activo'            => $this->getActivos()->getActivo()
        );
    }

    public function cargar($refactivos,$refmarcas,$modelo,$anio,$patente,$chasis,$nromotor,$tipo,$usuariocrea,$fechacrea,$activo,$fueradeservicio,$kilometros,$color) {

        $this->setRefactivos($refactivos);
        $this->setRefmarcas($refmarcas);
        $this->setModelo($modelo);
        $this->setAnio($anio);
        $this->setPatente($patente);
        $this->setChasis($chasis);
        $this->setNromotor($nromotor);
        $this->setTipo($tipo);
        $this->setUsuariocrea($usuariocrea);
        $this->setFechacrea($fechacrea);
        $this->setActivo($activo);
        $this->setFueradeservicio($fueradeservicio);
        $this->setKilometros($kilometros);
        $this->setColor($color);
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

    public function buscarPorValor($arCampos) {
        $db = new Database();

        $cadSet = '';
  
        foreach ($arCampos as $clave => $valor) {
        // $array[3] se actualizará con cada valor de $array...
           $cadSet .= "{$clave} = :{$clave},";
        }

        $set = substr($cadSet,0,-1);
  
        $sql = "select id from ".self::TABLA." where ".$set." ";
  
        $consulta = $db->connect()->prepare($sql);
        foreach ($arCampos as $key => &$val) {
            $consulta->bindParam($key, $val);
        }
  
        $consulta->execute();
  
        $res = $consulta->fetch();
  
        if($res){
  
           $this->buscarPorId($res['id']);
  
        }else{
           return false;
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
  
        $sql = "select id,patente from ".self::TABLA." where ".$set." ";

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

    //const CAMPOS = 'refactivos,refmarcas,modelo,anio,patente,chasis,nromotor,tipo,usuariocrea,fechacrea,activo';
    public function traerAjax($length, $start, $busqueda,$colSort,$colSortDir,$refactivos) {
        $where = '';

        $db = new Database();

       
        $busqueda = str_replace("'","",$busqueda);
        if ($busqueda != '') {
            $where = " where (a.activo like '%".$busqueda."%' or m.marca like '%".$busqueda."%' or t.modelo like '%".$busqueda."%' or t.anio like '%".$busqueda."%' or t.patente like '%".$busqueda."%' or t.chasis like '%".$busqueda."%' or t.nromotor like '%".$busqueda."%' or t.tipo like '%".$busqueda."%' or t.kilometros like '%".$busqueda."%' )";
        }

        $cadActivos = '';
        if ($refactivos > 0) {
            $cadActivos = ' and a.id = '.$refactivos;
        }
       
       
        $sql = "select
        t.id,
        (case when t.activo = '1' then 'Si' else 'No' end) as activo,
        (case when t.fueradeservicio = '1' then 'Si' else 'No' end) as fueradeservicio,
        a.activo,
        m.marca,
        t.modelo,
        t.anio,
        t.patente,
        t.chasis,
        t.nromotor,
        t.tipo,
        t.kilometros
        
        
        from ".self::TABLA." t
        inner
        join    tbactivos a on a.id = t.refactivos ".$cadActivos."
        inner
        join    tbmarcas m on m.id = t.refmarcas
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
     * Get the value of refactivos
     */ 
    public function getRefactivos()
    {
        return $this->refactivos;
    }

    /**
     * Set the value of refactivos
     *
     * @return  self
     */ 
    public function setRefactivos($refactivos)
    {
        $this->refactivos = $refactivos;

        return $this;
    }

    /**
     * Get the value of refmarcas
     */ 
    public function getRefmarcas()
    {
        return $this->refmarcas;
    }

    /**
     * Set the value of refmarcas
     *
     * @return  self
     */ 
    public function setRefmarcas($refmarcas)
    {
        $this->refmarcas = $refmarcas;

        return $this;
    }

    /**
     * Get the value of modelo
     */ 
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Set the value of modelo
     *
     * @return  self
     */ 
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get the value of anio
     */ 
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set the value of anio
     *
     * @return  self
     */ 
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get the value of patente
     */ 
    public function getPatente()
    {
        return $this->patente;
    }

    /**
     * Set the value of patente
     *
     * @return  self
     */ 
    public function setPatente($patente)
    {
        $this->patente = $patente;

        return $this;
    }

    /**
     * Get the value of chasis
     */ 
    public function getChasis()
    {
        return $this->chasis;
    }

    /**
     * Set the value of chasis
     *
     * @return  self
     */ 
    public function setChasis($chasis)
    {
        $this->chasis = $chasis;

        return $this;
    }

    /**
     * Get the value of nromotor
     */ 
    public function getNromotor()
    {
        return $this->nromotor;
    }

    /**
     * Set the value of nromotor
     *
     * @return  self
     */ 
    public function setNromotor($nromotor)
    {
        $this->nromotor = $nromotor;

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
     * Get the value of activo
     */ 
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Get the value of activo
     */ 
    public function getActivoStr()
    {
        return ($this->activo == '1' ? 'Si' : 'No');
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
     * Get the value of activos
     */ 
    public function getActivos()
    {
        return $this->activos;
    }

    /**
     * Set the value of activos
     *
     * @return  self
     */ 
    public function setActivos($activos)
    {
        $this->activos = $activos;

        return $this;
    }

    /**
     * Get the value of marcas
     */ 
    public function getMarcas()
    {
        return $this->marcas;
    }

    /**
     * Set the value of marcas
     *
     * @return  self
     */ 
    public function setMarcas($marcas)
    {
        $this->marcas = $marcas;

        return $this;
    }

    /**
     * Get the value of fueradeservicio
     */ 
    public function getFueradeservicio()
    {
        return $this->fueradeservicio;
    }

    /**
     * Set the value of fueradeservicio
     *
     * @return  self
     */ 
    public function setFueradeservicio($fueradeservicio)
    {
        $this->fueradeservicio = $fueradeservicio;

        return $this;
    }

    /**
     * Get the value of activo
     */ 
    public function getFueradeservicioStr()
    {
        return ($this->fueradeservicio == '1' ? 'Si' : 'No');
    }

    /**
     * Get the value of kilometros
     */ 
    public function getKilometros()
    {
        return $this->kilometros;
    }

    /**
     * Set the value of kilometros
     *
     * @return  self
     */ 
    public function setKilometros($kilometros)
    {
        $this->kilometros = $kilometros;

        return $this;
    }

    /**
     * Get the value of color
     */ 
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @return  self
     */ 
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }
}
    

?>