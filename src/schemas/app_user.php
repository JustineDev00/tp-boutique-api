<?php 
 namespace Schemas; 

 class App_user {

	 const COLUMNS = ["Id_app_user" => ["type" => "varchar(255)", "nullable" =>  0, "default" => ""],"name" => ["type" => "varchar(255)", "nullable" =>  1, "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  1, "default" => ""],"Id_Role" => ["type" => "varchar(255)", "nullable" =>  1, "default" => ""],"Id_account" => ["type" => "varchar(255)", "nullable" =>  1, "default" => ""]];
}