<?php

require_once 'autoload.php';



 echo nl2br($_SERVER['REQUEST_METHOD']  . $_SERVER['REQUEST_URI'] . "\n"); 
 
 

$dbc = new Controllers\DatabaseController;


 
//  echo nl2br($dbc->action);

//  $abc = new Controllers\ArticleController;
//  echo nl2br($abc->action);



$dbs = new Services\DatabaseService;
echo "end of program";


?>