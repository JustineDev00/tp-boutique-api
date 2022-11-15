<?php

namespace Controllers;

use Services\DatabaseService;
use Helpers\HttpRequest;
use Models\Model;
use Models\ModelList;

/**
 * utilise email & mot de passe (dans corps)
 * utilise pseudo (dans corps)
 * méthode : POST
 * routes : auth/login, auth/register, auth/:token
 */
class AuthController
{
    private ?string $appUser_uk; //clé unique de la table app_user === name
    private ?string $account_uk; //clé unique de la table account === email
    private ?string $password;
    private ?string $email;
    private ?string $name;
    private ?string $id;
    private array $body;
    private string $action;


    public function __construct(HttpRequest $request)
    {
        $request_body = file_get_contents('php://input');
        $this->body = json_decode($request_body, true) ?: [];
        if($request->method != 'POST' || !isset($this->body) ||empty($this->body)){
            $this->action = "error";

        }
        $this->action = $request->route[1];
        if($this->action === 'login'){
        $this->email = $this->body['email'] ?: "";
        $this->password = $this->body['password'] ?: "";
        }
        if($this->action === 'register'){
            $this->name = $this->body['name'] ?: "";
        }
        
    }

    public function execute() : ?array {
        $action = $this->action;
        $result = self::$action();
        return $result;
    }

    private function login() : ?array {
       if(!empty($this->email)){
            $dbs = new DatabaseService('account');
            $where = "email = ?";
            $row = $dbs->selectWhere($where, [$this->email]);
            if(isset($row) || !empty($row)){
                $row = $row[0]; //!! dû au comportement de selectWhere()
                $prefix = $_ENV['config']['hash']['prefix'];
                if(password_verify($this->password, $prefix . $row['password'])){
                    //va chercher id_role et name in app_user
                    $dbs = new DatabaseService('app_user');
                    $where = "Id_account = ?";
                    $row = $dbs->selectWhere($where, [$row["Id_account"]]);
                    if(isset($row) || !empty($row) ){
                        $row = $row[0];
                        return ["result" => true, "message" => "Connexion réussie", "name" => $row['name'], "role" => $row['Id_Role']];

                    }

                  

                }

            }


       }

        
        
        return ["result" => false, "message" => "La connexion a échoué"];

    }

    private function error() : ?array {
        return ["result" => false];

    }



}