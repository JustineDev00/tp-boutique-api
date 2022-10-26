<?php 
 namespace Schemas; 

 class adresse {

	 const COLUMNS = ["Id_adresse" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"numero_voie" => ["type" => "int(11)", "nullable" =>  " YES", "default" => ""],"nom_voie" => ["type" => "varchar(100)", "nullable" =>  " YES", "default" => ""],"code_postal" => ["type" => "varchar(150)", "nullable" =>  " YES", "default" => ""],"ville" => ["type" => "varchar(150)", "nullable" =>  " YES", "default" => ""],"pays" => ["type" => "varchar(150)", "nullable" =>  " YES", "default" => ""],"Id_app_user" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""]];
}