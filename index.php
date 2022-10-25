<?php

$env = 'dev';
$_ENV = json_decode(file_get_contents("src/configs/" . $env . ".config.json"), true);
$_ENV['env'] = $env;

require_once 'autoload.php';

use Helpers\HttpRequest;
use Helpers\HttpResponse;
use Services\DatabaseService;
use Controllers\DatabaseController;
use Tools\Initializer;


//Lorsque qu'on fait une requête avec init on initialise le fichier (on le crée ou on le recrée si init/force)
$request = HttpRequest::instance();
if ($_ENV['env'] == "dev" && !empty($request->route) && $request->route[0] == 'init') {
    if (Initializer::start($request)) {
        HttpResponse::send(["message" => "API Initialized"]);
    }
    HttpResponse::send(["message" => "API could not be initialized, try again..."]);
}

//Après l'initialisation si elle a eu lieu, le fichier regarde si la valeur de $request->route[0] correspond à une constante qui a été définie dans la classe Schemas/Tables;
if (!empty($request->route)) {
    $const = strtoupper($request->route[0]);
    $key = "Schemas\Table::$const";
    if (!defined($key)) { //si la valeur n'existe pas dans constante : erreur 404;
        HttpResponse::exit(404);
    }
} 
else {
    HttpResponse::exit(404); //erreur 404 si route vide
}
$controller = new DatabaseController($request); //traitement de la requête;
$result = $controller->execute();
if ($result) {
    HttpResponse::send(["data" => $result], 200);
}




// $tables = DatabaseService::getTables(); //remplacée par lecture de la classe Schemas/Table;


//Test 1 : fichier existe + bool true => OK
// $tableFile = Initializer::writeTableFile(true);
//Test 2 : fichier existe + bool false =>  OK
// $tableFile = Initializer::writeTableFile(false);
//Test 3 : fichier n'existe pas + bool true => OK
// $tableFile = Initializer::writeTableFile(true);
//Test 4 : fichier n'existe pas + bool false => OK



// if (empty($request->route) || !in_array($request->route[0], $tables)) {
//     HttpResponse::exit();
// }
// $tableFile = Initializer::start($request);

// $controller = new DatabaseController($request);

// $result = $controller->execute();
// HttpResponse::send(["data"=>$result]);

// HttpResponse::send(["message" => "La table " . $request->route[0] . " existe."]);
