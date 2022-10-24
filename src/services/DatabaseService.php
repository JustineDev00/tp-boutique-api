<?php

namespace services;

use PDO;
use PDOException;

class DatabaseService

{
    public string $table;
    public string $pk;

    public function __construct(string $table = null)
    {
        $this->table = $table;
        $this->pk = "id_" . $this->table;
    }
   
/**
* Retourne la liste des tables en base de données sous forme de tableau
*/
    // private function privQuery(string $sql, array $params = []){
    //     $statement = self::connect()->prepare($sql);
    //     $result = $statement->execute()

    // }
 


    private static ?PDO $connection = null;
    private function connect(): PDO

    {
        if (self::$connection == null) {
            $dbConfig = $_ENV['db'];
            $host = $dbConfig["host"];
            $port = $dbConfig["port"];
            $dbName = $dbConfig["dbName"];
            $dsn = "mysql:host=$host;port=$port;dbname=$dbName";
            $user = $dbConfig["user"];
            $pass = $dbConfig["pass"];
            try {
                $dbConnection = new PDO(
                    $dsn,
                    $user,
                    $pass,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    )
                );
            } 
            catch (PDOException $e) {
                die("Erreur de connexion à la base de données :
                $e->getMessage()");
            }

            self::$connection = $dbConnection;
        }

        return self::$connection;
    }

    public function query(string $sql, array $params = []): object
    {
        $statment = $this->connect()->prepare($sql);
        $result = $statment->execute($params);
        return (object)['result' => $result, 'statment' => $statment];
    }


    /**
     * Retourne la liste des tables en base de données sous forme de tableau
     */

    public static function getTables() : array
    {
        $dbs = new DatabaseService("");
        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = ?";       
        $resp = $dbs->query($sql, ['boutique']);
        
        // echo json_encode($resp);
        // CONSOLE.LOG les datas !
        
        $tables = $resp->statment->fetchAll(PDO::FETCH_COLUMN);
        return $tables;
    }

}