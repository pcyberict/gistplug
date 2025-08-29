<?php
require_once 'Database.php';

class User {
    private $db;
    public $id;
    public $username;
    public $email;
    public $password;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    public function checkPassword($password) {
        return password_verify($password, $this->password);
    }
    
    public function save() {
        if ($this->id) {
            // Update existing user
            $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            return $stmt->execute([$this->username, $this->email, $this->password, $this->id]);
        } else {
            // Create new user
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$this->username, $this->email, $this->password])) {
                $this->id = $this->db->lastInsertId();
                return true;
            }
            return false;
        }
    }
    
    public static function findByEmail($email) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData) {
            $user = new User();
            $user->id = $userData['id'];
            $user->username = $userData['username'];
            $user->email = $userData['email'];
            $user->password = $userData['password'];
            return $user;
        }
        return null;
    }
    
    public static function exists($username, $email) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        return $stmt->fetchColumn() > 0;
    }
}
?>