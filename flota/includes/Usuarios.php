<?php

class Usuarios {
    const TABLA = 'dbusuarios';
    const CAMPOS = 'username,email,password,nombre,apellido,validoemail,activo,refroles,direccion,telefono,refcargos,ultimaconexion,logueado';
    const RUTA = 'usuarios';

    private $id;
    private $username;
    private $email;
    private $password;
    private $nombre;
    private $apellido;
    private $validoemail;
    private $activo;
    private $refroles;
    private $direccion;
    private $telefono;
    private $refcargos;
    private $ultimaconexion;
    private $logueado;
    private $refzonas;
    private $actualizacion_gps;

    private $error;
    private $descripcionError;

    private $roles;
    private $cargos;
    private $zonas;

    public $usuariossistemas;

    public function __construct($email,$password)
    {

        $this->id = -1;
        $this->validoemail = '0';
        $this->email = $email;
        $this->password = $password;
        $this->actualizacion_gps = 600000;
        $this->roles = new Roles();
        $this->cargos = new Cargos();
        $this->zonas = new Zonas();
    }

    public function traerTodos() {
        $db = new Database();

        $sql = "SELECT id,username,nombre,apellido,email FROM ".self::TABLA." order by 1 ";

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
  
        $sql = "select u.id , u.nombre, u.apellido, c.cargo
        from ".self::TABLA." u
        left join tbcargos c on c.id = u.refcargos
        left join tbroles r on r.id = u.refroles
        where ".$set." ";

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

    public static function exists($email){
        try{
            $db = new Database();
            $query = $db->connect()->prepare('SELECT id FROM '.self::TABLA.' WHERE email = :email');
            $query->execute( ['email' => $email]);

            error_log('emil: '.$email);
            
            if($query->rowCount() > 0){
                
                return true;
                
            }else{
                return false;
            }
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    public function login($email, $password){
        try{
            $db = new Database();
            $query = $db->connect()->prepare('SELECT id, `password`, refroles, activo, nombre,username, logueado FROM '.self::TABLA.' WHERE email = :email');
            $query->execute( ['email' => $email]);

            $data = $query->fetch(PDO::FETCH_ASSOC);

            $this->setPassword($data['password']);
            $this->setRefroles($data['refroles']);
            $this->setActivo($data['activo']);
            $this->setId($data['id']);
            $this->setNombre($data['nombre']);
            $this->setUsername($data['username']);
            $this->setLogueado($data['logueado']);
            $this->usuariossistemas = new Usuariossistemas($data['id']);
            
            if($query->rowCount() > 0){
                
                if ($this->comparePassword($password)) {
                    return true;
                } else {
                    return false;
                }
                
            }else{
                return false;
            }
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    public static function getUser($email) {
        try{
            $db = new Database();
            $query = $db->connect()->prepare('SELECT * FROM '.self::TABLA.' WHERE email = :email');
            $query->execute( ['email' => $email]);
            
            $data = $query->fetch(PDO::FETCH_ASSOC);

            $user = new Usuarios($data['email'], $data['password']);

            $user->setId($data['id']);
            $user->setNombre($data['nombre']);
            $user->setApellido($data['apellido']);
            $user->setActivo($data['activo']);
            $user->setUsername($data['username']);

            return $user;

        }catch(PDOException $e){
            echo $e;
            return null;
        }
    }

    

    public function comparePassword($password) {
        return password_verify($password, $this->password);
    }


    public function savePassword() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario

            $hash = $this->getHashedPassword($this->password);

            $query = $db->connect()->prepare('update '.self::TABLA.' set password = :password where id = :id');

            $query->execute([
                'password'      => $hash,
                'id'        => $this->id
            ]);
            
            return true;

        } catch (PDOException $e) {
            
            error_log($e->getMessage());
            return false;
            
        }
    }

    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            
            $hash = $this->getHashedPassword($this->password);

            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' (username,password,nombre,apellido,email,validoemail,activo,refroles,direccion,telefono,refcargos,ultimaconexion,logueado,refzonas,actualizacion_gps) VALUES (:username, :password, :nombre, :apellido, :email, :validoemail, :activo, :refroles,:direccion,:telefono,:refcargos,:ultimaconexion,:logueado,:refzonas,:actualizacion_gps)');

            $query->execute([
                'username'      => $this->username,
                'password'      => $hash,
                'nombre'        => $this->nombre,
                'apellido'      => $this->apellido,
                'email'         => $this->email,
                'validoemail'   => $this->validoemail,
                'activo'        => $this->activo,
                'refroles'      => $this->refroles,
                'direccion'     => $this->direccion,
                'telefono'      => $this->telefono,
                'refcargos'     => $this->refcargos,
                'ultimaconexion'     => null,
                'logueado'      => '0',
                'refzonas'      => $this->refzonas,
                'actualizacion_gps' => $this->actualizacion_gps
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
                       username,
                       refroles,
                       email,
                       nombre,
                       apellido,
                       activo,
                       validoemail,
                       direccion,telefono,refcargos,ultimaconexion,logueado,refzonas,actualizacion_gps
              FROM ".self::TABLA." where id = :idusuario";
  
        $consulta = $db->connect()->prepare($sql);
  
        $consulta->bindParam(':idusuario', $id);
  
        $consulta->execute();
  
        $res = $consulta->fetch();
  
        if($res){
            
           $this->cargar(
               $res['username'],
               $res['refroles'],
               $res['email'],
               $res['nombre'],
               $res['apellido'],
               $res['activo'],
               $res['validoemail'],
               $res['direccion'],
               $res['telefono'],
               $res['refcargos'],
               $res['ultimaconexion'],
               $res['logueado'],
               $res['refzonas'],
               $res['actualizacion_gps']
            );
           $this->setId($id);
  
        }else{
           return false;
        }
    }

    public function cargar($username,$refroles,$email,$nombre,$apellido,$activo,$validaemail,$direccion,$telefono,$refcargos,$ultimaconexion,$logueado,$refzonas,$actualizacion_gps) {

        $this->setUsername($username);
        $this->setRefroles($refroles);
        $this->setEmail($email);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setActivo($activo);
        $this->setValidoemail($validaemail);

        $this->setDireccion($direccion);
        $this->setTelefono($telefono);
        $this->setRefcargos($refcargos);
        $this->setUltimaconexion($ultimaconexion);
        $this->setLogueado($logueado);
        $this->setRefzonas($refzonas);
        $this->setActualizacion_gps($actualizacion_gps);
  
     }


    public function buscarUsuarioPorValor($campo, $valor) {
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

        $this->getCargos()->buscarPorId($this->getRefcargos());
        $this->getZonas()->buscarPorId($this->getRefzonas());
        return array(
            'username'=> $this->getUsername(),
            'nombre'=> $this->getNombre(),
            'apellido'=> $this->getApellido(),
            'email'=> $this->getEmail(),
            'activo'=> $this->getActivo(),
            'validoemail'=> $this->getValidoemail(),
            'direccion'=> $this->getDireccion(),
            'telefono'=> $this->getTelefono(),
            'refcargos'=> $this->getRefcargos(),
            'ultimaconexion'=> $this->getUltimaconexion(),
            'logueado'=> $this->getLogueado(),
            'refroles' => $this->getRefroles(),
            'cargo' => $this->getCargos()->getCargo(),
            'refzonas'=> $this->getRefzonas(),
            'zona'=> $this->getZonas()->getZona(),
            'actualizacion_gps' => $this->getActualizacion_gps()
        );
    }


    public function borrar() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario

            $query = $db->connect()->prepare('DELETE FROM '.self::TABLA.' WHERE id = :idusuario');

            $query->execute([
                'idusuario'      => $this->id
            ]);

            return true;

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
        $consulta = $db->connect()->prepare('UPDATE '.self::TABLA.' SET '.$set.' where id = :idusuarios');
  
        //die(var_dump($consulta));
        foreach ($arCampos as $key => &$val) {
           $consulta->bindParam($key, $val);
        }
        $consulta->bindParam(':idusuarios', $this->id);
  
  
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


    public function modificarPassword() {

        $db = new Database();
  
        //die(var_dump($this->reftipopersonas));
        $consulta = $db->connect()->prepare('UPDATE '.self::TABLA.' SET password=:password where id = :idusuarios');
  
        //die(var_dump($consulta));
        $consulta->bindParam(':password', $this->getHashedPassword($this->password));
        $consulta->bindParam(':idusuarios', $this->id);
  
  
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


    public function traerAjax($length, $start, $busqueda,$colSort,$colSortDir) {
        $where = '';

        $db = new Database();

       
        $busqueda = str_replace("'","",$busqueda);
        if ($busqueda != '') {
            $where = " where (t.username like '%".$busqueda."%' or t.email like '%".$busqueda."%' or t.nombre like '%".$busqueda."%' or t.apellido like '%".$busqueda."%' or z.zona like '%".$busqueda."%')";
        }
       
       
        $sql = "select
        t.id,
        t.username,
        t.nombre,
        t.apellido,
        (case when t.activo = '1' then 'Si' else 'No' end) as activo,
        r.rol,
        c.cargo,
        z.zona
        from ".self::TABLA." t
        inner join tbroles r on r.id=t.refroles
        left join tbcargos c on c.id = t.refcargos
        left join tbzonas z on z.id = t.refzonas
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
            
        $data = $queryLimit;
        
        //var_dump($dataLimit);

        $res = array($dataLimit , $data->rowCount());
        return $res;
    }


    private function getHashedPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT, array('cost'=>10));
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
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

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

    public function validarEmail() {
        if(filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        else 
        {
            return false;
        }
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    

    /**
     * Get the value of validoemail
     */ 
    public function getValidoemail()
    {
        return $this->validoemail;
    }

    /**
     * Set the value of validoemail
     *
     * @return  self
     */ 
    public function setValidoemail($validoemail)
    {
        $this->validoemail = $validoemail;

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
     * Get the value of refroles
     */ 
    public function getRefroles()
    {
        return $this->refroles;
    }

    /**
     * Set the value of refroles
     *
     * @return  self
     */ 
    public function setRefroles($refroles)
    {
        $this->refroles = $refroles;

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
     * Get the value of apellido
     */ 
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set the value of apellido
     *
     * @return  self
     */ 
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

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
     * Get the value of ultimaconexion
     */ 
    public function getUltimaconexion()
    {
        return $this->ultimaconexion;
    }

    /**
     * Set the value of ultimaconexion
     *
     * @return  self
     */ 
    public function setUltimaconexion($ultimaconexion)
    {
        $this->ultimaconexion = $ultimaconexion;

        return $this;
    }

    /**
     * Get the value of logueado
     */ 
    public function getLogueado()
    {
        return $this->logueado;
    }

    /**
     * Set the value of logueado
     *
     * @return  self
     */ 
    public function setLogueado($logueado)
    {
        $this->logueado = $logueado;

        return $this;
    }

    /**
     * Get the value of roles
     */ 
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = $roles;

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
     * Get the value of refzonas
     */ 
    public function getRefzonas()
    {
        return $this->refzonas;
    }

    /**
     * Set the value of refzonas
     *
     * @return  self
     */ 
    public function setRefzonas($refzonas)
    {
        $this->refzonas = $refzonas;

        return $this;
    }

    /**
     * Get the value of zonas
     */ 
    public function getZonas()
    {
        return $this->zonas;
    }

    /**
     * Set the value of zonas
     *
     * @return  self
     */ 
    public function setZonas($zonas)
    {
        $this->zonas = $zonas;

        return $this;
    }

    /**
     * Get the value of actualizacion_gps
     */ 
    public function getActualizacion_gps()
    {
        return $this->actualizacion_gps;
    }

    /**
     * Set the value of actualizacion_gps
     *
     * @return  self
     */ 
    public function setActualizacion_gps($actualizacion_gps)
    {
        $this->actualizacion_gps = $actualizacion_gps;

        return $this;
    }

    /**
     * Get the value of usuariossistemas
     */ 
    public function getUsuariossistemas()
    {
        return $this->usuariossistemas;
    }

    /**
     * Set the value of usuariossistemas
     *
     * @return  self
     */ 
    public function setUsuariossistemas($usuariossistemas)
    {
        $this->usuariossistemas = $usuariossistemas;

        return $this;
    }
}


?>