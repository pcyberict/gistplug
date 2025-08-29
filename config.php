<?php
class Config {
    // Database configuration
    public static $db_path = 'instance/users.db'; // Keep for backward compatibility
    
    // PostgreSQL Database configuration
    public static function getDatabaseUrl() {
        return $_ENV['DATABASE_URL'] ?? 'sqlite:instance/users.db';
    }
    
    public static function isDatabasePostgreSQL() {
        return strpos(self::getDatabaseUrl(), 'postgres') === 0;
    }
    
    public static function getParsedDatabaseConfig() {
        $database_url = $_ENV['DATABASE_URL'] ?? null;
        
        if (!$database_url || !self::isDatabasePostgreSQL()) {
            return null;
        }
        
        $parsed = parse_url($database_url);
        
        return [
            'host' => $parsed['host'] ?? 'localhost',
            'port' => $parsed['port'] ?? 5432,
            'dbname' => ltrim($parsed['path'] ?? '', '/'),
            'user' => $parsed['user'] ?? '',
            'password' => $parsed['pass'] ?? '',
            'sslmode' => 'require'
        ];
    }
    
    // Admin credentials
    public static $admin_username = 'admin';
    public static $admin_password = 'Pcyber50@';
    
    // Application settings
    public static $secret_key = 'your_very_strong_secret_key';
    public static $streams_per_page = 10;
    public static $max_messages_per_minute = 5;
    
    // Profanity filter words
    public static $profanity_words = ['sex', 'blood', 'bitch'];
    
    // File paths
    public static $streams_file = 'streams.json';
    public static $chat_file = 'chat.json';
}
?>