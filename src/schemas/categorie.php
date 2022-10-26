<?php 
 namespace Schemas; 

 class categorie {

	 const COLUMNS = ["Id_categorie" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"name" => ["type" => "varchar(50)", "nullable" =>  " YES", "default" => ""],"image" => ["type" => "varchar(50)", "nullable" =>  " YES", "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  " YES", "default" => ""]];
}