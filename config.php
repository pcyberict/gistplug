<?php
class Config {
    // Database configuration
    public static $db_path = 'instance/users.db';
    
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