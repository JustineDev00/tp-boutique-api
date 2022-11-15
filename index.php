<?php

$env = 'dev';
$_ENV['config'] = json_decode(file_get_contents("src/configs/" . $env . ".config.json"), true);
$_ENV['env'] = $env;

require_once 'autoload.php';
require_once 'vendor/autoload.php';


use Helpers\HttpRequest;
use Helpers\HttpResponse;
use Services\DatabaseService;
use Services\MailerService;
use Controllers\DatabaseController;
use Controllers\AuthController;
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
    if (!defined($key)) { //si la valeur n'existe pas dans constante : 
        if(strtolower($const) === 'auth'){
            $authcontroller = new AuthController($request);
            $result = $authcontroller->execute();
            HttpResponse::send($result);

        }

        //erreur 404;
        HttpResponse::exit(404);
    }
} 
else {
    HttpResponse::exit(404); //erreur 404 si route vide
}


//Token Tests
// use Helpers\Token;
// $tokenFromDataArray = Token::create(["pseudo" => "Laurent", "id" => ""]);
// //crée un objet token depuis un tableau;
// $encoded = $tokenFromDataArray->encoded; 
// //récupère la valeur stockée dans "encoded" (le résultat de create)

// $tokenFromEncodedString = Token::create($encoded);
// //creer un Token à partir de la string encodée;
// $decoded = $tokenFromEncodedString->decoded;
// //décode la string;
// $test = $tokenFromEncodedString->isValid();
// //vérifie la validité du token encodé;
// $bp = true;

// //Mailer test
// $testMail = new MailerService();
// $mailParams =  [
//     "fromAddress" => ["newsletter@maboutique.com", "newsletter maboutique.com"],
//     "destAddresses" => ["dev.justine.verin@gmail.com"],
//     "replyAddress" => ['info@maboutique.com', "information maboutique.com"],
//     "subject" => "Attention",
//     "body" => "Merci de votre <b>attention</b>. àâä éèêë ìîï òôö ùûü.",
//     "altBody" => "Merci de votre attention. àâä éèêë ìîï òôö ùûü"
// ];
// $sent = $testMail->send($mailParams);
// echo($sent['message']);



//création de compte 


$bp = true;


