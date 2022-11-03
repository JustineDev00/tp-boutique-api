<?php

namespace Models;

use Services\DatabaseService;

class ModelList
{
    public string $table;
    public string $pk;
    public array $items; // Liste des instances de la classe Model

    public function __construct(string $table, array $list)
    {
        $this->table = $table;
        $this->pk = 'id_' . $this->table;
        $this->items = [];
        
        foreach ($list as $json) {
            // code
        }
    }
}
