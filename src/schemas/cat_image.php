<?php 
 namespace Schemas; 

 class Cat_image {

	 const COLUMNS = ["Id_image" => ["type" => "varchar(255)", "nullable" =>  0, "default" => ""],"src" => ["type" => "varchar(255)", "nullable" =>  1, "default" => ""],"alt" => ["type" => "varchar(255)", "nullable" =>  1, "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  1, "default" => ""],"Id_categorie" => ["type" => "varchar(255)", "nullable" =>  1, "default" => ""]];
}