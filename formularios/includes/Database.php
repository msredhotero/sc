<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');

class Database {
    private $host;
    private $user;
    private $db;
    private $password;
    private $charset;

    public function __construct()
    {
        

        $this->host     = 'localhost';
        $this->user     = 'root';
        $this->db       = 'simplecarga';
        $this->password = '';
        $this->charset  = 'utf8mb4';
        
        /*
        $this->host     = 'localhost';
        $this->user     = 'formsminnoc';
        $this->db       = 'jcs';
        $this->password = 'Formularios123!';
        $this->charset  = 'utf8mb4';
        */
        
    }

    public function connect() {
        try {
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $pdo = new PDO(
                $connection, 
                $this->user, 
                $this->password, 
                $options
            );
            
            return $pdo;
        } catch (\Throwable $e) {
            print_r('Error connection: ' . $e->getMessage());
        }
    }
}


?>