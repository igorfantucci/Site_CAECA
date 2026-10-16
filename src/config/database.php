<?php
/**
 * Conexão com Banco de Dados PDO (Suporte a SQLite e MySQL)
 * CAECA - UNIFEI
 */

define('DB_DRIVER', 'sqlite'); // Alterne para 'mysql' se estiver usando XAMPP/MySQL
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'caeca_db');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                if (DB_DRIVER === 'mysql') {
                    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);
                    self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);
                } else {
                    // Fallback nativo: SQLite
                    $sqlitePath = dirname(__DIR__, 2) . '/database/database.sqlite';
                    $dsn = 'sqlite:' . $sqlitePath;
                    self::$instance = new PDO($dsn, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    // Habilita suporte a chaves estrangeiras no SQLite
                    self::$instance->exec("PRAGMA foreign_keys = ON;");
                }
            } catch (PDOException $e) {
                // Se falhar MySQL, tenta fallback automático para SQLite
                if (DB_DRIVER === 'mysql') {
                    try {
                        $sqlitePath = dirname(__DIR__, 2) . '/database/database.sqlite';
                        $dsn = 'sqlite:' . $sqlitePath;
                        self::$instance = new PDO($dsn, null, null, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        ]);
                        self::$instance->exec("PRAGMA foreign_keys = ON;");
                        return self::$instance;
                    } catch (PDOException $fallbackError) {
                        die("Erro ao conectar ao banco de dados: " . $fallbackError->getMessage());
                    }
                }
                die("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
