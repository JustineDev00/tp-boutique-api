<?php 
 namespace Schemas; 

 class commande {

	 const COLUMNS = ["Id_commande" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"total" => ["type" => "decimal(15,2)", "nullable" =>  " YES", "default" => ""],"created_at" => ["type" => "datetime", "nullable" =>  " YES", "default" => ""],"validated_at" => ["type" => "datetime", "nullable" =>  " YES", "default" => ""],"expires_at" => ["type" => "datetime", "nullable" =>  " YES", "default" => ""],"delivered_at" => ["type" => "datetime", "nullable" =>  " YES", "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  " YES", "default" => ""],"Id_adresse" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""],"Id_app_user" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""]];
}