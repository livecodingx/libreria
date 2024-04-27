<?php

class Database {
    private $hostname = 'localhost';
    private $database = 'libreria';
    private $username = 'root';
    private $password = '';

    function conectar(){
        try {
            $conexion ="mysql:host=" .$this->hostname.";dbname=".$this->database;
            $option = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($conexion, $this->username, $this->password, $option);
            return $pdo;
        } catch (PDOException $e) {
            echo "Error de conexion". $e->getMessage();
            exit;
        }
    }

}

?>