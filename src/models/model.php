<?php

namespace Models;

use Services\DatabaseService;
use Schemas;

class Model
{

    public string $table;
    public string $pk;
    public array $schema;

    public function __construct(string $table, array $json)
    {
        $this->table = $table;
        $this->pk = "Id_$table";
        $this->schema = Model::getSchema($table);
        if (!isset($json[$this->pk])) {
            $json[$this->pk] = Model::nextGuid(); //générer un guid si pas d'Id fourni
        }
        foreach ($this->schema as $k => $v) {
            if (isset($json[$k])) {
                $this->$k = $json[$k];
            } elseif (
                $this->schema[$k]['nullable'] == 1 &&
                $this->schema[$k]['default'] == ''
            ) {
                $this->$k = null;
            } else //valeur par défaut
            {
                $this->$k = $this->schema[$k]['default'];
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
        return $schemaName::COLUMNS;
    }

    private static function nextGuid(int $length = 16): string
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
     * exemple : une instance de Model correspondant à la table role
     * a pour variables :
     * table, pk, schema, id_role, title, weight et is_deleted
     * seules les variables id_role, title, weight et is_deleted
     * existent en base de données
     * la méthode data les renvoie sous forme de tableau associatif
     * table, pk et schema ne sont pas renvoyée
     */
    public function data(): array
    {
        $data = (array) clone $this;  //$data est de type array, on ne récupère que les propriétés de $this dans cet array
        $dataClean = [];
        foreach ($this->schema as $key => $value) {  //On passe par toutes les clés du schéma
            if (array_key_exists($key, $data)) { //si la clé du schéma existe dans l'array $data
                $dataClean[$key] = $data[$key];
                
            ; //... alors cette clé est ajoutée dans $dataClean
            }
        }
        return $dataClean; //$dataClean ne contient que des paires clés-valeurs pour lesquelles les clés existent dans $this->schema;
    }
}
