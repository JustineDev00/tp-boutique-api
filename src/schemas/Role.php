<?php namespace Schemas; class Role {    const COLUMNS = [        'id_role' => ['type' => 'varchar(255)', 'nullable' => 'NO', 'default' => ''],         'title' => ['type' => 'varchar(50)', 'nullable' => 'YES', 'default' => ''],         'is_deleted' => ['type' => 'varchar(50)', 'nullable' => 'YES', 'default' => ''],     ];}