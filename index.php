<?php

$env = 'dev';
$_ENV['config'] = json_decode(file_get_contents("src/configs/" . $env . ".config.json"), true);
$_ENV['env'] = $env;


require_once 'autoload.php';
require_once 'vendor/autoload.php';


use helpers\HttpRequest;
use helpers\HttpResponse;
use Services\DatabaseService;
use Services\MailerService;
use Controllers\DatabaseController;
use Models\Model;
use Models\ModelList;
use Tools\Initializer;

use PHPMailer\PHPMailer;

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


//Token Tests
use helpers\TokenHelper;
$tokenFromDataArray = TokenHelper::create(["pseudo" => "Laurent", "id" => ""]);
//crée un objet token depuis un tableau;
$encoded = $tokenFromDataArray->encoded; 
//récupère la valeur stockée dans "encoded" (le résultat de create)

$tokenFromEncodedString = TokenHelper::create($encoded);
//creer un Token à partir de la string encodée;
$decoded = $tokenFromEncodedString->decoded;
//décode la string;
$test = $tokenFromEncodedString->isValid();
//vérifie la validité du token encodé;
// $bp = true;


// $controller = new DatabaseController($request);
// return $controller->execute();

//Mailer test
$testMail = new MailerService();
$mailParams =  [
    "fromAddress" => ["newsletter@maboutique.com", "newsletter maboutique.com"],
    "destAddresses" => ["florian.wartelle59@gmail.com"],
    "replyAddress" => ['info@maboutique.com', "information maboutique.com"],
    "subject" => "Attention",
    "body" => "Merci de votre <b>attention</b>. àâä éèêë ìîï òôö ùûü.",
    "altBody" => "Merci de votre attention. àâä éèêë ìîï òôö ùûü"
];
$sent = $testMail->send($mailParams);
echo($sent['message']);

$bp = true;