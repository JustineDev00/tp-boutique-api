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
            $model = new Model($table, $json);
            array_push($this->items, $model);
        }
    }

    public static function getSchema($table): array
    {
        $schemaName = "Schemas\\" . ucfirst($table);
        file_exists($schemaName ?: null);
        return $schemaName::COLUMNS;
    }

    /**
     * Même principe que pour Model mais sur une liste ($this->items)
     */
    public function data() // : array
    {
        $data = [];

        foreach($this->items as $items){
            $cleanData = $items->data();
            array_push($data, $cleanData);
        }

        return $data;
    }

    /**
     * Renvoie la liste des id contenus dans $this->items
     */
    public function idList($key = null): array
    {
        $idList = [];

        if (!isset($key)) {
            $key = $this->pk;
        }

        foreach($this->items as $items){
            array_push($idList, $items->$key);
        }

        return $idList;
    }

    /**
     * Renvoie l'instance contenue dans $this->items correspondant à $id
     */
    public function findById($id): ?Model
    {
        foreach($this->items as $item){

        }

        return $item;
    }
}
