<?php


class Porterias {

    const TABLA = 'dbporterias';
    const CAMPOS = 'refacciones,refcamiones,refacoplados,fecha,km,litros,destino,documentacion,checklist,mtrscubicos,refporterias,reftiposervicios';
    const CAMPOSVAR = ':refacciones,:refcamiones,:refacoplados,:fecha,:km,:litros,:destino,:documentacion,:checklist,:mtrscubicos,:refporterias,:reftiposervicios';
    const RUTA = 'porterias';

    private $id;
    private $refacciones;
    private $refcamiones;
    private $refacoplados;
    private $fecha;
    private $km;
    private $litros;
    private $destino;
    private $documentacion;
    private $checklist;
    private $mtrscubicos;
    private $refporterias;
    private $reftiposervicios;

    private $camiones;
    private $acciones;
    private $tiposervicios;

    private $error;
    private $descripcionError;


    public function __construct()
    {
        $this->camiones = new Camiones();
        $this->acciones = new Acciones();
        $this->tiposervicios = new Tiposervicios();
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

    public function ultimoKmCargado() {
        $db = new Database();

        $sql = "SELECT c.patente,c.kilometros, max(t.km) as km,c.id FROM ".self::TABLA." t
        inner
        join dbcamiones c
        on      c.id = t.refcamiones 
        group by c.patente,c.id,c.kilometros
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

    public function traerTodosFilter($arCampos) {
        $db = new Database();

        $cadSet = '';
  
        foreach ($arCampos as $clave => $valor) {
        // $array[3] se actualizará con cada valor de $array...
           $cadSet .= "{$clave} = :{$clave} and ";
        }


        $set = substr($cadSet,0,-4);
  
        $sql = "select id,".self::CAMPOS." from ".self::TABLA." where ".$set." order by 1 ";

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
    

    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            $pdo = $db->connect();
            $query = $pdo->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');
            
            $query->execute([
                'refacciones'      => $this->refacciones,
                'refcamiones'      => $this->refcamiones,
                'refacoplados'      => $this->refacoplados,
                'fecha'      => $this->fecha,
                'km'      => $this->km,
                'litros'      => $this->litros,
                'destino'      => $this->destino,
                'documentacion'      => $this->documentacion,
                'checklist'      => $this->checklist,
                'mtrscubicos'      => $this->mtrscubicos,
                'refporterias'      => $this->refporterias,
                'reftiposervicios'  => $this->reftiposervicios
            ]);

            $lastInsertId = $pdo->lastInsertId();

            return $lastInsertId;

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
           $cadSet .= "{$clave} = :{$clave} and ";
        }

        $set = substr($cadSet,0,-4);
  
        $sql = "select id from ".self::TABLA." where ".$set." ";

        //die(var_dump($sql));
  
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
        
            $this->cargar($res['refacciones'],$res['refcamiones'],$res['refacoplados'],$res['fecha'],$res['km'],$res['litros'],$res['destino'],$res['documentacion'],$res['checklist'],$res['mtrscubicos'],$res['refporterias'],$res['reftiposervicios']);
            $this->setId($id);

            
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        $this->getTiposervicios()->buscarPorId($this->reftiposervicios);
        return array(
            'refacciones'=> $this->refacciones,
            'refcamiones'=> $this->refcamiones,
            'refacoplados'=> $this->refacoplados,
            'fecha'=> $this->fecha,
            'km'=> $this->km,
            'litros'=> $this->litros,
            'destino'=> $this->destino,
            'documentacion'=> $this->documentacion,
            'checklist'=> $this->checklist,
            'mtrscubicos'=> $this->mtrscubicos,
            'refporterias'=> $this->refporterias,
            'id'=> $this->id,
            'tiposervicios' => $this->getTiposervicios()->devolverArray(),
            'reftiposervicios'=> $this->reftiposervicios
        );
    }

    public function cargar($refacciones,$refcamiones,$refacoplados,$fecha,$km,$litros,$destino,$documentacion,$checklist,$mtrscubicos,$refporterias,$reftiposervicios) {

        $this->setRefacciones($refacciones);
        $this->setRefcamiones($refcamiones);
        $this->setRefacoplados($refacoplados);
        $this->setFecha($fecha);
        $this->setKm($km);
        $this->setLitros($litros);
        $this->setDestino($destino);
        $this->setDocumentacion($documentacion);
        $this->setChecklist($checklist);
        $this->setMtrscubicos($mtrscubicos);
        $this->setRefporterias($refporterias);
        $this->setReftiposervicios($reftiposervicios);
        
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
            $where = " where (t.accion like '%".$busqueda."%' )";
        }
        $cadSalidas = '';
        if ($this->refacciones == 1) {
            $cadSalidas = ' and t.refporterias = 0 ';
        } else {
            $cadSalidas = ' and t.refporterias > 0 ';
        }
       
        $sql = "select
            t.id,
            c.patente as camion,
            ts.tiposervicio,
            coalesce(concat(ac.activo,' ', cc.patente),' ') as acoplado,
            t.fecha,
            t.km,
            t.litros,
            coalesce(concat(p.primerapellido,' ',p.segundoapellido,' ',p.nombres),'') as conductor,
            t.destino,
            a.accion,
            (case when t.documentacion = '1' then 'Si' else 'No' end) as documentacion,
            (case when t.checklist = '1' then 'Si' else 'No' end) as checklist,
            t.mtrscubicos,
            t.refacciones,
            t.refcamiones,
            t.refacoplados,
            t.refporterias
        from ".self::TABLA." t
        inner join tbacciones a on a.id = t.refacciones and a.id <= ".$this->refacciones." ".$cadSalidas."
        inner join dbcamiones c on c.id = t.refcamiones 
        
        left join dbcamiones cc on cc.id = t.refacoplados
        left join tbactivos ac on ac.id = cc.refactivos
        inner join tbtiposervicios ts on ts.id = t.reftiposervicios
        left join dbconductores con on con.refporterias = t.id and con.conduce='1'
        left join dbpersonal p on p.id = con.refpersonal
        ".$where."
        ORDER BY 1 ";
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
     * Get the value of refacciones
     */ 
    public function getRefacciones()
    {
        return $this->refacciones;
    }

    /**
     * Set the value of refacciones
     *
     * @return  self
     */ 
    public function setRefacciones($refacciones)
    {
        $this->refacciones = $refacciones;

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
     * Get the value of refacoplados
     */ 
    public function getRefacoplados()
    {
        return $this->refacoplados;
    }

    /**
     * Set the value of refacoplados
     *
     * @return  self
     */ 
    public function setRefacoplados($refacoplados)
    {
        $this->refacoplados = $refacoplados;

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
     * Get the value of km
     */ 
    public function getKm()
    {
        return $this->km;
    }

    /**
     * Set the value of km
     *
     * @return  self
     */ 
    public function setKm($km)
    {
        $this->km = $km;

        return $this;
    }

    /**
     * Get the value of litros
     */ 
    public function getLitros()
    {
        return $this->litros;
    }

    /**
     * Set the value of litros
     *
     * @return  self
     */ 
    public function setLitros($litros)
    {
        $this->litros = $litros;

        return $this;
    }

    /**
     * Get the value of destino
     */ 
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * Set the value of destino
     *
     * @return  self
     */ 
    public function setDestino($destino)
    {
        $this->destino = $destino;

        return $this;
    }

    /**
     * Get the value of documentacion
     */ 
    public function getDocumentacion()
    {
        return $this->documentacion;
    }

    /**
     * Get the value of documentacion
     */ 
    public function getDocumentacionStr()
    {
        return ($this->documentacion == '1' ? 'Si' : 'No');
    }

    /**
     * Set the value of documentacion
     *
     * @return  self
     */ 
    public function setDocumentacion($documentacion)
    {
        $this->documentacion = $documentacion;

        return $this;
    }

    /**
     * Get the value of checklist
     */ 
    public function getChecklist()
    {
        return $this->checklist;
    }

    /**
     * Get the value of checklist
     */ 
    public function getChecklistStr()
    {
        return ($this->checklist == '1' ? 'Si' : 'No');
    }

    /**
     * Set the value of checklist
     *
     * @return  self
     */ 
    public function setChecklist($checklist)
    {
        $this->checklist = $checklist;

        return $this;
    }

    /**
     * Get the value of mtrscubicos
     */ 
    public function getMtrscubicos()
    {
        return $this->mtrscubicos;
    }

    /**
     * Set the value of mtrscubicos
     *
     * @return  self
     */ 
    public function setMtrscubicos($mtrscubicos)
    {
        $this->mtrscubicos = $mtrscubicos;

        return $this;
    }

    /**
     * Get the value of refporterias
     */ 
    public function getRefporterias()
    {
        return $this->refporterias;
    }

    /**
     * Set the value of refporterias
     *
     * @return  self
     */ 
    public function setRefporterias($refporterias)
    {
        $this->refporterias = $refporterias;

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
     * Get the value of acciones
     */ 
    public function getAcciones()
    {
        return $this->acciones;
    }

    /**
     * Set the value of acciones
     *
     * @return  self
     */ 
    public function setAcciones($acciones)
    {
        $this->acciones = $acciones;

        return $this;
    }

    /**
     * Get the value of reftiposervicios
     */ 
    public function getReftiposervicios()
    {
        return $this->reftiposervicios;
    }

    /**
     * Set the value of reftiposervicios
     *
     * @return  self
     */ 
    public function setReftiposervicios($reftiposervicios)
    {
        $this->reftiposervicios = $reftiposervicios;

        return $this;
    }

    /**
     * Get the value of tiposervicios
     */ 
    public function getTiposervicios()
    {
        return $this->tiposervicios;
    }

    /**
     * Set the value of tiposervicios
     *
     * @return  self
     */ 
    public function setTiposervicios($tiposervicios)
    {
        $this->tiposervicios = $tiposervicios;

        return $this;
    }
}
    

?>