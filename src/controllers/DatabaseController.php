<?php
namespace Controllers;

use Helpers\HttpRequest;
use Services\DatabaseService;

class DatabaseController{
    private string $table;
    private string $pk; //???????
    private ?string $id;
    private array $body;
    private string $action;

    public function __construct(HttpRequest $request){
        $this->table = $request->route[0];
        if(isset($request->route[1])){
            $this->id = $request->route[1];
            $this->pk = "Id_" . $this->table;
        }
        if(isset($request->body)){
            $this->body = $request->body;
        }

        if($request->method == "GET"){
            $this->action = json_encode(self::get());
        }

    }
    public function execute() {
        return json_decode($this->action);

    }

    /**
* Action exécutée lors d'un GET
* Retourne le résultat du selectWhere de DatabaseService
* soit sous forme d'un tableau contenant toutes le lignes (si pas d'id)
* soit sous forme du tableau associatif correspondant à une ligne (si id)
*/
    private function get()  : ?array{
        $dbs = new DatabaseService($this->table);
        //construire $where and $bind à partir des variables privées
        $where = "is_deleted = ?";
        if(isset($this->pk)){
            $where = $where .  "AND $this->pk = ?";
            $bind = [0, $this->id];
        }
        else{
            $where = $where;
            $bind = [0];
        }
        $row = $dbs->selectWhere($where, $bind);
        return $row;

    }



}
?>