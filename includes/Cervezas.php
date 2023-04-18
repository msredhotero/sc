<?php


class Cervezas {

    const TABLA = 'dbcervezas';
    const CAMPOS = 'tipo,ibu,og,alcohol,precio,activo,usuariocrea,refproveedores,pinta,botellon';
    const CAMPOSVAR = ':tipo,:ibu,:og,:alcohol,:precio,:activo,:usuariocrea,:refproveedores,:pinta,:botellon';
    const ELIMINARACCION = 'eliminarCervezas';

    private $id;
    private $tipo;
    private $ibu;
    private $og;
    private $alcohol;
    private $precio;
    private $activo;
    private $usuariocrea;
    private $fechacrea;
    private $refproveedores;
    private $pinta;
    private $botellon;
    
    private $proveedores;

    private $error;
    private $descripcionError;

    public function __construct()
    {
        $this->proveedores = new Proveedores();
    }


    public function traerTodos() {
        $db = new Database();

        $sql = "SELECT t.id,t.tipo,t.ibu,t.og,t.alcohol,t.precio,t.pinta,t.botellon,t.activo,p.proveedor 
        FROM ".self::TABLA." t 
        left join tbproveedores p on t.refproveedores = p.id order by t.activo desc,t.tipo ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function traerTodosFilter() {
        $db = new Database();

        

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." where activo='1' order by 1 ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    
    
    //(tipo,ibu,og,alcohol,precio,activo,usuariocrea) VALUES (:tipo,:ibu,:og,:alcohol,:precio,:activo,:usuariocrea)
    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            $pdo = $db->connect();
            $query = $pdo->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.',fechacrea) VALUES ('.self::CAMPOSVAR.',:fechacrea)');
            
            $query->execute([
                'tipo'      => $this->tipo,
                'ibu'      => $this->ibu,
                'og'      => (int)$this->og,
                'alcohol'      => $this->alcohol,
                'precio'      => (float)$this->precio,
                'activo'      => $this->activo,
                'usuariocrea'      => $this->usuariocrea,
                'refproveedores'    => $this->refproveedores,
                'pinta' => (float)$this->pinta,
                'botellon' => (float)$this->botellon,
                'fechacrea'      => date('Y-m-d H:i:s')
            ]);

            $lastInsertId = $pdo->lastInsertId();

            return $lastInsertId;

        } catch (PDOException $e) {
            
            error_log($e->getMessage());
            return $e->getMessage();
            
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
            
           $this->cargar($res['tipo'],$res['ibu'],$res['og'],$res['alcohol'],$res['precio'],$res['activo'],$res['refproveedores'],$res['pinta'],$res['botellon']);
           $this->setId($id);

        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'tipo'=> $this->tipo,
            'ibu'=> $this->ibu,
            'og'=> $this->og,
            'alcohol'=> $this->alcohol,
            'precio'=> $this->precio,
            'activo'=> $this->activo,
            'refproveedores'=> $this->refproveedores,
            'pinta' => $this->pinta,
            'botellon' => $this->botellon
        );
    }

    public function cargar($tipo,$ibu,$og,$alcohol,$precio,$activo,$refproveedores,$pinta,$botellon) {

        $this->setTipo($tipo);
        $this->setIbu($ibu);
        $this->setOg($og);
        $this->setAlcohol($alcohol);
        $this->setPrecio($precio);
        $this->setActivo($activo);
        $this->setRefproveedores($refproveedores);
        $this->setPinta($pinta);
        $this->setBotellon($botellon);
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
            $where = " where (t.maridage like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        t.tipo,
        t.ibu,
        t.og,
        t.alcohol,
        t.precio,
        (case when t.activo='1' then 'Si' else 'No' end) as activo,
        p.proveedor
        from ".self::TABLA." t
        left join tbproveedores p on p.id = t.refproveedores
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
     * Get the value of ibu
     */ 
    public function getIbu()
    {
        return $this->ibu;
    }

    /**
     * Set the value of ibu
     *
     * @return  self
     */ 
    public function setIbu($ibu)
    {
        $this->ibu = $ibu;

        return $this;
    }

    /**
     * Get the value of og
     */ 
    public function getOg()
    {
        return $this->og;
    }

    /**
     * Set the value of og
     *
     * @return  self
     */ 
    public function setOg($og)
    {
        $this->og = $og;

        return $this;
    }

    /**
     * Get the value of alcohol
     */ 
    public function getAlcohol()
    {
        return $this->alcohol;
    }

    /**
     * Set the value of alcohol
     *
     * @return  self
     */ 
    public function setAlcohol($alcohol)
    {
        $this->alcohol = $alcohol;

        return $this;
    }

    /**
     * Get the value of precio
     */ 
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */ 
    public function setPrecio($precio)
    {
        $this->precio = $precio;

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
     * Get the value of activa
     */ 
    public function getActivoStr()
    {
        return ($this->activo == '1' ? 'Si':'No');
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
     * Get the value of refproveedores
     */ 
    public function getRefproveedores()
    {
        return $this->refproveedores;
    }

    /**
     * Set the value of refproveedores
     *
     * @return  self
     */ 
    public function setRefproveedores($refproveedores)
    {
        $this->refproveedores = $refproveedores;

        return $this;
    }

    /**
     * Get the value of proveedores
     */ 
    public function getProveedores()
    {
        return $this->proveedores;
    }

    /**
     * Set the value of proveedores
     *
     * @return  self
     */ 
    public function setProveedores($proveedores)
    {
        $this->proveedores = $proveedores;

        return $this;
    }

    /**
     * Get the value of pinta
     */ 
    public function getPinta()
    {
        return $this->pinta;
    }

    /**
     * Set the value of pinta
     *
     * @return  self
     */ 
    public function setPinta($pinta)
    {
        $this->pinta = $pinta;

        return $this;
    }

    

    /**
     * Get the value of botellon
     */ 
    public function getBotellon()
    {
        return $this->botellon;
    }

    /**
     * Set the value of botellon
     *
     * @return  self
     */ 
    public function setBotellon($botellon)
    {
        $this->botellon = $botellon;

        return $this;
    }
}
    

?>