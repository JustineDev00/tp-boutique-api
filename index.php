<?php

$env = 'dev';
$_ENV = json_decode(file_get_contents("src/configs/" . $env . ".config.json"), true);
$_ENV['env'] = $env;

require_once 'autoload.php';

<<<<<<< HEAD
use helpers\HttpRequest;
use helpers\HttpResponse;
use services\DatabaseService;
use controllers\DatabaseController;

$request = HttpRequest::instance();
$tables = DatabaseService::getTables();

if (empty($request->route) || !in_array($request->route[0], $tables)) {
    HttpResponse::exit();
}

$controller = new DatabaseController($request);

// $result = $controller->execute();
// HttpResponse::send(["data"=>$result]);

HttpResponse::send(["message" => "La table " . $request->route[0] . " existe."]);
=======
use Helpers\HttpRequest;
use Helpers\HttpResponse;
use Services\DatabaseService;
use Controllers\DatabaseController;

$request = HttpRequest::instance();
$tables = DatabaseService::getTables();
if(empty($request->route) || !in_array($request->route[0], $tables)){
    HttpResponse::exit();
}
$controller = new DatabaseController($request);
//$result = $controller->execute();
//HttpResponse::send(["data"=>$result]);
HttpResponse::send(["message"=>"La table ".$request->route[0]." existe."]);
>>>>>>> fe6e448 (sprint 1)
