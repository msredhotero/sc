<?php


class Documentaciones {

    const TABLA = 'dbdocumentaciones';
    const CAMPOS = 'reftabla,idreferencia,tipo,archivo,carpeta,fechacrea';
    const CAMPOSVAR = ':reftabla,:idreferencia,:tipo,:archivo,:carpeta,:fechacrea';
    const RUTA = 'documentaciones';

    private $id;
    private $reftabla;
    private $idreferencia;
    private $tipo;
    private $archivo;
    private $carpeta;
    private $fechacrea;

    private $error;
    private $descripcionError;

    public function __construct($reftabla,$idreferencia)
    {
        $this->reftabla = $reftabla;
        $this->idreferencia = $idreferencia;
    }

    public static function borrarArchivo($direccion) {
        if (unlink($direccion)) {
            return true;
        } else {
            return false;
        }
    }

    public function storeImage( $photo, $carpeta) {
        $target_dir = "../../data/".$carpeta.'/'.$this->idreferencia.'/';
        if (!file_exists("../../data/".$carpeta.'/')) {
            mkdir("../../data/".$carpeta.'/', 0777);
        }

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777);
        }
        $extarr     = explode('.', $photo['name']);
        $filename   = $extarr[sizeof($extarr)-2];
        $ext        = $extarr[sizeof($extarr)-1];
        $hash       = md5(date('Ymdgi').$filename).'.'.$ext;
        $target_file= $target_dir.$hash;
        $uploadOk   = 1;
        //$check      = getimagesize($photo['tmp_name']);

        //if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            //$uploadOk = 1;
        //} else {
            //echo "File is not an image.";
            //$uploadOk = 0;
        //}

        if ($uploadOk == 0) {
            //echo "Sorry, your file was not uploaded.";
            //$this->redirect('user', ['error' => Errors::ERROR_USER_UPDATEPHOTO_FORMAT]);
        // if everything is ok, try to upload file
            return '';
        } else {
            if (move_uploaded_file($photo["tmp_name"], $target_file)) {
                $this->setArchivo($hash);
                $this->setTipo($ext);
                $this->setCarpeta($carpeta);
                
                return $hash;
            } else {
                return "";
            }
        }
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
    
    
    public function save() {
        $db = new Database();
        try {
            
            // TODO: existe el usuario
            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

            $query->execute([
                'reftabla'      => $this->reftabla,
                'idreferencia'  => $this->idreferencia,
                'tipo'      => $this->tipo,
                'archivo'      => $this->archivo,
                'carpeta'          => $this->carpeta,
                'fechacrea' => date('Y-m-d H:i:s')
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
                       ".self::CAMPOS."
              FROM ".self::TABLA." where id = :id";
  
        $consulta = $db->connect()->prepare($sql);
  
        $consulta->bindParam(':id', $id);
  
        $consulta->execute();
  
        $res = $consulta->fetch();

        if($res){
            
           $this->cargar($res['tipo'],$res['archivo'],$res['carpeta'],$res['fechacrea']);
           $this->setId($id);

        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'tipo'      => $this->tipo,
            'archivo'      => $this->archivo,
            'carpeta'          => $this->carpeta,
            'fechacrea'          => $this->fechacrea
        );
    }

    public function cargar($tipo,$archivo,$carpeta,$fechacrea) {
        $this->setTipo($tipo);
        $this->setArchivo($archivo);
        $this->setCarpeta($carpeta);
        $this->setFechacrea($fechacrea);
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

    public function buscarPorValor($arCampos) {
        $db = new Database();

        $cadSet = '';
        $set = '';
  
        foreach ($arCampos as $clave => $valor) {
        // $array[3] se actualizará con cada valor de $array...
           $cadSet .= "{$clave} = :{$clave} and ";
        }

        $set = substr($cadSet,0,-4);
  
        $sql = "select id from ".self::TABLA." where ".$set." ";
  
        $consulta = $db->connect()->prepare($sql);
        foreach ($arCampos as $key => &$val) {
            $consulta->bindParam($key, $val);
         }

        
  
        $consulta->execute();
  
        $res = $consulta->fetch();
  
        if($res){
  
           $this->buscarPorId($res['id']);
  
        }else{
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
            $where = " and (concat(a.activo, ' ', m.marca, ' ', c.modelo, ' ', c.anio, ' ', patente)  like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        concat(a.activo, ' ', m.marca, ' ', c.modelo, ' ', c.anio, ' ', patente) as activo,
        t.tipo,
        t.archivo,
        t.fechacrea
        from ".self::TABLA." t 
        join    tbtablas tt on tt.idtabla = t.reftabla and t.idreferencia = ".$this->idreferencia."
        inner join dbcamiones c on c.id = t.refcamiones
        inner join tbactivos a on a.id = c.refactivos
        inner join tbmarcas m on m.id = c.refmarcas
        where tt.idtabla = ".$this->reftabla." ".$where."
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

    /**
     * Get the value of archivo
     */ 
    public function getArchivoCompleto()
    {
        return 'data/'.$this->carpeta.'/'.$this->idreferencia.'/'.$this->archivo;
    }

    /**
     * Get the value of archivo
     */ 
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set the value of archivo
     *
     * @return  self
     */ 
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get the value of carpeta
     */ 
    public function getCarpeta()
    {
        return $this->carpeta;
    }

    /**
     * Set the value of carpeta
     *
     * @return  self
     */ 
    public function setCarpeta($carpeta)
    {
        $this->carpeta = $carpeta;

        return $this;
    }

    /**
     * Get the value of fechacrea
     */ 
    public function getFechacrea()
    {
        return $this->fechacrea;
    }

    /**
     * Set the value of fechacrea
     *
     * @return  self
     */ 
    public function setFechacrea($fechacrea)
    {
        $this->fechacrea = $fechacrea;

        return $this;
    }

    /**
     * Get the value of reftabla
     */ 
    public function getReftabla()
    {
        return $this->reftabla;
    }

    /**
     * Set the value of reftabla
     *
     * @return  self
     */ 
    public function setReftabla($reftabla)
    {
        $this->reftabla = $reftabla;

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

    public function sanear_string($string)
    {

        $string = trim($string);

        $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
        );

        $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
        );

        $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
        );

        $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
        );

        $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
        );

        $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
        );

        $string = str_replace(
        array('(', ')', '{', '}',' '),
        array('', '', '', '',''),
        $string
        );

        return $string;
    }
}
    

?>