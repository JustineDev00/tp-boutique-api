<?php namespace Schemas; class App_user{    const COLUMNS = [        'id_app_user' => ['type' => 'varchar(255)', 'nullable' => '0', 'default' => ''],         'name' => ['type' => 'varchar(255)', 'nullable' => '1', 'default' => ''],         'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '0', 'default' => '0'],         'id_role' => ['type' => 'varchar(255)', 'nullable' => '1', 'default' => ''],         'id_account' => ['type' => 'varchar(255)', 'nullable' => '1', 'default' => ''],     ];}