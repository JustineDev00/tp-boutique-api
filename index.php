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

use Models\Model;
use Models\ModelList;

$articleModel = new Model("article", ["title"=>"Une veste mauve", "content"=>"Une super veste", "price"=>"25,6", "stock"=>"20"]);
$articleData = $articleModel->data();

// ------------------- TEST ------------------------

$list = [["title"=>"Une veste mauve", "content"=>"Une super veste", "price"=>"25,6", "stock"=>"20"], ["title"=>"Une veste jaune", "content"=>"Une moche veste", "price"=>"10,1", "stock"=>"100"]];

$modelList = new ModelList("article", $list);

$schema = $modelList::getSchema("article");
$listData = $modelList->data();
$listId = $modelList->idList();
// $modelById = $modelList->findById("f5b09ccpq6210503");







// --------------------------------------------------

$request = HttpRequest::instance();

if ($_ENV['env'] == 'dev' && !empty($request->route) && $request->route[0] == 'init') {
    if (Initializer::start($request)) {
        HttpResponse::send(["message" => "Api Initialized"]);
    }
    HttpResponse::send(["message" => "Api Not Initialized, try again ..."]);
}

//Standard routes
if (!empty($request->route)) {

    $const = strtoupper($request->route[0]);
    $key = "Schemas\Table::$const";

    if (!defined($key)) {
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