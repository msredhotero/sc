<?php


class Fakegeolocalizacion {

    const TABLA = 'dbfakegeolocalizacion';
    const CAMPOS = 'refusuarios,fecha';
    const CAMPOSVAR = ':refusuarios,:fecha';
    const RUTA = 'dbfakegeolocalizacion';

    private $id;
    private $refusuarios;
    private $fecha;

    private $error;
    private $descripcionError;


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


    public function traerTodosFilter($arCampos) {
    $db = new Database();

    $cadSet = "";

    foreach ($arCampos as $clave => $valor) {
    // $array[3] se actualizará con cada valor de $array...
    $cadSet .= "{$clave} = :{$clave} and ";
    }


    $set = substr($cadSet,0,-4);

    $sql = "select id,".self::CAMPOS." from ".self::TABLA." where ".$set." ";

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
    'refusuarios' => $this->refusuarios,
    'fecha' => $this->fecha,

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

    $sql = "SELECT id,
    ".self::CAMPOS."
    FROM ".self::TABLA." where id = :id";

    $consulta = $db->connect()->prepare($sql);

    $consulta->bindParam(":id", $id);

    $consulta->execute();

    $res = $consulta->fetch();



    if($res){

    $this->cargar($res["refusuarios"],$res["fecha"]);
    $this->setId($id);



    }else{
    return null;
    }
    }

    public function devolverArray() {
    return array(
    'refusuarios' => $this->refusuarios,
    'fecha' => $this->fecha,

    );
    }

    public function cargar($refusuarios,$fecha) {
    $this->setRefusuarios($refusuarios);
    $this->setFecha($fecha);

    }


    public function borrar() {
    $db = new Database();
    try {

    $query = $db->connect()->prepare("DELETE FROM ".self::TABLA." WHERE id = :id");

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

    $consulta = $db->connect()->prepare("UPDATE ".self::TABLA." SET ".$set." where id = :id");

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


    public function getId()
    {
    return $this->id;
    }

    public function setId($id)
    {
    $this->id = $id;

    return $this;
    }

    public function getRefusuarios()
    {
    return $this->refusuarios;
    }

    public function setRefusuarios($refusuarios)
    {
    $this->refusuarios = $refusuarios;

    return $this;
    }

    public function getFecha()
    {
    return $this->fecha;
    }

    public function setFecha($fecha)
    {
    $this->fecha = $fecha;

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