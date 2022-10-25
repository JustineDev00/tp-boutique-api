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
                $table = "const " . strtoupper($table) . "= \"" . $table . "\";";
                return $table;
            }, $tables);
            $constString = implode("\n", $constList);
            $fileContent = "<?php namespace Schemas; \n class Table {" . $constString . "}";
            file_put_contents($tableFile, $fileContent);
            
        }
        return $tables;
    }

    public static function start(HttpRequest $request): bool
    {
        $isForce = count($request->route) > 1 && $request->route[1] == 'force';
        try {
            self::writeTableFile($isForce);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}
