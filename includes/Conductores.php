<?php


class Conductores {

    const TABLA = 'dbconductores';
    const CAMPOS = 'refpersonal,refporterias,conduce';
    const CAMPOSVAR = ':refpersonal,:refporterias,:conduce';
    const RUTA = 'conductores';

    private $id;
    private $refpersonal;
    private $refporterias;
    private $conduce;

    private $personal;
    private $porterias;

    private $error;
    private $descripcionError;


    public function __construct()
    {
        $this->personal = new Personal();
        $this->porterias = new Porterias();
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

    public function traerDisponibles() {
        $db = new Database();

        $sql = "select
        p.id,p.nombres,p.primerapellido,p.segundoapellido
        from		dbpersonal p
        left
        join		(
        select c.refpersonal
        from
        dbconductores c 
        inner
        join		dbporterias po
        on			po.id = c.refporterias and po.refporterias=0) po
        on			po.refpersonal = p.id
        where		p.refcargos=5 and po.refpersonal is null
        group by	p.id,p.nombres,p.primerapellido,p.segundoapellido";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }

    }

    public function devolverConductor($arCampos) {
        $lst = $this->traerTodosFilter($arCampos);
        //die(var_dump($lst));
        $found_conduce = array_search('1', array_column($lst, 'conduce'));

        return $lst[$found_conduce];
    }

    public function traerTodosFilter($arCampos) {
        $db = new Database();

        $cadSet = '';
  
        foreach ($arCampos as $clave => $valor) {
        // $array[3] se actualizará con cada valor de $array...
           $cadSet .= " t.{$clave} = :{$clave} and ";
        }


        $set = substr($cadSet,0,-4);
  
        $sql = "select 
            t.id, 
            concat(p.primerapellido,' ',p.segundoapellido,' ',p.nombres ) as apyn,
            t.conduce,
            t.refpersonal from ".self::TABLA." t
        inner join dbpersonal p on p.id = t.refpersonal
        where ".$set." order by 1 ";

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
                'refpersonal'      => $this->refpersonal,
                'refporterias'  => $this->refporterias,
                'conduce'   => $this->conduce
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
            
           $this->cargar($res['refpersonal'],$res['refporterias'],$res['conduce']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        $this->getPersonal()->buscarPorId($this->refpersonal);
        return array(
            'refpersonal'=> $this->refpersonal,
            'refporterias'=> $this->refporterias,
            'conduce'=> $this->getConduceStr(),
            'pasajero'=> $this->personal->getPrimerapellido().' '.$this->getPersonal()->getSegundoapellido().' '.$this->personal->getNombres()
        );
    }

    public function cargar($refpersonal,$refporterias,$conduce) {

        $this->setRefpersonal($refpersonal);
        $this->setRefporterias($refporterias);
        $this->setConduce($conduce);
        
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

    public function borrarPorPorteria() {
        $db = new Database();
        try {

            $query = $db->connect()->prepare('DELETE FROM '.self::TABLA.' WHERE refporterias = :refporterias');

            try {
                $query->execute([
                    'refporterias'      => $this->refporterias
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
            $where = " where (t.cargo like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        t.cargo
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
     * Get the value of refpersonal
     */ 
    public function getRefpersonal()
    {
        return $this->refpersonal;
    }

    /**
     * Set the value of refpersonal
     *
     * @return  self
     */ 
    public function setRefpersonal($refpersonal)
    {
        $this->refpersonal = $refpersonal;

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
     * Get the value of conduce
     */ 
    public function getConduce()
    {
        return $this->conduce;
    }

    /**
     * Get the value of conduce
     */ 
    public function getConduceStr()
    {
        return ($this->conduce == '1' ? 'Si' : 'No');
    }

    /**
     * Set the value of conduce
     *
     * @return  self
     */ 
    public function setConduce($conduce)
    {
        $this->conduce = $conduce;

        return $this;
    }

    /**
     * Get the value of personal
     */ 
    public function getPersonal()
    {
        return $this->personal;
    }

    /**
     * Set the value of personal
     *
     * @return  self
     */ 
    public function setPersonal($personal)
    {
        $this->personal = $personal;

        return $this;
    }

    /**
     * Get the value of porterias
     */ 
    public function getPorterias()
    {
        return $this->porterias;
    }

    /**
     * Set the value of porterias
     *
     * @return  self
     */ 
    public function setPorterias($porterias)
    {
        $this->porterias = $porterias;

        return $this;
    }
}
    

?>