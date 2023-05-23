<?php


class Seguros {

    const TABLA = 'dbseguros';
    const CAMPOS = 'refcamiones,refaseguradoras,nropoliza,vencimiento,rige';
    const CAMPOSVAR = ':refcamiones,:refaseguradoras,:nropoliza,:vencimiento,:rige';
    const RUTA = 'seguros';

    private $id;
    private $refcamiones;
    private $refaseguradoras;
    private $nropoliza;
    private $vencimiento;
    private $rige;

    private $error;
    private $descripcionError;

    private $camiones;
    private $aseguradoras;

    public function __construct($refcamiones)
    {
        $this->camiones = new Camiones();
        $this->aseguradoras = new Aseguradoras();
        $this->setRefcamiones($refcamiones);

        if ($refcamiones>0) {
            $this->camiones->buscarPorId($refcamiones);
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
                'refaseguradoras'   => $this->refaseguradoras,
                'refcamiones'       => $this->refcamiones,
                'nropoliza'         => $this->nropoliza,
                'vencimiento'       => $this->vencimiento,
                'rige'          => $this->rige
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
            
           $this->cargar($res['refcamiones'],$res['refaseguradoras'],$res['nropoliza'],$res['vencimiento'],$res['rige']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'refcamiones'      => $this->refcamiones,
            'refaseguradoras'      => $this->refaseguradoras,
            'nropoliza'      => $this->nropoliza,
            'vencimiento'      => $this->vencimiento,
            'rige'          => $this->rige
        );
    }

    public function cargar($refcamiones,$refaseguradoras,$nropoliza,$vencimiento,$rige) {
        $this->setRefcamiones($refcamiones);
        $this->setRefaseguradoras($refaseguradoras);
        $this->setNropoliza($nropoliza);
        $this->setVencimiento($vencimiento);
        $this->setRige($rige);
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
            $where = " where (t.nropoliza like '%".$busqueda."%' or t.vencimiento like '%".$busqueda."%' or ase.aseguradora like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        ase.aseguradora,
        t.vencimiento,
        t.nropoliza,
        t.rige,
        coalesce(concat('../../data/',d.carpeta,'/',d.idreferencia,'/',d.archivo),'') as archivo
        from ".self::TABLA." t 
        inner join dbcamiones c on c.id = t.refcamiones and c.id = ".$this->refcamiones."
        inner join tbactivos a on a.id = c.refactivos
        inner join tbaseguradoras ase on ase.id = t.refaseguradoras
        left join dbdocumentaciones d on d.reftabla = 4 and d.idreferencia = t.id
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
     * Get the value of rige
     */ 
    public function getRige()
    {
        return $this->rige;
    }

    /**
     * Set the value of rige
     *
     * @return  self
     */ 
    public function setRige($rige)
    {
        $this->rige = $rige;

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
     * Get the value of aseguradoras
     */ 
    public function getAseguradoras()
    {
        return $this->aseguradoras;
    }

    /**
     * Set the value of aseguradoras
     *
     * @return  self
     */ 
    public function setAseguradoras($aseguradoras)
    {
        $this->aseguradoras = $aseguradoras;

        return $this;
    }

    /**
     * Get the value of refaseguradoras
     */ 
    public function getRefaseguradoras()
    {
        return $this->refaseguradoras;
    }

    /**
     * Set the value of refaseguradoras
     *
     * @return  self
     */ 
    public function setRefaseguradoras($refaseguradoras)
    {
        $this->refaseguradoras = $refaseguradoras;

        return $this;
    }

    /**
     * Get the value of nropoliza
     */ 
    public function getNropoliza()
    {
        return $this->nropoliza;
    }

    /**
     * Set the value of nropoliza
     *
     * @return  self
     */ 
    public function setNropoliza($nropoliza)
    {
        $this->nropoliza = $nropoliza;

        return $this;
    }
}
    

?>