<?php


class Formulariosdetalles {

    const TABLA = 'dbformulariosdetalles';
    const CAMPOS = 'reftabla,idreferencia,refformulariosconector,refpreguntascuestionario,refrespuestascuestionario,pregunta,respuesta,reftiporespuesta,archivo,tipo,carpeta,latitud,longitud,fechacrea,usuariocrea,reftabladatos,columna,refmateriales,cantidad';
    const CAMPOSVAR = ':reftabla,:idreferencia,:refformulariosconector,:refpreguntascuestionario,:refrespuestascuestionario,:pregunta,:respuesta,:reftiporespuesta,:archivo,:tipo,:carpeta,:latitud,:longitud,:fechacrea,:usuariocrea,:reftabladatos,:columna,:refmateriales,:cantidad';
    const RUTA = 'formulariosdetalles';

    private $id;
    private $reftabla;
    private $idreferencia;
    private $refformulariosconector;
    private $refpreguntascuestionario;
    private $refrespuestascuestionario;
    private $pregunta;
    private $respuesta;
    private $reftiporespuesta;
    private $archivo;
    private $tipo;
    private $carpeta;
    private $latitud;
    private $longitud;
    private $fechacrea;
    private $usuariocrea;
    private $reftabladatos;
    private $columna;
    private $refmateriales;
    private $cantidad;

    private $preguntascuestionario;
    private $formulariosconector;

    private $error;
    private $descripcionError;


    public function __construct($usuariocrea)
    {
        $this->usuariocrea = $usuariocrea;
        $this->preguntascuestionario = new Preguntascuestionario();
        $this->formulariosconector = new Formulariosconector(3,0);

    }

    /*
    * @Descripcion: verifica si el formulario tienen todas las respuestas cargadas para finalizarlo
    * @Param: $reftabla, $$idreferencia
    *
    */
    public function puedeFinalizarFormulario() {
        //cantidad de respuestas cargadas
        $resRespuestas = $this->traerPorReferenciaAgrupado();

        //obtengo el formulario para buscar las preguntas
        $this->getFormulariosconector()->buscarPorId($this->getRefformulariosconector());

        //obtengo las preguntas
        $resPreguntas = $this->getPreguntascuestionario()->traerTodosFilter(array('refformularios'=> $this->getFormulariosconector()->getRefFormularios()));

        //die(var_dump(count($resRespuestas)));

        if (count($resRespuestas) >= count($resPreguntas)) {
            return true;
        }

        return false;


    }


    public function traerPorReferenciaAgrupado() {
        $db = new Database();
        try {

            $query = $db->connect()->prepare('select 
            refpreguntascuestionario 
            FROM '.self::TABLA.' 
            WHERE reftabla = :reftabla and idreferencia = :idreferencia and refformulariosconector = :refformulariosconector 
            group by refpreguntascuestionario');

            try {
                $query->execute([
                    'reftabla'      => $this->reftabla,
                    'idreferencia'      => $this->idreferencia,
                    'refformulariosconector'      => $this->refformulariosconector,
                ]);

                $resultado = $query->fetchAll();

                return $resultado;

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

    public static function borrarArchivo($direccion) {
        if (unlink($direccion)) {
            return true;
        } else {
            return false;
        }
    }

    public function storeImage( $photo, $carpeta) {
        $target_dir = "../../data/ot/";
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
                $this->setRespuesta($hash);
                $this->setTipo($ext);
                $this->setCarpeta($carpeta);
                
                //return $hash;
            } else {
                //return "";
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

            $db->connect()->query("SET wait_timeout=1200;");

            $query->execute([
                'reftabla'      => $this->reftabla,
                'idreferencia'      => $this->idreferencia,
                'refformulariosconector'      => $this->refformulariosconector,
                'refpreguntascuestionario'      => $this->refpreguntascuestionario,
                'refrespuestascuestionario'      => $this->refrespuestascuestionario,
                'pregunta'      => $this->pregunta,
                'respuesta'      => $this->respuesta,
                'reftiporespuesta'      => $this->reftiporespuesta,
                'archivo'      => $this->archivo,
                'tipo'      => $this->tipo,
                'carpeta'      => $this->carpeta,
                'latitud'      => $this->latitud,
                'longitud'      => $this->longitud,
                'fechacrea'      => $this->fechacrea,
                'usuariocrea'      => $this->usuariocrea,
                'reftabladatos' => $this->reftabladatos,
                'columna' => $this->columna,
                'refmateriales' => $this->refmateriales,
                'cantidad' => $this->cantidad,
            ]);

            return true;

        } catch (PDOException $e) {
            
            error_log($e->getMessage());
            echo $e->getMessage();
            
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
            
           $this->cargar($res['reftabla'],$res['idreferencia'],$res['refformulariosconector'],$res['refpreguntascuestionario'],$res['refrespuestascuestionario'],$res['pregunta'],$res['respuesta'],$res['reftiporespuesta'],$res['archivo'],$res['tipo'],$res['carpeta'],$res['latitud'],$res['longitud'],$res['fechacrea'],$res['usuariocrea'],$res["reftabladatos"],$res["columna"],$res["refmateriales"],$res["cantidad"]);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'reftabla'      => $this->reftabla,
            'idreferencia'      => $this->idreferencia,
            'refformulariosconector'      => $this->refformulariosconector,
            'refpreguntascuestionario'      => $this->refpreguntascuestionario,
            'refrespuestascuestionario'      => $this->refrespuestascuestionario,
            'pregunta'      => $this->pregunta,
            'respuesta'      => $this->respuesta,
            'reftiporespuesta'      => $this->reftiporespuesta,
            'archivo'      => $this->archivo,
            'tipo'      => $this->tipo,
            'carpeta'      => $this->carpeta,
            'latitud'      => $this->latitud,
            'longitud'      => $this->longitud,
            'fechacrea'      => $this->fechacrea,
            'fechacrea'      => $this->usuariocrea,
            'reftabladatos' => $this->reftabladatos,
            'columna' => $this->columna,
            'refmateriales' => $this->refmateriales,
            'cantidad' => $this->cantidad,
        );
    }

    public function cargar($reftabla,$idreferencia,$refformulariosconector,$refpreguntascuestionario,$refrespuestascuestionario,$pregunta,$respuesta,$reftiporespuesta,$archivo,$tipo,$carpeta,$latitud,$longitud,$fechacrea,$usuariocrea,$reftabladatos,$columna,$refmateriales,$cantidad) {

        $this->setReftabla($reftabla);
        $this->setIdreferencia($idreferencia);
        $this->setRefformulariosconector($refformulariosconector);
        $this->setRefpreguntascuestionario($refpreguntascuestionario);
        $this->setRefrespuestascuestionario($refrespuestascuestionario);
        $this->setPregunta($pregunta);
        $this->setRespuesta($respuesta);
        $this->setReftiporespuesta($reftiporespuesta);
        $this->setArchivo($archivo);
        $this->setTipo($tipo);
        $this->setCarpeta($carpeta);
        $this->setLatitud($latitud);
        $this->setLongitud($longitud);
        $this->setFechacrea($fechacrea);
        $this->setUsuariocrea($usuariocrea);
        $this->setReftabladatos($reftabladatos);
        $this->setColumna($columna);
        $this->setRefmateriales($refmateriales);
        $this->setCantidad($cantidad);
        
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

    public function borrarPorReferencia() {
        $db = new Database();
        try {

            $query = $db->connect()->prepare('DELETE FROM '.self::TABLA.' WHERE reftabla = :reftabla and idreferencia = :idreferencia and refformulariosconector = :refformulariosconector ');

            try {
                $query->execute([
                    'reftabla'      => $this->reftabla,
                    'idreferencia'      => $this->idreferencia,
                    'refformulariosconector'      => $this->refformulariosconector,
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
            $where = " where (t.cargo like '%".$busqueda."%' )";
        }
       
       
        $sql = "select
        t.id,
        t.cargo
        from ".self::TABLA." t
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
            
        $data = $query;
        
        //var_dump($dataLimit);

        $res = array($dataLimit , $data->rowCount());
        return $res;
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

    public function traerPorReferencia() {
        $db = new Database();

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." where reftabla = :reftabla and idreferencia = :idreferencia and refformulariosconector = :refformulariosconector order by 1 ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute([
                'idreferencia'  => $this->idreferencia,
                'reftabla'      => $this->reftabla,
                'refformulariosconector' => $this->refformulariosconector

            ]);

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function traerPorReferenciaSimple() {
        $db = new Database();

        $sql = "SELECT id,".self::CAMPOS." FROM ".self::TABLA." where reftabla = :reftabla and idreferencia = :idreferencia and refformulariosconector = :refformulariosconector and refpreguntascuestionario = :refpreguntascuestionario order by 1 ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute([
                'idreferencia'  => $this->idreferencia,
                'reftabla'      => $this->reftabla,
                'refformulariosconector' => $this->refformulariosconector,
                'refpreguntascuestionario' => $this->refpreguntascuestionario

            ]);

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function traerPorReferenciaSimplePresupuesto() {
        $db = new Database();

        $sql = "SELECT refmateriales as id,cantidad as respuesta FROM ".self::TABLA." where reftabla = :reftabla and idreferencia = :idreferencia and refformulariosconector = :refformulariosconector and refpreguntascuestionario = :refpreguntascuestionario order by 1 ";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute([
                'idreferencia'  => $this->idreferencia,
                'reftabla'      => $this->reftabla,
                'refformulariosconector' => $this->refformulariosconector,
                'refpreguntascuestionario' => $this->refpreguntascuestionario

            ]);

            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
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

    /**
     * Get the value of refformulariosconector
     */ 
    public function getRefformulariosconector()
    {
        return $this->refformulariosconector;
    }

    /**
     * Set the value of refformulariosconector
     *
     * @return  self
     */ 
    public function setRefformulariosconector($refformulariosconector)
    {
        $this->refformulariosconector = $refformulariosconector;

        return $this;
    }

    /**
     * Get the value of refpreguntascuestionario
     */ 
    public function getRefpreguntascuestionario()
    {
        return $this->refpreguntascuestionario;
    }

    /**
     * Set the value of refpreguntascuestionario
     *
     * @return  self
     */ 
    public function setRefpreguntascuestionario($refpreguntascuestionario)
    {
        $this->refpreguntascuestionario = $refpreguntascuestionario;

        return $this;
    }

    /**
     * Get the value of refrespuestascuestionario
     */ 
    public function getRefrespuestascuestionario()
    {
        return $this->refrespuestascuestionario;
    }

    /**
     * Set the value of refrespuestascuestionario
     *
     * @return  self
     */ 
    public function setRefrespuestascuestionario($refrespuestascuestionario)
    {
        $this->refrespuestascuestionario = $refrespuestascuestionario;

        return $this;
    }

    /**
     * Get the value of pregunta
     */ 
    public function getPregunta()
    {
        return $this->pregunta;
    }

    /**
     * Set the value of pregunta
     *
     * @return  self
     */ 
    public function setPregunta($pregunta)
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    /**
     * Get the value of respuesta
     */ 
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set the value of respuesta
     *
     * @return  self
     */ 
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get the value of reftiporespuesta
     */ 
    public function getReftiporespuesta()
    {
        return $this->reftiporespuesta;
    }

    /**
     * Set the value of reftiporespuesta
     *
     * @return  self
     */ 
    public function setReftiporespuesta($reftiporespuesta)
    {
        $this->reftiporespuesta = $reftiporespuesta;

        return $this;
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
     * Get the value of latitud
     */ 
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set the value of latitud
     *
     * @return  self
     */ 
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get the value of longitud
     */ 
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set the value of longitud
     *
     * @return  self
     */ 
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

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
     * Get the value of usuariocrea
     */ 
    public function getUsuariocrea()
    {
        return $this->usuariocrea;
    }

    /**
     * Set the value of usuariocrea
     *
     * @return  self
     */ 
    public function setUsuariocrea($usuariocrea)
    {
        $this->usuariocrea = $usuariocrea;

        return $this;
    }

    /**
     * Get the value of reftabladatos
     */ 
    public function getReftabladatos()
    {
        return $this->reftabladatos;
    }

    /**
     * Set the value of reftabladatos
     *
     * @return  self
     */ 
    public function setReftabladatos($reftabladatos)
    {
        $this->reftabladatos = $reftabladatos;

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

    /**
     * Get the value of refmateriales
     */ 
    public function getRefmateriales()
    {
        return $this->refmateriales;
    }

    /**
     * Set the value of refmateriales
     *
     * @return  self
     */ 
    public function setRefmateriales($refmateriales)
    {
        $this->refmateriales = $refmateriales;

        return $this;
    }

    /**
     * Get the value of cantidad
     */ 
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */ 
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get the value of preguntascuestionario
     */ 
    public function getPreguntascuestionario()
    {
        return $this->preguntascuestionario;
    }

    /**
     * Set the value of preguntascuestionario
     *
     * @return  self
     */ 
    public function setPreguntascuestionario($preguntascuestionario)
    {
        $this->preguntascuestionario = $preguntascuestionario;

        return $this;
    }

    /**
     * Get the value of formulariosconector
     */ 
    public function getFormulariosconector()
    {
        return $this->formulariosconector;
    }

    /**
     * Set the value of formulariosconector
     *
     * @return  self
     */ 
    public function setFormulariosconector($formulariosconector)
    {
        $this->formulariosconector = $formulariosconector;

        return $this;
    }
}
    

?>