<?php


class Heladeras {

    const TABLA = 'dbheladeras';
    const CAMPOS = 'refcervezas,litros,reseteos,activo,usuariomodi,fechamodifi';
    const CAMPOSVAR = ':refcervezas,:litros,:reseteos,:activo,:usuariomodi,:fechamodifi';
    const ELIMINARACCION = 'eliminarHeladeras';

    private $id;
    private $refcervezas;
    private $litros;
    private $reseteos;
    private $usuariomodi;
    private $fechamodifi;
    private $activo;

    

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


    public function traerTodosFilter($options) {
        $db = new Database();

        $where = '';
        if (isset($options['refcervezas'])) {
            $where .= 'where t.refcervezas = ('.$options['refcervezas'].') ';
        }

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." ".$where." order by 1 ";

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
            $query = $pdo->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.',fechacrea) VALUES ('.self::CAMPOSVAR.')');
            
            $query->execute([
                'refcervezas'      => $this->refcervezas,
                'litros'      => $this->litros,
                'reseteos'      => $this->reseteos,
                'activo'      => $this->activo,
                'usuariomodi'      => $this->usuariomodi,
                'fechamodifi'      => $this->fechamodifi
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
            
           $this->cargar($res['refcervezas'],$res['litros'],$res['reseteos'],$res['activo'],$res['usuariomodi'],$res['fechamodifi']);
           $this->setId($id);

        }else{
           return null;
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

    public function devolverArray() {
        return array(
            'refcervezas'=> $this->refcervezas,
            'litros'=> $this->litros,
            'reseteos'=> $this->reseteos,
            'activo'=> $this->activo
        );
    }

    public function cargar($refcervezas,$litros,$reseteos,$activo,$usuariomodi,$fechamodifi) {

        $this->setRefcervezas($refcervezas);
        $this->setLitros($litros);
        $this->setReseteos($reseteos);
        $this->setActivo($activo);
        $this->setUsuariomodi($usuariomodi);
        $this->setFechamodifi($fechamodifi);
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
     * Get the value of refcervezas
     */ 
    public function getRefcervezas()
    {
        return $this->refcervezas;
    }

    /**
     * Set the value of refcervezas
     *
     * @return  self
     */ 
    public function setRefcervezas($refcervezas)
    {
        $this->refcervezas = $refcervezas;

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
     * Get the value of litros
     */ 
    public function getLitrosPorcentaje($vendido)
    {
        if ($vendido > 0) {
            if ($vendido > $this->litros) {
                return 0;
            } else {
                return (($this->litros - $vendido) * 100) / $this->litros;
            }
            
        } else {
            return 100;
        }
        
    }

    /**
     * Get the value of litros
     */ 
    public function getLitrosPorcentajeColor($porcentaje)
    {
        switch ($porcentaje) {
            case ($porcentaje < 10):
                return 'danger';
            break;
            case ($porcentaje >= 10 && $porcentaje <  25):
                return 'warning';
            break;
            case ($porcentaje >= 25 && $porcentaje <  85):
                return 'info';
            break;
            case ($porcentaje >= 85 && $porcentaje <=  100):
                return 'success';
            break;
        }
        
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
     * Get the value of reseteos
     */ 
    public function getReseteos()
    {
        return $this->reseteos;
    }

    /**
     * Set the value of reseteos
     *
     * @return  self
     */ 
    public function setReseteos($reseteos)
    {
        $this->reseteos = $reseteos;

        return $this;
    }

    /**
     * Get the value of usuariomodi
     */ 
    public function getUsuariomodi()
    {
        return $this->usuariomodi;
    }

    /**
     * Set the value of usuariomodi
     *
     * @return  self
     */ 
    public function setUsuariomodi($usuariomodi)
    {
        $this->usuariomodi = $usuariomodi;

        return $this;
    }

    /**
     * Get the value of fechamodifi
     */ 
    public function getFechamodifi()
    {
        return $this->fechamodifi;
    }

    /**
     * Set the value of fechamodifi
     *
     * @return  self
     */ 
    public function setFechamodifi($fechamodifi)
    {
        $this->fechamodifi = $fechamodifi;

        return $this;
    }
}
    

?>