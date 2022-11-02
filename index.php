<?php

$env = 'dev';
$_ENV = json_decode(file_get_contents("src/configs/" . $env . ".config.json"), true);
$_ENV['env'] = $env;

require_once 'autoload.php';

use Helpers\HttpRequest;
use Helpers\HttpResponse;
use Services\DatabaseService;
use Controllers\DatabaseController;
use Models\Model;
use Models\ModelList;
use Tools\Initializer;


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
// if ($result) {
//     HttpResponse::send(["data" => $result], 200);
// }

//sprint 5 : test de getSchema() de la classe Model
$result = Model::getSchema("role");
// $testGUID = Model::nextGuid();


//sprint 5 : test de la classe Model

// $testModel = new Model("article", ['title' => 'moule coeur', 'matiere' => 'silicone']);
// $testData = $testModel->data();


//sprint 5 : test de la classe Model List;

// $testModelList = new ModelList("article", [['title' => 'moule coeur'], ['title' => 'lot 12 emporte-pièces'], ['title' => 'pâte amandes 500g']]); //créer un tableau de modèles d'une table donnée;
// $testModelListData = $testModelList->data(); //convertit la liste de modèles en tableau;
// $testModelListIds = $testModelList->idList(); //affiche la liste des ids des modèles de la liste;
// $testFindModel = $testModelList->findById($testModelListIds[0]);
//sélectionne dans la liste des modèles le modèle dont l'Id est égal à l'Id passée en paramètres;
// echo(json_encode($testFindModel->data()));


//Tests sprint 6;
$dbs = new DatabaseService("article");
//insertion de 3 lignes => OK!!
// $testSp6 = $dbs->insertOrUpdate([['title' => 'moule coeur'], ['title' => 'lot 12 emporte-pièces'], ['title' => 'pâte amandes 500g']]);

//test édition des lignes insérées:


$testSp6Update = $dbs->insertOrUpdate([["Id_article" => "f54kucbsfre554l13", 'title' => "pâte d'amandes 1kg"]]);




