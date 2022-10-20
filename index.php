<?php

require_once 'autoload.php';



 echo nl2br($_SERVER['REQUEST_METHOD']  . $_SERVER['REQUEST_URI']); 
 $dbs = new Controllers\DatabaseController;
//  echo nl2br($dbs->action);

//  $abs = new Controllers\ArticleController;
//  echo nl2br($abs->action);





?>