<?php


class Tablas {


    const TABLA = 'tbtablas';
    const CAMPOS = 'tabla,especifico,nombreid';
    const CAMPOSVAR = ':tabla,:especifico,:nombreid';
    const RUTA = 'tbtablas';

    private $id;
    private $tabla;
    private $especifico;
    private $nombreid;

    private $idreferencia;
    private $columna;

    private $error;
    private $descripcionError;

    public $arSolicitudes = [1=>'Cliente',2=>'Actividades',3=>'Nro Aviso',4=>'Clave Aviso',5=>'Descripcion'];

    public function __construct($id)
    {
        $this->id = $id;
        $this->buscarPorId($id);
        
    }

    public function getArSolicitudes($id) {
        return $this->arSolicitudes[$id];
    }

    public function devolverValor() {
        $db = new Database();
        $cadColumna = '';
        if (array_key_exists($this->getColumna(), $this->arSolicitudes)) {
            switch ($this->getColumna()) {
                case 1:
                    $cadColumna = ' c.nombre ';
                break;
                case 2:
                    $cadColumna = ' ta.actividad ';
                break;
                case 3:
                    $cadColumna = ' v.nroaviso ';
                break;
                case 4:
                    $cadColumna = ' v.claseaviso ';
                break;
                case 5:
                    $cadColumna = ' v.descripcion ';
                break;

            }
        }
        

        $sql = "SELECT ".$cadColumna." FROM dbordenestrabajodetalle otd
        inner join dbordenestrabajocabecera ot on ot.id = otd.refordenestrabajocabecera
        inner join dbsolicitudesvisitas v on v.id = ot.refsolicitudesvisitas
        inner join dbclientes c on c.id = v.refclientes
        left join dbsucursales su on su.idreferencia = v.refclientes and su.reftabla = 1 and su.id = v.refsucursales
        inner join tbsemaforo se on se.id = ot.refsemaforo
        inner join tbestados e on e.id = ot.refestados
        inner join tbtipoactividades ta on ta.id = v.reftipoactividades
        where otd.id= :idreferencia";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute([':idreferencia'=> $this->getIdreferencia()]);

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function devolverColumnasHTML() {
        $db = new Database();

        $sql = "show columns from ".$this->getTabla()." ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            $cad = '';
            foreach ($this->arSolicitudes as $row=> $value) {
                if ($row['Field'] != 'id') {
                    $cad .= '<option value="'.$row.'">'.$value.'</option>';
                }
                
            }

            return $cad;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }


    public function traerTodos() {
        $db = new Database();

        $sql = "SELECT idtabla,".self::CAMPOS." FROM ".self::TABLA." order by 1 ";

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

    $cadSet = "";

    foreach ($arCampos as $clave => $valor) {
    // $array[3] se actualizará con cada valor de $array...
    $cadSet .= "{$clave} = :{$clave} and ";
    }


    $set = substr($cadSet,0,-4);

    $sql = "select idtabla,".self::CAMPOS." from ".self::TABLA." where ".$set." ";

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

    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

    // TODO: existe el usuario
    $query = $db->connect()->prepare("INSERT INTO ".self::TABLA." (".self::CAMPOS.") VALUES (".self::CAMPOSVAR.")");

    //die(var_dump(self::CAMPOS));

    $query->execute([
    'tabla' => $this->tabla,
    'especifico' => $this->especifico,
    'nombreid' => $this->nombreid,

    ]);

    //echo $query->debugDumpParams();

    return true;

    } catch (PDOException $e) {

    //echo $query->debugDumpParams();

    error_log($e->getMessage());
    echo $e->getMessage();
    return false;

    }
    }

    public function buscarPorId($id) {
    $db = new Database();

    $sql = "SELECT idtabla,
    ".self::CAMPOS."
    FROM ".self::TABLA." where idtabla = :id";

    $consulta = $db->connect()->prepare($sql);

    $consulta->bindParam(":id", $id);

    $consulta->execute();

    $res = $consulta->fetch();



    if($res){

    $this->cargar($res["tabla"],$res["especifico"],$res["nombreid"]);
    $this->setId($id);



    }else{
    return null;
    }
    }

    public function devolverArray() {
    return array(
    'tabla' => $this->tabla,
    'especifico' => $this->especifico,
    'nombreid' => $this->nombreid,

    );
    }

    public function cargar($tabla,$especifico,$nombreid) {
    $this->setTabla($tabla);
    $this->setEspecifico($especifico);
    $this->setNombreid($nombreid);

    }


    public function borrar() {
    $db = new Database();
    try {

    $query = $db->connect()->prepare("DELETE FROM ".self::TABLA." WHERE idtabla = :id");

    try {
    $query->execute([
    "id" => $this->id
    ]);

    $this->setError(0);

    }catch(PDOException $e){
    $this->setError(1);
    $this->setDescripcionError("Ha surgido un error y no se puede modificar");


    }

    } catch (PDOException $e) {

    error_log($e->getMessage());
    return false;

    }
    }

    public function modificarFilter($arCampos) {

    $db = new Database();

    $cadSet = "";

    foreach ($arCampos as $clave => $valor) {
    // $array[3] se actualizará con cada valor de $array...
    $cadSet .= "{$clave} = :{$clave},";

    }

    $set = substr($cadSet,0,-1);

    $consulta = $db->connect()->prepare("UPDATE ".self::TABLA." SET ".$set." where idtabla = :id");

    //die(var_dump($consulta));
    foreach ($arCampos as $key => &$val) {
    $consulta->bindParam($key, $val);
    }
    $consulta->bindParam(":id", $this->id);


    try {
    $consulta->execute();

    $this->setError(0);
    }catch(PDOException $e){
    $this->setError(1);
    $this->setDescripcionError("Ha surgido un error y no se puede modificar ");


    }

    //$this->setIdendoso($conexion->lastInsertId());

    $db = null;
    }



    public function getTabla()
    {
    return $this->tabla;
    }

    public function setTabla($tabla)
    {
    $this->tabla = $tabla;

    return $this;
    }

    public function getEspecifico()
    {
    return $this->especifico;
    }

    public function setEspecifico($especifico)
    {
    $this->especifico = $especifico;

    return $this;
    }

    public function getNombreid()
    {
    return $this->nombreid;
    }

    public function setNombreid($nombreid)
    {
    $this->nombreid = $nombreid;

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
     * Get the value of idreferencia
     */ 
    public function getIdreferencia()
    {
        return $this->idreferencia;
    }

    /**
     * Set the value of idreferencia
     *
     * @return  self
     */ 
    public function setIdreferencia($idreferencia)
    {
        $this->idreferencia = $idreferencia;

        return $this;
    }

    /**
     * Get the value of columna
     */ 
    public function getColumna()
    {
        return $this->columna;
    }

    /**
     * Set the value of columna
     *
     * @return  self
     */ 
    public function setColumna($columna)
    {
        $this->columna = $columna;

        return $this;
    }
}