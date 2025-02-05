<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $env = parse_ini_file(__DIR__ . '/../.env', true);

        if (!$env) {
            die("❌ Fichier .env introuvable ou illisible !");
        }

        $host = $env['DB_HOST'] ?? 'localhost';
        $dbname = $env['DB_NAME'] ?? 'doc2wheels';
        $username = $env['DB_USER'] ?? 'docadmin';
        $password = $env['DB_PASS'] ?? 'docadmin';

        try {
            $this->pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);

            // ✅ Vérifier l'utilisateur PostgreSQL réellement utilisé
            $stmt = $this->pdo->query("SELECT current_user;");
            $currentUser = $stmt->fetchColumn();

        } catch (PDOException $e) {
            error_log("Erreur de connexion PostgreSQL : " . $e->getMessage());
            die("Erreur de connexion à la base de données.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>
