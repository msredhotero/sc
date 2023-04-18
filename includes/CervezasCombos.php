<?php

class CervezasCombos {
    
    
    private $id;
    private $refcervezas;
    private $refcombos;
    private $valor;
    private $tabla;

    private $error;
    private $descripcionError;

    private $cervezas;
    private $maridages;
    private $skills;

    public function __construct($refcombos, $tabla, $valor)
    {   
        $this->tabla = $tabla;
        $this->refcombos = $refcombos;
        $this->valor = $valor;
        $this->cervezas = new Cervezas();
        $this->maridages = new Maridages();
        $this->skills = new Skills();
    }

    public function traerTodosSeleccionados() {
        $db = new Database();


        $sql = "select 
            t.id,t.refcervezas,t.".$this->refcombos."
        from ".$this->tabla." t 
        
        where t.refcervezas = ".$this->refcervezas."
        order by t.id";

        $consulta = $db->connect()->prepare($sql);

        $consulta->execute();

        $resultado = $consulta->fetchAll();

        return $resultado;
    }

    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            $query = $db->connect()->prepare('INSERT INTO '.$this->tabla.' (refcervezas,'.$this->refcombos.') VALUES (:refcervezas,:'.$this->refcombos.')');

            $query->execute([
                $this->refcombos      => $this->valor,
                'refcervezas' => $this->refcervezas
            ]);

            return true;

        } catch (PDOException $e) {
            
            error_log($e->getMessage());
            return false;
            
        }
    }

    public function borrar() {
        $db = new Database();
        try {

            $query = $db->connect()->prepare('DELETE FROM '.$this->tabla.' WHERE id = :id');

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

    public function borrarPorCerveza() {
        $db = new Database();
        try {

            $query = $db->connect()->prepare('DELETE FROM '.$this->tabla.' WHERE refcervezas = :refcervezas');

            try {
                $query->execute([
                    'refcervezas'      => $this->refcervezas
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
     * Get the value of refcombos
     */ 
    public function getRefcombos()
    {
        return $this->refcombos;
    }

    /**
     * Set the value of refcombos
     *
     * @return  self
     */ 
    public function setRefcombos($refcombos)
    {
        $this->refcombos = $refcombos;

        return $this;
    }

    /**
     * Get the value of tabla
     */ 
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * Set the value of tabla
     *
     * @return  self
     */ 
    public function setTabla($tabla)
    {
        $this->tabla = $tabla;

        return $this;
    }

    /**
     * Get the value of valor
     */ 
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @return  self
     */ 
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get the value of cervezas
     */ 
    public function getCervezas()
    {
        return $this->cervezas;
    }

    /**
     * Set the value of cervezas
     *
     * @return  self
     */ 
    public function setCervezas($cervezas)
    {
        $this->cervezas = $cervezas;

        return $this;
    }

    /**
     * Get the value of maridages
     */ 
    public function getMaridages()
    {
        return $this->maridages;
    }

    /**
     * Set the value of maridages
     *
     * @return  self
     */ 
    public function setMaridages($maridages)
    {
        $this->maridages = $maridages;

        return $this;
    }

    /**
     * Get the value of skills
     */ 
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set the value of skills
     *
     * @return  self
     */ 
    public function setSkills($skills)
    {
        $this->skills = $skills;

        return $this;
    }
}


?>