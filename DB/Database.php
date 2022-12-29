<?php

namespace App\RobiMvc\Core\DB;

use App\RobiMvc\Core\Application;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $db_user = $config['db_user'] ?? '';
        $db_pass = $config['db_pass'] ?? '';

        $this->pdo = new \PDO($dsn, $db_user, $db_pass);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR.'/database/migrations');
        // vd($files);
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if($migration === '.' || $migration === '..'){
                continue;
            }
            require_once Application::$ROOT_DIR.'/database/migrations/'.$migration;
            // vd($fnwe);
            $class_name = pathinfo($migration, PATHINFO_FILENAME);
            // vd($class_name);
            $instance = new $class_name();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if(!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        }else{
            $this->log("All migrations are completed");
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations(
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        ");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        $values = implode(",", array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("
            INSERT INTO migrations (migration) VALUES $values
        ");
        $statement->execute();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    protected function log($mesasge)
    {
        echo '[' . date('d-m-Y H:i:s') . '] - ' . $mesasge . PHP_EOL;
    }
}