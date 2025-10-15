<?php

$host = "localhost";
$db_name = "saep_db";
$username = "root";
$password = "";
$pdo="";


try {
    $pdo = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Erro de conexão: " . $exception->getMessage();
}
return $pdo;

