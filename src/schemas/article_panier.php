<?php 
 namespace Schemas; 

 class Article_panier {

	 const COLUMNS = ["Id_article" => ["type" => "varchar(255)", "nullable" =>  0, "default" => ""],"Id_commande" => ["type" => "varchar(255)", "nullable" =>  0, "default" => ""],"quantite" => ["type" => "int(11)", "nullable" =>  1, "default" => ""],"total_price" => ["type" => "decimal(15,2)", "nullable" =>  1, "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  1, "default" => ""]];
}