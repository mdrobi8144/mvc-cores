<?php

namespace App\RobiMvc\Core\DB;

use App\RobiMvc\Core\Application;
use App\RobiMvc\Core\Model;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;
    abstract public function attributes(): array;
    abstract public static function primaryKey(): string;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("
            INSERT INTO $tableName (". implode(',', $attributes) .")
            VALUES(".implode(',', $params).")
        ");
        // dd([$attributes, $params, $statement]);
        foreach($attributes as $attribute){
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }

    public static function findOne($where)
    {
        $tableName = static::tableName();
        // $tableName = 'users';
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    public static function all()
    {
        $statement = Application::$app->db->prepare("SELECT * FROM users ORDER BY id DESC LIMIT 1");
        $statement->execute();
        $record = $statement->fetchObject();
        return $record;
    }
}