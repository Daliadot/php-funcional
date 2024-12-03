<?php
session_start();
include('db.php'); // Conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']); // Sanitiza o username
    $password = trim($_POST['password']); // Sanitiza a senha

    // Verifica se os campos não estão vazios
    if (empty($username) || empty($password)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        // Consulta SQL para verificar o usuário no banco
        $query = "SELECT id, username, password FROM user WHERE username = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        // Liga o parâmetro e executa a consulta
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Usuário encontrado
            $user = $result->fetch_assoc();

            // Verifica a senha
            if (password_verify($password, $user['password'])) {
                // Cria a sessão com o id do usuário
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redireciona para a página principal ou página de perfil
                header('Location:index.php');
                exit;
            } else {
                echo "Usuário ou senha incorretos.";
            }
        } else {
            echo "Usuário não encontrado.";
        }

        $stmt->close();
    }
}
?>

<!-- Formulário de login -->
<form method="POST">
    <label for="username">Nome de Usuário:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
</form>
