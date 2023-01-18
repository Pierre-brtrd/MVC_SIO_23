<?php

namespace App\Core;

use PDO;
use PDOException;

class Db extends PDO
{
    // Instance unique de notre classe
    private static $instance;

    // informations de connexion
    private const DBHOST = "mvcsio23-db-1";
    private const DBUSER = "root";
    private const DBPASS = "root";
    private const DBNAME = "demo_mvc";

    public function __construct()
    {
        // DSN de connexion
        $dsn = 'mysql:dbname=' . self::DBNAME . ';host=' . self::DBHOST;

        // On appelle le constructeur de la classe PDO
        try {
            parent::__construct($dsn, self::DBUSER, self::DBPASS);

            // Définir les parametres de PDO
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
