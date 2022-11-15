<?php namespace Controllers;

use helpers\HttpRequest;
use Helpers\HttpRequestHelper;
use Services\DatabaseService;

class DatabaseController {
  
  private string $table;
  private string $pk;
  private ?string $id;
  private array $body;
  private string $action;
  
  public function __construct(HttpRequest $request) {
    $this->table = $request->route[0];
    $this->pk = "Id_$this->table";
    
    $this->id = isset($request->route[1]) ? $request->route[1] : null;
    
    $this->body = json_decode(file_get_contents('php://input'), true) ?: [];
    
    $this->action = $request->method;
  }
  
  public function execute() : ?array {
    return $this->{$this->action}();
  }
  
  public function get(): ?array {
    $dbs = new DatabaseService($this->table);
    
    $resp = $dbs->selectWhere(is_null($this->id) ?: "$this->pk=?", [$this->id]);
    
    return $resp;
  }
  
  public function put(): ?array {
    $dbs = new DatabaseService($this->table);
    $rows = $dbs->insertOrUpdate($this->body);
    
    return $rows;
  }
  
  public function patch(): ?array {
    $dbs = new DatabaseService($this->table);
    $rows = $dbs->softDelete($this->body);
    
    return $rows;
  }
  
  public function delete(): ?array {
    $dbs = new DatabaseService($this->table);
    $rows = $dbs->hardDelete($this->body);
    
    return $rows;
  }
  
}

?>