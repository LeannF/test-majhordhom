<?php 
    namespace App\Config;

    use PDO;
    use PDOException;
    use Dotenv\Dotenv;
    // Charge automatiquement les classes installées avec Composer
    require_once __DIR__. '/../vendor/autoload.php';


    class Database{
        private static ?PDO $instance =null;

        public static function getInstance(): PDO{
            if (self::$instance === null) {
                // Charge les variables du fichier .env
                $dotenv = Dotenv::createImmutable(__DIR__); // __DIR__ = dossier actuel
                $dotenv->load();

                $host = $_ENV['DB_HOST'];
                $dbname = $_ENV['DB_NAME'];
                $username = $_ENV['DB_USER'];
                $password = $_ENV['DB_PASS'];

                try {
                    self::$instance = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Erreur de connexion à la base de données : " . $e->getMessage());
                } 
            }
            return self::$instance;
        }
    }
?>