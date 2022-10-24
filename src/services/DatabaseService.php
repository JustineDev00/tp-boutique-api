<?php
namespace Services;
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
    private static ?PDO $connection = null;
    private function connect() : PDO
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
                } catch (PDOException $e) {
                        die("Erreur de connexion à la base de données :$e->getMessage()");
                }
                self::$connection = $dbConnection;
            }
            return self::$connection;
    }

    public function query(string $sql, array $params = []) : object
        {
        $statement = $this->connect()->prepare($sql);
        $result = $statement->execute($params);
        return (object)['result' => $result, 'statement' => $statement];
        }
/**
* Retourne la liste des tables en base de données sous forme de tableau
*/
    // private function privQuery(string $sql, array $params = []){
    //     $statement = self::connect()->prepare($sql);
    //     $result = $statement->execute()

    // }
    public static function getTables() : array
    {
        $dbs = new DatabaseService("test");
        $sql = "SELECT table_name from information_schema.tables WHERE table_schema = ?";
        $params = "tp-boutique";
        $resp = $dbs->query($sql, [$params]);
        if($resp->result && $resp->statement->rowCount() > 0){
            $row = $resp->statement->fetchAll(PDO::FETCH_COLUMN);
            return $row;
            
        }

    }

    public function selectWhere(string $where='1', array $bind = []) :  array 
    {
        $sql = "SELECT * from $this->table WHERE $where";
        $params = $bind;
        $resp = $this->query($sql, $params);
        $row = $resp->statement->fetchAll(PDO::FETCH_GROUP);
        return $row;


    }

}





        
?>