<?php 
 namespace Schemas; 

 class art_image {

	 const COLUMNS = ["Id_image" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"src" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""],"alt" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  " YES", "default" => ""],"Id_article" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""]];
}