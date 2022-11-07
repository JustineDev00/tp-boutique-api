<?php

namespace Models;

use Services\DatabaseService;
//Principe : à partir d'une table donnée, on va chercher l'ensemble des lignes;
//Les lignes sont retournées au format JSON;
//Chaque ligne est utilisée pour créér un objet de classe Model dont le schéma est le schéma de la table.
class ModelList
{
    public string $table;
    public string $pk;
    public array $items; //liste des instances de la classe Model
    public function __construct(string $table, array $list)
    {
        $this->table = $table;
        $this->pk = "Id_$this->table";
        $this->items = [];
        foreach ($list as $json) {
            $json = (array) $json;
            $model = new Model($this->table, $json);
            array_push($this->items, $model);
        }
    }

    public static function getSchema($table): array
    {
        $schemaName = "Schemas\\" . ucfirst($table);
        return $schemaName::COLUMNS;
    }


    /**
     * Même principe que pour Model mais sur une liste ($this->items)
     */
    public function data(): array
    {
        $dataList = [];
        foreach($this->items as $item){
            $data = (array) clone $item;  //$data est de type array, on ne récupère que les propriétés de $this dans cet array
            $dataClean = [];
            foreach ($item->schema as $key => $value){  //On passe par toutes les clés de l'array $data;
                if (array_key_exists($key, $data)) { //si la clé du schéma existe dans l'array $data
                    $dataClean[$key] = $data[$key];
                    
                ; //... alors cette clé est ajoutée dans $dataClean
                }  
            }
           array_push($dataList, $dataClean);

        }
        return $dataList;
    }

    public function dataV2() : array{
        $dataList = [];
        foreach($this->items as $item){
            $modelData = $item->data();
            array_push($dataList, $modelData);
        }
        return $dataList;

    }


    /**
     * Renvoie la liste des id contenus dans $this->items
     */
    public function idList($key = null): array
    {
        $idList = [];
        foreach($this->items as $item){
            if (!isset($key)) { //si la clé n'est pas précisée ...
                $key = $this->pk; //alors on ressort la liste des Id;
            }
        array_push($idList, $item->$key);
        }
       
        return $idList;
    }


    /**
     * Renvoie l'instance contenue dans $this->items correspondant à $id
     */
    public function findById($id): ?Model
    {
        $key = $this->pk;
        foreach($this->items as $item){
            if($item->$key == $id){
                return $item;
            }
        }
        return "L'objet ayant pour id $id est introuvable";
    }
}
