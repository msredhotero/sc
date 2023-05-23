<?php


class Ubicacionesusuarios {

    const TABLA = 'dbubicacionesusuarios';
    const CAMPOS = 'refusuarios,latitud,longitud,fecha,checkin,fechacheckout,fechareal,fecharealcheckout,latitudcheckout,longitudcheckout';
    const CAMPOSVAR = ':refusuarios,:latitud,:longitud,:fecha,:checkin,:fechacheckout,:fechareal,:fecharealcheckout,:latitudcheckout,:longitudcheckout';
    const RUTA = 'ubicacionesusuarios';

    private $id;
    private $refusuarios;
    private $latitud;
    private $longitud;
    private $fecha;
    private $checkin;
    private $fechacheckout;
    private $fechareal;
    private $fecharealcheckout;
    private $latitudcheckout;
    private $longitudcheckout;

    private $error;
    private $descripcionError;

    private $usuarios;

    public function __construct()
    {
        $this->usuarios = new Usuarios('','');
    }

    function devolverDireccion($lat,$lng) {


        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&sensor=false&key=AIzaSyAxMFdevPFgOqYhnaNMiItJ2p1TyVD3YUM&libraries=places';
        $json = @file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        $city = '';
        if($status=="OK") {
        //Get address from json data
        for ($j=0;$j<count($data->results[0]->address_components);$j++) {
            $cn=array($data->results[0]->address_components[$j]->types[0]);
            if(in_array("locality", $cn)) {
                $city= $data->results[0]->formatted_address;
            }
        }
        } else{
        return 'Direccion no encontrada';
        }
        //Print city 
        return $city;
    }

    public function traerUbicacionesMapa($option) {
        $db = new Database();
        $cadWhere = '';
        if (isset($option['fechadesde'])) {
            $cadWhere = " and uu.fechareal between '".$option['fechadesde']."' and '".$option['fechahasta']."' ";
        }

        $sql = "select
                r.id,
                r.fecha,
                r.checkin,
                r.fechacheckout,
                r.latitud,
                r.longitud,
                r.colormapa,
                r.fechareal
        from (
            SELECT 
                uu.id,
                (case when uu.checkin='1' then uu.fecha
                when uu.checkin='2' then uu.fechacheckout
                else uu.fechareal end) as fecha,
                uu.checkin,
                uu.fechacheckout,
                (case when uu.checkin = '2' then uu.latitudcheckout else uu.latitud end) as latitud,
                (case when uu.checkin = '2' then uu.longitudcheckout else uu.longitud end) as longitud,
                (case when uu.checkin = '1' then 'checkin'
                    when uu.checkin = '2' then 'checkout'
                    when uu.checkin = '0' then 'usuarios'
                    end) as colormapa,
                uu.fechareal
                FROM dbubicacionesusuarios uu
                inner join dbusuarios u on u.id = uu.refusuarios
                where u.id= ".$this->refusuarios." ".$cadWhere."
                union all
            SELECT 
                uu.id,
                uu.fecha,
                '1',
                uu.fechacheckout,
                uu.latitud,
                uu.longitud,
                'checkin' as colormapa,
                uu.fechareal
                FROM dbubicacionesusuarios uu
                inner join dbusuarios u on u.id = uu.refusuarios
                where uu.checkin='2' and u.id= ".$this->refusuarios." ".$cadWhere."
            ) r
            order by (case when r.checkin='2' then r.fechacheckout else r.fecha end),r.checkin ";

        //die(var_dump($sql));

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function traerUltimoCheckin() {
        $db = new Database();

        $sql = "select
        checkin, fecha
        from	 dbubicacionesusuarios where refusuarios=:refusuarios and checkin in ('1','2')
        order by fecha desc
        limit 1";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute(['refusuarios'=> $this->refusuarios]);

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
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

    public function traerTodosSinCheckOut() {
        $db = new Database();

        $sql = "SELECT 
                u.id, u.nombre, u.apellido,t.fecha, u.telefono
            FROM
                ".self::TABLA." t
            inner join dbusuarios u on u.id = t.refusuarios 
            WHERE
                u.refroles=2 and t.checkin = '1' ";

        //die(var_dump($sql));

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
        
    }


    public function traerTodosCheckInOut() {
        $db = new Database();

        $sql = "select
        r.id,
        r.fecha,
        r.checkin,
        r.fechacheckout,
        r.latitud,
        r.longitud,
        r.colormapa,
        r.fechareal,
        r.apellido,
        r.nombre
from (
    SELECT 
        uu.id,
        (case when uu.checkin='1' then uu.fecha
        when uu.checkin='2' then uu.fechacheckout
        else uu.fechareal end) as fecha,
        uu.checkin,
        uu.fechacheckout,
        (case when uu.checkin = '2' then uu.latitudcheckout else uu.latitud end) as latitud,
        (case when uu.checkin = '2' then uu.longitudcheckout else uu.longitud end) as longitud,
        (case when uu.checkin = '1' then 'checkin'
            when uu.checkin = '2' then 'checkout'
            when uu.checkin = '0' then 'usuarios'
            end) as colormapa,
        uu.fechareal,
        u.apellido,
        u.nombre
        FROM dbubicacionesusuarios uu
        inner join dbusuarios u on u.id = uu.refusuarios
        where uu.checkin in ('1','2')
        union all
    SELECT 
        uu.id,
        uu.fecha,
        '1',
        uu.fechacheckout,
        uu.latitud,
        uu.longitud,
        'checkin' as colormapa,
        uu.fechareal,
        u.apellido,
        u.nombre
        FROM dbubicacionesusuarios uu
        inner join dbusuarios u on u.id = uu.refusuarios
        where uu.checkin='2' 
    ) r
    order by r.apellido,r.nombre,(case when r.checkin='2' then r.fechacheckout else r.fecha end),r.checkin";

        //die(var_dump($sql));

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
  
        $sql = "select id from ".self::TABLA." where ".$set." ";

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
            
            // TODO: existe el usuario
            $query = $db->connect()->prepare('INSERT INTO '.self::TABLA.' ('.self::CAMPOS.') VALUES ('.self::CAMPOSVAR.')');

            $query->execute([
                'latitud'      => $this->latitud,
                'refusuarios'   => $this->refusuarios,
                'longitud'   => $this->longitud,
                'fecha'   => $this->fecha,
                'checkin'   => $this->checkin,
                'fechacheckout'   => $this->fechacheckout,
                'fechareal'   => $this->fechareal,
                'fecharealcheckout'   => $this->fecharealcheckout,
                'latitudcheckout'   => $this->latitudcheckout,
                'longitudcheckout'   => $this->longitudcheckout
            ]);

            return true;

        } catch (PDOException $e) {
            
            error_log($e->getMessage());
            return false;
            
        }
    }

    public function buscarPorValor($campo, $valor) {
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
            
           $this->cargar($res['refusuarios'],$res['latitud'],$res['longitud'],$res['fecha'],$res['checkin'],$res['fechacheckout'],$res['fechareal'],$res['fecharealcheckout']);
           $this->setId($id);

           
  
        }else{
           return null;
        }
    }

    public function devolverArray() {
        return array(
            'refusuarios'   => $this->refusuarios,
            'latitud'      => $this->latitud,
            'longitud'   => $this->longitud,
            'fecha'   => $this->fecha,
            'checkin'   => $this->checkin,
            'fechacheckout'   => $this->fechacheckout,
            'fechareal'   => $this->fechareal,
            'fecharealcheckout'   => $this->fecharealcheckout
        );
    }

    public function cargar($refusuarios,$latitud,$longitud,$fecha,$checkin,$fechacheckout,$fechareal,$fecharealcheckout) {

        $this->setRefusuarios($refusuarios);
        $this->setLatitud($latitud);
        $this->setLongitud($longitud);
        $this->setFecha($fecha);
        $this->setCheckin($checkin);
        $this->setFechacheckout($fechacheckout);
        $this->setFechareal($fechareal);
        $this->setFecharealcheckout($fecharealcheckout);
        
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
            $where = " and (u.nombre like '%".$busqueda."%' or u.apellido like '%".$busqueda."%' or ca.cargo like '%".$busqueda."%' )";
        }
        
       
        $sql = "select
        r.id,
        u.nombre,
        u.apellido,
        ca.cargo
        from ".self::TABLA." r
        inner join dbusuarios u on u.id = r.refusuarios
        inner join tbcargos ca on ca.id = u.refcargos
        inner join dbordenestrabajocabecera t on r.refordenestrabajocabecera = t.id
        inner join dbsolicitudesvisitas v on v.id = t.refsolicitudesvisitas
        inner join dbclientes c on c.id = v.refclientes
        left join dbsucursales su on su.idreferencia = v.refclientes and su.reftabla = 1 and su.id = v.refsucursales
        inner join tbsemaforo se on se.id = t.refsemaforo
        inner join tbestados e on e.id = t.refestados
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

    public function rptListadoCheckinout($length, $start, $busqueda,$colSort,$colSortDir,$min,$max) {
        $where = '';

        $db = new Database();

        $cadFecha = '';
		if ($min != '' && $max != '') {
			$cadFecha = " and r.fecha between '".$min."' and '".$max."' ";
		} else {
			if ($min != '' && $max == '') {
				$cadFecha = " and r.fecha >= '".$min."' ";
			} else {
				if ($min == '' && $max != '') {
					$cadFecha = " and r.fecha <= '".$max."' ";
				}
			}
		}

       
        $busqueda = str_replace("'","",$busqueda);
        if ($busqueda != '') {
            $where = " and (r.apellido like '%".$busqueda."%' or r.nombre like '%".$busqueda."%' or r.checkin like '%".$busqueda."%' )";
        }
        
       
        $sql = "select
                r.id,
                r.apellido,
                r.nombre,
                r.checkin,
                r.fecha,
                'Direccion no encontrada' as direccion,
                concat('https://maps.google.com/?q=',r.latitud,',',r.longitud) as direccion,
                r.latitud,
                r.longitud,
                r.fechacheckout,
                r.fechareal,
                r.colormapa

         from (
            SELECT 
                uu.id,
                (case when uu.checkin='1' then uu.fecha
                when uu.checkin='2' then uu.fechacheckout
                else uu.fechareal end) as fecha,
                uu.checkin,
                uu.fechacheckout,
                (case when uu.checkin = '2' then uu.latitudcheckout else uu.latitud end) as latitud,
                (case when uu.checkin = '2' then uu.longitudcheckout else uu.longitud end) as longitud,
                (case when uu.checkin = '1' then 'checkin'
                    when uu.checkin = '2' then 'checkout'
                    when uu.checkin = '0' then 'usuarios'
                    end) as colormapa,
                uu.fechareal,
                u.apellido,
                u.nombre
                FROM dbubicacionesusuarios uu
                inner join dbusuarios u on u.id = uu.refusuarios
                where uu.checkin in ('1','2')
                union all
            SELECT 
                uu.id,
                uu.fecha,
                '1',
                uu.fechacheckout,
                uu.latitud,
                uu.longitud,
                'checkin' as colormapa,
                uu.fechareal,
                u.apellido,
                u.nombre
                FROM dbubicacionesusuarios uu
                inner join dbusuarios u on u.id = uu.refusuarios
                where uu.checkin='2' 
        ) r
        where 1=1 ".$cadFecha.$where."
        order by r.apellido,r.nombre,(case when r.checkin='2' then r.fechacheckout else r.fecha end),r.checkin 
        
         ";
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
     * Get the value of fecha
     */ 
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of checkin
     */ 
    public function getCheckin()
    {
        return $this->checkin;
    }

    /**
     * Set the value of checkin
     *
     * @return  self
     */ 
    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;

        return $this;
    }

    

    /**
     * Get the value of fechacheckout
     */ 
    public function getFechacheckout()
    {
        return $this->fechacheckout;
    }

    /**
     * Set the value of fechacheckout
     *
     * @return  self
     */ 
    public function setFechacheckout($fechacheckout)
    {
        $this->fechacheckout = $fechacheckout;

        return $this;
    }

    /**
     * Get the value of fechareal
     */ 
    public function getFechareal()
    {
        return $this->fechareal;
    }

    /**
     * Set the value of fechareal
     *
     * @return  self
     */ 
    public function setFechareal($fechareal)
    {
        $this->fechareal = $fechareal;

        return $this;
    }

    /**
     * Get the value of fecharealcheckout
     */ 
    public function getFecharealcheckout()
    {
        return $this->fecharealcheckout;
    }

    /**
     * Set the value of fecharealcheckout
     *
     * @return  self
     */ 
    public function setFecharealcheckout($fecharealcheckout)
    {
        $this->fecharealcheckout = $fecharealcheckout;

        return $this;
    }

    /**
     * Get the value of latitudcheckout
     */ 
    public function getLatitudcheckout()
    {
        return $this->latitudcheckout;
    }

    /**
     * Set the value of latitudcheckout
     *
     * @return  self
     */ 
    public function setLatitudcheckout($latitudcheckout)
    {
        $this->latitudcheckout = $latitudcheckout;

        return $this;
    }

    /**
     * Get the value of longitudcheckout
     */ 
    public function getLongitudcheckout()
    {
        return $this->longitudcheckout;
    }

    /**
     * Set the value of longitudcheckout
     *
     * @return  self
     */ 
    public function setLongitudcheckout($longitudcheckout)
    {
        $this->longitudcheckout = $longitudcheckout;

        return $this;
    }
}
    

?>