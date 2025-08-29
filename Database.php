<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            if (Config::isDatabasePostgreSQL()) {
                $config = Config::getParsedDatabaseConfig();
                if ($config) {
                    $dsn = sprintf(
                        'pgsql:host=%s;port=%d;dbname=%s;sslmode=%s',
                        $config['host'],
                        $config['port'],
                        $config['dbname'],
                        $config['sslmode']
                    );
                    $this->connection = new PDO($dsn, $config['user'], $config['password']);
                } else {
                    throw new PDOException("Failed to parse PostgreSQL configuration");
                }
            } else {
                // Fallback to SQLite
                $this->connection = new PDO('sqlite:' . Config::$db_path);
            }
            
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    private function createTables() {
        $this->createUsersTable();
        $this->createNewsTable();
    }
    
    private function createUsersTable() {
        if (Config::isDatabasePostgreSQL()) {
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                username VARCHAR(80) NOT NULL UNIQUE,
                email VARCHAR(120) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            )";
        } else {
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(80) NOT NULL UNIQUE,
                email VARCHAR(120) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            )";
        }
        $this->connection->exec($sql);
    }
    
    private function createNewsTable() {
        if (Config::isDatabasePostgreSQL()) {
            $sql = "CREATE TABLE IF NOT EXISTS news_articles (
                id SERIAL PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) UNIQUE NOT NULL,
                excerpt TEXT,
                content TEXT NOT NULL,
                category VARCHAR(100) NOT NULL,
                tags TEXT,
                status VARCHAR(20) DEFAULT 'draft' CHECK (status IN ('draft', 'published', 'scheduled')),
                featured BOOLEAN DEFAULT FALSE,
                allow_comments BOOLEAN DEFAULT TRUE,
                featured_image VARCHAR(500),
                additional_images TEXT,
                seo_title VARCHAR(255),
                seo_description TEXT,
                publish_date TIMESTAMP,
                author VARCHAR(100) DEFAULT 'Admin',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            $this->connection->exec($sql);
            
            // Create indexes for better performance
            $indexes = [
                "CREATE INDEX IF NOT EXISTS idx_news_status ON news_articles(status)",
                "CREATE INDEX IF NOT EXISTS idx_news_category ON news_articles(category)",
                "CREATE INDEX IF NOT EXISTS idx_news_featured ON news_articles(featured)",
                "CREATE INDEX IF NOT EXISTS idx_news_created_at ON news_articles(created_at DESC)"
            ];
            
            foreach ($indexes as $index) {
                try {
                    $this->connection->exec($index);
                } catch (PDOException $e) {
                    // Index might already exist, continue
                }
            }
        } else {
            // SQLite version
            $sql = "CREATE TABLE IF NOT EXISTS news_articles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) UNIQUE NOT NULL,
                excerpt TEXT,
                content TEXT NOT NULL,
                category VARCHAR(100) NOT NULL,
                tags TEXT,
                status VARCHAR(20) DEFAULT 'draft',
                featured INTEGER DEFAULT 0,
                allow_comments INTEGER DEFAULT 1,
                featured_image VARCHAR(500),
                additional_images TEXT,
                seo_title VARCHAR(255),
                seo_description TEXT,
                publish_date DATETIME,
                author VARCHAR(100) DEFAULT 'Admin',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
            $this->connection->exec($sql);
        }
    }
}
?>