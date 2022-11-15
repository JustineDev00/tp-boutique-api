<?php 
 namespace Schemas; 

 class Account {

	 const COLUMNS = ["Id_account" => ["type" => "varchar(255)", "nullable" =>  0, "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  1, "default" => ""],"email" => ["type" => "varchar(50)", "nullable" =>  1, "default" => ""],"password" => ["type" => "varchar(50)", "nullable" =>  1, "default" => ""]];
}