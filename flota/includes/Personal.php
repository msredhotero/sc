<?php


class Personal {

    const TABLA = 'dbpersonal';
    const CAMPOS = 'nombres,primerapellido,segundoapellido,rut,email,movil,refareas,refcargos,fechaalta,fechabaja,activo';
    const CAMPOSVAR = ':nombres,:primerapellido,:segundoapellido,:rut,:email,:movil,:refareas,:refcargos,:fechaalta,:fechabaja,:activo';
    const RUTA = 'personal';

    private $id;
    private $nombres;
    private $primerapellido;
    private $segundoapellido;
    private $rut;
    private $email;
    private $movil;
    private $refareas;
    private $refcargos;
    private $fechaalta;
    private $fechabaja;
    private $activo;

    private $error;
    private $descripcionError;

    private $cargos;
    private $areas;

    public function __construct()
    {
        $this->cargos = new Cargos();
        $this->areas = new Areas();
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
            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

            $query->execute([
                'nombres' => $this->nombres,
                'primerapellido' => $this->primerapellido,
                'segundoapellido' => $this->segundoapellido,
                'rut' => $this->rut,
                'email' => $this->email,
                'movil' => $this->movil,
                'refareas' => $this->refareas,
                'refcargos' => $this->refcargos,
                'fechaalta' => $this->fechaalta,
                'fechabaja' => $this->fechabaja,
                'activo' => $this->activo
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
            
           $this->cargar($res['nombres'],$res['primerapellido'],$res['segundoapellido'],$res['rut'],$res['email'],$res['movil'],$res['refareas'],$res['refcargos'],$res['fechaalta'],$res['fechabaja'],$res['activo']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'nombres' => $this->nombres,
            'primerapellido' => $this->primerapellido,
            'segundoapellido' => $this->segundoapellido,
            'rut' => $this->rut,
            'email' => $this->email,
            'movil' => $this->movil,
            'refareas' => $this->refareas,
            'refcargos' => $this->refcargos,
            'fechaalta' => $this->fechaalta,
            'fechabaja' => $this->fechabaja,
            'activo' => $this->activo
        );
    }

    public function cargar($nombres,$primerapellido,$segundoapellido,$rut,$email,$movil,$refareas,$refcargos,$fechaalta,$fechabaja,$activo) {

        $this->setNombres($nombres);
        $this->setPrimerapellido($primerapellido);
        $this->setSegundoapellido($segundoapellido);
        $this->setRut($rut);
        $this->setEmail($email);
        $this->setMovil($movil);
        $this->setRefareas($refareas);
        $this->setRefcargos($refcargos);
        $this->setFechaalta($fechaalta);
        $this->setFechabaja($fechabaja);
        $this->setActivo($activo);
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
            $where = " where ((concat(t.nombres, ' ',t.primerapellido,' ', t.segundoapellido) like '%".$busqueda."%' ) || (t.rut like '%".$busqueda."%'))";
        }
       
       
        $sql = "select
        t.id,
        concat(t.nombres, ' ',t.primerapellido,' ', t.segundoapellido) as apyn,
        t.rut,
        t.email,
        t.movil,
        a.area,
        c.cargo,
        t.fechaalta,
        t.fechabaja,
        (case when t.activo = '1' then 'Si' else 'No' end) as activo
        from ".self::TABLA." t
        inner join tbareas a on a.id = t.refareas
        inner join tbcargos c on c.id = t.refcargos
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
     * Get the value of nombres
     */ 
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set the value of nombres
     *
     * @return  self
     */ 
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get the value of primerapellido
     */ 
    public function getPrimerapellido()
    {
        return $this->primerapellido;
    }

    /**
     * Set the value of primerapellido
     *
     * @return  self
     */ 
    public function setPrimerapellido($primerapellido)
    {
        $this->primerapellido = $primerapellido;

        return $this;
    }

    /**
     * Get the value of segundoapellido
     */ 
    public function getSegundoapellido()
    {
        return $this->segundoapellido;
    }

    /**
     * Set the value of segundoapellido
     *
     * @return  self
     */ 
    public function setSegundoapellido($segundoapellido)
    {
        $this->segundoapellido = $segundoapellido;

        return $this;
    }

    /**
     * Get the value of rut
     */ 
    public function getRut()
    {
        return $this->rut;
    }

    /**
     * Set the value of rut
     *
     * @return  self
     */ 
    public function setRut($rut)
    {
        $this->rut = $rut;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of movil
     */ 
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * Set the value of movil
     *
     * @return  self
     */ 
    public function setMovil($movil)
    {
        $this->movil = $movil;

        return $this;
    }

    /**
     * Get the value of refareas
     */ 
    public function getRefareas()
    {
        return $this->refareas;
    }

    /**
     * Set the value of refareas
     *
     * @return  self
     */ 
    public function setRefareas($refareas)
    {
        $this->refareas = $refareas;

        return $this;
    }

    /**
     * Get the value of refcargos
     */ 
    public function getRefcargos()
    {
        return $this->refcargos;
    }

    /**
     * Set the value of refcargos
     *
     * @return  self
     */ 
    public function setRefcargos($refcargos)
    {
        $this->refcargos = $refcargos;

        return $this;
    }

    /**
     * Get the value of fechaalta
     */ 
    public function getFechaalta()
    {
        return $this->fechaalta;
    }

    /**
     * Set the value of fechaalta
     *
     * @return  self
     */ 
    public function setFechaalta($fechaalta)
    {
        $this->fechaalta = $fechaalta;

        return $this;
    }

    /**
     * Get the value of fechabaja
     */ 
    public function getFechabaja()
    {
        return $this->fechabaja;
    }

    /**
     * Set the value of fechabaja
     *
     * @return  self
     */ 
    public function setFechabaja($fechabaja)
    {
        $this->fechabaja = $fechabaja;

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
     * Get the value of cargos
     */ 
    public function getCargos()
    {
        return $this->cargos;
    }

    /**
     * Set the value of cargos
     *
     * @return  self
     */ 
    public function setCargos($cargos)
    {
        $this->cargos = $cargos;

        return $this;
    }

    /**
     * Get the value of areas
     */ 
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * Set the value of areas
     *
     * @return  self
     */ 
    public function setAreas($areas)
    {
        $this->areas = $areas;

        return $this;
    }
}
    

?>