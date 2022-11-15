<?php namespace Services;

use Helpers\HttpResponseHelper;
use Models\ModelList;
use PDO;
use PDOException;

class DatabaseService {
  
  private static ?PDO $connection = null ;
  
  public ?string $table;
  public string $pk;
  
  public function __construct(?string $table = null){
    $this->table = $table;
    $this->pk = "Id_$this->table";
  
  }
  
  private function connect() : PDO {
    if(self::$connection == null){
      $dbConfig = $_ENV['config']["db"];

      
      $host = $dbConfig['host'];
      $port = $dbConfig['port'];
      $dbName = $dbConfig['dbName'];
      
      $dsn = "mysql:host=$host;port=$port;dbname=$dbName";
      
      $user = $dbConfig['user'];
      $pass = $dbConfig['pass'];
      
      try {
        $dbConnection = new PDO($dsn, $user, $pass, array(
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ));
      } catch (PDOException $ex) {
        die("Erreur de connexion à la base de données : $ex->getMessage()");
      }
      
      self::$connection = $dbConnection;
    }
    
    return self::$connection;
  }
  
  public function query(string $sql, array $params = []): object {
    $statement = $this->connect()->prepare($sql);
  
    $result = $statement->execute($params);
    
    return (object)['result'=>$result, "statement"=>$statement];
  }
  
  public function selectWhere(string $where = "1", array $bind = [], int $fetch_type = PDO::FETCH_CLASS): array {
    $sql = "SELECT * FROM $this->table WHERE $where;";
    $resp = $this->query($sql, $bind);
    $rows = $resp->statement->fetchAll($fetch_type);
    
    return $rows;
  }
  
  public function insertOrUpdate(array $body): ?array {
    $modelList = new ModelList($this->table, $body['items']);
    
    $idList = $modelList->idList();
    
    $where = "$this->pk IN (";
    foreach($idList as $id){
        $where .= "?, ";
    }
    $where = substr($where, 0, -2) . ")";
    
    $resp = $this->selectWhere($where, $idList, PDO::FETCH_ASSOC);
    
    foreach($body['items'] as $data){
      $exist = false;
      foreach($resp as &$arr){
        if(!isset($arr[$this->pk]) || !isset($data[$this->pk])){
          continue;
        }
        
        if($arr[$this->pk] === $data[$this->pk]){
          $exist = true;
          foreach($data as $k => $v){
            $arr[$k] = $v;
          }
          break;
        }
      }
      if(!$exist){
        array_push($resp, $data);
      }
    }
    
    $modelList = new ModelList($this->table, $resp);
    
    $columns = "";
    $values = "";
    $duplicateUpdate = "";
    $valuesToBind = [];
    
    foreach($modelList->data() as $data){
        $values .= "(";
        
        if(empty($columns)){
            $columns .= "(";
            foreach(array_keys($data) as $key){
                $columns .= "$key, ";
                $duplicateUpdate .= "$key=VALUES($key), ";
            }
            $columns = substr($columns, 0, -2) . ")";
            $duplicateUpdate = substr($duplicateUpdate, 0, -2);
        }
        
        foreach($data as $k => $v){
            $values .= "?, ";
            array_push($valuesToBind, $v);
        }
        $values = substr($values, 0, -2) . "), ";
    }
    
    $values = substr($values, 0, -2);
    
    $sql = "INSERT INTO $this->table $columns VALUES $values ON DUPLICATE KEY UPDATE $duplicateUpdate;";
    
    $this->query($sql, $valuesToBind);
    
    return $modelList->data();
  }
  
  public function softDelete(array $body): ?array {
    $modelList = new ModelList($this->table, $body['items']);
    
    $idList = $modelList->idList();
    $where = "";
    
    foreach($idList as $id){
      $where .= '?, ';
    }
    
    $where = substr($where, 0, -2);
    
    $sql = "UPDATE $this->table SET is_deleted=? WHERE $this->pk IN ($where);";
    
    $this->query($sql, [1, ...$idList]);
    
    $sql = "SELECT * FROM $this->table WHERE $this->pk IN ($where);";
    
    $resp = $this->query($sql, $idList);
    if($resp->result){
      $rows = $resp->statement->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    }
    
    return null;
  }
  
  public function hardDelete(array $body): ?array {
    $modelList = new ModelList($this->table, $body['items']);
    
    $idList = $modelList->idList();
    $where = "";
    
    foreach($idList as $id){
      $where .= '?, ';
    }
    
    $where = substr($where, 0, -2);
    
    $sql = "DELETE FROM $this->table WHERE $this->pk IN ($where);";
    
    $this->query($sql, $idList);
    
    $sql = "SELECT * FROM $this->table WHERE $this->pk IN ($where);";
    
    $resp = $this->query($sql, $idList);
    if($resp->result){
      $rows = $resp->statement->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    }
    
    return null;
  }
  
  public static function getTables(): array {
    $dbs = new DatabaseService();
    
    $response = $dbs->query('SELECT table_name FROM information_schema.tables WHERE table_schema=?', [$_ENV['db']['dbName']]);
    $rows = $response->statement->fetchAll(PDO::FETCH_COLUMN);
    
    return $rows;
  }
  
  public function getSchema(): array {
    $schema = [];
    $sql = "SHOW FULL COLUMNS FROM $this->table";
    
    $response = $this->query($sql);
    $schema = $response->statement->fetchAll(PDO::FETCH_CLASS);
    
    return $schema;
  }
  
}

?>