<?php
$host = "localhost";  // endereço do servidor
$dbname = "portal_notícias";  // nome do banco de dados
$username = "root";  // usuário do banco de dados
$password = "";  // senha do banco de dados

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
