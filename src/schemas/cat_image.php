<?php 
 namespace Schemas; 

 class cat_image {

	 const COLUMNS = ["Id_image" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"src" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""],"alt" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  " YES", "default" => ""],"Id_categorie" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""]];
}