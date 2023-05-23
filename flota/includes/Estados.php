<?php

class Estados {

    const TABLA = 'tbestados';
    const CAMPOS = 'estado';
    const CAMPOSVAR = ':estado';
    const ELIMINARACCION = 'eliminarEstados';

    private $id;
    private $estado;
    private $tipo;

    public function __construct()
    {
        
    }

    public function nivelesByTipo() {
        //jefe de mecanicos
        if ($this->tipo == 6) {
            switch ($this->id) {
                case 1:
                    return $this->traerTodosFilter(['id'=>'1,2,3,4,5']);
                break;
                case 2:
                    return $this->traerTodosFilter(['id'=>'2,3,4,5']);
                break;
                case 3:
                    return $this->traerTodosFilter(['id'=>'3,4,5']);
                break;
                case 4:
                    return $this->traerTodosFilter(['id'=>'4']);
                break;
                case 5:
                    return $this->traerTodosFilter(['id'=>'5']);
                break;
            }
        }
        if ($this->tipo == 5) {
            switch ($this->id) {
                case 1:
                    return $this->traerTodosFilter(['id'=>'1,2,3']);
                break;
                case 2:
                    return $this->traerTodosFilter(['id'=>'2,3']);
                break;
                case 3:
                    return $this->traerTodosFilter(['id'=>'3']);
                break;
                case 4:
                    return $this->traerTodosFilter(['id'=>'4']);
                break;
                case 5:
                    return $this->traerTodosFilter(['id'=>'5']);
                break;
            }
        }
        
    }


    public function traerTodos() {
        $db = new Database();

        $sql = "select id,estado from tbestados order by 1";

        $consulta = $db->connect()->prepare($sql);

        $consulta->execute();

        $resultado = $consulta->fetchAll();

        return $resultado;
    }

    public function traerTodosFilter($options) {
        $db = new Database();
        $where = '';
        if (isset($options['id'])) {
            $where .= " where id in (".$options['id'].")";
        }

        $sql = "SELECT 
            id,estado
            FROM ".self::TABLA." 
            ".$where." order by 1 ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
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
            
           $this->cargar($res['estado']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function cargar($estado) {

        $this->setEstado($estado);
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
     * Get the value of estado
     */ 
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */ 
    public function setEstado($estado)
    {
        $this->estado = $estado;

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
}

?>