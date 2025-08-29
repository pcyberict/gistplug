<?php
class StreamManager {
    
    public static function loadStreams() {
        if (!file_exists(Config::$streams_file)) {
            return [];
        }
        $content = file_get_contents(Config::$streams_file);
        return json_decode($content, true) ?: [];
    }
    
    public static function saveStreams($streams) {
        return file_put_contents(Config::$streams_file, json_encode($streams, JSON_PRETTY_PRINT));
    }
    
    public static function loadChat() {
        if (!file_exists(Config::$chat_file)) {
            return [];
        }
        $content = file_get_contents(Config::$chat_file);
        return json_decode($content, true) ?: [];
    }
    
    public static function saveChat($chat) {
        return file_put_contents(Config::$chat_file, json_encode($chat, JSON_PRETTY_PRINT));
    }
    
    public static function addChatMessage($user, $message) {
        $chat = self::loadChat();
        $chat[] = ['user' => $user, 'message' => $message];
        return self::saveChat($chat);
    }
    
    public static function containsProfanity($text) {
        $text = strtolower($text);
        foreach (Config::$profanity_words as $word) {
            if (strpos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }
    
    public static function cleanMessage($text) {
        $words = explode(' ', $text);
        foreach ($words as &$word) {
            if (in_array(strtolower($word), Config::$profanity_words)) {
                $word = str_repeat('*', strlen($word));
            }
        }
        return implode(' ', $words);
    }
}
?>