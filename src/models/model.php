<?php

namespace Models;

use Services\DatabaseService;

class Model
{

    public string $table;
    public string $pk;
    public array $schema;

    public function __construct(string $table, array $json)
    {
        // Le param $json correspond aux données en entrée

        $this->table = $table;
        $this->pk = 'id_' . $this->table;
        $this->schema = self::getSchema($table);

        // Une fois construite, notre instance, en plus des variables table, pk et schema,
        // Possede les variables id_role, title, weight et is_deleted 

        // Si $json ne contient pas d'id, crée un nouvel id (nextGuid)
        if (!isset($json[$this->pk])) {
            $json[$this->pk] = self::nextGuid();
        }

        // Complète le contenu de $json pour obtenir une instance 
        // Ayant les mêmes propriétés que le schéma (avec des valeurs par défaut si elles sont définit dans le schéma)
        // (ex pour la table role, $json vaut ["title"=>"seller", "weight"=>2, ... ]);
        // Seul title et weight existent dans la table (schema),
        // $this->title = "seller" et $this->weight = 2

        foreach ($this->schema as $k => $v) {

            // Ajoute à l'instance toutes les colonnes contenues dans $json 
            // Si elles sont présentes dans le schéma
            if (isset($json[$k])) {
                $this->$k = $json[$k];
                // Ajoute $k comme variable à l'instance

            } elseif ($this->schema[$k]['nullable'] == 1 && $this->schema[$k]['default'] == '') {
                $this->$k = null;
                // Ajoute $k comme variable à l'instance
            }

            // is_deleted à une valeur par défaut qui vaut 0 dans le schéma,
            // $this->is_deleted = 0;
            else {
                $this->$k = $this->schema[$k]['default'];
                // Ajoute $k comme variable à l'instance
            }
        }
    }

    /**
     * Renvoie le schéma (colonnes de la table) défini dans la classe Schemas
     * correspondant à $table sous forme de tableau associatif
     * (classe Schemas généré au sprint 4)
     */
    
    public static function getSchema(string $table): array
    {
        $schemaName = "Schemas\\" . ucfirst($table);
        file_exists($schemaName ?: null);
        return $schemaName::COLUMNS;
    }

    private function nextGuid(int $length = 16): string
    {
        $time = microtime(true) * 10000;
        $guid = base_convert($time, 10, 32);
        while (strlen($guid) < $length) {
            $random = base_convert(random_int(0, 10), 10, 32);
            $guid .= $random;
        }
        return $guid;
    }

    /**
     * Renvoie la liste des données sous forme de tableau associatif
     * (Récupérez les colonnes grâce au schéma)
     * Exemple : une instance de Model correspondant à la table role
     * A pour variables : table, pk, schema, id_role, title, weight et is_deleted
     * Seules les variables id_role, title, weight et is_deleted existent en base de données
     * La méthode data les renvoie sous forme de tableau associatif
     * table, pk et schema ne sont pas renvoyée
     */

    public function data(): array
    {
        $data = (array) clone $this;
        foreach ($data as $key => $v) {
            if (!isset($this->schema[$key])) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
