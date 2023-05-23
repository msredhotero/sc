<?php


include('Database.php');

class Construir {

    public $tabla;
    
    private $campos;
    private $camposBind;
    private $camposBindSet;
    private $camposVar;
    private $variables;
    private $gettersetter;
    private $setvariables;
    private $getvariables;

    private $tabla_id;

    public function __construct($tabla)
    {
        $this->setTabla($tabla);
        $this->generar();
    }

    public function variablesTodas() {
        $cad = "
        const TABLA = '".$this->tabla."';<br>
        const CAMPOS = '".substr($this->getCampos(),0,-1)."';<br>
        const CAMPOSVAR = '".substr($this->getCamposVar(),0,-1)."';<br>
        const RUTA = '".$this->tabla."';<br><br>
        ".$this->getVariables()."<br>
        ";

        return $cad;
    }

    public function GT() {
        $cad = "
        ".$this->getGettersetter()."<br>
        ";

        return $cad;
    }

    public function methods() {
        $cad = '
        public function traerTodos() {<br>
            $db = new Database();<br>
            <br>
            $sql = "SELECT '.$this->getTabla_id().',".self::CAMPOS." FROM ".self::TABLA." order by 1 ";<br>
            <br>
            try {<br>
                $consulta = $db->connect()->prepare($sql);<br>
                <br>
                $consulta->execute();<br>
                <br>
                $resultado = $consulta->fetchAll();<br>
                <br>
                return $resultado;<br>
                <br>
            } catch (PDOException $e) {<br>
                return $e->getMessage();<br>
            }<br>
            <br>
        }<br>
        <br>
        <br>
        public function traerTodosFilter($arCampos) {<br>
            $db = new Database();<br>
            <br>
            $cadSet = "";<br>
            <br>
            foreach ($arCampos as $clave => $valor) {<br>
            // $array[3] se actualizará con cada valor de $array...<br>
               $cadSet .= "{$clave} = :{$clave} and ";<br>
            }<br>
            <br>
            <br>
            $set = substr($cadSet,0,-4);<br>
            <br>
            $sql = "select '.$this->getTabla_id().',".self::CAMPOS." from ".self::TABLA." where ".$set." ";<br>
            <br>
            //die(var_dump($set));<br>
            <br>
            $consulta = $db->connect()->prepare($sql);<br>
            foreach ($arCampos as $key => &$val) {<br>
                $consulta->bindParam($key, $val);<br>
            }<br>
            <br>
            try {<br>
                $consulta = $db->connect()->prepare($sql);<br>
                <br>
                foreach ($arCampos as $key => &$val) {<br>
                    //die(var_dump($val));<br>
                    $consulta->bindParam($key, $val);<br>
                }<br>
                <br>
                $consulta->execute();<br>
                <br>
                $resultado = $consulta->fetchAll();<br>
                <br>
                return $resultado;<br>
                <br>
            } catch (PDOException $e) {<br>
                return $e->getMessage();<br>
            }<br>
            <br>
        }<br>
        <br>
        public function save() {<br>
            $db = new Database();<br>
            try {<br>
                <br>
                //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);<br>
                <br>
                // TODO: existe el usuario<br>
                $query = $db->connect()->prepare("INSERT INTO ".self::TABLA." (".self::CAMPOS.") VALUES (".self::CAMPOSVAR.")");<br>
                <br>
                //die(var_dump(self::CAMPOS));<br>
                <br>
                $query->execute([<br>
                    '.$this->getCamposBind().'<br>
                ]);<br>
                <br>
                //echo $query->debugDumpParams();<br>
                <br>
                return true;<br>
                <br>
            } catch (PDOException $e) {<br>
                <br>
                //echo $query->debugDumpParams();<br>
                <br>
                error_log($e->getMessage());<br>
                echo $e->getMessage();<br>
                return false;<br>
                <br>
            }<br>
        }<br>
        <br>
        public function buscarPorId($id) {<br>
            $db = new Database();<br>
            <br>
            $sql = "SELECT '.$this->getTabla_id().',<br>
                           ".self::CAMPOS."<br>
                  FROM ".self::TABLA." where '.$this->getTabla_id().' = :id";<br>
                  <br>
            $consulta = $db->connect()->prepare($sql);<br>
            <br>
            $consulta->bindParam(":id", $id);<br>
            <br>
            $consulta->execute();<br>
            <br>
            $res = $consulta->fetch();<br>
            <br>
            <br>
            <br>
            if($res){<br>
                <br>
               $this->cargar('.substr($this->getSetvariables(),0,-1).');<br>
               $this->setId($id);<br>
               <br>
               <br>
               <br>
            }else{<br>
               return null;<br>
            }<br>
        }<br>
        <br>
        public function devolverArray() {<br>
            return array(<br>
                '.$this->getCamposBind().'<br>
            );<br>
        }<br>
        <br>
        public function cargar('.substr($this->getGetvariables(),0,-1).') {<br>
            '.$this->getCamposBindSet().'<br>
        }<br>
        <br>
        <br>
        public function borrar() {<br>
            $db = new Database();<br>
            try {<br>
                <br>
                $query = $db->connect()->prepare("DELETE FROM ".self::TABLA." WHERE '.$this->getTabla_id().' = :id");<br>
                <br>
                try {<br>
                    $query->execute([<br>
                        "id"      => $this->id<br>
                    ]);<br>
                    <br>
                    $this->setError(0);<br>
                    <br>
                }catch(PDOException $e){<br>
                    $this->setError(1);<br>
                    $this->setDescripcionError("Ha surgido un error y no se puede modificar");<br>
                    <br>
                    <br>
                }<br>
                <br>
            } catch (PDOException $e) {<br>
                <br>
                error_log($e->getMessage());<br>
                return false;<br>
                <br>
            }<br>
        }<br>
        <br>
        public function modificarFilter($arCampos) {<br>
            <br>
            $db = new Database();<br>
            <br>
            $cadSet = "";<br>
            <br>
            foreach ($arCampos as $clave => $valor) {<br>
            // $array[3] se actualizará con cada valor de $array...<br>
               $cadSet .= "{$clave} = :{$clave},";<br>
               <br>
            }<br>
            <br>
            $set = substr($cadSet,0,-1);<br>
            <br>
            $consulta = $db->connect()->prepare("UPDATE ".self::TABLA." SET ".$set." where '.$this->getTabla_id().' = :id");<br>
            <br>
            //die(var_dump($consulta));<br>
            foreach ($arCampos as $key => &$val) {<br>
               $consulta->bindParam($key, $val);<br>
            }<br>
            $consulta->bindParam(":id", $this->id);<br>
            <br>
            <br>
            try {<br>
                $consulta->execute();<br>
                <br>
                $this->setError(0);<br>
            }catch(PDOException $e){<br>
               $this->setError(1);<br>
               $this->setDescripcionError("Ha surgido un error y no se puede modificar ");<br>
               <br>
               <br>
            }<br>
            <br>
            //$this->setIdendoso($conexion->lastInsertId());<br>
            <br>
            $db = null;<br>
        }<br>
        <br>
        ';

        return $cad;
    }

    public function generar() {
        $db = new Database();

        $sql = "show columns from ".$this->tabla." ";

        $cadColumnas = '';
        $cadColumnasVar = '';
        $cadColumnasBind = '';
        $cadColumnasBindSet = '';

        $cadVariables = '';
        $cadGT = '';
        $cadSetVariables = '';
        $cadGetVariables = '';

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            $primero = 0;

            foreach ($resultado as $rowC) {
                if ($primero != 0) {

                
                    $cadColumnas .= $rowC[0].',';
                    $cadColumnasVar .= ':'.$rowC[0].',';
                    $cadColumnasBind .= "'".$rowC[0]."'"." => $"."this->".$rowC[0].",<br>";
                    $cadSetVariables .= '$res["'.$rowC[0].'"],';
                    $cadGetVariables .= '$'.$rowC[0].',';
                    $cadColumnasBindSet .= '$this->set'.ucfirst($rowC[0]).'($'.$rowC[0].');<br>';
                }

                $cadVariables .= "private $".$rowC[0].';<br>';
                $cadGT .= "
                public function get".ucfirst($rowC[0])."()<br>
                {<br>
                    return $"."this->".$rowC[0].";<br>
                }<br>
                <br>
                public function set".ucfirst($rowC[0])."($".$rowC[0].")<br>
                {<br>
                    $"."this->".$rowC[0]." = $".$rowC[0].";<br>
                    <br>
                    return $"."this;<br>
                }<br><br>";

                if ($primero==0) {
                    $this->setTabla_id($rowC[0]);
                }

                $primero = 1;
            }

            $this->setCampos($cadColumnas);
            $this->setCamposVar($cadColumnasVar);
            $this->setCamposBind($cadColumnasBind);
            $this->setCamposBindSet($cadColumnasBindSet);

            $this->setVariables($cadVariables);
            $this->setGettersetter($cadGT);
            $this->setSetvariables($cadSetVariables);
            $this->setGetvariables($cadGetVariables);

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get the value of campos
     */ 
    public function getCampos()
    {
        return $this->campos;
    }

    /**
     * Set the value of campos
     *
     * @return  self
     */ 
    public function setCampos($campos)
    {
        $this->campos = $campos;

        return $this;
    }

    /**
     * Get the value of camposBind
     */ 
    public function getCamposBind()
    {
        return $this->camposBind;
    }

    /**
     * Set the value of camposBind
     *
     * @return  self
     */ 
    public function setCamposBind($camposBind)
    {
        $this->camposBind = $camposBind;

        return $this;
    }

    /**
     * Get the value of camposVar
     */ 
    public function getCamposVar()
    {
        return $this->camposVar;
    }

    /**
     * Set the value of camposVar
     *
     * @return  self
     */ 
    public function setCamposVar($camposVar)
    {
        $this->camposVar = $camposVar;

        return $this;
    }

    /**
     * Get the value of variables
     */ 
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set the value of variables
     *
     * @return  self
     */ 
    public function setVariables($variables)
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Get the value of gettersetter
     */ 
    public function getGettersetter()
    {
        return $this->gettersetter;
    }

    /**
     * Set the value of gettersetter
     *
     * @return  self
     */ 
    public function setGettersetter($gettersetter)
    {
        $this->gettersetter = $gettersetter;

        return $this;
    }

    /**
     * Get the value of setvariables
     */ 
    public function getSetvariables()
    {
        return $this->setvariables;
    }

    /**
     * Set the value of setvariables
     *
     * @return  self
     */ 
    public function setSetvariables($setvariables)
    {
        $this->setvariables = $setvariables;

        return $this;
    }

    /**
     * Get the value of getvariables
     */ 
    public function getGetvariables()
    {
        return $this->getvariables;
    }

    /**
     * Set the value of getvariables
     *
     * @return  self
     */ 
    public function setGetvariables($getvariables)
    {
        $this->getvariables = $getvariables;

        return $this;
    }

    /**
     * Get the value of camposBindSet
     */ 
    public function getCamposBindSet()
    {
        return $this->camposBindSet;
    }

    /**
     * Set the value of camposBindSet
     *
     * @return  self
     */ 
    public function setCamposBindSet($camposBindSet)
    {
        $this->camposBindSet = $camposBindSet;

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
     * Get the value of tabla_id
     */ 
    public function getTabla_id()
    {
        return $this->tabla_id;
    }

    /**
     * Set the value of tabla_id
     *
     * @return  self
     */ 
    public function setTabla_id($tabla_id)
    {
        $this->tabla_id = $tabla_id;

        return $this;
    }
}