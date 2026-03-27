<?php
class Database {
    private static ?PDO $instance = null;

    
    private const HOST    = 'localhost';
    private const DBNAME  = 'stage_db';
    private const USER    = 'root';
    private const PASS    = 'StageHub.fr@123';          

    public static function connect(): PDO {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=' . self::HOST . ';dbname=' . self::DBNAME . ';charset=utf8mb4',
                    self::USER,
                    self::PASS,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
            } catch (PDOException $e) {
                // Ne jamais afficher l'erreur en production
                error_log($e->getMessage());
                die('Erreur de connexion à la base de données.');
            }
        }
        return self::$instance;
    }
}