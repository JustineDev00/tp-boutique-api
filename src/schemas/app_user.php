<?php 
 namespace Schemas; 

 class app_user {

	 const COLUMNS = ["Id_app_user" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"name" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  " YES", "default" => ""],"Id_Role" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""],"Id_account" => ["type" => "varchar(255)", "nullable" =>  " YES", "default" => ""]];
}