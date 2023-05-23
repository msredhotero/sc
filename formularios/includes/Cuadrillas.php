<?php


class Cuadrillas {

    const TABLA = 'dbcuadrillas';
    const CAMPOS = 'refordenestrabajocabecera,refusuarios,asignado';
    const CAMPOSVAR = ':refordenestrabajocabecera,:refusuarios,:asignado';
    const RUTA = 'cuadrillas';

    private $id;
    private $refordenestrabajocabecera;
    private $refusuarios;
    private $asignado;

    private $error;
    private $descripcionError;

    private $ordenestrabajocabecera;
    private $usuarios;

    public function __construct()
    {
        $this->ordenestrabajocabecera = new Ordenestrabajocabecera();
        $this->usuarios = new Usuarios('','');
    }

    public function existeAsignadoPorOrden() {
        $lstResult = $this->traerTodosFilter(['refordenestrabajocabecera'=>$this->refordenestrabajocabecera,'asignado'=>'1']);

        if (count($lstResult)>0) {
            
            if ($lstResult[0]['refusuarios']==$this->refusuarios) {
                return 0;
            }
            return 1;
        }

        return 0;

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

    public function traerTodosDisponibles() {
        $db = new Database();

        $sql = "SELECT 
                u.id, u.nombre, u.apellido
            FROM
                dbusuarios u
                    LEFT JOIN
                dbcuadrillas t ON u.id = t.refusuarios AND t.refordenestrabajocabecera = ".$this->getRefordenestrabajocabecera()."
            WHERE
                t.id IS NULL and u.refroles=2";

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

    public function resetAsigandos() {
        $db = new Database();

        $consulta = $db->connect()->prepare("update ".self::TABLA." set asignado='0' where refordenestrabajocabecera = :refordenestrabajocabecera ");

        $consulta->bindParam(':refordenestrabajocabecera', $this->refordenestrabajocabecera);

        try {
            $consulta->execute();
  
            $this->setError(0);
        }catch(PDOException $e){
           $this->setError(1);
           $this->setDescripcionError('Ha surgido un error y no se puede modificar la solicitud');
            //echo 'Ha surgido un error y no se puede crear la solicitud: ' . $e->getMessage();
           exit;
        }
  
        //$this->setIdendoso($conexion->lastInsertId());
  
        $db = null;
    }

    public function traerTodosFilter($arCampos) {
        $db = new Database();

        $cadSet = '';
  
        foreach ($arCampos as $clave => $valor) {
        // $array[3] se actualizará con cada valor de $array...
           $cadSet .= "{$clave} = :{$clave} and ";
        }


        $set = substr($cadSet,0,-4);
  
        $sql = "select id,refusuarios from ".self::TABLA." where ".$set." ";

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
                'refordenestrabajocabecera'      => $this->refordenestrabajocabecera,
                'refusuarios'   => $this->refusuarios,
                'asignado'  => $this->asignado
            ]);

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
            
           $this->cargar($res['refordenestrabajocabecera'],$res['refusuarios'],$res['asignado']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        $this->getUsuarios()->buscarPorId($this->refusuarios);
        return array(
            'refordenestrabajocabecera'=> $this->refordenestrabajocabecera,
            'refusuarios'=> $this->refusuarios,
            'asignado' => $this->asignado,
            'usuario' => $this->getUsuarios()->devolverArray()
        );
    }

    public function cargar($refordenestrabajocabecera,$refusuarios,$asignado) {

        $this->setRefordenestrabajocabecera($refordenestrabajocabecera);
        $this->setRefusuarios($refusuarios);
        $this->setAsignado($asignado);
        
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
            $where = " and (u.nombre like '%".$busqueda."%' or u.apellido like '%".$busqueda."%' or ca.cargo like '%".$busqueda."%' )";
        }
        
        $cadOT = '';
        if ($this->getOrdenestrabajocabecera()>0) {
            $cadOT = ' where t.id = '.$this->getOrdenestrabajocabecera();
        } else {
            $cadOT = ' where t.id < 0 ';
        }
       
        $sql = "select
        r.id,
        u.nombre,
        u.apellido,
        ca.cargo,
        (case when r.asignado = '1' then 'Si' else 'No' end) as asignado 
        from ".self::TABLA." r
        inner join dbusuarios u on u.id = r.refusuarios
        inner join tbcargos ca on ca.id = u.refcargos
        inner join dbordenestrabajocabecera t on r.refordenestrabajocabecera = t.id
        inner join dbsolicitudesvisitas v on v.id = t.refsolicitudesvisitas
        inner join dbclientes c on c.id = v.refclientes
        left join dbsucursales su on su.idreferencia = v.refclientes and su.reftabla = 1 and su.id = v.refsucursales
        inner join tbsemaforo se on se.id = t.refsemaforo
        inner join tbestados e on e.id = t.refestados
        ".$cadOT.$where."
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
     * Get the value of refordenestrabajocabecera
     */ 
    public function getRefordenestrabajocabecera()
    {
        return $this->refordenestrabajocabecera;
    }

    /**
     * Set the value of refordenestrabajocabecera
     *
     * @return  self
     */ 
    public function setRefordenestrabajocabecera($refordenestrabajocabecera)
    {
        $this->refordenestrabajocabecera = $refordenestrabajocabecera;

        return $this;
    }

    /**
     * Get the value of refusuarios
     */ 
    public function getRefusuarios()
    {
        return $this->refusuarios;
    }

    /**
     * Set the value of refusuarios
     *
     * @return  self
     */ 
    public function setRefusuarios($refusuarios)
    {
        $this->refusuarios = $refusuarios;

        return $this;
    }

    /**
     * Get the value of ordenestrabajocabecera
     */ 
    public function getOrdenestrabajocabecera()
    {
        return $this->ordenestrabajocabecera;
    }

    /**
     * Set the value of ordenestrabajocabecera
     *
     * @return  self
     */ 
    public function setOrdenestrabajocabecera($ordenestrabajocabecera)
    {
        $this->ordenestrabajocabecera = $ordenestrabajocabecera;

        return $this;
    }

    /**
     * Get the value of usuarios
     */ 
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Set the value of usuarios
     *
     * @return  self
     */ 
    public function setUsuarios($usuarios)
    {
        $this->usuarios = $usuarios;

        return $this;
    }

    /**
     * Get the value of asignado
     */ 
    public function getAsignado()
    {
        return $this->asignado;
    }

    /**
     * Get the value of asignado
     */ 
    public function getAsignadoStr()
    {
        return ($this->asignado == '1' ? 'Si' : 'No');
    }

    /**
     * Set the value of asignado
     *
     * @return  self
     */ 
    public function setAsignado($asignado)
    {
        $this->asignado = $asignado;

        return $this;
    }
}
    

?>