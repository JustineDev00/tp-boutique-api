<?php 
 namespace Schemas; 

 class article {

	 const COLUMNS = ["Id_article" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"title" => ["type" => "varchar(50)", "nullable" =>  " YES", "default" => ""],"content" => ["type" => "varchar(50)", "nullable" =>  " YES", "default" => ""],"price" => ["type" => "decimal(15,2)", "nullable" =>  " YES", "default" => ""],"updated_at" => ["type" => "date", "nullable" =>  " YES", "default" => ""],"created_at" => ["type" => "date", "nullable" =>  " YES", "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  " YES", "default" => ""],"stock" => ["type" => "int(11)", "nullable" =>  " YES", "default" => ""],"Id_categorie" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""]];
}