<?php

// http://boutique-api/role

$env = 'dev';
$_ENV = json_decode(file_get_contents("src/configs/" . $env . ".config.json"), true);
$_ENV['env'] = $env;

require_once 'autoload.php';

use Helpers\HttpRequest;
use Helpers\HttpResponse;
use Services\DatabaseService;
use Controllers\DatabaseController;
use Tools\Initializer;
use Models\Model;
use Models\ModelList;

$request = HttpRequest::instance();

if ($_ENV['env'] == 'dev' && !empty($request->route) && $request->route[0] == 'init') {
    if (Initializer::start($request)) {
        HttpResponse::send(["message" => "Api Initialized"]);
    }
    HttpResponse::send(["message" => "Api Not Initialized, try again ..."]);
}

// ----------------------------------------------------------------------------------
// --------------------- Sprint 5 : Test de la classe Model -------------------------
// ----------------------------------------------------------------------------------

$articleModel = new Model("article", ["title"=>"Une veste mauve", "content"=>"Une super veste", "price"=>"25,6", "stock"=>"20"]);
$articleData = $articleModel->data();

// ----------------------------------------------------------------------------------
// --------------------- Sprint 5 : Test de la classe ModelList ---------------------
// ----------------------------------------------------------------------------------

$list = [["title"=>"Une veste mauve", "content"=>"Une super veste", "price"=>"25,6", "stock"=>"20"], ["title"=>"Une veste jaune", "content"=>"Une moche veste", "price"=>"10,1", "stock"=>"100"]];
$modelList = new ModelList("article", $list);

$schema = $modelList::getSchema("article");
$listData = $modelList->data();
$listId = $modelList->idList();

$modelById = $modelList->findById($listId[0]);
$breakPoint = 0;

// Après l'initialisation si elle a eu lieu, le fichier regarde si la valeur de $request->route[0] 
// correspond à une constante qui a été définie dans la classe Schemas/Tables;

if (!empty($request->route)) {

    $const = strtoupper($request->route[0]);
    $key = "Schemas\Table::$const";

    if (!defined($key)) { // Si la valeur n'existe pas dans constante : erreur 404;
        HttpResponse::exit(404);
    }

} else {
    HttpResponse::exit(404);
}

$controller = new DatabaseController($request);
$result = $controller->execute();

if ($result) {
    HttpResponse::send(["data" => $result], 200);
}