<?php

class Reportes extends Ordenestrabajos {

    public function __construct($refcamiones)
    {
        parent::__construct($refcamiones);
    }

    public function porcenteCumplimiento() {
        $db = new Database();

        $sql = "SELECT count(t.id) as cant,sum(case when t.refestados = 4 then 1 else 0 end) as finalizadas FROM ".self::TABLA." t
        inner join tbestados e on e.id = t.refestados";

        try {
            $consulta = $db->connect()->prepare($sql);

            $consulta->execute();

            $resultado = $consulta->fetchAll();

            return $resultado;

        } catch (PDOException $e) {
            return $e->getMessage();
        } 
    }
}



?>