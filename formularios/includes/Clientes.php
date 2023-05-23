<?php


class Clientes {

    const TABLA = 'dbclientes';
    const CAMPOS = 'nombre,direccion,cuit,contacto,telefono,email,latitud,longitud,codpostal';
    const CAMPOSVAR = ':nombre,:direccion,:cuit,:contacto,:telefono,:email,:latitud,:longitud,:codpostal';
    const RUTA = 'clientes';

    private $id;
    private $nombre;
    private $direccion;
    private $cuit;
    private $contacto;
    private $telefono;
    private $email;
    private $latitud;
    private $longitud;
    private $codpostal;

    private $error;
    private $descripcionError;


    public function __construct()
    {

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
  
        $sql = "select id,".self::CAMPOS." from ".self::TABLA." where ".$set." ";

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
                'nombre'      => $this->nombre,
                'direccion'    => $this->direccion,
                'cuit'    => $this->cuit,
                'contacto'    => $this->contacto,
                'email'    => $this->email,
                'latitud'    => $this->latitud,
                'longitud'    => $this->longitud,
                'codpostal'    => $this->codpostal,
                'telefono'     => $this->telefono
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

           $this->cargar($res['nombre'],$res['direccion'],$res['cuit'],$res['contacto'],$res['telefono'],$res['email'],$res['latitud'],$res['longitud'],$res['codpostal']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'nombre'      => $this->nombre,
            'direccion'    => $this->direccion,
            'cuit'    => $this->cuit,
            'contacto'    => $this->contacto,
            'telefono'    => $this->telefono,
            'email'    => $this->email,
            'latitud'    => $this->latitud,
            'longitud'    => $this->longitud,
            'codpostal'    => $this->codpostal
        );
    }

    public function cargar($nombre,$direccion,$cuit,$contacto,$telefono,$email,$latitud,$longitud,$codpostal) {

        $this->setNombre($nombre);
        $this->setDireccion($direccion);
        $this->setCuit($cuit);
        $this->setContacto($contacto);
        $this->setTelefono($telefono);
        $this->setEmail($email);
        $this->setLatitud($latitud);
        $this->setLongitud($longitud);
        $this->setCodpostal($codpostal);
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
            $where = " where (t.nombre like '%".$busqueda."%' or t.direccion like '%".$busqueda."%' or t.cuit like '%".$busqueda."%' or t.email like '%".$busqueda."%' or t.codpostal like '%".$busqueda."%')";
        }
       
        $sql = "select
        t.id,
        t.nombre,
        t.direccion,
        t.cuit,
        t.contacto,
        t.email,
        t.codpostal
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
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of direccion
     */ 
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set the value of direccion
     *
     * @return  self
     */ 
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get the value of cuit
     */ 
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set the value of cuit
     *
     * @return  self
     */ 
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get the value of contacto
     */ 
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set the value of contacto
     *
     * @return  self
     */ 
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get the value of telefono
     */ 
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */ 
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

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
     * Get the value of latitud
     */ 
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set the value of latitud
     *
     * @return  self
     */ 
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get the value of longitud
     */ 
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set the value of longitud
     *
     * @return  self
     */ 
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get the value of codpostal
     */ 
    public function getCodpostal()
    {
        return $this->codpostal;
    }

    /**
     * Set the value of codpostal
     *
     * @return  self
     */ 
    public function setCodpostal($codpostal)
    {
        $this->codpostal = $codpostal;

        return $this;
    }
}
    

?>