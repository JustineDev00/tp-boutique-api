<?php

namespace Services;

use Models\ModelList;
use PDO;
use PDOException;

class DatabaseService

{
    public ?string $table;
    public string $pk;

    public function __construct(?string $table = null)
    {
        $this->table = $table;
        $this->pk = "Id_" . $this->table;
    }

    private static ?PDO $connection = null;
    private function connect(): PDO

    {
        if (self::$connection == null) {
            $dbConfig = $_ENV['config']['db'];
            $host = $dbConfig["host"];
            $port = $dbConfig["port"];
            $dbName = $dbConfig["dbName"];
            $dsn = "mysql:host=$host;port=$port;dbname=$dbName";
            $user = $dbConfig["user"];
            $pass = $dbConfig["pass"];
            try {
                $dbConnection = new PDO(
                    $dsn,
                    $user,
                    $pass,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    )
                );
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données :
                $e->getMessage()");
            }

            self::$connection = $dbConnection;
        }

        return self::$connection;
    }

    public function query(string $sql, array $params = []): object
    {
        $statement = $this->connect()->prepare($sql);
        $result = $statement->execute($params);
        return (object)['result' => $result, 'statement' => $statement];
    }


    /**
     * Retourne la liste des tables en base de données sous forme de tableau
     */

    public static function getTables(): array
    {
        $dbs = new DatabaseService();
        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = ?";
        $resp = $dbs->query($sql, ['tp-boutique']);
        $tables = $resp->statement->fetchAll(PDO::FETCH_COLUMN);
        return $tables;
    }

    public function selectWhere(string $where = "1", array $bind = []): array
    {
        $sql = "SELECT * FROM $this->table WHERE $where;";
        $resp = $this->query($sql, $bind);
        $rows = $resp->statement->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }



    /**
     * Retourne la liste des colonnes d'une table (son schéma)
     */
    public function getSchemas()
    {
        $schemas = [];
        $sql = "SHOW COLUMNS FROM $this->table";
        $resp = $this->query($sql);
        $schemas = $resp->statement->fetchAll(PDO::FETCH_CLASS);
        return $schemas;
    }

    public function insertOrUpdate(array $body): ?array
    {
        $insertOrUpdateList = [];

        //créer un ModelList à partir du body de la requête
        $modelList = new ModelList($this->table, $body);
        //récupérer en DB les lignes de la table DONT l'id est dans $modelList->items
        $modelListIds = $modelList->idList(); //liste des ids des modèles dans le corps de la requête;
        $questionMarks = str_repeat("?,", count($modelListIds));
        $questionMarks = "(" . trim($questionMarks, ",") . ")";
        $where = $this->pk . " IN " . $questionMarks;
        $existingRowsList = $this->selectWhere($where, $modelListIds);
        //créer un ModelList avec les lignes existantes
        $existingModelsList = new ModelList($this->table, $existingRowsList); //Totalité des lignes converties en modèle;

        //construire la requête sql et le tableau de valeurs
        //pour insérer les lignes qui n'existent pas en DB

        //=> lire les ids des lignes des tableaux $modelList et $existingModelsList 

        // les models qui ne sont pas en BDD (ils doivent être insérés)
        $modelListToAdd = [];
        // Les models qui sont déjà en BDD (ils doivent être mis à jour)
        $modelListToUpdate = [];
        if (count($existingModelsList->data()) === 0) {
            $modelListToAdd = $modelList->data();
        } else {
            foreach ($modelList->data() as $model) {
                $id = $model[$this->pk];
                foreach ($existingModelsList->data() as $existingModel) {
                    $existingModelId = $existingModel[$this->pk];
                    if ($id === $existingModelId) {
                        array_push($modelListToUpdate, $model);
                    } else {
                        array_push($modelListToAdd, $model);
                    }
                }
            }
        }





        foreach ($modelListToAdd as $model) {
            //$model = un array associé où clé = colonne de la table et valeur = valeur pour chaque colonne
            //boucler sur le modèle
            $columns = "";
            $values = "";
            $valuesToBind = [];
            foreach ($model as $key => $value) {
                $columns .= $key . ",";
                $values .= "?,";
                array_push($valuesToBind, $value);
            }
            $columns = trim($columns, ',');
            $values = trim($values, ',');
            $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";
            $resp = $this->query($sql, $valuesToBind);
           
            if ($resp->result && $resp->statement->rowCount() == 1) {
                
                $row = $this->selectWhere("$this->pk = ?", [$model[$this->pk]]);
                array_push($insertOrUpdateList, $row[0]);
            }
            
        }

        foreach ($modelListToUpdate as $model) {
            $id = $model[$this->pk];
            unset($model[$this->pk]);
            $columns = "";
            $valuesToBind = [];
            foreach ($model as $col => $v) {
                if (!isset($v)) {
                    continue;
                }
                $columns .= $col . '=?,';
                array_push($valuesToBind, $v);
            }
            array_push($valuesToBind, 0, $id);
            $columns = trim($columns, ',');

            $sql = "UPDATE $this->table SET $columns WHERE is_deleted = ? AND $this->pk = ?;";
            $resp = $this->query($sql, $valuesToBind);
            $rowCount = $resp->statement->rowCount();
            if ($resp->result) {
                $row = $this->selectWhere("$this->pk = ?", [$id]);
                array_push($insertOrUpdateList, $row[0]);
            }
        }
        return $insertOrUpdateList;
    }

    public function insertOrUpdateV2(array $body): ?array
    {
        $insertOrUpdateList = [];
        $modelList = new ModelList($this->table, $body);
        $modelListData = $modelList->data();
        foreach ($modelListData as $model) {
            //Une requête par modèle;
            $columns = "";
            $values = "";
            $columnsForUpdate = "";
            $valuesToBind = [];
            foreach ($model as $col => $v) {
                if (!isset($v)) {
                    continue;
                }
                $columns .= $col . ",";
                $values .= "?,";
                $columnsForUpdate .= $col . '=?,';
                array_push($valuesToBind, $v);
            }
            foreach ($valuesToBind as $v) {
                array_push($valuesToBind, $v);
            }
            $columns = trim($columns, ',');
            $values = trim($values, ',');
            $columnsForUpdate = trim($columnsForUpdate, ',');
            $sql = "INSERT INTO $this->table ($columns) VALUES ($values) ON DUPLICATE KEY UPDATE $columnsForUpdate";
            $resp = $this->query($sql, $valuesToBind);
            if ($resp->result) {
                $row = $this->selectWhere("$this->pk = ?", [$model[$this->pk]]);
                array_push($insertOrUpdateList, $row);
            } else {
                echo ("une erreur a eu lieu lors de la mise à jour de la base de données");
            }
        }
        return $insertOrUpdateList;
    }

    public function softDelete(array $body): ?array
    {
        $modelList = new ModelList($this->table, $body);
        $ids = $modelList->idList();
        $questionMarks = str_repeat("?,", count($ids));
        $questionMarks = "(" . trim($questionMarks, ",") . ")";
        $sql = "UPDATE $this->table SET is_deleted = ? WHERE $this->pk IN $questionMarks";
        $valuesToBind = [1];
        foreach ($ids as $id) {
            array_push($valuesToBind, $id);
        }
        $resp = $this->query($sql, $valuesToBind);
        if ($resp->result) {
            $where = "is_deleted = ? AND $this->pk IN $questionMarks";
            $rows = $this->selectWhere($where, $valuesToBind);
            return $rows;
        }
        return null;
    }

    /**
     * permet la suppression (effective) d'une ou plusieurs lignes
     * renvoie un tableau vide si la suppression est effective, sinon null
     */
    public function hardDelete(array $body): ?array
    {
        $modelList = new ModelList($this->table, $body);
        $ids = $modelList->idList();
        $questionMarks = str_repeat("?,", count($ids));
        $questionMarks = "(" . trim($questionMarks, ",") . ")";
        $sql = "DELETE FROM $this->table WHERE is_deleted = ? AND $this->pk IN $questionMarks";
        $valuesToBind = [1];
        foreach ($ids as $id) {
            array_push($valuesToBind, $id);
        }
        $resp = $this->query($sql, $valuesToBind);
        if($resp->result && $resp->statement->rowCount() <= count($ids)){
            $where = "is_deleted =? AND $this->pk IN $questionMarks";
            $rows = $this->selectWhere($where, $valuesToBind);
            $rows['count'] = $resp->statement->rowCount();
            return $rows;
        }
        return null;
    }
}
