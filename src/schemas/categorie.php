<?php 
 namespace Schemas; 

 class Categorie {

	 const COLUMNS = ["Id_categorie" => ["type" => "varchar(255)", "nullable" =>  0, "default" => ""],"name" => ["type" => "varchar(50)", "nullable" =>  1, "default" => ""],"image" => ["type" => "varchar(50)", "nullable" =>  1, "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  1, "default" => ""]];
}