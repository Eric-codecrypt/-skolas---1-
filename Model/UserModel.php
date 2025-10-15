<?php

require_once __DIR__ . '/../Config.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        // Config.php retorna um PDO
        $this->db = require __DIR__ . '/../Config.php';
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : null;
    }

    public function create($name, $email, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password_hash) VALUES (?,?,?)');
        $stmt->execute([$name, $email, $hash]);
        return (int)$this->db->lastInsertId();
    }
}
