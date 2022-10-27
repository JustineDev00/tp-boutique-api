<?php 
 namespace Schemas; 

 class Adresse {

	 const COLUMNS = ["Id_adresse" => ["type" => "varchar(255)", "nullable" =>  0, "default" => ""],"numero_voie" => ["type" => "int(11)", "nullable" =>  1, "default" => ""],"nom_voie" => ["type" => "varchar(100)", "nullable" =>  1, "default" => ""],"code_postal" => ["type" => "varchar(150)", "nullable" =>  1, "default" => ""],"ville" => ["type" => "varchar(150)", "nullable" =>  1, "default" => ""],"pays" => ["type" => "varchar(150)", "nullable" =>  1, "default" => ""],"Id_app_user" => ["type" => "varchar(255)", "nullable" =>  1, "default" => ""]];
}