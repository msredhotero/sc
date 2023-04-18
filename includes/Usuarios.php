<?php

class Usuarios {
    const TABLA = 'dbusuarios';
    const CAMPOS = 'username,email,password,nombre,apellido,validoemail,activo,refroles';
    const ELIMINARACCION = 'eliminarUsuarios';

    private $id;
    private $username;
    private $email;
    private $password;
    private $nombre;
    private $apellido;
    private $validoemail;
    private $activo;
    private $refroles;
    private $error;
    private $descripcionError;


    public function __construct($email,$password)
    {

        $this->id = -1;
        $this->validoemail = '0';
        $this->email = $email;
        $this->password = $password;
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
            $query = $db->connect()->prepare('SELECT id, `password`, refroles, activo, nombre,username FROM '.self::TABLA.' WHERE email = :email');
            $query->execute( ['email' => $email]);

            $data = $query->fetch(PDO::FETCH_ASSOC);

            $this->setPassword($data['password']);
            $this->setRefroles($data['refroles']);
            $this->setActivo($data['activo']);
            $this->setId($data['id']);
            $this->setNombre($data['nombre']);
            $this->setUsername($data['username']);
            
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

            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' (username,password,nombre,apellido,email,validoemail,activo,refroles) VALUES (:username, :password, :nombre, :apellido, :email, :validoemail, :activo, :refroles)');

            $query->execute([
                'username'      => $this->username,
                'password'      => $hash,
                'nombre'        => $this->nombre,
                'apellido'      => $this->apellido,
                'email'         => $this->email,
                'validoemail'   => $this->validoemail,
                'activo'        => $this->activo,
                'refroles'      => $this->refroles
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
                       validoemail
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
               $res['validoemail']);
           $this->setId($id);
  
        }else{
           return false;
        }
    }

    public function cargar($username,$refroles,$email,$nombre,$apellido,$activo,$validaemail) {

        $this->setUsername($username);
        $this->setRefroles($refroles);
        $this->setEmail($email);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setActivo($activo);
        $this->setValidoemail($validaemail);
  
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
        return array(
            'username'=> $this->getUsername(),
            'nombre'=> $this->getNombre(),
            'apellido'=> $this->getApellido(),
            'email'=> $this->getEmail(),
            'activo'=> $this->getActivo(),
            'validoemail'=> $this->getValidoemail(),
            'refroles'=> $this->getRefroles()
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


    public function traerAjax($length, $start, $busqueda,$colSort,$colSortDir) {
        $where = '';

        $db = new Database();

       
        $busqueda = str_replace("'","",$busqueda);
        if ($busqueda != '') {
            $where = " where (t.username like '%".$busqueda."%' or t.email like '%".$busqueda."%' or t.nombre like '%".$busqueda."%' or t.apellido like '%".$busqueda."%')";
        }
       
       
        $sql = "select
        t.id,
        t.username,
        t.email,
        t.nombre,
        t.apellido,
        (case when t.activo = '1' then 'Si' else 'No' end) as activo,
        (case when t.validoemail = '1' then 'Si' else 'No' end) as validoemail,
        r.rol
        from ".self::TABLA." t
        inner join tbroles r on r.id=t.refroles
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
}


?>