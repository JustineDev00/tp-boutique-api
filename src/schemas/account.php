<?php 
 namespace Schemas; 

 class account {

	 const COLUMNS = ["Id_account" => ["type" => "varchar(255)", "nullable" =>  " NO", "default" => ""],"is_deleted" => ["type" => "tinyint(1)", "nullable" =>  " NO", "default" => "0"],"email" => ["type" => "varchar(50)", "nullable" =>  " YES", "default" => ""],"password" => ["type" => "varchar(50)", "nullable" =>  " YES", "default" => ""]];
}