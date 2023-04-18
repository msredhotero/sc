<?php


class Archivospersonal {

    const TABLA = 'dbarchivospersonal';
    const CAMPOS = 'refpersonal,refarchivos,realizado,vencimiento';
    const CAMPOSVAR = ':refpersonal,:refarchivos,:realizado,:vencimiento';
    const RUTA = 'archivospersonal';

    private $id;
    private $refpersonal;
    private $realizado;
    private $vencimiento;
    private $refarchivos;

    private $error;
    private $descripcionError;

    private $personal;
    private $archivos;

    public function __construct($refpersonal)
    {
        $this->personal = new Personal();
        $this->setRefpersonal($refpersonal);
        $this->archivos = new Archivos();

        if ($refpersonal>0) {
            $this->personal->buscarPorId($refpersonal);
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
    

    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

            $query->execute([
                'refpersonal'      => $this->refpersonal,
                'realizado'      => $this->realizado,
                'vencimiento'      => $this->vencimiento,
                'refarchivos'      => $this->refarchivos
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
            
           $this->cargar($res['refpersonal'],$res['refarchivos'],$res['realizado'],$res['vencimiento']);
           $this->setId($id);

           
  
        }else{
           return null;
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
  
        $sql = "select id from ".self::TABLA." where ".$cadSet." ";
  
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

    public function devolverArray() {
        return array(
            'refpersonal'      => $this->refpersonal,
            'refarchivos'      => $this->refarchivos,
            'realizado'      => $this->realizado,
            'vencimiento'      => $this->vencimiento
        );
    }

    public function cargar($refpersonal,$refarchivos,$realizado,$vencimiento) {

        $this->setRefpersonal($refpersonal);
        $this->setRefarchivos($refarchivos);
        $this->setRealizado($realizado);
        $this->setVencimiento($vencimiento);
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
            $where = " where (ar.descripcion like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        t.realizado,
        t.vencimiento,
        coalesce(concat('../../data/',d.carpeta,'/',d.idreferencia,'/',d.archivo),'') as archivo
        from ".self::TABLA." t 
        inner join dbpersonal c on c.id = t.refpersonal and c.id = ".$this->refpersonal."
        inner join dbarchivos ar on ar.id = t.refarchivos and ar.id = ".$this->refarchivos."
        inner join tbtipodocumentacion td on td.id = ar.reftipodocumentacion
        left join dbdocumentaciones d on d.reftabla = 7 and d.idreferencia = t.id
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
     * Get the value of realizado
     */ 
    public function getRealizado()
    {
        return $this->realizado;
    }

    /**
     * Set the value of realizado
     *
     * @return  self
     */ 
    public function setRealizado($realizado)
    {
        $this->realizado = $realizado;

        return $this;
    }

    /**
     * Get the value of vencimiento
     */ 
    public function getVencimiento()
    {
        return $this->vencimiento;
    }

    /**
     * Set the value of vencimiento
     *
     * @return  self
     */ 
    public function setVencimiento($vencimiento)
    {
        $this->vencimiento = $vencimiento;

        return $this;
    }



    /**
     * Get the value of refarchivos
     */ 
    public function getRefarchivos()
    {
        return $this->refarchivos;
    }

    /**
     * Set the value of refarchivos
     *
     * @return  self
     */ 
    public function setRefarchivos($refarchivos)
    {
        $this->refarchivos = $refarchivos;

        return $this;
    }

    /**
     * Get the value of archivos
     */ 
    public function getArchivos()
    {
        return $this->archivos;
    }

    /**
     * Set the value of archivos
     *
     * @return  self
     */ 
    public function setArchivos($archivos)
    {
        $this->archivos = $archivos;

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
}
    

?>