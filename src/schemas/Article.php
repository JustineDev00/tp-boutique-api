<?php namespace Schemas; class Article{    const COLUMNS = [        'id_article' => ['type' => 'varchar(255)', 'nullable' => '0', 'default' => ''],         'title' => ['type' => 'varchar(50)', 'nullable' => '1', 'default' => ''],         'content' => ['type' => 'varchar(50)', 'nullable' => '1', 'default' => ''],         'price' => ['type' => 'decimal(15,2)', 'nullable' => '1', 'default' => ''],         'updated_at' => ['type' => 'date', 'nullable' => '1', 'default' => ''],         'created_at' => ['type' => 'date', 'nullable' => '1', 'default' => ''],         'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '0', 'default' => '0'],         'stock' => ['type' => 'int(11)', 'nullable' => '1', 'default' => ''],         'id_categorie' => ['type' => 'varchar(255)', 'nullable' => '1', 'default' => ''],     ];}