<?php

namespace Models;

use Services\DatabaseService;

class Model
{

    public string $table; // $this->table
    public string $pk; // $this->pk (nom de l'id de la table)
    public array $schema; // $this->schema (à l'aide de getSchema)

    public function __construct(string $table, array $json)
    {
        // Le param $json correspond aux données en entrée
        // (ex pour la table role : ["id_role"=>"...", "title"=>"", ...])
        // si $json ne contient pas d'id, crée un nouvel id (nextGuid)

        // 3. ajoute à l'instance toutes les colonnes contenues dans $json 
        // si elles sont présentes dans le schéma
        // 4. complète le contenu de $json pour obtenir une instance 
        // ayant les mêmes propriétés que le schéma (avec des valeurs par défaut si elles sont définit dans le schéma)
        // (ex pour la table role, $json vaut ["title"=>"seller", "weight"=>2, "nimportequoi"=>"..."])

        // seul title et weight existent dans la table (schema),
        // ils sont donc ajoutés comme variable à l'instance
        // n'importe quoi n'est pas gardé,
        // $this->title = "seller" et $this->weight = 2

        // il manque les colonnes id_role et is_deleted
        // id_role étant manquant, on le crée avec nextGuid,
        // $this->id_role = "................" (16 caractères)

        // is_deleted à une valeur par défaut qui vaut 0 dans le schéma,
        // $this->is_deleted = 0

        // une fois construite, notre instance, en plus des variables table, pk et schema,
        // possede les variables id_role, title, weight et is_deleted

        // ------------------------------------------------------------------------------------------

        $this->table = $table;
        $this->pk = 'id_' . $this->table;
        $this->schema = self::getSchema($table);

        if (!isset($json[$this->pk])) {
            $json[$this->pk] = self::nextGuid();
        }

        foreach ($this->schema as $k => $v) {
            if (isset($json[$k])) {
                // $this->$k = $v;
                // echo "$k : $v";
            } 
            
            elseif ($this->schema[$k]['nullable'] == 1 && $this->schema[$k]['default'] == '') {
                // $this->$k = null;
            } 
            
            else { // valeur par défaut
                // $this->$k = 
            }
        }
    }

    /**
     * Renvoie le schéma (colonnes de la table) défini dans la classe Schemas
     * correspondant à $table sous forme de tableau associatif
     * (classe Schemas généré au sprint 4)
     */
    public static function getSchema(string $table) // : array
    {
        $schemaName = "Schemas\\" . ucfirst($table);
        file_exists($schemaName ?: null);
        return $schemaName::COLUMNS;
    }

    /**
     * Crée un identifiant unique de $length caractères (par defaut)
     * L'idée est de se servir de la fonction microtime() pour récupérer le timestamp
     * Puis de le convertir en base 32 [a-z][0-9] ce qui vous donnera 9 caractères
     * Completer ensuite en générant autant de caractère aléatoire (base 32)
     * que nécessaire pour obtenir la $length souhaitée (16 par defaut)
     */
    private function nextGuid(int $length = 16): string
    {

        $microTimeNumber = microtime(true);
        $microTConverted = base_convert($microTimeNumber, 10, 32);

        $guidToArray = str_split($microTConverted, 1);
        $tabLength = count($guidToArray);

        if ($tabLength < $length) {
            $nToAdd = $length - $tabLength;

            for ($i = 0; $i < $nToAdd; $i++) {
                $valueToConvert = rand(1, 32);
                if ($valueToConvert == 10) {
                    $valueToConvert = rand(1, 32);
                    if ($valueToConvert == 10) {
                        $valueToConvert = rand(1, 32);
                        if ($valueToConvert == 10) {
                            $valueToConvert = rand(1, 32);
                        }
                    }
                }
                $guidToAdd = base_convert($valueToConvert, 10, 32);
                array_push($guidToArray, $guidToAdd);
            }
        }

        if ($tabLength > $length) {
            $nToDelete = $tabLength - $length;

            for ($i = 0; $i < $nToDelete; $i++) {
                array_splice($guidToArray, 1, 1);
            }
        }

        $guid = implode($guidToArray);
        return $guid;
    }
}
