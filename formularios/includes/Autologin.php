<?php


class Autologin {
    const TABLA = 'dbautologin';

    private $id;
    private $refusuarios;
    private $token;
    private $url;
    private $usado;
    private $email;
    private $nombrecompleto;
    //private Usuarios $usuarios;
    private $usuarios;

    private $error;
    private $descripcionError;

    public function __construct($refusuarios, $url, $email, $nombrecompleto)
    {
        
        $this->usuarios = new Usuarios($email,'');
        $this->refusuarios = $refusuarios;
        $this->url = $url;
        $this->email = $email;
        $this->nombrecompleto = $nombrecompleto;
        $this->usado = '0';
        
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

    public function traerToken($token) {
        try{
            $db = new Database();
            $query = $db->connect()->prepare('SELECT refusuarios,id FROM '.self::TABLA.' WHERE token = :token');
            $query->execute( ['token' => $token]);
            
            $data = $query->fetch(PDO::FETCH_ASSOC);

            

            //$autologin = new Autologin(0,'','','');

            if($query->rowCount() > 0){
                
                $this->setRefusuarios($data['refusuarios']);
                $this->setId($data['id']);
                $this->usuarios->buscarPorId($data['refusuarios']);
            } else {
                $this->setRefusuarios(0);
            }

            return true;

        }catch(PDOException $e){
            echo $e;
            return null;
        }
    }

    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario

            $this->setToken();
            //getUser($this->email
            if ($this->nombrecompleto == 'recupero') {
                //$user = $this->getUsuarios()::getUser($this->email);

                $user = $this->getUsuarios()->buscarUsuarioPorValor('email',$this->email);
                $this->setRefusuarios($this->getUsuarios()->getId());
            }

            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' (refusuarios,token,url,usado,email,nombrecompleto) VALUES (:refusuarios, :token, :url, :usado, :email, :nombrecompleto)');

            $query->execute([
                'refusuarios'      => $this->refusuarios,
                'token'      => $this->token,
                'url'=> $this->url,
                'usado'         => $this->usado,
                'email'   => $this->email,
                'nombrecompleto'        => $this->nombrecompleto
            ]);

            return true;

        } catch (PDOException $e) {
            
            error_log($e->getMessage());
            return false;
            
        }
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
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    public function GUID()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken()
    {
        $this->token = $this->GUID();

        return $this;
    }

    /**
     * Get the value of url
     */ 
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @return  self
     */ 
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the value of usado
     */ 
    public function getUsado()
    {
        return $this->usado;
    }

    /**
     * Set the value of usado
     *
     * @return  self
     */ 
    public function setUsado($usado)
    {
        $this->usado = $usado;

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
     * Get the value of nombrecompleto
     */ 
    public function getNombrecompleto()
    {
        return $this->nombrecompleto;
    }

    /**
     * Set the value of nombrecompleto
     *
     * @return  self
     */ 
    public function setNombrecompleto($nombrecompleto)
    {
        $this->nombrecompleto = $nombrecompleto;

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
}


?>