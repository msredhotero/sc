<?php

class Roles {
    const TABLA = 'tbroles';

    private $id;
    private $rol;
    private $activo;

    public function __construct()
    {
        
    }

    public function traerRoles() {
        $db = new Database();

        $sql = "select id,rol from ".self::TABLA." ";

        $consulta = $db->connect()->prepare($sql);

        $consulta->execute();

        $res = $consulta->fetchAll();

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
     * Get the value of rol
     */ 
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set the value of rol
     *
     * @return  self
     */ 
    public function setRol($rol)
    {
        $this->rol = $rol;

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
}



?>