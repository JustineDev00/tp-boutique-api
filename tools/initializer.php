<?php

namespace Tools;

use Services\DatabaseService;
use Helpers\HttpRequest;
use Exception;

class Initializer
{
    /*
* Génère la classe Schemas\Table (crée le fichier)
* qui liste toutes les tables en base de données
* sous forme de constante
* Renvoie la liste des tables sous forme de tableau
* Si $isForce vaut false et que la classe existe déjà, elle n'est pas
*réécrite
* Si $isForce vaut true, la classe est supprimée (si elle existe) et
réécrite
*/
    private static function writeTableFile(bool $isForce = false)
    {

        $tables = DatabaseService::getTables();
        $tableFile = "src/schemas/table.php";
        if (file_exists($tableFile) && $isForce) {
            //???
            //Supprimer le fichier s’il existe
            //Si la suppression ne fonctionne pas déclenche une Exception
            try {
                unlink($tableFile);
            } catch (Exception $e) {
                echo "Une erreur est survenue lors de la suppression du fichier : $e";
            }
        }
        if (!file_exists($tableFile)) {
            $constList = array_map(function ($table) {
                $table = "const " . strtoupper($table) . " = \"" . $table . "\";";
                return $table;
            }, $tables);
            $constString = implode("\n\t", $constList);
            $fileContent = "<?php \n namespace Schemas; \n\n class Table {\n\n\t" . $constString . "\n}";
            file_put_contents($tableFile, $fileContent);
        }
        return $tables;
    }


    public static function writeSchemasFiles(array $tables, bool $isForce)
    {
        /**
         * Génère une classe schema (crée le fichier) pour chaque table présente dans $tables
         * décrivant la structure de la table à l'aide de DatabaseService getSchema()
         * Si $isForce vaut false et que la classe existe déjà, elle n'est pas réécrite
         * Si $isForce vaut true, la classe est supprimée (si elle existe) et réécrite
         */
        // $tables = DatabaseService::getTables();
        foreach ($tables as $table) {
            $className = ucfirst($table);
            $schemaFile = "src/schemas/$table.php";
            if (file_exists($schemaFile) && $isForce) {
                //???
                //Supprimer le fichier s’il existe
                //Si la suppression ne fonctionne pas déclenche une Exception
                try {
                    unlink($schemaFile);
                } catch (Exception $e) {
                    echo "Une erreur est survenue lors de la suppression du fichier : $e";
                }
            }
            if (!file_exists($schemaFile)) {
                //???
                //Créer le fichier (voir exemple ci dessous)
                //Si l’écriture ne fonctionne pas déclenche une Exception
                try{
                    $constString = "";
                    //faire un getSchemas sur chaque table;
                    //On récupère un tableau associatif
                    //field = nom de la colonne; 
                    $dbs = new DatabaseService($table);
                    $rows = $dbs->getSchemas();
                    foreach($rows as $row){
                        $row->Null = $row->Null == "YES" ? 1 : 0;
                        $line = "\"$row->Field\" => [\"type\" => \"$row->Type\", \"nullable\" =>  " .  $row->Null. ", \"default\" => \"$row->Default\"],";
                        $constString .= $line;
                    }
                    $fileContent = "<?php \n namespace Schemas; \n\n class $className {\n\n\t const COLUMNS = [" . trim($constString, ",") . "];\n}";
                    file_put_contents($schemaFile, $fileContent);
                }
                catch(Exception $e){
                    echo "Une erreur est survenue lors de la création du fichier : $e";
                }

            }
        }
        return true;
    }

    public static function start(HttpRequest $request): bool
    {
        $isForce = count($request->route) > 1 && $request->route[1] == 'force';
        try {
            self::writeTableFile($isForce);
            $tables = DatabaseService::getTables();
            self::writeSchemasFiles($tables, $isForce);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}
