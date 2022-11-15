<?php 
 namespace Schemas; 

 class Commande {

	 const COLUMNS = ["Id_commande" => ["type" => "varchar(255)", "nullable" =>  0, "default" => ""],"total" => ["type" => "decimal(15,2)", "nullable" =>  1, "default" => ""],"created_at" => ["type" => "datetime", "nullable" =>  1, "default" => ""],"validated_at" => ["type" => "datetime", "nullable" =>  1, "default" => ""],"expires_at" => ["type" => "datetime", "nullable" =>  1, "default" => ""],"delivered_at" => ["type" => "datetime", "nullable" =>  1, "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  1, "default" => ""],"Id_adresse" => ["type" => "varchar(255)", "nullable" =>  1, "default" => ""],"Id_app_user" => ["type" => "varchar(255)", "nullable" =>  1, "default" => ""]];
}