<?php 
 namespace Schemas; 

 class article_commande {

	 const COLUMNS = ["Id_article" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"Id_commande" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"quantite" => ["type" => "int(11)", "nullable" =>  " YES", "default" => ""],"total_price" => ["type" => "decimal(15,2)", "nullable" =>  " YES", "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  " YES", "default" => ""]];
}