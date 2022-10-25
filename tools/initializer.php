<?php

namespace Tools;

use Services\DatabaseService;
use Helpers\HttpRequest;
use Exception;

class Initializer
{

    /**
     * Exécute la méthode writeTableFile
     * Renvoie true si l'exécution s'est bien passée, false sinon
     */

    public static function start(HttpRequest $request): bool
    {
        $isForce = count($request->route) > 1 && $request->route[1] == 'force';

        try {
            self::writeTableFile();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Génère la classe Schemas\Table (crée le fichier)
     * qui liste toutes les tables en base de données sous forme de constante
     * Renvoie la liste des tables sous forme de tableau
     * Si $isForce vaut false et que la classe existe déjà, elle n'est pas réécrite
     * Si $isForce vaut true, la classe est supprimée (si elle existe) et réécrite
     */

    private static function writeTableFile(bool $isForce = false): array
    {
        $tables = DatabaseService::getTables();
        $tableFile = "src/schemas/table.php";

        if (file_exists($tableFile) && $isForce = true) {

            $test = unlink($tableFile);
            if ($test == false) {
                throw new Exception("Le fichier n'a pas pu être supprimé.");
            }
        }

        if (!file_exists($tableFile)) {

            $fileContent = "<?php namespace Schemas; class Table{";
            for ($i = 0; $i < count($tables); ++$i) {
                $value = $tables[$i];
                $fileContent .= "const " . strtoupper($value) . " = " . "'" . $value . "';";
            }
            $fileContent .= "}";

            $test = file_put_contents($tableFile, $fileContent);
            if ($test == false) {
                throw new Exception("Le fichier n'a pas pu être écrit.");
            }
        }

        return $tables;
    }
}
